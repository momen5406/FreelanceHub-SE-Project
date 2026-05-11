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

$jobs = $db->select("
    SELECT j.*, u.name as client_name,
           (SELECT id FROM milestones WHERE job_id = j.id AND status != 'Completed' LIMIT 1) as milestone_id,
           (SELECT status FROM milestones WHERE job_id = j.id AND status != 'Completed' LIMIT 1) as milestone_status,
           (SELECT status FROM deliverables d 
            WHERE d.milestone_id = (SELECT id FROM milestones WHERE job_id = j.id LIMIT 1) 
            AND d.freelancer_id = $freelancerId LIMIT 1) as submission_status
    FROM jobs j
    LEFT JOIN users u ON j.client_id = u.id
    WHERE j.assigned_freelancer_id = $freelancerId
    GROUP BY j.id
    ORDER BY 
        CASE j.status 
            WHEN 'In Progress' THEN 1 
            WHEN 'Completed' THEN 2 
            ELSE 3 
        END,
        j.created_at DESC
");

$db->closeConnection();
?>

<style>
.job-card {
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.2s;
}

.job-card:hover {
    border-color: #e8a045;
    box-shadow: 0 8px 25px rgba(232, 160, 69, 0.1);
}

.btn-submit {
    background: #27ae60;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    text-decoration: none;
    display: inline-block;
    font-size: 0.85rem;
}

.btn-submit:hover {
    background: #219a52;
    color: white;
}

.btn-view {
    background: #1a1a2e;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    text-decoration: none;
    display: inline-block;
    margin-left: 10px;
    font-size: 0.85rem;
}

.btn-view:hover {
    background: #2d2d4a;
    color: white;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.7rem;
    display: inline-block;
    font-weight: 600;
}

.status-progress {
    background: #e8a04520;
    color: #e8a045;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-approved {
    background: #d4edda;
    color: #155724;
}

.status-completed {
    background: #d4edda;
    color: #155724;
}

.btn-waiting {
    background: #f39c12;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    cursor: not-allowed;
    font-size: 0.85rem;
}

.btn-disabled {
    background: #95a5a6;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    cursor: not-allowed;
    font-size: 0.85rem;
}

@media (max-width: 768px) {
    .job-card .d-flex {
        flex-direction: column;
    }

    .text-end {
        text-align: left !important;
        margin-top: 15px;
    }

    .btn-view {
        margin-left: 0;
        margin-top: 10px;
    }
}
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 style="color: #1a1a2e;">My Jobs</h2>
            <p class="text-muted">Jobs where you have been hired</p>
        </div>
        <a href="../jobs/index.php" class="btn btn-outline-secondary">Browse More Jobs</a>
    </div>

    <?php if (empty($jobs)): ?>
    <div class="text-center p-5" style="background: #fff; border: 2px dashed #e2dfd8; border-radius: 12px;">
        <i class="bi bi-briefcase" style="font-size: 48px; color: #e2dfd8;"></i>
        <h4 class="mt-3">No jobs yet</h4>
        <p class="text-muted">When a client accepts your proposal, the job will appear here.</p>
        <a href="../jobs/index.php" class="btn-submit mt-2">Browse Jobs</a>
    </div>
    <?php else: ?>
    <?php foreach ($jobs as $job): ?>
    <div class="job-card">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div class="flex-grow-1">
                <h4 style="color: #1a1a2e; margin-bottom: 8px;"><?= htmlspecialchars($job['title']) ?></h4>
                <div class="text-muted small mb-2">
                    <span><i class="bi bi-person"></i> Client: <?= htmlspecialchars($job['client_name']) ?></span>
                    <span class="mx-2">|</span>
                    <span><i class="bi bi-currency-dollar"></i> Budget: $<?= number_format($job['budget'], 2) ?></span>
                    <span class="mx-2">|</span>
                    <span><i class="bi bi-calendar"></i> Posted:
                        <?= date('M d, Y', strtotime($job['created_at'])) ?></span>
                </div>

                <?php if ($job['status'] == 'Completed'): ?>
                <span class="status-badge status-completed">
                    <i class="bi bi-trophy"></i> Job Completed - Great Work!
                </span>
                <?php elseif ($job['milestone_status'] == 'Awaiting Approval'): ?>
                <span class="status-badge status-pending">
                    <i class="bi bi-clock-history"></i> Awaiting Client Approval
                </span>
                <?php elseif ($job['milestone_status'] == 'Completed'): ?>
                <span class="status-badge status-approved">
                    <i class="bi bi-check-circle"></i> Milestone Completed
                </span>
                <?php elseif ($job['submission_status'] == 'Approved'): ?>
                <span class="status-badge status-approved">
                    <i class="bi bi-check-circle"></i> Approved
                </span>
                <?php else: ?>
                <span class="status-badge status-progress">
                    <i class="bi bi-play-circle"></i> In Progress
                </span>
                <?php endif; ?>
            </div>
            <div class="text-end">
                <?php if ($job['status'] == 'Completed'): ?>
                <button class="btn-disabled" disabled>
                    <i class="bi bi-trophy"></i> Completed
                </button>
                <?php elseif ($job['milestone_status'] == 'Awaiting Approval'): ?>
                <button class="btn-waiting" disabled>
                    <i class="bi bi-clock"></i> Waiting for Client
                </button>
                <?php elseif ($job['milestone_status'] == 'Completed'): ?>
                <button class="btn-disabled" disabled>
                    <i class="bi bi-check-circle"></i> Milestone Done
                </button>
                <?php elseif ($job['submission_status'] == 'Approved'): ?>
                <button class="btn-disabled" disabled>
                    <i class="bi bi-check-circle"></i> Already Approved
                </button>
                <?php elseif ($job['milestone_id']): ?>
                <a href="../milestones/submit-work.php?job_id=<?= $job['id'] ?>&milestone_id=<?= $job['milestone_id'] ?>"
                    class="btn-submit">
                    <i class="bi bi-cloud-upload"></i> Submit Work
                </a>
                <?php else: ?>
                <button class="btn-disabled" disabled>
                    <i class="bi bi-hourglass"></i> No Milestones
                </button>
                <?php endif; ?>
                <a href="../jobs/view.php?id=<?= $job['id'] ?>" class="btn-view">
                    <i class="bi bi-eye"></i> View
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>