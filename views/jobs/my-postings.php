<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Client') {
  header('Location: ../auth/login.php');
  exit();
}

require_once __DIR__ . '/../../app/core/database.php';
require_once __DIR__ . '/../partials/header.php';

$db = new Database();
$db->openConnection();

$clientId = $_SESSION['user_id'];

$myJobs = $db->select("
    SELECT j.*, n.name as niche_name,
           (SELECT COUNT(*) FROM proposals WHERE job_id = j.id) as proposals_count,
           (SELECT COUNT(*) FROM milestones WHERE job_id = j.id) as milestones_count,
           (SELECT COUNT(*) FROM milestones WHERE job_id = j.id AND status = 'Completed') as completed_milestones_count,
           (SELECT COUNT(*) FROM milestones WHERE job_id = j.id AND status = 'Awaiting Approval') as awaiting_approval_count,
           (SELECT COUNT(*) FROM deliverables d
            JOIN milestones m ON d.milestone_id = m.id
            WHERE m.job_id = j.id AND d.status = 'Approved') as approved_deliverables_count
    FROM jobs j
    LEFT JOIN niche_categories n ON j.niche_id = n.id
    WHERE j.client_id = $clientId
    ORDER BY j.created_at DESC
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

.btn-fh-primary {
    background: #e8a045;
    color: #1a1a2e;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.5rem 1rem;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
}

.btn-fh-primary:hover {
    background: #d4903a;
    transform: translateY(-1px);
}

.btn-fh-outline {
    border: 2px solid #e2dfd8;
    color: #1a1a2e;
    background: transparent;
    border-radius: 8px;
    font-weight: 600;
    padding: 0.5rem 1rem;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
}

.btn-fh-outline:hover {
    border-color: #1a1a2e;
    background: #1a1a2e;
    color: #fff;
}

.status-open {
    background: #27ae60;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-progress {
    background: #e8a045;
    color: #1a1a2e;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-awaiting {
    background: #fff3cd;
    color: #856404;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-approved {
    background: #d4edda;
    color: #155724;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-completed {
    background: #6c757d;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 style="color: #1a1a2e;">My Job Postings</h2>
            <p class="text-muted">Manage your jobs and review proposals from freelancers</p>
        </div>
        <a href="create.php" class="btn-fh-primary"><i class="bi bi-plus-circle me-2"></i>Post New Job</a>
    </div>

    <?php if (empty($myJobs)): ?>
    <div class="text-center p-5" style="background: #fff; border: 2px dashed #e2dfd8; border-radius: 12px;">
        <i class="bi bi-folder2-open" style="font-size: 3rem; color: #e2dfd8;"></i>
        <h4 class="mt-3">No jobs posted yet</h4>
        <p class="text-muted">Click "Post New Job" to create your first job posting.</p>
    </div>
    <?php else: ?>
    <?php foreach ($myJobs as $job): ?>
    <?php
        $milestonesCount = (int) ($job['milestones_count'] ?? 0);
        $completedMilestonesCount = (int) ($job['completed_milestones_count'] ?? 0);
        $awaitingApprovalCount = (int) ($job['awaiting_approval_count'] ?? 0);
        $approvedDeliverablesCount = (int) ($job['approved_deliverables_count'] ?? 0);
    ?>
    <div class="job-card">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-3 mb-2 flex-wrap">
                    <h4 style="color: #1a1a2e;" class="mb-0"><?= htmlspecialchars($job['title']) ?></h4>
                    <?php if ($job['status'] == 'Open'): ?>
                    <span class="status-open">Open for Bids</span>
                    <?php elseif ($job['status'] == 'Completed'): ?>
                    <span class="status-completed">Completed</span>
                    <?php elseif ($awaitingApprovalCount > 0): ?>
                    <span class="status-awaiting">Awaiting Approval</span>
                    <?php elseif ($approvedDeliverablesCount > 0 || $completedMilestonesCount > 0): ?>
                    <span class="status-approved">
                        Milestone Approved<?= $milestonesCount > 1 ? " ($completedMilestonesCount/$milestonesCount)" : '' ?>
                    </span>
                    <?php else: ?>
                    <span class="status-progress">In Progress</span>
                    <?php endif; ?>
                </div>
                <div class="text-muted small mb-2">
                    <span><i class="bi bi-tag me-1"></i> Budget: $<?= number_format($job['budget'], 2) ?></span>
                    <span class="mx-2">|</span>
                    <span><i class="bi bi-calendar me-1"></i> Posted:
                        <?= date('M d, Y', strtotime($job['created_at'])) ?></span>
                    <span class="mx-2">|</span>
                    <span><i class="bi bi-folder me-1"></i> Niche:
                        <?= htmlspecialchars($job['niche_name'] ?? 'General') ?></span>
                </div>
                <p class="text-muted small mb-0"><?= htmlspecialchars(substr($job['description'], 0, 120)) ?>...</p>
            </div>
            <div class="text-end">
                <?php if ($job['status'] == 'Open'): ?>
                <a href="../proposals/list.php?job_id=<?= $job['id'] ?>" class="btn-fh-primary d-block mb-2">
                    <i class="bi bi-file-text me-1"></i> Review Proposals (<?= $job['proposals_count'] ?? 0 ?>)
                </a>
                <a href="show.php?id=<?= $job['id'] ?>" class="btn-fh-outline d-block">View Details</a>
                <?php else: ?>
                <a href="show.php?id=<?= $job['id'] ?>" class="btn-fh-outline d-block">View Details</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
