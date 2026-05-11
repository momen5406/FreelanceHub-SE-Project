<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../partials/header.php';

$jobId = $_GET['job_id'] ?? 0;
?>

<style>
.fh-card {
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(26, 26, 46, 0.04);
}

.btn-fh-primary {
    background: #e8a045;
    color: #1a1a2e;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.75rem 1.5rem;
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
    padding: 0.75rem 1.5rem;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
}

.btn-fh-outline:hover {
    border-color: #1a1a2e;
    background: #1a1a2e;
    color: #fff;
}
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="fh-card p-5">
                <div style="font-size: 64px;">✅</div>
                <h2 style="color: #27ae60;" class="mt-3">Proposal Submitted!</h2>
                <p class="mt-3">Your proposal has been submitted successfully.</p>
                <p class="text-muted">The client will review your proposal and get back to you.</p>

                <div class="mt-4">
                    <a href="../jobs/view.php?id=<?= $jobId ?>" class="btn-fh-outline me-2">View Job</a>
                    <a href="../jobs/index.php" class="btn-fh-primary">Browse More Jobs</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>