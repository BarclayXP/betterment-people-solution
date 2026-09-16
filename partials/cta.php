<?php
// Closing call to action. Pages can set $ctaTitle and $ctaText before including this.
$ctaTitle = $ctaTitle ?? 'Let Us Support Your Team and Business Goals';
$ctaText = $ctaText ?? 'Book a consultation and share your enquiry so we can map the right support for your organisation.';
?>
<section class="section cta-section">
    <div class="page-container">
        <div class="cta-band fade-in-up">
            <div class="cta-copy">
                <p class="eyebrow">Ready to Start?</p>
                <h2><?php echo htmlspecialchars($ctaTitle, ENT_QUOTES, 'UTF-8'); ?></h2>
                <p><?php echo htmlspecialchars($ctaText, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="cta-actions">
                <a class="button-light" href="contact.php#booking-form">Book a Consultation <?php echo icon('arrow-right'); ?></a>
                <a class="cta-secondary" href="mailto:<?php echo htmlspecialchars($config['contact_email'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo icon('mail'); ?><span><?php echo htmlspecialchars($config['contact_email'], ENT_QUOTES, 'UTF-8'); ?></span></a>
            </div>
        </div>
    </div>
</section>
