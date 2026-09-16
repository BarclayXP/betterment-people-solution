<?php
$config = require __DIR__ . '/config.php';
require_once __DIR__ . '/icons.php';

date_default_timezone_set($config['timezone']);

// Show PHP error details only when the site runs on this computer (start-site.bat).
// On the live website, errors go to the server's log instead of visitors' screens.
$runningLocally = PHP_SAPI === 'cli-server';
ini_set('display_errors', $runningLocally ? '1' : '0');
ini_set('log_errors', '1');

// Turns a UK phone number such as 0330 133 7737 into a link phones can dial (tel:+443301337737).
function phone_href(string $phone): string
{
    $digits = preg_replace('/\D+/', '', $phone);
    return 'tel:' . ($digits !== '' && $digits[0] === '0' ? '+44' . substr($digits, 1) : $digits);
}

function site_is_https(): bool
{
    return (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off')
        || (int) ($_SERVER['SERVER_PORT'] ?? 0) === 443;
}

if (!headers_sent()) {
    // Don't advertise which PHP version the server runs.
    header_remove('X-Powered-By');

    // Browsers may only load this site's own files, may not run inline or injected
    // scripts, and may not show the site inside another website's frame.
    header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; font-src 'self'; "
        . "img-src 'self' data:; connect-src 'self'; form-action 'self'; frame-ancestors 'none'; "
        . "base-uri 'self'; object-src 'none'");
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=()');
    header('Cross-Origin-Opener-Policy: same-origin');

    if (site_is_https()) {
        // Tells browsers to always use the secure (https) address from now on.
        header('Strict-Transport-Security: max-age=31536000');
    }
}
