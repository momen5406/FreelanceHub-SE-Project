<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Freelancer') {
    header('Location: ../auth/login.php');
    exit();
}

$jobId = $_GET['job_id'] ?? 0;
$milestoneId = $_GET['milestone_id'] ?? 0;

if ($milestoneId == 0 || $jobId == 0) {
    header('Location: ../freelancer/my-jobs.php');
    exit();
}

require_once __DIR__ . '/../../app/core/database.php';
require_once __DIR__ . '/../../app/models/Escrow.php';
require_once __DIR__ . '/../partials/header.php';

$db = new Database();
$db->openConnection();

// ESCROW LOCKING CHECK - Check if funds are locked for this milestone
$escrow = new Escrow();
$fundsLocked = $escrow->isFundsLocked($milestoneId);
$lockedAmount = $escrow->getLockedAmount($milestoneId);

if (!$fundsLocked) {
    echo '<div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-danger text-center" style="padding: 2rem; border-radius: 12px;">
                        <i class="bi bi-exclamation-triangle" style="font-size: 48px;"></i>
                        <h3 class="mt-3">Cannot Submit Work</h3>
                        <p>Client has not deposited funds for this milestone.</p>
                        <p><strong>Amount Required: $' . number_format($lockedAmount, 2) . '</strong></p>
                        <a href="../freelancer/my-jobs.php" class="btn btn-primary mt-3">Go Back</a>
                    </div>
                </div>
            </div>
          </div>';
    require_once __DIR__ . '/../partials/footer.php';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $notes = $db->connection->real_escape_string($_POST['notes'] ?? '');

    // Update milestone status
    $db->update("UPDATE milestones SET status = 'Awaiting Approval' WHERE id = $milestoneId");

    // Insert or update deliverable
    $check = $db->select("SELECT id FROM deliverables WHERE milestone_id = $milestoneId AND freelancer_id = {$_SESSION['user_id']}");

    if (empty($check)) {
        $db->insert("INSERT INTO deliverables (milestone_id, freelancer_id, notes, status, submitted_at) 
                     VALUES ($milestoneId, {$_SESSION['user_id']}, '$notes', 'Pending', NOW())");
    } else {
        $db->update("UPDATE deliverables SET notes = '$notes', status = 'Pending', submitted_at = NOW() WHERE milestone_id = $milestoneId");
    }

    $db->closeConnection();

    echo "<script>alert('Work submitted successfully! Client has been notified.'); window.location.href='../freelancer/my-jobs.php';</script>";
    exit();
}

$db->closeConnection();
?>

<style>
.submit-card {
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
    padding: 2rem;
    margin-top: 2rem;
}

.btn-submit {
    background: #27ae60;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 700;
    width: 100%;
}

.btn-submit:hover {
    background: #219a52;
}

.escrow-info {
    background: #e8f4f8;
    border: 1px solid #3498db;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 20px;
    text-align: center;
}

.escrow-info i {
    color: #3498db;
    font-size: 20px;
    margin-right: 8px;
}
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="submit-card">
                <h2 style="color: #1a1a2e;" class="mb-4">Submit Work</h2>

                <div class="escrow-info">
                    <i class="bi bi-shield-lock"></i>
                    <strong>Escrow Protected:</strong> $<?= number_format($lockedAmount, 2) ?> is locked for this
                    milestone
                </div>

                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle"></i>
                    Submitting work for Milestone #<?= $milestoneId ?>
                </div>

                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label">Submission Notes</label>
                        <textarea name="notes" class="form-control" rows="5" required
                            placeholder="Describe what you have completed..."></textarea>
                    </div>

                    <button type="submit" name="submit" class="btn-submit">
                        <i class="bi bi-check-circle"></i> Submit Work
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>