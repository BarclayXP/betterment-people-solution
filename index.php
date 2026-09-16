<?php
$pageTitle = 'People & Business Growth Consultancy';
$pageDescription = 'Betterment People Solutions provides practical, people-focused solutions for your business: people strategy, leadership and performance, growth and change, and employee relations advice.';
$currentPage = 'home';

$services = require __DIR__ . '/includes/services.php';

$process = [
    [
        'step' => '01',
        'title' => 'Discover',
        'text' => 'We interview stakeholders, review current workflows, and identify high-impact constraints.'
    ],
    [
        'step' => '02',
        'title' => 'Design',
        'text' => 'Together we define target outcomes, priorities, and a delivery roadmap with ownership.'
    ],
    [
        'step' => '03',
        'title' => 'Deliver',
        'text' => 'We implement initiatives with your team and monitor performance against clear success metrics.'
    ]
];

$testimonials = [
    [
        'quote' => 'Their people strategy support helped us modernise workforce planning during a critical period of change.',
        'name' => 'Senior Leader',
        'role' => 'Judiciary'
    ],
    [
        'quote' => 'Betterment People Solutions brought practical HR leadership and helped us improve engagement across teams.',
        'name' => 'People Director',
        'role' => 'Financial Services'
    ],
    [
        'quote' => 'We valued the clear, structured approach to change management and leadership performance.',
        'name' => 'Programme Sponsor',
        'role' => 'Government and Metropolitan Police'
    ]
];

$sectors = ['Financial services', 'Government', 'Judiciary', 'Metropolitan Police', 'Regulators', 'Start-ups and SMEs'];

include __DIR__ . '/partials/header.php';
?>

<section class="hero">
    <div class="page-container">
        <div class="hero-copy fade-in-up">
            <p class="eyebrow">People &amp; Business Growth Consultancy &middot; London</p>
            <h1>Build Stronger Teams.<br><span class="text-brand">Deliver Better Outcomes.</span></h1>
            <p class="lead">Providing practical, people-focused solutions for your business.</p>
            <div class="hero-actions">
                <a class="button-primary" href="contact.php#booking-form">Book a Consultation <?php echo icon('arrow-right'); ?></a>
                <a class="button-secondary" href="services.php">Explore Services</a>
            </div>
            <ul class="hero-points">
                <li><?php echo icon('award'); ?>10+ years of senior HR experience</li>
                <li><?php echo icon('file-text'); ?>Fixed-fee projects or ongoing support</li>
            </ul>
        </div>
    </div>
</section>

<section class="section">
    <div class="page-container">
        <div class="section-heading">
            <p class="eyebrow">Our Services</p>
            <h2>Services Designed to Add Value Across Your Business</h2>
        </div>
        <div class="card-grid services-grid">
            <?php foreach ($services as $index => $service): ?>
                <article class="card service-card fade-in-up delay-<?php echo $index; ?>">
                    <div class="icon-badge"><?php echo icon($service['icon']); ?></div>
                    <h3><?php echo htmlspecialchars($service['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo htmlspecialchars($service['summary'], ENT_QUOTES, 'UTF-8'); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
        <p class="section-link"><a class="text-link" href="services.php">See how each service works <?php echo icon('arrow-right'); ?></a></p>
    </div>
</section>

<section class="section section-tint">
    <div class="page-container grid-2">
        <div class="section-intro">
            <p class="eyebrow">How We Work</p>
            <h2>A Structured Approach to People and Business Impact</h2>
            <p class="lead">Each engagement is tailored to your needs, from everyday HR support to strategic change programmes.</p>
        </div>
        <ol class="timeline">
            <?php foreach ($process as $phase): ?>
                <li class="timeline-item fade-in-up">
                    <span class="timeline-step"><?php echo htmlspecialchars($phase['step'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <div>
                        <h3><?php echo htmlspecialchars($phase['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p><?php echo htmlspecialchars($phase['text'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>

<section class="section">
    <div class="page-container">
        <div class="proof-band fade-in-up">
            <div class="proof-stat">
                <p class="stat-value">10+</p>
                <p class="stat-label">Years of senior HR experience across complex, regulated organisations</p>
            </div>
            <div class="proof-sectors">
                <p class="eyebrow">Sector Experience</p>
                <ul class="chip-list">
                    <?php foreach ($sectors as $sector): ?>
                        <li><?php echo htmlspecialchars($sector, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="section section-tint">
    <div class="page-container">
        <div class="section-heading">
            <p class="eyebrow">Client Outcomes</p>
            <h2>Experience Across Critical Sectors</h2>
        </div>
        <div class="card-grid testimonial-grid">
            <?php foreach ($testimonials as $index => $testimonial): ?>
                <figure class="card quote-card fade-in-up delay-<?php echo $index; ?>">
                    <blockquote>
                        <p><?php echo htmlspecialchars($testimonial['quote'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </blockquote>
                    <figcaption>
                        <strong><?php echo htmlspecialchars($testimonial['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                        <span><?php echo htmlspecialchars($testimonial['role'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </figcaption>
                </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/cta.php'; ?>

<?php include __DIR__ . '/partials/footer.php'; ?>
