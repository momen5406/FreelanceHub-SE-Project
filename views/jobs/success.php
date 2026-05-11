<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Client') {
    header('Location: ../auth/login.php');
    exit();
}

$jobId = $_GET['id'] ?? 0;

require_once __DIR__ . '/../partials/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow">
                <div class="card-body py-5">
                    <div style="font-size: 64px;">✅</div>
                    <h2 style="color: #27ae60;" class="mt-3">Job Posted Successfully!</h2>
                    <p class="mt-3">Your job has been posted to the marketplace. Freelancers can now view and apply.</p>
                    <p><strong>Job ID: #<?= $jobId ?></strong></p>
                    <div class="mt-4">
                        <a href="my-postings.php" class="btn" style="background: #e8a045; color: #1a1a2e;">View My
                            Jobs</a>
                        <a href="create.php" class="btn btn-secondary">Post Another Job</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>