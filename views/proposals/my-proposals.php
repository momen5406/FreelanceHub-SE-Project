<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Freelancer') {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../app/core/database.php';
require_once __DIR__ . '/../partials/header.php';

$db = new Database();
$db->openConnection();

$freelancerId = $_SESSION['user_id'];

$proposals = $db->select("
    SELECT p.*, j.title as job_title, j.budget as job_budget, j.status as job_status,
           u.name as client_name
    FROM proposals p
    LEFT JOIN jobs j ON p.job_id = j.id
    LEFT JOIN users u ON j.client_id = u.id
    WHERE p.freelancer_id = $freelancerId
    ORDER BY p.created_at DESC
");

$db->closeConnection();
?>

<style>
.fh-card {
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.2s;
}

.fh-card:hover {
    border-color: #e8a045;
    box-shadow: 0 8px 25px rgba(232, 160, 69, 0.1);
}

.status-pending {
    background: #fff3cd;
    color: #856404;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-accepted {
    background: #d4edda;
    color: #155724;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.btn-fh-outline {
    border: 2px solid #e2dfd8;
    color: #1a1a2e;
    background: transparent;
    border-radius: 8px;
    font-weight: 600;
    padding: 0.4rem 1rem;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
    font-size: 0.85rem;
}

.btn-fh-outline:hover {
    border-color: #e8a045;
    background: #e8a045;
    color: #1a1a2e;
}
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 style="color: #1a1a2e;">My Proposals</h2>
            <p class="text-muted">Track all your submitted proposals and their status</p>
        </div>
        <a href="../jobs/index.php" class="btn-fh-outline"><i class="bi bi-search me-2"></i>Browse More Jobs</a>
    </div>

    <?php if (empty($proposals)): ?>
    <div class="text-center p-5" style="background: #fff; border: 2px dashed #e2dfd8; border-radius: 12px;">
        <i class="bi bi-file-text" style="font-size: 3rem; color: #e2dfd8;"></i>
        <h4 class="mt-3">No proposals submitted yet</h4>
        <p class="text-muted">Browse jobs and submit your first proposal.</p>
        <a href="../jobs/index.php" class="btn-fh-outline mt-3">Browse Jobs</a>
    </div>
    <?php else: ?>
    <?php foreach ($proposals as $proposal): ?>
    <div class="fh-card">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-3 mb-2 flex-wrap">
                    <h4 style="color: #1a1a2e;" class="mb-0"><?= htmlspecialchars($proposal['job_title']) ?></h4>
                    <?php if ($proposal['status'] == 'Pending'): ?>
                    <span class="status-pending">Pending Review</span>
                    <?php elseif ($proposal['status'] == 'Accepted'): ?>
                    <span class="status-accepted">Accepted ✓</span>
                    <?php else: ?>
                    <span class="status-rejected">Rejected ✗</span>
                    <?php endif; ?>
                </div>

                <div class="text-muted small mb-3">
                    <span><i class="bi bi-person me-1"></i> Client:
                        <?= htmlspecialchars($proposal['client_name'] ?? 'Unknown') ?></span>
                    <span class="mx-2">|</span>
                    <span><i class="bi bi-currency-dollar me-1"></i> Your Bid:
                        $<?= number_format($proposal['bid_amount'], 2) ?></span>
                    <span class="mx-2">|</span>
                    <span><i class="bi bi-tag me-1"></i> Job Budget:
                        $<?= number_format($proposal['job_budget'], 2) ?></span>
                    <span class="mx-2">|</span>
                    <span><i class="bi bi-calendar me-1"></i> Submitted:
                        <?= date('M d, Y', strtotime($proposal['created_at'])) ?></span>
                </div>

                <div class="mb-2">
                    <strong>Your Proposal Message:</strong>
                    <p class="text-muted mt-1 mb-0">
                        <?= nl2br(htmlspecialchars(substr($proposal['description'], 0, 200))) ?>...</p>
                </div>
            </div>
            <div class="text-end">
                <a href="../jobs/view.php?id=<?= $proposal['job_id'] ?>" class="btn-fh-outline">
                    <i class="bi bi-eye me-1"></i> View Job
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>