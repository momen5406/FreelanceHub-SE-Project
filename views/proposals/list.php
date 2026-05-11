<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Client') {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../app/core/database.php';
require_once __DIR__ . '/../partials/header.php';

$jobId = $_GET['job_id'] ?? 0;

$db = new Database();
$db->openConnection();

$job = $db->select("SELECT * FROM jobs WHERE id = $jobId AND client_id = " . $_SESSION['user_id']);

if (empty($job)) {
    echo "<div class='container mt-5'><h3>Job not found</h3></div>";
    require_once __DIR__ . '/../partials/footer.php';
    exit();
}

$job = $job[0];

$proposals = $db->select("
    SELECT p.*, u.name as freelancer_name, u.reputation_score
    FROM proposals p
    LEFT JOIN users u ON p.freelancer_id = u.id
    WHERE p.job_id = $jobId
    ORDER BY 
        CASE p.status 
            WHEN 'Pending' THEN 1 
            WHEN 'Accepted' THEN 2 
            WHEN 'Rejected' THEN 3 
        END,
        p.bid_amount ASC
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

.btn-accept {
    background: #27ae60;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.4rem 1rem;
    font-size: 0.85rem;
}

.btn-reject {
    background: #e74c3c;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.4rem 1rem;
    font-size: 0.85rem;
}

.btn-accept:hover {
    background: #219a52;
}

.btn-reject:hover {
    background: #c0392b;
}
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 style="color: #1a1a2e;">Proposals for: <?= htmlspecialchars($job['title']) ?></h2>
            <p class="text-muted">Review and manage freelancer proposals</p>
        </div>
        <a href="../jobs/my-postings.php" class="btn-fh-outline">← Back to My Jobs</a>
    </div>

    <?php if (empty($proposals)): ?>
    <div class="text-center p-5 fh-card">
        <i class="bi bi-inbox" style="font-size: 3rem; color: #e2dfd8;"></i>
        <h4 class="mt-3">No proposals yet</h4>
        <p class="text-muted">Freelancers haven't submitted proposals for this job yet.</p>
    </div>
    <?php else: ?>
    <?php foreach ($proposals as $proposal): ?>
    <div class="fh-card">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h5 class="mb-1" style="color: #1a1a2e;"><?= htmlspecialchars($proposal['freelancer_name']) ?></h5>
                <div class="text-warning small">
                    <i class="bi bi-star-fill"></i> <?= number_format($proposal['reputation_score'] ?? 0, 1) ?> / 5.0
                </div>
            </div>
            <div>
                <?php if ($proposal['status'] == 'Pending'): ?>
                <span class="status-pending">Pending Review</span>
                <?php elseif ($proposal['status'] == 'Accepted'): ?>
                <span class="status-accepted">Accepted ✓</span>
                <?php else: ?>
                <span class="status-rejected">Rejected ✗</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-3">
            <strong>Bid Amount:</strong> $<?= number_format($proposal['bid_amount'], 2) ?>
        </div>

        <div class="mb-3">
            <strong>Proposal Message:</strong>
            <p class="text-muted mt-2"><?= nl2br(htmlspecialchars($proposal['description'])) ?></p>
        </div>

        <?php if ($proposal['status'] == 'Pending'): ?>
        <div class="d-flex gap-2 mt-3">
            <a href="accept.php?id=<?= $proposal['id'] ?>&job_id=<?= $jobId ?>" class="btn-accept"
                onclick="return confirm('Accept this proposal? The freelancer will be hired.')">
                <i class="bi bi-check-circle me-1"></i> Accept & Hire
            </a>
            <a href="reject.php?id=<?= $proposal['id'] ?>&job_id=<?= $jobId ?>" class="btn-reject"
                onclick="return confirm('Reject this proposal?')">
                <i class="bi bi-x-circle me-1"></i> Reject
            </a>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>