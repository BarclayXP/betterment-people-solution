<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/contact-form.php';

$pageTitle = 'Book a Consultation';
$pageDescription = 'Contact Betterment People Solutions and book a consultation slot with the team.';
$currentPage = 'contact';

// Only accept session IDs this site created, and keep the cookie away from scripts,
// other websites and (on the live https site) unencrypted connections.
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => site_is_https()]);
session_start();
if (empty($_SESSION['contact_token'])) {
    $_SESSION['contact_token'] = bin2hex(random_bytes(32));
}

$csvPath = contact_csv_path($config);
$allSlots = contact_all_slots(new DateTimeImmutable('today'));
$values = array_fill_keys(CONTACT_FIELDS, '');
$errors = [];
$formError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$values, $errors] = contact_validate($_POST, $allSlots, contact_taken_slots($csvPath));
    $token = isset($_POST['token']) && is_string($_POST['token']) ? $_POST['token'] : '';

    if (!hash_equals($_SESSION['contact_token'], $token)) {
        $formError = 'Your session timed out. Please check your details and submit the form again.';
    } elseif (!empty($_POST['hp_check'])) {
        // The hidden field was filled in, so this is almost certainly a spam bot. Drop it quietly.
        header('Location: contact.php', true, 303);
        exit;
    } elseif (!$errors && !contact_allow_submission($config['storage_dir'], (string) ($_SERVER['REMOTE_ADDR'] ?? ''), (int) $config['max_submissions_per_day'])) {
        $formError = 'You have already sent several requests today. Please email ' . $config['contact_email']
            . ' or call ' . $config['contact_phone'] . ' and we will be happy to help.';
    } elseif (!$errors) {
        $record = $values;
        $record['submitted_at'] = date('Y-m-d H:i:s');
        $record['booking_label'] = $allSlots[$values['booking_slot']] ?? 'None available - arrange a time';
        $saveResult = contact_save($csvPath, $record);

        if ($saveResult === 'slot_taken') {
            $values['booking_slot'] = '';
            $errors['booking_slot'] = 'Sorry, that slot has just been booked. Please choose another.';
        } else {
            $emailSent = contact_send_emails($config, $record);
            if ($saveResult === 'saved' || $emailSent) {
                // Redirect so refreshing the page does not send the form twice.
                $_SESSION['contact_success'] = ['name' => $record['name'], 'slot' => $record['booking_label']];
                $_SESSION['contact_token'] = bin2hex(random_bytes(32));
                header('Location: contact.php#booking-form', true, 303);
                exit;
            }
            $formError = 'Sorry, we could not send your request just now. Please email ' . $config['contact_email']
                . ' or call ' . $config['contact_phone'] . ' and we will get back to you.';
        }
    }
}

$success = $_SESSION['contact_success'] ?? null;
unset($_SESSION['contact_success']);

$availableSlots = array_slice(array_diff_key($allSlots, contact_taken_slots($csvPath)), 0, 10, true);

include __DIR__ . '/partials/header.php';
?>

<section class="hero hero-compact">
    <div class="page-container">
        <div class="hero-copy fade-in-up">
            <p class="eyebrow">Contact</p>
            <h1>Book a Consultation or Send an Enquiry</h1>
            <p class="lead">Share your enquiry and choose a preferred consultation slot. New clients can submit everything in one form.</p>
        </div>
    </div>
</section>

<section class="section section-flush-top">
    <div class="page-container contact-layout">
        <form method="post" action="contact.php#booking-form" id="booking-form" class="card form-card fade-in-up">
            <div class="form-card-header">
                <h2>Booking and Enquiry Form</h2>
                <p class="form-note">All fields are required unless marked optional.</p>
            </div>

            <?php if ($success): ?>
                <div class="notice-success" role="status">
                    <p><strong>Thanks, <?php echo htmlspecialchars($success['name'], ENT_QUOTES, 'UTF-8'); ?>.</strong> Your consultation request for <?php echo htmlspecialchars($success['slot'], ENT_QUOTES, 'UTF-8'); ?> has been received. We will be in touch soon to confirm.</p>
                </div>
            <?php endif; ?>
            <?php if ($formError !== ''): ?>
                <div class="notice-error" role="alert">
                    <p><?php echo htmlspecialchars($formError, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php elseif ($errors): ?>
                <div class="notice-error" role="alert">
                    <p><strong>Please check the form.</strong> <?php echo count($errors) === 1 ? 'One detail needs' : count($errors) . ' details need'; ?> fixing before we can send your request.</p>
                </div>
            <?php endif; ?>

            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['contact_token'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="form-trap" aria-hidden="true">
                <label for="hp_check">Leave this field empty</label>
                <input id="hp_check" name="hp_check" type="text" tabindex="-1" autocomplete="off">
            </div>

            <fieldset class="form-section">
                <legend><span class="form-step">1</span>Your Details</legend>
                <div class="form-grid">
                    <div class="form-field">
                        <label class="label" for="name">Full Name</label>
                        <input class="input" id="name" name="name" type="text" maxlength="100" autocomplete="name" value="<?php echo htmlspecialchars($values['name'], ENT_QUOTES, 'UTF-8'); ?>" required<?php echo contact_invalid_attrs($errors, 'name'); ?>>
                        <?php echo contact_error($errors, 'name'); ?>
                    </div>

                    <div class="form-field">
                        <label class="label" for="job_title">Job Title</label>
                        <input class="input" id="job_title" name="job_title" type="text" maxlength="100" autocomplete="organization-title" value="<?php echo htmlspecialchars($values['job_title'], ENT_QUOTES, 'UTF-8'); ?>" required<?php echo contact_invalid_attrs($errors, 'job_title'); ?>>
                        <?php echo contact_error($errors, 'job_title'); ?>
                    </div>

                    <div class="form-field">
                        <label class="label" for="email">Work Email</label>
                        <input class="input" id="email" name="email" type="email" maxlength="254" autocomplete="email" value="<?php echo htmlspecialchars($values['email'], ENT_QUOTES, 'UTF-8'); ?>" required<?php echo contact_invalid_attrs($errors, 'email'); ?>>
                        <?php echo contact_error($errors, 'email'); ?>
                    </div>

                    <div class="form-field">
                        <label class="label" for="company">Company</label>
                        <input class="input" id="company" name="company" type="text" maxlength="150" autocomplete="organization" value="<?php echo htmlspecialchars($values['company'], ENT_QUOTES, 'UTF-8'); ?>" required<?php echo contact_invalid_attrs($errors, 'company'); ?>>
                        <?php echo contact_error($errors, 'company'); ?>
                    </div>

                    <div class="form-field">
                        <label class="label" for="phone">Preferred Contact Number</label>
                        <input class="input" id="phone" name="phone" type="tel" maxlength="25" autocomplete="tel" value="<?php echo htmlspecialchars($values['phone'], ENT_QUOTES, 'UTF-8'); ?>" required<?php echo contact_invalid_attrs($errors, 'phone'); ?>>
                        <?php echo contact_error($errors, 'phone'); ?>
                    </div>

                    <div class="form-field">
                        <label class="label" for="client_type">Client Type</label>
                        <select class="input" id="client_type" name="client_type" required<?php echo contact_invalid_attrs($errors, 'client_type'); ?>>
                            <option value="">Select one</option>
                            <?php foreach (CONTACT_CLIENT_TYPES as $optionValue => $optionLabel): ?>
                                <option value="<?php echo $optionValue; ?>" <?php echo $values['client_type'] === $optionValue ? 'selected' : ''; ?>><?php echo $optionLabel; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php echo contact_error($errors, 'client_type'); ?>
                    </div>
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend><span class="form-step">2</span>Your Consultation</legend>
                <div class="form-grid">
                    <div class="form-field form-field-wide">
                        <?php if ($availableSlots): ?>
                            <label class="label" for="booking_slot">Consultation Date and Time Slot</label>
                            <select class="input" id="booking_slot" name="booking_slot" required<?php echo contact_invalid_attrs($errors, 'booking_slot'); ?>>
                                <option value="">Choose a slot</option>
                                <?php foreach ($availableSlots as $slotValue => $slotLabel): ?>
                                    <option value="<?php echo htmlspecialchars($slotValue, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $values['booking_slot'] === $slotValue ? 'selected' : ''; ?>><?php echo htmlspecialchars($slotLabel, ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <p class="label">Consultation Date and Time Slot</p>
                            <p class="form-note">All consultation slots for the next two weeks are booked. Send your request and we will arrange a time with you.</p>
                        <?php endif; ?>
                        <?php echo contact_error($errors, 'booking_slot'); ?>
                    </div>

                    <div class="form-field">
                        <label class="label" for="callback">Would you like a call back?</label>
                        <select class="input" id="callback" name="callback" required<?php echo contact_invalid_attrs($errors, 'callback'); ?>>
                            <option value="">Select one</option>
                            <?php foreach (CONTACT_CALLBACK_OPTIONS as $optionValue => $optionLabel): ?>
                                <option value="<?php echo $optionValue; ?>" <?php echo $values['callback'] === $optionValue ? 'selected' : ''; ?>><?php echo $optionLabel; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php echo contact_error($errors, 'callback'); ?>
                    </div>

                    <div class="form-field">
                        <label class="label" for="callback_time">Best Time for Call Back <span class="optional">(if yes)</span></label>
                        <input class="input" id="callback_time" name="callback_time" type="text" maxlength="100" placeholder="e.g. Weekdays after 3:00 PM" value="<?php echo htmlspecialchars($values['callback_time'], ENT_QUOTES, 'UTF-8'); ?>"<?php echo $values['callback'] === 'yes' ? ' required' : ''; ?><?php echo contact_invalid_attrs($errors, 'callback_time'); ?>>
                        <?php echo contact_error($errors, 'callback_time'); ?>
                    </div>
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend><span class="form-step">3</span>Your Enquiry</legend>
                <div class="form-stack">
                    <div class="form-field">
                        <label class="label" for="message">Query / Enquiry <span class="optional">(max <?php echo CONTACT_MAX_MESSAGE_CHARS; ?> characters or <?php echo CONTACT_MAX_MESSAGE_WORDS; ?> words)</span></label>
                        <textarea class="input" id="message" name="message" rows="5" maxlength="<?php echo CONTACT_MAX_MESSAGE_CHARS; ?>" required<?php echo contact_invalid_attrs($errors, 'message'); ?>><?php echo htmlspecialchars($values['message'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                        <p class="form-note form-count" id="message-count" hidden></p>
                        <?php echo contact_error($errors, 'message'); ?>
                    </div>

                    <div class="form-field">
                        <label class="label" for="alt_email">Email for a copy of your booking <span class="optional">(optional)</span></label>
                        <input class="input" id="alt_email" name="alt_email" type="email" maxlength="254" placeholder="name@company.com" value="<?php echo htmlspecialchars($values['alt_email'], ENT_QUOTES, 'UTF-8'); ?>"<?php echo contact_invalid_attrs($errors, 'alt_email'); ?>>
                        <?php echo contact_error($errors, 'alt_email'); ?>
                    </div>
                </div>
            </fieldset>

            <div class="form-submit">
                <p class="form-note">New clients: please complete every field so we can respond quickly with next steps.</p>
                <button class="button-primary" type="submit">Submit Booking Request <?php echo icon('arrow-right'); ?></button>
            </div>
        </form>

        <aside class="contact-aside">
            <div class="card fade-in-up delay-1">
                <h2>Contact Information</h2>
                <ul class="contact-list">
                    <li>
                        <span class="contact-icon"><?php echo icon('mail'); ?></span>
                        <div><span class="contact-label">Email</span><a href="mailto:<?php echo htmlspecialchars($config['contact_email'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo str_replace('@', '<wbr>@', htmlspecialchars($config['contact_email'], ENT_QUOTES, 'UTF-8')); ?></a></div>
                    </li>
                    <li>
                        <span class="contact-icon"><?php echo icon('phone'); ?></span>
                        <div><span class="contact-label">Phone</span><a href="<?php echo htmlspecialchars(phone_href($config['contact_phone']), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($config['contact_phone'], ENT_QUOTES, 'UTF-8'); ?></a></div>
                    </li>
                    <li>
                        <span class="contact-icon"><?php echo icon('linkedin'); ?></span>
                        <div><span class="contact-label">LinkedIn</span><a href="https://www.linkedin.com/company/betterment-people-solutions/" target="_blank" rel="noopener noreferrer">betterment-people-solutions</a></div>
                    </li>
                    <li>
                        <span class="contact-icon"><?php echo icon('globe'); ?></span>
                        <div><span class="contact-label">Website</span><a href="https://www.betterment-consulting.com" target="_blank" rel="noopener noreferrer">www.betterment-consulting.com</a></div>
                    </li>
                    <li>
                        <span class="contact-icon"><?php echo icon('clock'); ?></span>
                        <div><span class="contact-label">Availability</span>Monday to Friday, 9:00 AM - 6:00 PM</div>
                    </li>
                </ul>
            </div>
            <div class="card qr-card fade-in-up delay-2">
                <img class="qr-code" src="qr.png" alt="QR code to save Betterment People Solutions contact details" width="212" height="207">
                <div>
                    <p class="label">Digital Business Card</p>
                    <p class="form-note">Scan with your phone camera to save our contact details.</p>
                </div>
            </div>
        </aside>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
