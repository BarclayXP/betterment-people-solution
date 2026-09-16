<?php
$pageTitle = 'People and Business Growth Services';
$pageDescription = 'Explore Betterment People Solutions services: people strategy, leadership and performance, growth and change, and employee relations and people advice.';
$currentPage = 'services';

$services = require __DIR__ . '/includes/services.php';

$specialities = [
    'Organisational development',
    'Managerial coaching and soft skills training',
    'Policy development',
    'Employee relations',
    'Rewards and benefits',
    'Projects to scale business',
];

$engagementModels = [
    [
        'title' => 'Fixed-Fee Projects',
        'icon' => 'file-text',
        'text' => 'A clearly scoped piece of work with an agreed fee, such as designing your people strategy or supporting a change programme from start to finish.'
    ],
    [
        'title' => 'Ongoing Strategic Partnership',
        'icon' => 'repeat',
        'text' => 'A trusted people advisor for founders and leadership teams as they scale, with regular planning and support throughout the year.'
    ]
];

include __DIR__ . '/partials/header.php';
?>

<section class="hero hero-compact">
    <div class="page-container">
        <div class="hero-copy fade-in-up">
            <p class="eyebrow">Our Services</p>
            <h1>People Services Designed for Business Growth</h1>
            <p class="lead">From people strategy to complex workplace matters, we offer flexible, practical support shaped around what your business needs today.</p>
            <div class="hero-actions">
                <a class="button-primary" href="contact.php#booking-form">Discuss Your Needs <?php echo icon('arrow-right'); ?></a>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="page-container services-detail-grid">
        <?php foreach ($services as $index => $service): ?>
            <article class="card service-detail fade-in-up delay-<?php echo $index % 2; ?>">
                <div class="service-detail-top">
                    <div class="icon-badge"><?php echo icon($service['icon']); ?></div>
                    <span class="service-number"><?php echo sprintf('%02d', $index + 1); ?></span>
                </div>
                <h2><?php echo htmlspecialchars($service['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                <p class="service-summary"><?php echo htmlspecialchars($service['summary'], ENT_QUOTES, 'UTF-8'); ?></p>
                <ul class="list-check">
                    <?php foreach ($service['items'] as $item): ?>
                        <li><?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
                <a class="text-link card-link" href="contact.php#booking-form">Discuss this service <?php echo icon('arrow-right'); ?></a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section section-tint">
    <div class="page-container">
        <div class="section-heading">
            <p class="eyebrow">Specialities</p>
            <h2>Expertise Built Over a Decade of Senior HR Practice</h2>
        </div>
        <ul class="speciality-grid">
            <?php foreach ($specialities as $index => $speciality): ?>
                <li class="fade-in-up delay-<?php echo $index % 3; ?>"><?php echo htmlspecialchars($speciality, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>

<section class="section">
    <div class="page-container grid-2">
        <div class="section-intro">
            <p class="eyebrow">Ways to Work Together</p>
            <h2>Support That Fits Where Your Business Is Today</h2>
            <p class="lead">No HR jargon, no generic frameworks, and no work that does not lead to clear decisions or measurable improvement.</p>
        </div>
        <div class="stack-lg">
            <?php foreach ($engagementModels as $index => $model): ?>
                <article class="card feature-row fade-in-up delay-<?php echo $index; ?>">
                    <div class="icon-badge"><?php echo icon($model['icon']); ?></div>
                    <div>
                        <h3><?php echo htmlspecialchars($model['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p><?php echo htmlspecialchars($model['text'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
$ctaTitle = 'Not Sure Which Service Fits?';
$ctaText = 'Tell us what is happening in your business and we will recommend the right level of support.';
include __DIR__ . '/partials/cta.php';
?>

<?php include __DIR__ . '/partials/footer.php'; ?>
