<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if (!isset($pageTitle)) {
    $pageTitle = 'Betterment People Solutions';
}
if (!isset($pageDescription)) {
    $pageDescription = 'People strategy and HR consultancy services for businesses at every stage.';
}
if (!isset($currentPage)) {
    $currentPage = '';
}
$fullTitle = $pageTitle . ' | Betterment People Solutions';
?>
<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($fullTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="theme-color" content="#174a6b">

    <!-- How the page looks when the link is shared on LinkedIn, WhatsApp, email, etc. -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Betterment People Solutions">
    <meta property="og:title" content="<?php echo htmlspecialchars($fullTitle, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:image" content="https://www.betterment-consulting.com/assets/img/og-image.png">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link rel="apple-touch-icon" href="assets/img/favicon.png">
    <script>
        // Lets the CSS hide content until main.js reveals it on scroll. If main.js
        // fails to load, the class is removed again so the page is never left blank.
        document.documentElement.classList.add('js');
        window.addEventListener('load', function () {
            if (!window.bpsScriptsLoaded) {
                document.documentElement.classList.remove('js');
            }
        });
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>
    <header class="site-header">
        <div class="page-container nav-wrap">
            <a class="brand" href="index.php" aria-label="Betterment People Solutions home">
                <img class="brand-logo" src="assets/img/logo-mark.png" alt="" width="290" height="251">
                <span class="brand-text">
                    <span class="brand-name">Betterment</span>
                    <span class="brand-tagline">People Solutions</span>
                </span>
            </a>
            <button class="menu-toggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="main-menu">Menu</button>
            <nav id="main-menu" class="site-nav" aria-label="Main navigation">
                <a class="<?php echo $currentPage === 'home' ? 'active' : ''; ?>" href="index.php"<?php echo $currentPage === 'home' ? ' aria-current="page"' : ''; ?>>Home</a>
                <a class="<?php echo $currentPage === 'services' ? 'active' : ''; ?>" href="services.php"<?php echo $currentPage === 'services' ? ' aria-current="page"' : ''; ?>>Services</a>
                <a class="<?php echo $currentPage === 'about' ? 'active' : ''; ?>" href="about.php"<?php echo $currentPage === 'about' ? ' aria-current="page"' : ''; ?>>About</a>
                <a class="<?php echo $currentPage === 'contact' ? 'active' : ''; ?>" href="contact.php"<?php echo $currentPage === 'contact' ? ' aria-current="page"' : ''; ?>>Contact</a>
                <a class="button-primary nav-cta" href="contact.php#booking-form">Book a Consultation</a>
            </nav>
        </div>
    </header>
    <main id="main">
