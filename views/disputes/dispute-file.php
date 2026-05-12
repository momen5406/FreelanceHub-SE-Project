<?php
require_once __DIR__ . '/../../app/core/database.php';
require_once __DIR__ . '/../partials/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$role = $_SESSION['role'] ?? '';
$isModerator = ($role === 'Admin' || $role === 'Dispute Mediator');
$disputeId = (int)($_GET['id'] ?? 0);

if ($disputeId <= 0) {
    header('Location: list.php');
    exit();
}

$db = new Database();
$db->openConnection();

$dispute = $db->select("
    SELECT d.*, j.title as job_title, j.client_id, j.assigned_freelancer_id,
           rb.name as raised_by_name, au.name as against_user_name
    FROM dispute d
    LEFT JOIN jobs j ON d.job_id = j.id
    LEFT JOIN users rb ON d.raised_by_id = rb.id
    LEFT JOIN users au ON d.against_user_id = au.id
    WHERE d.id = $disputeId
    LIMIT 1
");

if (empty($dispute)) {
    $db->closeConnection();
    header('Location: list.php');
    exit();
}

$dispute = $dispute[0];
$userId = (int)$_SESSION['user_id'];

if (!$isModerator && $userId !== (int)$dispute['raised_by_id'] && $userId !== (int)$dispute['against_user_id']) {
    $db->closeConnection();
    header('Location: list.php');
    exit();
}

$messages = $db->select("
    SELECT dm.*, u.name as sender_name
    FROM dispute_messages dm
    LEFT JOIN users u ON dm.user_id = u.id
    WHERE dm.dispute_id = $disputeId
    ORDER BY dm.created_at ASC
");

$safeMessages = $db->select("
    SELECT sm.*, u.name as sender_name
    FROM dispute_safe_room_messages sm
    LEFT JOIN users u ON sm.user_id = u.id
    WHERE sm.dispute_id = $disputeId
    ORDER BY sm.created_at ASC
");

$milestones = $db->select("
    SELECT m.*, e.amount as escrow_amount, e.status as escrow_status
    FROM milestones m
    LEFT JOIN escrow_transactions e ON m.escrow_id = e.id
    WHERE m.job_id = " . (int)$dispute['job_id'] . "
    ORDER BY m.id ASC
");

$deliverables = $db->select("
    SELECT d.*, m.title as milestone_title
    FROM deliverables d
    LEFT JOIN milestones m ON d.milestone_id = m.id
    WHERE m.job_id = " . (int)$dispute['job_id'] . "
    ORDER BY d.submitted_at ASC
");

$qa = $db->select("
    SELECT qc.*, m.title as milestone_title
    FROM qa_checklists qc
    LEFT JOIN milestones m ON qc.milestone_id = m.id
    WHERE qc.job_id = " . (int)$dispute['job_id'] . "
    ORDER BY qc.id DESC
");

$verdict = $db->select("SELECT * FROM dispute_verdicts WHERE dispute_id = $disputeId LIMIT 1");
$verdict = !empty($verdict) ? $verdict[0] : null;

$db->closeConnection();
?>

<style>
.fh-card {
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(26, 26, 46, 0.04);
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
.btn-fh-outline {
    border: 2px solid #e2dfd8;
    background: transparent;
    color: #1a1a2e;
    border-radius: 8px;
    font-weight: 700;
}
.btn-fh-outline:hover {
    border-color: #1a1a2e;
    background: #1a1a2e;
    color: #fff;
}
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1a1a2e;">Dispute File</h2>
            <div class="text-muted">Evidence package for dispute #<?= (int)$disputeId ?></div>
        </div>
        <div class="d-flex gap-2">
            <a href="view.php?id=<?= (int)$disputeId ?>" class="btn btn-fh-outline">Back to Dispute</a>
            <button class="btn btn-fh-primary" onclick="window.print()">Print / Save PDF</button>
        </div>
    </div>

    <div class="fh-card p-4 mb-4">
        <h5 class="fw-bold mb-2" style="color:#1a1a2e;"><?= htmlspecialchars($dispute['job_title'] ?? 'Job') ?></h5>
        <div class="text-muted mb-1">Status: <strong><?= htmlspecialchars($dispute['status']) ?></strong></div>
        <div class="text-muted mb-1">Raised by: <?= htmlspecialchars($dispute['raised_by_name'] ?? 'Unknown') ?></div>
        <div class="text-muted mb-1">Against: <?= htmlspecialchars($dispute['against_user_name'] ?? 'Unknown') ?></div>
        <div class="text-muted mb-3">Opened: <?= date('M d, Y h:i A', strtotime($dispute['created_at'])) ?></div>
        <div class="p-3 rounded-3" style="background:#f5f4f0;">
            <div class="fw-semibold mb-1">Reason</div>
            <div class="text-muted"><?= nl2br(htmlspecialchars($dispute['reason'])) ?></div>
        </div>
    </div>

    <div class="fh-card p-4 mb-4">
        <h6 class="fw-bold mb-3" style="color:#1a1a2e;">Dispute Messages</h6>
        <?php if (empty($messages)): ?>
        <div class="text-muted">No dispute messages.</div>
        <?php else: ?>
        <?php foreach ($messages as $m): ?>
        <div class="mb-3 pb-3 border-bottom">
            <div class="fw-semibold"><?= htmlspecialchars($m['sender_name'] ?? 'User') ?></div>
            <div class="text-muted small mb-2"><?= date('M d, Y h:i A', strtotime($m['created_at'])) ?></div>
            <div><?= nl2br(htmlspecialchars($m['message'])) ?></div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="fh-card p-4 mb-4">
        <h6 class="fw-bold mb-3" style="color:#1a1a2e;">Safe-Room Messages (Monitored)</h6>
        <?php if (empty($safeMessages)): ?>
        <div class="text-muted">No safe-room messages.</div>
        <?php else: ?>
        <?php foreach ($safeMessages as $m): ?>
        <div class="mb-3 pb-3 border-bottom">
            <div class="fw-semibold"><?= htmlspecialchars($m['sender_name'] ?? 'User') ?></div>
            <div class="text-muted small mb-2"><?= date('M d, Y h:i A', strtotime($m['created_at'])) ?></div>
            <div><?= nl2br(htmlspecialchars($m['message'])) ?></div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="fh-card p-4 mb-4">
        <h6 class="fw-bold mb-3" style="color:#1a1a2e;">Milestones & Escrow</h6>
        <?php if (empty($milestones)): ?>
        <div class="text-muted">No milestones found.</div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead style="background:#f5f4f0;">
                    <tr>
                        <th class="text-muted">Milestone</th>
                        <th class="text-muted">Status</th>
                        <th class="text-muted">Escrow</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($milestones as $ms): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($ms['title'] ?? ('Milestone #' . (int)$ms['id'])) ?></td>
                        <td><?= htmlspecialchars($ms['status'] ?? '') ?></td>
                        <td class="text-muted">
                            <?= $ms['escrow_amount'] !== null ? ('$' . number_format((float)$ms['escrow_amount'], 2)) : '-' ?>
                            <?= $ms['escrow_status'] ? (' · ' . htmlspecialchars($ms['escrow_status'])) : '' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <div class="fh-card p-4 mb-4">
        <h6 class="fw-bold mb-3" style="color:#1a1a2e;">Deliverables</h6>
        <?php if (empty($deliverables)): ?>
        <div class="text-muted">No deliverables found.</div>
        <?php else: ?>
        <?php foreach ($deliverables as $d): ?>
        <div class="mb-3 pb-3 border-bottom">
            <div class="fw-semibold"><?= htmlspecialchars($d['milestone_title'] ?? 'Milestone') ?> · <?= htmlspecialchars($d['status'] ?? '') ?></div>
            <div class="text-muted small mb-2">
                Submitted: <?= $d['submitted_at'] ? date('M d, Y h:i A', strtotime($d['submitted_at'])) : '-' ?>
            </div>
            <div class="text-muted"><?= nl2br(htmlspecialchars($d['notes'] ?? '')) ?></div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="fh-card p-4 mb-4">
        <h6 class="fw-bold mb-3" style="color:#1a1a2e;">QA Checklists</h6>
        <?php if (empty($qa)): ?>
        <div class="text-muted">No QA checklist entries found.</div>
        <?php else: ?>
        <?php foreach ($qa as $q): ?>
        <div class="mb-3 pb-3 border-bottom">
            <div class="fw-semibold"><?= htmlspecialchars($q['milestone_title'] ?? 'Milestone') ?></div>
            <div class="text-muted small mb-2">Allowed submission: <strong><?= ((int)($q['submission_allowed'] ?? 0) === 1) ? 'Yes' : 'No' ?></strong></div>
            <div class="text-muted small">
                Files Uploaded: <?= (int)$q['files_uploaded'] ?> · Documentation: <?= (int)$q['documentation_complete'] ?> · Meets Requirements: <?= (int)$q['meets_requirements'] ?> · No Errors: <?= (int)$q['no_errors_found'] ?> · Commented: <?= (int)$q['code_commented'] ?> · Tests: <?= (int)$q['tests_passed'] ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="fh-card p-4 mb-4">
        <h6 class="fw-bold mb-3" style="color:#1a1a2e;">Verdict</h6>
        <?php if (!$verdict): ?>
        <div class="text-muted">No verdict recorded.</div>
        <?php else: ?>
        <div class="text-muted mb-1">Freelancer: <strong><?= (int)$verdict['freelancer_percentage'] ?>%</strong> · Client: <strong><?= (int)$verdict['client_percentage'] ?>%</strong></div>
        <div class="text-muted"><?= nl2br(htmlspecialchars($verdict['verdict_notes'] ?? '')) ?></div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
