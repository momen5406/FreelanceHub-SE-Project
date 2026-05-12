<?php
require_once __DIR__ . '/../../app/controllers/DisputeController.php';
$controller = new DisputeController();
$data = $controller->handleRaise();
$job = $data['job'];
$jobId = $data['job_id'];
$error = $data['error'];
require_once __DIR__ . '/../partials/header.php';
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
    padding: 0.75rem 1.5rem;
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
    padding: 0.75rem 1.5rem;
}

.btn-fh-outline:hover {
    border-color: #1a1a2e;
    background: #1a1a2e;
    color: #fff;
}
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="fh-card p-4 p-md-5">
                <h2 class="fw-bold mb-1" style="color:#1a1a2e;">Raise Dispute</h2>
                <p class="text-muted mb-4">Open a dispute for <strong><?= htmlspecialchars($job['title']) ?></strong>.</p>

                <?php if ($error !== ''): ?>
                <div class="alert alert-danger mb-3"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="job_id" value="<?= (int)$jobId ?>">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Reason</label>
                        <textarea name="reason" class="form-control" rows="6" required
                            placeholder="Describe the issue and what happened."></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="list.php" class="btn btn-fh-outline">Cancel</a>
                        <button type="submit" class="btn btn-fh-primary">Submit Dispute</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
