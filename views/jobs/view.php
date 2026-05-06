<?php require_once '../../views/partials/header.php'; ?>

<?php

// Eldata htegy mn eldatabase we tt7t fel UI hena
$job = [
  'id' => 1,
  'client_id' => 2, // Assuming user_id 2 is the client who posted this
  'title' => 'Next.js E-commerce Platform',
  'category' => 'Web Development',
  'budget' => 1500,
  'description' => "We are looking for a highly skilled MERN/Next.js stack developer to build a modern e-commerce platform. \n\nRequirements:\n- Strong experience with Tailwind CSS.\n- Node.js backend API creation.\n- Integration with Supabase for authentication.\n\nThe project must be completed within 30 days. Please include links to previous e-commerce builds in your proposal.",
  'status' => 'Open',
  'client_name' => 'Sarah The Client',
  'client_reputation' => 4.8
];
?>

<style>
  .fh-card {
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(26, 26, 46, 0.04);
  }

  .job-title {
    color: #1a1a2e;
    font-weight: 800;
    font-size: 2rem;
    letter-spacing: -0.5px;
  }

  .meta-badge {
    background: #f5f4f0;
    color: #6c757d;
    font-weight: 600;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    font-size: 0.85rem;
  }

  .budget-display {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1a1a2e;
    line-height: 1;
  }

  .btn-fh-primary {
    background: #e8a045;
    color: #1a1a2e;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.8rem;
    transition: all 0.2s;
  }

  .btn-fh-primary:hover {
    background: #d4903a;
    transform: translateY(-1px);
    color: #1a1a2e;
  }

  .btn-fh-outline {
    border: 2px solid #1a1a2e;
    color: #1a1a2e;
    background: transparent;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.8rem;
    transition: all 0.2s;
  }

  .btn-fh-outline:hover {
    background: #1a1a2e;
    color: #fff;
  }
</style>

<!-- Breadcrumb Navigation -->
<nav aria-label="breadcrumb" class="mb-4 mt-2">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/jobs" class="text-muted text-decoration-none">Jobs</a></li>
    <li class="breadcrumb-item text-muted" aria-current="page"><?= htmlspecialchars($job['category']) ?></li>
  </ol>
</nav>

<div class="row g-4 mb-5">

  <!-- LEFT COLUMN: Job Details -->
  <div class="col-lg-8">
    <div class="fh-card p-4 p-md-5 h-100">
      <h1 class="job-title mb-3"><?= htmlspecialchars($job['title']) ?></h1>

      <div class="d-flex flex-wrap gap-2 mb-4 pb-4 border-bottom">
        <span class="meta-badge"><i class="bi bi-tag me-1"></i> <?= htmlspecialchars($job['category']) ?></span>
        <?php if ($job['status'] === 'Open'): ?>
          <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-3 fw-bold">Open for Bids</span>
        <?php else: ?>
          <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-3 fw-bold"><?= htmlspecialchars($job['status']) ?></span>
        <?php endif; ?>
      </div>

      <h5 class="fw-bold mb-3" style="color: #1a1a2e;">Project Description</h5>
      <div class="text-muted lh-lg" style="font-size: 1.05rem;">
        <?= nl2br(htmlspecialchars($job['description'])) ?>
      </div>
    </div>
  </div>

  <!-- RIGHT COLUMN: Action Panel -->
  <div class="col-lg-4">

    <!-- Budget & Action Card -->
    <div class="fh-card p-4 mb-4" style="border-top: 4px solid #1a1a2e;">
      <p class="text-muted fw-bold mb-1">Estimated Budget</p>
      <div class="budget-display mb-4">$<?= number_format($job['budget']) ?></div>

      <div class="d-grid gap-3">

        <?php if (!isset($_SESSION['user_id'])): ?>
          <!-- GUEST: Not logged in -->
          <a href="/auth/login" class="btn btn-fh-primary">Log in to Apply</a>

        <?php elseif ($_SESSION['role'] === 'Freelancer'): ?>
          <!-- FREELANCER: Can submit a bid -->
          <a href="../../views/proposals/submit.php?job_id=<?= $job['id'] ?>" class="btn btn-fh-primary">
            <i class="bi bi-send me-2"></i>Submit Proposal
          </a>

        <?php elseif ($_SESSION['role'] === 'Client' && $_SESSION['user_id'] == $job['client_id']): ?>
          <!-- JOB OWNER: Can view proposals or edit their own job -->
          <a href="../../views/proposals/list?job_id=<?= $job['id'] ?>" class="btn btn-fh-primary">
            <i class="bi bi-file-earmark-person me-2"></i>Review Proposals
          </a>
          <a href="../../views/jobs/edit?id=<?= $job['id'] ?>" class="btn btn-fh-outline">
            <i class="bi bi-pencil me-2"></i>Edit Job Details
          </a>

        <?php elseif ($_SESSION['role'] === 'Client' && $_SESSION['user_id'] != $job['client_id']): ?>
          <!-- OTHER CLIENT: Just browsing someone else's job -->
          <button class="btn btn-secondary disabled" title="Clients cannot bid on jobs">Client Account</button>

        <?php endif; ?>

      </div>
    </div>

    <!-- About the Client Card -->
    <div class="fh-card p-4">
      <h6 class="fw-bold mb-3" style="color: #1a1a2e;">About the Client</h6>
      <div class="d-flex align-items-center gap-3 mb-3">
        <div style="width: 45px; height: 45px; background: #e8a045; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #1a1a2e; font-size: 1.2rem;">
          <?= strtoupper(substr($job['client_name'], 0, 1)) ?>
        </div>
        <div>
          <h6 class="mb-0 fw-bold"><?= htmlspecialchars($job['client_name']) ?></h6>
          <div class="text-warning small mt-1">
            <i class="bi bi-star-fill"></i> <?= number_format($job['client_reputation'], 1) ?> / 5.0
          </div>
        </div>
      </div>
      <hr class="text-muted opacity-25">
      <div class="text-muted small">
        <i class="bi bi-check-circle-fill text-success me-1"></i> Payment Verified
      </div>
    </div>

  </div>
</div>

<?php require_once '../../views/partials/footer.php'; ?>