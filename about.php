<?php
$pageTitle = 'About Betterment People Solutions';
$pageDescription = 'Learn about our founder-led HR and people strategy consultancy and the sectors we support.';
$currentPage = 'about';

$values = [
    [
        'title' => 'Business-Aligned People Strategy',
        'icon' => 'target',
        'text' => 'We design people plans that directly support your commercial and operational priorities.'
    ],
    [
        'title' => 'Practical HR Leadership',
        'icon' => 'shield',
        'text' => 'Our advice is grounded in real-world HR practice across complex and regulated environments.'
    ],
    [
        'title' => 'Sustainable Change',
        'icon' => 'trending-up',
        'text' => 'We support long-term capability through strong leadership, engagement, and performance habits.'
    ]
];

include __DIR__ . '/partials/header.php';
?>

<section class="hero hero-compact hero-photo">
    <div class="hero-media" aria-hidden="true">
        <img src="assets/img/photo-leading-meeting.jpg" srcset="assets/img/photo-leading-meeting-960.jpg 960w, assets/img/photo-leading-meeting.jpg 1920w" sizes="(max-width: 980px) 100vw, 64vw" alt="" width="1920" height="1282" fetchpriority="high">
    </div>
    <div class="page-container">
        <div class="hero-copy fade-in-up">
            <p class="eyebrow">About Us</p>
            <h1>Founder-Led People Consultancy</h1>
            <p class="lead">Betterment People Solutions was founded to help businesses strengthen their biggest resource: their people.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="page-container">
        <div class="section-heading">
            <p class="eyebrow">Company Overview</p>
            <h2>Betterment People Solutions</h2>
            <p class="lead">We can help founders and leadership teams solve people, culture, and performance challenges that slow growth.</p>
        </div>
        <div class="grid-2">
            <article class="card fade-in-up">
                <h3>Why We Exist</h3>
                <p>As businesses scale, clarity breaks down. Managers struggle. Culture becomes inconsistent. Good people leave and leaders end up firefighting instead of focusing on growth.</p>
                <p class="callout">Betterment People Solutions exists to fix that.</p>
                <p>We work with start-ups and growing SMEs to bring structure, clarity, and confidence to how they lead, manage, and engage their people, without the cost or complexity of a full-time HR leader.</p>
            </article>
            <article class="card fade-in-up delay-1">
                <h3>Our Work Focuses On</h3>
                <ul class="list-check">
                    <li>People strategy aligned to business goals</li>
                    <li>Culture and operating model design</li>
                    <li>Leadership and management capability</li>
                    <li>Engagement, retention, and performance</li>
                    <li>Mediation and complex people issues</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<section class="section section-tint">
    <div class="page-container grid-2">
        <article class="card fade-in-up">
            <h3>Clients Typically Engage When</h3>
            <ul class="list-check">
                <li>Growth has outpaced structure</li>
                <li>Culture feels inconsistent or fragile</li>
                <li>Managers are not equipped to lead well</li>
                <li>Engagement is declining or unclear</li>
                <li>People risks are starting to show</li>
            </ul>
        </article>
        <article class="card fade-in-up delay-1">
            <h3>How We Work</h3>
            <p>Practical, commercially minded, and outcomes-focused. That means no HR jargon, no generic frameworks, and no work that does not lead to clear decisions or measurable improvement.</p>
            <p>I can offer fixed-fee projects and ongoing strategic partnerships, acting as a trusted people advisor to founders and leadership teams as they scale.</p>
            <p>If you are building a business and want people strategy that actually works, I would be happy to connect.</p>
        </article>
    </div>
</section>

<section class="section">
    <div class="page-container">
        <div class="section-heading">
            <p class="eyebrow">Guiding Principles</p>
            <h2>How We Create Value</h2>
        </div>
        <div class="card-grid">
            <?php foreach ($values as $index => $value): ?>
                <article class="card fade-in-up delay-<?php echo $index; ?>">
                    <div class="icon-badge"><?php echo icon($value['icon']); ?></div>
                    <h3><?php echo htmlspecialchars($value['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo htmlspecialchars($value['text'], ENT_QUOTES, 'UTF-8'); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
$ctaTitle = 'Connect With Betterment';
$ctaText = 'Whether you need a fixed-fee project or an ongoing people partner, we would be happy to talk.';
include __DIR__ . '/partials/cta.php';
?>

<?php include __DIR__ . '/partials/footer.php'; ?>
