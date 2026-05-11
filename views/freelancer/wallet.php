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

// Get freelancer wallet balance
$user = $db->select("SELECT name, wallet_balance, total_earned FROM users WHERE id = $freelancerId");
$balance = $user[0]['wallet_balance'] ?? 0;
$totalEarned = $user[0]['total_earned'] ?? 0;
$name = $user[0]['name'] ?? '';

// Get recent transactions (approved payments)
$transactions = $db->select("
    SELECT d.*, j.title as job_title, m.title as milestone_title, d.approved_at
    FROM deliverables d
    JOIN milestones m ON d.milestone_id = m.id
    JOIN jobs j ON m.job_id = j.id
    WHERE d.freelancer_id = $freelancerId AND d.status = 'Approved'
    ORDER BY d.approved_at DESC
    LIMIT 10
");

$db->closeConnection();
?>

<style>
.wallet-card {
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    text-align: center;
}

.balance-amount {
    font-size: 48px;
    font-weight: 800;
    color: #27ae60;
}

.balance-label {
    color: #6c757d;
    font-size: 14px;
    text-transform: uppercase;
}

.total-earned {
    font-size: 24px;
    font-weight: 700;
    color: #1a1a2e;
}

.btn-withdraw {
    background: #e8a045;
    color: #1a1a2e;
    border: none;
    border-radius: 8px;
    padding: 10px 24px;
    font-weight: 700;
    text-decoration: none;
    display: inline-block;
}

.btn-withdraw:hover {
    background: #d4903a;
}

.transaction-table {
    width: 100%;
    border-collapse: collapse;
}

.transaction-table th,
.transaction-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e2dfd8;
}

.transaction-table th {
    background: #f8f9fa;
    color: #1a1a2e;
}

.status-approved {
    color: #27ae60;
    font-weight: 600;
}
</style>

<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <h2 style="color: #1a1a2e;">My Wallet</h2>
            <p class="text-muted">Manage your earnings and withdrawals</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="wallet-card">
                <div class="balance-label">Available Balance</div>
                <div class="balance-amount">$<?= number_format($balance, 2) ?></div>
                <a href="#" class="btn-withdraw mt-3" onclick="alert('Withdrawal request feature coming soon!')">
                    <i class="bi bi-arrow-right"></i> Withdraw Funds
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="wallet-card">
                <div class="balance-label">Total Lifetime Earnings</div>
                <div class="total-earned">$<?= number_format($totalEarned, 2) ?></div>
                <p class="text-muted mt-2"><i class="bi bi-info-circle"></i> All time earnings from completed milestones
                </p>
            </div>
        </div>
    </div>

    <div class="card" style="background: #fff; border: 1.5px solid #e2dfd8; border-radius: 12px; padding: 1.5rem;">
        <h4 style="color: #1a1a2e;">Recent Transactions</h4>

        <?php if (empty($transactions)): ?>
        <div class="text-center p-5">
            <i class="bi bi-receipt" style="font-size: 48px; color: #e2dfd8;"></i>
            <p class="mt-3 text-muted">No transactions yet. Complete milestones to get paid!</p>
        </div>
        <?php else: ?>
        <table class="transaction-table">
            <thead>
                <tr>
                    <th>Job</th>
                    <th>Milestone</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= htmlspecialchars($transaction['job_title']) ?></td>
                    <td><?= htmlspecialchars($transaction['milestone_title']) ?></td>
                    <td><strong>$<?= number_format($transaction['amount'] ?? 0, 2) ?></strong></td>
                    <td><?= date('M d, Y', strtotime($transaction['approved_at'])) ?></td>
                    <td><span class="status-approved">✓ Approved</span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>