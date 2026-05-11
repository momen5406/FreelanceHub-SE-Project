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

$user = $db->select("SELECT wallet_balance, total_earned FROM users WHERE id = $freelancerId");
$balance = $user[0]['wallet_balance'] ?? 0;
$totalEarned = $user[0]['total_earned'] ?? 0;

$transactions = $db->select("
    SELECT j.title as job_title, m.title as milestone_title, d.approved_at
    FROM deliverables d
    JOIN milestones m ON d.milestone_id = m.id
    JOIN jobs j ON m.job_id = j.id
    WHERE d.freelancer_id = $freelancerId AND d.status = 'Approved'
    ORDER BY d.approved_at DESC
    LIMIT 5
");

$db->closeConnection();
?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card text-center p-4 mb-4">
                <h5>Available Balance</h5>
                <h2 style="color: #27ae60;">$<?= number_format($balance, 2) ?></h2>
            </div>
        </div>
        <div class="col-md-6 mx-auto">
            <div class="card text-center p-4 mb-4">
                <h5>Total Earned</h5>
                <h3>$<?= number_format($totalEarned, 2) ?></h3>
            </div>
        </div>
    </div>

    <div class="card p-3">
        <h5>Recent Payments</h5>
        <?php if (empty($transactions)): ?>
        <p class="text-muted text-center py-3">No payments yet</p>
        <?php else: ?>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Job</th>
                    <th>Milestone</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['job_title']) ?></td>
                    <td><?= htmlspecialchars($t['milestone_title']) ?></td>
                    <td><?= date('M d, Y', strtotime($t['approved_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>