<?php
$pageTitle = 'People and HR Services';
$pageDescription = 'Explore Betterment People Solutions service lines for people strategy, engagement, and change management.';
$currentPage = 'services';

$serviceDetails = [
    [
        'title' => 'Strategic People Partner Retainer',
        'duration' => 'Ongoing',
        'icon' => 'users',
        'description' => 'A retained partnership to align annual people strategy with business goals and leadership priorities.',
        'items' => [
            'Quarterly people planning cycles',
            'Leadership advisory and coaching',
            'Business-aligned workforce priorities'
        ]
    ],
    [
        'title' => 'People Strategy Design and Curation',
        'duration' => '4-8 weeks',
        'icon' => 'compass',
        'description' => 'Design a tailored people strategy that supports growth, culture, and capability development.',
        'items' => [
            'Organisational capability review',
            'Workforce and talent strategy design',
            'Implementation roadmap and governance'
        ]
    ],
    [
        'title' => 'Engagement, Performance, and Change Management',
        'duration' => 'Project based',
        'icon' => 'trending-up',
        'description' => 'Support programmes that improve engagement, evaluate performance, and deliver sustainable organisational change.',
        'items' => [
            'Engagement and performance frameworks',
            'Change impact and communication planning',
            'Leadership ecosystem improvement'
        ]
    ]
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
            <p class="eyebrow">Service Portfolio</p>
            <h1>People Services Designed for Business Impact</h1>
            <p class="lead">We offer flexible support from strategic retainers to focused delivery programmes, depending on your current priorities.</p>
            <div class="hero-actions">
                <a class="button-primary" href="contact.php#booking-form">Discuss Your Needs <?php echo icon('arrow-right'); ?></a>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="page-container card-grid services-detail-grid">
        <?php foreach ($serviceDetails as $index => $service): ?>
            <article class="card service-detail fade-in-up delay-<?php echo $index; ?>">
                <div class="service-detail-top">
                    <div class="icon-badge"><?php echo icon($service['icon']); ?></div>
                    <p class="pill"><?php echo htmlspecialchars($service['duration'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <h2><?php echo htmlspecialchars($service['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                <p><?php echo htmlspecialchars($service['description'], ENT_QUOTES, 'UTF-8'); ?></p>
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
