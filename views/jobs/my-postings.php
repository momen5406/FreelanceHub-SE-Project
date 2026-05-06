<?php require_once '../../views/partials/header.php'; ?>

<?php
// Tgeb eljobs ely leha 3laka bel client ely 3aml log in byst5dam el userId felsession
$myJobs = [
  [
    'id' => 1,
    'title' => 'Next.js E-commerce Platform',
    'posted_date' => 'Oct 24, 2026',
    'status' => 'In Progress',
    'proposals_count' => 3,
    'freelancer_hired' => 'Momen'
  ]
];
?>

<style>
  .fh-list-card {
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
    transition: all 0.2s ease;
    margin-bottom: 1rem;
  }

  .fh-list-card:hover {
    border-color: #1a1a2e;
    box-shadow: 0 4px 15px rgba(26, 26, 46, 0.05);
  }

  .job-title-link {
    color: #1a1a2e;
    font-weight: 800;
    font-size: 1.25rem;
    text-decoration: none;
    transition: color 0.2s;
  }

  .job-title-link:hover {
    color: #e8a045;
  }

  .status-badge {
    font-weight: 700;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    font-size: 0.8rem;
  }

  .status-open {
    background: rgba(46, 204, 113, 0.15);
    color: #27ae60;
  }

  .status-progress {
    background: rgba(232, 160, 69, 0.15);
    color: #d35400;
  }

  .status-completed {
    background: #f5f4f0;
    color: #6c757d;
  }

  .btn-fh-primary {
    background: #e8a045;
    color: #1a1a2e;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.5rem 1rem;
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
    padding: 0.5rem 1rem;
    transition: all 0.2s;
  }

  .btn-fh-outline:hover {
    border-color: #1a1a2e;
    background: #1a1a2e;
    color: #fff;
  }
</style>

<div class="row mb-4 mt-2 align-items-center">
  <div class="col-md-8">
    <h2 style="color: #1a1a2e; font-weight: 800; letter-spacing: -0.5px;">Manage My Projects</h2>
    <p class="text-muted mb-0">Track your active listings, review proposals, and manage payments.</p>
  </div>
  <div class="col-md-4 text-md-end mt-3 mt-md-0">
    <a href="../../views/jobs/create.php" class="btn btn-fh-primary px-4 py-2">
      <i class="bi bi-plus-lg me-2"></i> Post a New Job
    </a>
  </div>
</div>

<div class="row mb-5">
  <div class="col-12">

    <?php if (empty($myJobs)): ?>
      <!-- THE EMPTY STATE -->
      <div class="text-center p-5 fh-list-card" style="border-style: dashed; border-width: 2px;">
        <div style="width: 80px; height: 80px; background: #f5f4f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
          <i class="bi bi-folder2-open text-muted" style="font-size: 2rem;"></i>
        </div>
        <h4 style="color: #1a1a2e; font-weight: 800;">No projects posted yet</h4>
        <p class="text-muted mb-4">Ready to hire top freelance talent? Create your first job posting today.</p>
        <a href="../../views/jobs/create.php" class="btn btn-fh-primary px-4">Post Your First Job</a>
      </div>

    <?php else: ?>
      <!-- THE JOB LIST -->
      <?php foreach ($myJobs as $job): ?>

        <div class="fh-list-card p-4">
          <div class="row align-items-center text-center text-md-start">

            <!-- Column 1: Job Info -->
            <div class="col-md-5 mb-3 mb-md-0">
              <a href="../../views/jobs/view.php?id=<?= $job['id'] ?>" class="job-title-link d-block mb-1">
                <?= htmlspecialchars($job['title']) ?>
              </a>
              <div class="text-muted small">
                <i class="bi bi-calendar3 me-1"></i> Posted on <?= htmlspecialchars($job['posted_date']) ?>
              </div>
            </div>

            <!-- Column 2: Status & Stats -->
            <div class="col-md-3 mb-3 mb-md-0">
              <?php if ($job['status'] === 'Open'): ?>
                <span class="status-badge status-open mb-2 d-inline-block">
                  <i class="bi bi-door-open me-1"></i> Open for Bids
                </span>
                <div class="small fw-bold" style="color: #1a1a2e;">
                  <?= $job['proposals_count'] ?> Proposals received
                </div>

              <?php elseif ($job['status'] === 'In Progress'): ?>
                <span class="status-badge status-progress mb-2 d-inline-block">
                  <i class="bi bi-hammer me-1"></i> In Progress
                </span>
                <div class="small text-muted">
                  Hired: <span class="fw-bold" style="color: #1a1a2e;"><?= htmlspecialchars($job['freelancer_hired']) ?></span>
                </div>

              <?php else: ?>
                <span class="status-badge status-completed mb-2 d-inline-block">
                  <i class="bi bi-check-circle me-1"></i> Completed
                </span>
              <?php endif; ?>
            </div>

            <!-- Column 3: Contextual Action Buttons -->
            <div class="col-md-4 text-md-end">
              <?php if ($job['status'] === 'Open'): ?>
                <a href="/proposals/list?job_id=<?= $job['id'] ?>" class="btn btn-fh-primary w-100 w-md-auto mb-2 mb-md-0">
                  Review Bids <span class="badge bg-dark ms-1"><?= $job['proposals_count'] ?></span>
                </a>
                <a href="/jobs/edit?id=<?= $job['id'] ?>" class="btn btn-fh-outline w-100 w-md-auto ms-md-2">Edit</a>

              <?php elseif ($job['status'] === 'In Progress'): ?>
                <a href="../../views/workspace/dashboard.php?job_id=<?= $job['id'] ?>" class="btn btn-dark w-100 w-md-auto border-0" style="background: #1a1a2e;">
                  <i class="bi bi-kanban me-2"></i> Manage Workspace
                </a>

              <?php elseif ($job['status'] === 'Completed'): ?>
                <a href="/jobs/view?id=<?= $job['id'] ?>" class="btn btn-fh-outline w-100 w-md-auto">View Details</a>
              <?php endif; ?>
            </div>

          </div>
        </div>

      <?php endforeach; ?>
    <?php endif; ?>

  </div>
</div>

<?php require_once '../../views/partials/footer.php'; ?>