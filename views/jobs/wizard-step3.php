<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Client') {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../app/core/database.php';

$jobData = $_SESSION['job_wizard'] ?? null;

if (!$jobData) {
    header('Location: create.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $db->openConnection();

    $title = $db->connection->real_escape_string($jobData['title']);
    $description = $db->connection->real_escape_string($jobData['description']);
    $budget = $jobData['budget'];
    $nicheId = $jobData['niche_id'];
    $clientId = $_SESSION['user_id'];
    $dynamicFields = json_encode($_POST);

    $query = "INSERT INTO jobs (title, description, budget, niche_id, client_id, dynamic_fields, status, created_at) 
              VALUES ('$title', '$description', $budget, $nicheId, $clientId, '$dynamicFields', 'Open', NOW())";

    $result = $db->insert($query);
    $db->closeConnection();

    if ($result) {
        unset($_SESSION['job_wizard']);
        header('Location: success.php?id=' . $result);
        exit();
    }
}

require_once __DIR__ . '/../partials/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header"
                    style="background: #e8a045; color: #1a1a2e; font-weight: bold; font-size: 1.2rem;">
                    Step 3: Review & Submit
                </div>
                <div class="card-body">
                    <div class="mb-3"><strong>Job Title:</strong> <?= htmlspecialchars($jobData['title']) ?></div>
                    <div class="mb-3">
                        <strong>Description:</strong><br><?= nl2br(htmlspecialchars($jobData['description'])) ?></div>
                    <div class="mb-3"><strong>Budget:</strong> $<?= number_format($jobData['budget'], 2) ?></div>
                    <div class="mb-3"><strong>Niche:</strong> <?= htmlspecialchars($jobData['niche_name']) ?></div>

                    <form method="POST">
                        <div class="d-flex justify-content-between mt-4">
                            <a href="wizard-step2.php" class="btn btn-secondary">← Back</a>
                            <button type="submit" class="btn"
                                style="background: #27ae60; color: white; font-weight: bold;">✅ Submit Job</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>