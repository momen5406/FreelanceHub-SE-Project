<?php
session_start();

require_once __DIR__ . '/../../app/core/database.php';
require_once __DIR__ . '/../partials/header.php';

$jobId = $_GET['id'] ?? 0;

$db = new Database();
$db->openConnection();

$job = $db->select("
    SELECT j.*, u.name as client_name, u.reputation_score as client_reputation, n.name as category
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

<style>
.fh-card {
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(26, 26, 46, 0.04);
}

.job-title {
    color: #1a1a2e;
    font-weight: 800;
    font-size: 2rem;
    letter-spacing: -0.5px;
}

.meta-badge {
    background: #f5f4f0;
    color: #6c757d;
    font-weight: 600;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    font-size: 0.85rem;
}

.budget-display {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1a1a2e;
    line-height: 1;
}

.btn-fh-primary {
    background: #e8a045;
    color: #1a1a2e;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.8rem;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-fh-primary:hover {
    background: #d4903a;
    transform: translateY(-1px);
    color: #1a1a2e;
}

.btn-fh-outline {
    border: 2px solid #1a1a2e;
    color: #1a1a2e;
    background: transparent;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.8rem;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-fh-outline:hover {
    background: #1a1a2e;
    color: #fff;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.8rem;
    text-align: center;
    display: block;
}

.detail-section {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e2dfd8;
}

.detail-label {
    font-weight: 700;
    color: #1a1a2e;
    min-width: 150px;
    display: inline-block;
}
</style>

<!-- Breadcrumb Navigation -->
<nav aria-label="breadcrumb" class="mb-4 mt-2">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php" class="text-muted text-decoration-none">Jobs</a></li>
        <li class="breadcrumb-item text-muted" aria-current="page">
            <?= htmlspecialchars($job['category'] ?? 'Job Details') ?></li>
    </ol>
</nav>

<div class="row g-4 mb-5">

    <!-- LEFT COLUMN: Job Details -->
    <div class="col-lg-8">
        <div class="fh-card p-4 p-md-5 h-100">
            <h1 class="job-title mb-3"><?= htmlspecialchars($job['title']) ?></h1>

            <div class="d-flex flex-wrap gap-2 mb-4 pb-4 border-bottom">
                <span class="meta-badge"><i class="bi bi-tag me-1"></i>
                    <?= htmlspecialchars($job['category'] ?? 'General') ?></span>
                <?php if ($job['status'] === 'Open'): ?>
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-3 fw-bold">Open for
                    Bids</span>
                <?php elseif ($job['status'] === 'In Progress'): ?>
                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-3 fw-bold">In Progress</span>
                <?php else: ?>
                <span
                    class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-3 fw-bold"><?= htmlspecialchars($job['status']) ?></span>
                <?php endif; ?>
            </div>

            <h5 class="fw-bold mb-3" style="color: #1a1a2e;">Project Description</h5>
            <div class="text-muted lh-lg" style="font-size: 1.05rem;">
                <?= nl2br(htmlspecialchars($job['description'])) ?>
            </div>

            <?php if ($dynamicFields && !empty($dynamicFields)): ?>
            <div class="detail-section">
                <h5 class="fw-bold mb-3" style="color: #1a1a2e;">Additional Requirements</h5>
                <?php foreach ($dynamicFields as $key => $value):
            if (empty($value)) continue;
            $label = ucfirst(str_replace('_', ' ', $key));
          ?>
                <div class="mb-2">
                    <span class="detail-label"><?= htmlspecialchars($label) ?>:</span>
                    <?php if (is_array($value)): ?>
                    <?php foreach ($value as $v): ?>
                    <span class="badge bg-light text-dark me-1"><?= htmlspecialchars($v) ?></span>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <span><?= htmlspecialchars($value) ?></span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- RIGHT COLUMN: Action Panel -->
    <div class="col-lg-4">

        <!-- Budget & Action Card -->
        <div class="fh-card p-4 mb-4" style="border-top: 4px solid #1a1a2e;">
            <p class="text-muted fw-bold mb-1">Estimated Budget</p>
            <div class="budget-display mb-4">$<?= number_format($job['budget']) ?></div>

            <div class="d-grid gap-3">

                <?php if (!isset($_SESSION['user_id'])): ?>
                <!-- GUEST: Not logged in -->
                <a href="../auth/login.php" class="btn-fh-primary">Log in to Apply</a>

                <?php elseif ($_SESSION['role'] === 'Freelancer'): ?>
                <!-- FREELANCER: Can submit a bid -->
                <a href="../proposals/submit.php?job_id=<?= $job['id'] ?>" class="btn-fh-primary">
                    <i class="bi bi-send me-2"></i>Submit Proposal
                </a>

                <?php elseif ($_SESSION['role'] === 'Client' && $_SESSION['user_id'] == $job['client_id']): ?>
                <!-- JOB OWNER: Can view proposals or edit their own job -->
                <a href="../proposals/list.php?job_id=<?= $job['id'] ?>" class="btn-fh-primary">
                    <i class="bi bi-file-earmark-person me-2"></i>Review Proposals
                </a>
                <a href="edit.php?id=<?= $job['id'] ?>" class="btn-fh-outline">
                    <i class="bi bi-pencil me-2"></i>Edit Job Details
                </a>

                <?php elseif ($_SESSION['role'] === 'Client' && $_SESSION['user_id'] != $job['client_id']): ?>
                <!-- OTHER CLIENT: Just browsing someone else's job -->
                <button class="btn-secondary" disabled style="border: none; cursor: not-allowed;">Clients cannot bid on
                    jobs</button>

                <?php endif; ?>

            </div>
        </div>

        <!-- About the Client Card -->
        <div class="fh-card p-4">
            <h6 class="fw-bold mb-3" style="color: #1a1a2e;">About the Client</h6>
            <div class="d-flex align-items-center gap-3 mb-3">
                <div
                    style="width: 45px; height: 45px; background: #e8a045; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #1a1a2e; font-size: 1.2rem;">
                    <?= strtoupper(substr($job['client_name'], 0, 1)) ?>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($job['client_name']) ?></h6>
                    <div class="text-warning small mt-1">
                        <i class="bi bi-star-fill"></i> <?= number_format($job['client_reputation'] ?? 0, 1) ?> / 5.0
                    </div>
                </div>
            </div>
            <hr class="text-muted opacity-25">
            <div class="text-muted small">
                <i class="bi bi-check-circle-fill text-success me-1"></i> Payment Verified
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>