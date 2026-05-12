<?php
require_once __DIR__ . '/../../app/controllers/DisputeController.php';
$controller = new DisputeController();
$data = $controller->handleView();
$dispute = $data['dispute'];
$messages = $data['messages'];
$isModerator = $data['is_moderator'];
$currentUserId = (int)$_SESSION['user_id'];
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

.chat-wrap {
    max-height: 420px;
    overflow-y: auto;
}

.msg-row {
    display: flex;
    margin-bottom: 0.9rem;
}

.msg-row.mine {
    justify-content: flex-end;
}

.msg-bubble {
    max-width: 72%;
    padding: 0.8rem 1rem;
    border-radius: 12px;
    font-size: 0.95rem;
    border: 1px solid #e2dfd8;
    background: #f8f7f4;
    color: #1a1a2e;
}

.msg-row.mine .msg-bubble {
    background: rgba(232, 160, 69, 0.2);
    border-color: rgba(232, 160, 69, 0.35);
}

.msg-meta {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 0.4rem;
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

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold mb-0" style="color:#1a1a2e;">Dispute #<?= (int)$dispute['id'] ?></h2>
        <a href="<?= $isModerator ? 'mediator-dashboard.php' : 'list.php' ?>" class="btn btn-fh-outline">Back</a>
    </div>

    <div class="fh-card p-4 mb-4">
        <div class="row g-3">
            <div class="col-md-8">
                <h5 class="fw-bold mb-2" style="color:#1a1a2e;"><?= htmlspecialchars($dispute['job_title'] ?? 'Job') ?></h5>
                <div class="text-muted mb-1">Raised by: <?= htmlspecialchars($dispute['raised_by_name'] ?? 'Unknown') ?></div>
                <div class="text-muted mb-1">Against: <?= htmlspecialchars($dispute['against_user_name'] ?? 'Unknown') ?></div>
                <div class="text-muted">Opened: <?= date('M d, Y h:i A', strtotime($dispute['created_at'])) ?></div>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($dispute['status']) ?></span>
            </div>
            <div class="col-12 mt-2">
                <div class="p-3 rounded-3" style="background:#f5f4f0;">
                    <div class="fw-semibold mb-1">Reason</div>
                    <div class="text-muted"><?= nl2br(htmlspecialchars($dispute['reason'])) ?></div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($dispute['status'] === 'Resolved' || $dispute['status'] === 'Dismissed'): ?>
    <div class="fh-card p-4 mb-4">
        <h6 class="fw-bold mb-2" style="color:#1a1a2e;">Resolution</h6>
        <div class="text-muted mb-2"><?= nl2br(htmlspecialchars($dispute['resolution_notes'] ?? 'No notes provided.')) ?></div>
        <small class="text-muted">Resolved at: <?= $dispute['resolved_at'] ? date('M d, Y h:i A', strtotime($dispute['resolved_at'])) : '-' ?></small>
    </div>
    <?php endif; ?>

    <div class="fh-card p-4 mb-4">
        <h5 class="fw-bold mb-3" style="color:#1a1a2e;">Dispute Thread</h5>
        <div class="chat-wrap">
            <?php if (empty($messages)): ?>
            <div class="text-muted">No messages yet.</div>
            <?php else: ?>
            <?php foreach ($messages as $message): ?>
            <div class="msg-row <?= ((int)$message['user_id'] === $currentUserId) ? 'mine' : '' ?>">
                <div class="msg-bubble">
                    <div><?= nl2br(htmlspecialchars($message['message'])) ?></div>
                    <div class="msg-meta"><?= htmlspecialchars($message['sender_name'] ?? 'User') ?> ·
                        <?= date('M d, Y h:i A', strtotime($message['created_at'])) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($dispute['status'] !== 'Resolved' && $dispute['status'] !== 'Dismissed'): ?>
    <div class="fh-card p-4 mb-4">
        <h6 class="fw-bold mb-3" style="color:#1a1a2e;">Send Message</h6>
        <form method="POST">
            <div class="mb-3">
                <textarea name="message" class="form-control" rows="4" required placeholder="Write your message"></textarea>
            </div>
            <button type="submit" name="send_message" class="btn btn-fh-primary">Post Message</button>
        </form>
    </div>
    <?php endif; ?>

    <?php if ($isModerator): ?>
    <div class="fh-card p-4 mb-4">
        <h6 class="fw-bold mb-3" style="color:#1a1a2e;">Mediator Action Panel</h6>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Status</label>
                <select class="form-select" name="status" required>
                    <option value="Under Review" <?= $dispute['status'] === 'Under Review' ? 'selected' : '' ?>>Under Review</option>
                    <option value="Resolved" <?= $dispute['status'] === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                    <option value="Dismissed" <?= $dispute['status'] === 'Dismissed' ? 'selected' : '' ?>>Dismissed</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Resolution Notes</label>
                <textarea class="form-control" rows="4" name="resolution_notes"
                    placeholder="Write decision notes for both parties"><?= htmlspecialchars($dispute['resolution_notes'] ?? '') ?></textarea>
            </div>
            <button type="submit" name="update_status" class="btn btn-fh-primary">Update Dispute</button>
        </form>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
