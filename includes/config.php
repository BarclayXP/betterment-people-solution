<?php
// Site settings. Check these before putting the site live.
return [
    // Booking and enquiry emails from the contact form are sent here.
    'contact_email' => 'partners@betterment-consulting.com',
    'contact_phone' => '03301337737',

    // Sender address for emails the website sends. Use an address on your own
    // domain, otherwise email providers are likely to mark the emails as spam.
    'from_email' => 'website@betterment-consulting.com',

    'timezone' => 'Europe/London',

    // Every form submission is also saved here as submissions.csv (opens in Excel),
    // so no booking is lost if an email fails to send.
    'storage_dir' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage',
];
