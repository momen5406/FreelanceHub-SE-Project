<?php require_once '../../views/partials/header.php'; ?>

<?php
// Nafs elkalam hatgeb eldata mn eldatabase
$job_id = $_GET['job_id'] ?? 1;
$job = [
  'title' => 'Next.js E-commerce Platform',
  'budget' => 1500,
  'category' => 'Web Development'
];
?>

<style>
  .fh-card {
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(26, 26, 46, 0.04);
  }

  .job-summary-card {
    background: #1a1a2e;
    color: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
  }

  .form-control-fh {
    border-radius: 8px;
    padding: 0.65rem 1rem;
    border: 1.5px solid #e2dfd8;
    background-color: #faf9f6;
    transition: all 0.2s;
  }

  .form-control-fh:focus {
    border-color: #e8a045;
    box-shadow: 0 0 0 0.25rem rgba(232, 160, 69, 0.25);
    background-color: #fff;
  }

  .form-label {
    font-weight: 700;
    color: #1a1a2e;
    font-size: 0.95rem;
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
    color: #1a1a2e;
  }

  .btn-fh-outline {
    border: 2px solid #e2dfd8;
    color: #1a1a2e;
    background: transparent;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.75rem 1.5rem;
    transition: all 0.2s;
  }

  .btn-fh-outline:hover {
    border-color: #1a1a2e;
    background: #1a1a2e;
    color: #fff;
  }
</style>

<!-- Breadcrumb Navigation -->
<nav aria-label="breadcrumb" class="mb-3 mt-2">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/jobs" class="text-muted text-decoration-none">Jobs</a></li>
    <li class="breadcrumb-item"><a href="/jobs/view?id=<?= $job_id ?>" class="text-muted text-decoration-none">Project Details</a></li>
    <li class="breadcrumb-item text-muted fw-bold" aria-current="page">Submit Proposal</li>
  </ol>
</nav>

<div class="row justify-content-center mb-5">
  <div class="col-lg-8">

    <!-- 1. The Contextual Job Summary -->
    <div class="job-summary-card mb-4 shadow-sm">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <span class="badge mb-2" style="background: rgba(232,160,69,0.2); color: #e8a045; border: 1px solid rgba(232,160,69,0.5);">
            <?= htmlspecialchars($job['category']) ?>
          </span>
          <h4 class="fw-bold mb-1" style="letter-spacing: -0.5px;">Applying for: <?= htmlspecialchars($job['title']) ?></h4>
        </div>
        <div class="text-end">
          <small class="text-white-50 d-block">Client's Budget</small>
          <h4 class="fw-bold" style="color: #e8a045;">$<?= number_format($job['budget']) ?></h4>
        </div>
      </div>
    </div>

    <!-- 2. The Proposal Form -->
    <div class="fh-card p-4 p-md-5">
      <h4 class="fw-bold mb-4" style="color: #1a1a2e;">Your Proposal Details</h4>

      <form action="/proposals/submit" method="POST">

        <input type="hidden" name="job_id" value="<?= $job_id ?>">

        <div class="row mb-4">
          <div class="col-md-6">
            <label for="bid_amount" class="form-label">Your Bid Amount ($)</label>
            <div class="input-group">
              <span class="input-group-text border-end-0 bg-transparent border-2" style="border-color: #e2dfd8; color: #888;">$</span>
              <input type="number" class="form-control border-start-0 border-2 shadow-none" id="bid_amount" name="bid_amount" required min="5" placeholder="e.g. <?= $job['budget'] ?>" style="border-color: #e2dfd8; padding: 0.65rem;">
            </div>
          </div>
        </div>

        <div class="mb-5">
          <label for="description" class="form-label">Cover Letter</label>
          <textarea class="form-control form-control-fh" id="description" name="description" rows="7" required placeholder="Introduce yourself, explain why you are the best fit for this project, and outline your approach..."></textarea>
        </div>

        <hr class="text-muted opacity-25 mb-4">

        <div class="d-flex justify-content-end gap-3">
          <a href="/jobs/view?id=<?= $job_id ?>" class="btn btn-fh-outline">Cancel</a>
          <button type="submit" class="btn btn-fh-primary">
            <i class="bi bi-send-check me-2"></i> Submit Proposal
          </button>
        </div>

      </form>
    </div>

  </div>
</div>

<?php require_once '../../views/partials/footer.php'; ?>