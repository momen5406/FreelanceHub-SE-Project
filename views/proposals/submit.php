<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Freelancer') {
  header('Location: ../auth/login.php');
  exit();
}

require_once __DIR__ . '/../../app/core/database.php';

$jobId = $_GET['job_id'] ?? 0;

$db = new Database();
$db->openConnection();

$job = $db->select("
    SELECT j.*, u.name as client_name 
    FROM jobs j
    LEFT JOIN users u ON j.client_id = u.id
    WHERE j.id = $jobId AND j.status = 'Open'
");

if (empty($job)) {
  $db->closeConnection();
  header('Location: ../jobs/index.php');
  exit();
}

$job = $job[0];
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $bidAmount = $_POST['bid_amount'] ?? 0;
  $description = $_POST['description'] ?? '';
  $freelancerId = $_SESSION['user_id'];

  $description = $db->connection->real_escape_string($description);

  $checkQuery = "SELECT * FROM proposals WHERE job_id = $jobId AND freelancer_id = $freelancerId";
  $existing = $db->select($checkQuery);

  if (!empty($existing)) {
    $error = "You have already submitted a proposal for this job.";
  } else {
    $query = "INSERT INTO proposals (bid_amount, description, status, freelancer_id, job_id, created_at) 
                  VALUES ($bidAmount, '$description', 'Pending', $freelancerId, $jobId, NOW())";

    $result = $db->insert($query);
    $db->closeConnection();

    if ($result) {
      header("Location: success.php?job_id=$jobId");
      exit();
    } else {
      $error = "Failed to submit proposal. Please try again.";
    }
  }
}

$db->closeConnection();

// Include header AFTER all redirects are done
require_once __DIR__ . '/../partials/header.php';
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
}

.btn-fh-outline:hover {
    border-color: #1a1a2e;
    background: #1a1a2e;
    color: #fff;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="fh-card">
                <h2 style="color: #1a1a2e;" class="mb-4">Submit Proposal</h2>

                <div class="mb-4 pb-3 border-bottom">
                    <h5><?= htmlspecialchars($job['title']) ?></h5>
                    <p class="text-muted mb-1">Client: <?= htmlspecialchars($job['client_name']) ?></p>
                    <p class="text-muted">Budget: $<?= number_format($job['budget'], 2) ?></p>
                </div>

                <?php if (isset($error)): ?>
                <div class="alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Your Bid Amount ($) <span class="text-danger">*</span></label>
                        <input type="number" name="bid_amount" class="form-control" required min="1"
                            max="<?= $job['budget'] ?>">
                        <small class="text-muted">Max budget: $<?= number_format($job['budget'], 2) ?></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Proposal Message <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="6" required
                            placeholder="Describe why you are the best fit for this job..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="../jobs/view.php?id=<?= $jobId ?>" class="btn-fh-outline">Cancel</a>
                        <button type="submit" class="btn-fh-primary">Submit Proposal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>