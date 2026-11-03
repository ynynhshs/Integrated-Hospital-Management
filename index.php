
<?php

// Load the PHP API helper.
require_once __DIR__ . '/includes/api.php';

// Retrieve subsystem records from the Node.js API.
$apiResult = getSubsystemsFromAPI();

// Use the database records when the API request succeeds.
$subsystems = $apiResult['success']
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
                <p class="welcome-eyebrow">WELCOME TO MEDILINK</p>

                <h2>One Portal. Connected Hospital Services.</h2>

                <p>
                    Access and navigate the different hospital management
                    subsystems through one centralized portal.
                </p>
            </div>

            <div class="welcome-decoration">
                <span>+</span>
                <span>✚</span>
                <span>+</span>
            </div>

        </section>

        <!-- Hospital Subsystems -->
        <section class="subsystems-section" id="subsystems">

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



            <div class="subsystem-grid">

                <?php foreach ($subsystems as $subsystem): ?>

                    <?php
                        $isAvailable =
                            $subsystem['status'] === 'Available'
                            && !empty($subsystem['url']);

                        $statusClass = getStatusClass($subsystem['status']);
                    ?>

                    <article class="subsystem-card">

                        <div class="card-top">

                            <div class="subsystem-number">
                                <?php echo sprintf('%02d', $subsystem['id']); ?>
                            </div>

                            <span class="status-badge <?php echo escapeHTML($statusClass); ?>">
                                <?php echo escapeHTML($subsystem['status']); ?>
                            </span>

                        </div>

                        <div class="card-content">

                            <span class="subsystem-category">
                                <?php echo escapeHTML($subsystem['category']); ?>
                            </span>

                            <h3>
                                <?php echo escapeHTML($subsystem['name']); ?>
                            </h3>

                            <p>
                                <?php echo escapeHTML($subsystem['description']); ?>
                            </p>

                        </div>

                        <div class="card-footer">

                            <?php if ($isAvailable): ?>

                                <a
                                    href="<?php echo escapeHTML($subsystem['url']); ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="subsystem-link"
                                >
                                    Open Subsystem
                                    <span>↗</span>
                                </a>

                            <?php elseif ($subsystem['status'] === 'In Development'): ?>

                                <span class="card-action development-action">
                                    Currently in development
                                </span>

                            <?php else: ?>

                                <span class="card-action coming-action">
                                    Not yet available
                                </span>

                            <?php endif; ?>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        </section>

    </main>

</div>

</body>
</html>
