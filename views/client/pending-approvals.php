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

$pendingSubmissions = $db->select("
    SELECT d.*, m.title as milestone_title, j.title as job_title, j.id as job_id,
           u.name as freelancer_name, m.id as milestone_id
    FROM deliverables d
    JOIN milestones m ON d.milestone_id = m.id
    JOIN jobs j ON m.job_id = j.id
    JOIN users u ON d.freelancer_id = u.id
    WHERE j.client_id = $clientId AND d.status = 'Pending'
    ORDER BY d.submitted_at DESC
");

$db->closeConnection();
?>

<div class="container py-4">
    <div class="card">
        <div class="card-header bg-white">
            <h4 class="mb-0">Pending Approvals</h4>
        </div>
        <div class="card-body">
            <?php if (empty($pendingSubmissions)): ?>
            <div class="alert alert-info">No pending submissions found.</div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Job</th>
                            <th>Milestone</th>
                            <th>Freelancer</th>
                            <th>Submitted</th>
                            <th>Notes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingSubmissions as $submission): ?>
                        <tr>
                            <td><?= htmlspecialchars($submission['job_title']) ?></td>
                            <td><?= htmlspecialchars($submission['milestone_title']) ?></td>
                            <td><?= htmlspecialchars($submission['freelancer_name']) ?></td>
                            <td><?= date('M d, Y', strtotime($submission['submitted_at'])) ?></td>
                            <td><?= htmlspecialchars(substr($submission['notes'], 0, 50)) ?>...</td>
                            <td>
                                <a href="approve-work.php?id=<?= $submission['id'] ?>&milestone_id=<?= $submission['milestone_id'] ?>&job_id=<?= $submission['job_id'] ?>"
                                    class="btn btn-success btn-sm"
                                    onclick="return confirm('Approve this work?')">Approve</a>
                                <a href="reject-work.php?id=<?= $submission['id'] ?>&milestone_id=<?= $submission['milestone_id'] ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Reject this work?')">Reject</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>