<?php
session_start();

require_once __DIR__ . '/../../app/core/database.php';
require_once __DIR__ . '/../partials/header.php';

$jobId = $_GET['id'] ?? 0;

$db = new Database();
$db->openConnection();

$job = $db->select("
    SELECT j.*, u.name as client_name, n.name as niche_name
    FROM jobs j
    LEFT JOIN users u ON j.client_id = u.id
    LEFT JOIN niche_categories n ON j.niche_id = n.id
    WHERE j.id = $jobId
");

$db->closeConnection();

if (empty($job)) {
    echo "<div class='container mt-5'><h3>Job not found</h3></div>";
    require_once __DIR__ . '/../partials/footer.php';
    exit();
}

$job = $job[0];
$dynamicFields = json_decode($job['dynamic_fields'], true);
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header" style="background: #e8a045; color: #1a1a2e;">
                    <h3 class="mb-0"><?= htmlspecialchars($job['title']) ?></h3>
                </div>
                <div class="card-body">
                    <div class="mb-3"><strong>💰 Budget:</strong> $<?= number_format($job['budget'], 2) ?></div>
                    <div class="mb-3"><strong>📂 Niche:</strong> <?= htmlspecialchars($job['niche_name']) ?></div>
                    <div class="mb-3"><strong>📅 Posted:</strong> <?= date('M d, Y', strtotime($job['created_at'])) ?>
                    </div>
                    <div class="mb-3"><strong>📝
                            Description:</strong><br><?= nl2br(htmlspecialchars($job['description'])) ?></div>

                    <?php if ($dynamicFields && !empty($dynamicFields)): ?>
                    <div class="mb-3"><strong>⚙️ Additional Requirements:</strong><br>
                        <ul class="list-group">
                            <?php foreach ($dynamicFields as $key => $value):
                                    if (empty($value)) continue;
                                    $label = ucfirst(str_replace('_', ' ', $key));
                                    if (is_array($value)) {
                                        $value = implode(', ', $value);
                                    }
                                ?>
                            <li class="list-group-item"><strong><?= htmlspecialchars($label) ?>:</strong>
                                <?= htmlspecialchars($value) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <div class="text-center mt-4">
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'Freelancer'): ?>
                        <a href="../proposals/create.php?job_id=<?= $job['id'] ?>" class="btn"
                            style="background: #e8a045; color: #1a1a2e;">Submit Proposal</a>
                        <?php elseif (!isset($_SESSION['user_id'])): ?>
                        <a href="../auth/login.php" class="btn" style="background: #e8a045; color: #1a1a2e;">Login to
                            Apply</a>
                        <?php endif; ?>
                        <a href="index.php" class="btn btn-secondary">Browse More Jobs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>