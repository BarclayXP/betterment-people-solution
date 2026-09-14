<?php
// Contact and booking form: consultation slots, validation, saving and email.

const CONTACT_FIELDS = [
    'name',
    'job_title',
    'email',
    'company',
    'phone',
    'client_type',
    'booking_slot',
    'callback',
    'callback_time',
    'message',
    'alt_email',
];

// Column order of storage/submissions.csv. Only add new columns at the end.
const CONTACT_COLUMNS = [
    'submitted_at',
    'client_type',
    'name',
    'job_title',
    'company',
    'email',
    'alt_email',
    'phone',
    'booking_slot',
    'booking_label',
    'callback',
    'callback_time',
    'message',
];

const CONTACT_CLIENT_TYPES = [
    'new' => 'New client',
    'existing' => 'Existing client',
    'prospective' => 'Prospective client',
];

const CONTACT_CALLBACK_OPTIONS = [
    'yes' => 'Yes',
    'no' => 'No',
];

const CONTACT_MAX_MESSAGE_CHARS = 500;
const CONTACT_MAX_MESSAGE_WORDS = 250;

function contact_csv_path(array $config): string
{
    return rtrim($config['storage_dir'], '/\\') . DIRECTORY_SEPARATOR . 'submissions.csv';
}

/**
 * Weekday consultation slots (10:00 AM and 2:00 PM) over the next 14 days,
 * as 'Y-m-d H:i' => display label.
 */
function contact_all_slots(DateTimeImmutable $today): array
{
    $slots = [];
    for ($dayOffset = 1; $dayOffset <= 14; $dayOffset++) {
        $day = $today->modify('+' . $dayOffset . ' day');
        if ((int) $day->format('N') > 5) {
            continue;
        }
        foreach (['10:00', '14:00'] as $time) {
            $slot = new DateTimeImmutable($day->format('Y-m-d') . ' ' . $time);
            $slots[$slot->format('Y-m-d H:i')] = $slot->format('D, d M Y - h:i A');
        }
    }
    return $slots;
}

/** Slots already booked, as 'Y-m-d H:i' => true. */
function contact_taken_slots(string $csvPath): array
{
    if (!is_file($csvPath)) {
        return [];
    }
    $handle = fopen($csvPath, 'r');
    if ($handle === false) {
        return [];
    }
    flock($handle, LOCK_SH);
    $taken = contact_read_taken_slots($handle);
    flock($handle, LOCK_UN);
    fclose($handle);
    return $taken;
}

/** @param resource $handle */
function contact_read_taken_slots($handle): array
{
    $slotColumn = array_search('booking_slot', CONTACT_COLUMNS, true);
    $taken = [];
    rewind($handle);
    while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {
        $slot = (string) ($row[$slotColumn] ?? '');
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $slot)) {
            $taken[$slot] = true;
        }
    }
    return $taken;
}

/** Counts characters without needing the mbstring extension. */
function contact_length(string $value): int
{
    return (int) preg_match_all('/./su', $value);
}

/**
 * Cleans and checks the submitted form.
 *
 * @return array{0: array<string, string>, 1: array<string, string>} [values, errors keyed by field]
 */
function contact_validate(array $input, array $allSlots, array $takenSlots): array
{
    $values = [];
    foreach (CONTACT_FIELDS as $field) {
        $value = isset($input[$field]) && is_string($input[$field]) ? $input[$field] : '';
        if (preg_match('//u', $value) !== 1) {
            $value = ''; // Not valid UTF-8.
        }
        $values[$field] = trim(str_replace(["\r\n", "\r"], "\n", $value));
    }

    $errors = [];
    $textFields = [
        'name' => ['Please enter your full name.', 100],
        'job_title' => ['Please enter your job title.', 100],
        'email' => ['Please enter your work email.', 254],
        'company' => ['Please enter your company name.', 150],
        'phone' => ['Please enter a contact number.', 25],
    ];
    foreach ($textFields as $field => [$requiredMessage, $maxLength]) {
        if ($values[$field] === '') {
            $errors[$field] = $requiredMessage;
        } elseif (contact_length($values[$field]) > $maxLength) {
            $errors[$field] = 'Please keep this under ' . $maxLength . ' characters.';
        } elseif (preg_match('/[\x00-\x1F\x7F]/', $values[$field])) {
            $errors[$field] = 'This contains characters that are not allowed.';
        }
    }

    if (!isset($errors['email']) && filter_var($values['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Please enter a valid email address, for example name@company.com.';
    }

    if (!isset($errors['phone'])) {
        $digitCount = preg_match_all('/\d/', $values['phone']);
        if (!preg_match('/^\+?[0-9 ().-]+$/', $values['phone']) || $digitCount < 7 || $digitCount > 15) {
            $errors['phone'] = 'Please enter a valid phone number, for example 020 7946 0958.';
        }
    }

    if (!isset(CONTACT_CLIENT_TYPES[$values['client_type']])) {
        $errors['client_type'] = 'Please choose a client type.';
    }

    // When every slot is booked the form can be sent without one, and the team arranges a time.
    $freeSlots = array_diff_key($allSlots, $takenSlots);
    if ($values['booking_slot'] !== '' || $freeSlots !== []) {
        if (!isset($allSlots[$values['booking_slot']])) {
            $values['booking_slot'] = '';
            $errors['booking_slot'] = 'Please choose a consultation date and time.';
        } elseif (!isset($freeSlots[$values['booking_slot']])) {
            $values['booking_slot'] = '';
            $errors['booking_slot'] = 'Sorry, that slot has just been booked. Please choose another.';
        }
    }

    if (!isset(CONTACT_CALLBACK_OPTIONS[$values['callback']])) {
        $errors['callback'] = 'Please tell us whether you would like a call back.';
    }

    if ($values['callback'] === 'yes' && $values['callback_time'] === '') {
        $errors['callback_time'] = 'Please tell us the best time to call you.';
    } elseif (contact_length($values['callback_time']) > 100) {
        $errors['callback_time'] = 'Please keep this under 100 characters.';
    } elseif (preg_match('/[\x00-\x1F\x7F]/', $values['callback_time'])) {
        $errors['callback_time'] = 'This contains characters that are not allowed.';
    }

    $wordCount = count(preg_split('/\s+/u', $values['message'], -1, PREG_SPLIT_NO_EMPTY) ?: []);
    if ($values['message'] === '') {
        $errors['message'] = 'Please tell us briefly how we can help.';
    } elseif (contact_length($values['message']) > CONTACT_MAX_MESSAGE_CHARS || $wordCount > CONTACT_MAX_MESSAGE_WORDS) {
        $errors['message'] = 'Please keep your message to ' . CONTACT_MAX_MESSAGE_CHARS . ' characters or ' . CONTACT_MAX_MESSAGE_WORDS . ' words.';
    } elseif (preg_match('/[\x00-\x08\x0B-\x1F\x7F]/', $values['message'])) {
        $errors['message'] = 'Your message contains characters that are not allowed.';
    }

    if ($values['alt_email'] !== '' && filter_var($values['alt_email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['alt_email'] = 'Please enter a valid email address, or leave this empty.';
    }

    // Keep errors in the same order as the fields on the page.
    $orderedErrors = [];
    foreach (CONTACT_FIELDS as $field) {
        if (isset($errors[$field])) {
            $orderedErrors[$field] = $errors[$field];
        }
    }

    return [$values, $orderedErrors];
}

/** Stops spreadsheet programs from running submitted text as a formula. */
function contact_csv_safe(string $value): string
{
    return preg_match('/^[=+\-@\t\r]/', $value) ? "'" . $value : $value;
}

/**
 * Appends a submission to the CSV file. The file stays locked while the slot is
 * checked, so two people cannot book the same slot at the same moment.
 *
 * @return string 'saved', 'slot_taken' or 'error'
 */
function contact_save(string $csvPath, array $record): string
{
    $dir = dirname($csvPath);
    if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) {
        return 'error';
    }
    $handle = @fopen($csvPath, 'c+');
    if ($handle === false) {
        return 'error';
    }
    if (!flock($handle, LOCK_EX)) {
        fclose($handle);
        return 'error';
    }

    $result = 'saved';
    $taken = contact_read_taken_slots($handle);
    if ($record['booking_slot'] !== '' && isset($taken[$record['booking_slot']])) {
        $result = 'slot_taken';
    } else {
        fseek($handle, 0, SEEK_END);
        if (ftell($handle) === 0) {
            fwrite($handle, "\xEF\xBB\xBF"); // UTF-8 marker so Excel shows accented names correctly.
            fputcsv($handle, CONTACT_COLUMNS, ',', '"', '');
        }
        $row = [];
        foreach (CONTACT_COLUMNS as $column) {
            $row[] = contact_csv_safe((string) ($record[$column] ?? ''));
        }
        if (fputcsv($handle, $row, ',', '"', '') === false || !fflush($handle)) {
            $result = 'error';
        }
    }

    flock($handle, LOCK_UN);
    fclose($handle);
    return $result;
}

function contact_mail_subject(string $subject): string
{
    return '=?UTF-8?B?' . base64_encode($subject) . '?=';
}

/**
 * Emails the submission to the team, plus a copy of the booking details to the
 * optional email address. Returns whether the team email was accepted for delivery.
 */
function contact_send_emails(array $config, array $record): bool
{
    $headers = [
        'MIME-Version' => '1.0',
        'Content-Type' => 'text/plain; charset=UTF-8',
        'From' => 'Betterment People Solutions Website <' . $config['from_email'] . '>',
    ];
    $callback = CONTACT_CALLBACK_OPTIONS[$record['callback']]
        . ($record['callback_time'] !== '' ? ' (' . $record['callback_time'] . ')' : '');

    $teamBody = implode("\r\n", [
        'New booking request from the website contact form.',
        '',
        'Client type: ' . CONTACT_CLIENT_TYPES[$record['client_type']],
        'Name: ' . $record['name'],
        'Job title: ' . $record['job_title'],
        'Company: ' . $record['company'],
        'Work email: ' . $record['email'],
        'Other email: ' . ($record['alt_email'] !== '' ? $record['alt_email'] : '-'),
        'Phone: ' . $record['phone'],
        'Consultation slot: ' . $record['booking_label'],
        'Call back: ' . $callback,
        '',
        'Message:',
        str_replace("\n", "\r\n", $record['message']),
        '',
        'Submitted: ' . $record['submitted_at'],
    ]);
    $teamSent = @mail(
        $config['contact_email'],
        contact_mail_subject('New booking request: ' . $record['name'] . ', ' . $record['company']),
        $teamBody,
        $headers + ['Reply-To' => $record['email']]
    );
    if (!$teamSent) {
        error_log('Contact form: could not email submission from ' . $record['email'] . ' to ' . $config['contact_email']);
    }

    if ($record['alt_email'] !== '') {
        $copyBody = implode("\r\n", [
            'Hello ' . $record['name'] . ',',
            '',
            'Thank you for your consultation request with Betterment People Solutions.',
            '',
            'Requested slot: ' . $record['booking_label'],
            'Call back: ' . $callback,
            '',
            'We will be in touch to confirm. If anything needs to change, reply to this email or call us on ' . $config['contact_phone'] . '.',
            '',
            'Betterment People Solutions',
        ]);
        $copySent = @mail(
            $record['alt_email'],
            contact_mail_subject('Your consultation request with Betterment People Solutions'),
            $copyBody,
            $headers + ['Reply-To' => $config['contact_email']]
        );
        if (!$copySent) {
            error_log('Contact form: could not email booking copy to ' . $record['alt_email']);
        }
    }

    return $teamSent;
}

/** Accessibility attributes that link a field to its error message. */
function contact_invalid_attrs(array $errors, string $field): string
{
    return isset($errors[$field]) ? ' aria-invalid="true" aria-describedby="' . $field . '-error"' : '';
}

function contact_error(array $errors, string $field): string
{
    if (!isset($errors[$field])) {
        return '';
    }
    return '<p class="field-error" id="' . $field . '-error">' . htmlspecialchars($errors[$field], ENT_QUOTES, 'UTF-8') . '</p>';
}
