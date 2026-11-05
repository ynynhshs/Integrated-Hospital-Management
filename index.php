<?php

// Load the PHP API helper.
require_once __DIR__ . '/includes/api.php';

// Retrieve subsystem records from the Node.js API.
$apiResult = getSubsystemsFromAPI();

// Use the database records when the API request succeeds.
$subsystems = ($apiResult['success'] && is_array($apiResult['data']))
    ? $apiResult['data']
    : [];

$totalSubsystems = count($subsystems);

// Escape output before displaying database values.
function escapeHTML($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

// Assign CSS classes based on subsystem status.
function getStatusClass($status) {
    switch ($status) {
        case 'Available':
            return 'status-available';

        case 'In Development':
            return 'status-development';

        default:
            return 'status-coming-soon';
    }
}

// Resolve a background image for each expanding panel.
function getSubsystemImage(array $subsystem, $index, array $fallbacks) {

    // Use a custom image supplied by the API when present.
    $custom = trim((string) ($subsystem['image'] ?? ''));

    if ($custom !== '') {
        return $custom;
    }

    // Otherwise try to match a themed image by category/name.
    $haystack = strtolower(
        (string) ($subsystem['category'] ?? '') . ' ' .
        (string) ($subsystem['name'] ?? '')
    );

    $keywordImages = [
        'pharmac'  => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?q=80&w=1200&auto=format&fit=crop',
        'lab'      => 'https://images.unsplash.com/photo-1582719471384-894fbb16e074?q=80&w=1200&auto=format&fit=crop',
        'billing'  => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?q=80&w=1200&auto=format&fit=crop',
        'finance'  => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?q=80&w=1200&auto=format&fit=crop',
        'record'   => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?q=80&w=1200&auto=format&fit=crop',
        'patient'  => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?q=80&w=1200&auto=format&fit=crop',
        'appoint'  => 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?q=80&w=1200&auto=format&fit=crop',
        'inventor' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=1200&auto=format&fit=crop',
        'staff'    => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=1200&auto=format&fit=crop',
        'hr'       => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=1200&auto=format&fit=crop',
        'emergenc' => 'https://images.unsplash.com/photo-1516574187841-cb9cc2ca948b?q=80&w=1200&auto=format&fit=crop',
    ];

    foreach ($keywordImages as $keyword => $image) {
        if (strpos($haystack, $keyword) !== false) {
            return $image;
        }
    }

    // Fall back to a rotating medical image.
    if (!empty($fallbacks)) {
        return $fallbacks[$index % count($fallbacks)];
    }

    return '';
}

$fallbackImages = [
    'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?q=80&w=1200&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?q=80&w=1200&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1538108149393-fbbd81895907?q=80&w=1200&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1551076805-e1869033e561?q=80&w=1200&auto=format&fit=crop',
];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MediLink | Hospital Management Portal</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="app-container">

    <main class="main-content" id="dashboard">


        <!-- Welcome Section -->
        <section class="welcome-section">

            <div>

                <h2>WELCOME TO HOSPITAL MANAGEMENT SYSTEM</h2>

                <p>
                    Access and navigate the different hospital management
                    subsystems through one centralized portal.
                </p>
            </div>

            <div class="welcome-decoration">
                <span>+</span>
                <span>⛨</span>
                <span>+</span>
            </div>

        </section>

        <!-- Hospital Subsystems -->
        <section class="subsystems-section elastic-section" id="subsystems">

            <div class="section-heading">

                <div>
                    <h2>Hospital Subsystems</h2>

                    <p>
                        Select an available subsystem to open its application.
                    </p>
                </div>

                <span class="subsystem-count">
                    <?php echo $totalSubsystems; ?> SYSTEMS
                </span>

            </div>

            
            <?php if (!$apiResult['success']): ?>

                <div class="api-error-message">
                    <strong>Unable to load hospital subsystems.</strong>

                    <p><?php echo escapeHTML($apiResult['message']); ?></p>
                </div>

            <?php endif; ?>


            <?php if (empty($subsystems)): ?>

                <div class="elastic-empty">
                    No hospital subsystems are currently available.
                </div>

            <?php else: ?>

                <!-- Elastic accordion -->
                <div class="elastic-grid" id="elasticGrid">

                    <?php foreach ($subsystems as $index => $subsystem): ?>

                        <?php
                            $status = $subsystem['status'] ?? 'Coming Soon';

                            $url = trim((string) ($subsystem['url'] ?? ''));

                            $urlScheme = strtolower(
                                (string) parse_url($url, PHP_URL_SCHEME)
                            );

                            $isValidUrl =
                                filter_var($url, FILTER_VALIDATE_URL) !== false
                                && in_array($urlScheme, ['http', 'https'], true);

                            $isAvailable =
                                $status === 'Available' && $isValidUrl;

                            $statusClass = getStatusClass($status);

                            $displayStatus = ($status === 'Available' && !$isValidUrl)
                                ? 'Coming Soon'
                                : $status;

                            $image = getSubsystemImage(
                                $subsystem,
                                $index,
                                $fallbackImages
                            );

                            $isActive = $index === 0;
                        ?>

                        <div
                            class="elastic-panel <?php echo $isActive ? 'active' : ''; ?>"
                            data-index="<?php echo (int) $index; ?>"
                            role="button"
                            tabindex="0"
                            aria-expanded="<?php echo $isActive ? 'true' : 'false'; ?>"
                            aria-label="Show <?php echo escapeHTML($subsystem['name']); ?>"
                        >

                            <!-- Background image -->
                            <div class="elastic-bg">
                                <img src="<?php echo escapeHTML($image); ?>" alt="">
                            </div>

                            <!-- Vertical label (inactive) -->
                            <div class="elastic-label">
                                <h3><?php echo escapeHTML($subsystem['category']); ?></h3>
                            </div>

                            <!-- Expanded content -->
                            <div class="elastic-content">

                                <div class="elastic-badges">

                                    <span class="elastic-badge">
                                        <?php echo escapeHTML($subsystem['category']); ?>
                                    </span>

                                    <span class="status-badge elastic-status <?php echo escapeHTML($statusClass); ?>">
                                        <?php echo escapeHTML($displayStatus); ?>
                                    </span>

                                </div>

                                <h2 class="elastic-heading">
                                    <?php echo escapeHTML($subsystem['name']); ?>
                                </h2>

                                <p class="elastic-sub">
                                    <?php echo escapeHTML($subsystem['description']); ?>
                                </p>

                                <?php if ($isAvailable): ?>

                                    <a
                                        class="elastic-btn"
                                        href="<?php echo escapeHTML($url); ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        Open Subsystem →
                                    </a>

                                <?php elseif ($status === 'In Development'): ?>

                                    <span class="elastic-btn disabled">
                                        Currently in development
                                    </span>

                                <?php else: ?>

                                    <span class="elastic-btn disabled">
                                        Not yet available
                                    </span>

                                <?php endif; ?>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


        </section>

    </main>

</div>

<script src="assets/js/main.js"></script>

</body>
</html>