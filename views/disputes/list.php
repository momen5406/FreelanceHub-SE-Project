<?php
require_once __DIR__ . '/../../app/controllers/DisputeController.php';
$controller = new DisputeController();
$controller->requireLogin();
if (($_SESSION['role'] ?? '') !== 'Client' && ($_SESSION['role'] ?? '') !== 'Freelancer' && ($_SESSION['role'] ?? '') !== 'Admin' && ($_SESSION['role'] ?? '') !== 'Dispute Mediator') {
    header('Location: ../auth/login.php');
    exit();
}
$disputes = $controller->getListData();
require_once __DIR__ . '/../partials/header.php';
?>

<style>
.fh-card {
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(26, 26, 46, 0.04);
}

.status-badge {
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.3rem 0.65rem;
    border-radius: 20px;
}

.status-open {
    background: rgba(232, 160, 69, 0.15);
    color: #a25f12;
}

.status-review {
    background: rgba(33, 150, 243, 0.12);
    color: #1b6ca8;
}

.status-resolved {
    background: rgba(46, 204, 113, 0.14);
    color: #1b8a4a;
}

.status-dismissed {
    background: rgba(231, 76, 60, 0.12);
    color: #b3392f;
}

.btn-fh-primary {
    background: #e8a045;
    color: #1a1a2e;
    border: none;
    border-radius: 8px;
    font-weight: 700;
}

.btn-fh-primary:hover {
    background: #d4903a;
    color: #1a1a2e;
}
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1a1a2e;">My Disputes</h2>
            <p class="text-muted mb-0">Track dispute status and continue dispute conversations.</p>
        </div>
    </div>

    <div class="fh-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead style="background:#f5f4f0;">
                    <tr>
                        <th class="px-4 py-3 text-muted">Job</th>
                        <th class="px-4 py-3 text-muted">Status</th>
                        <th class="px-4 py-3 text-muted">Opened</th>
                        <th class="px-4 py-3 text-muted text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($disputes)): ?>
                    <tr>
                        <td colspan="4" class="px-4 py-5 text-center text-muted">No disputes found.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($disputes as $dispute): ?>
                    <?php
                        $statusClass = 'status-open';
                        if ($dispute['status'] === 'Under Review') {
                            $statusClass = 'status-review';
                        } elseif ($dispute['status'] === 'Resolved') {
                            $statusClass = 'status-resolved';
                        } elseif ($dispute['status'] === 'Dismissed') {
                            $statusClass = 'status-dismissed';
                        }
                        ?>
                    <tr>
                        <td class="px-4 py-3 fw-semibold"><?= htmlspecialchars($dispute['job_title'] ?? 'Job') ?></td>
                        <td class="px-4 py-3">
                            <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($dispute['status']) ?></span>
                        </td>
                        <td class="px-4 py-3 text-muted"><?= date('M d, Y', strtotime($dispute['created_at'])) ?></td>
                        <td class="px-4 py-3 text-end">
                            <a href="view.php?id=<?= (int)$dispute['id'] ?>" class="btn btn-sm btn-fh-primary">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
