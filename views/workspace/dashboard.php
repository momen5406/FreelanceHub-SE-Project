<?php require_once '../../views/partials/header.php'; ?>

<?php
$job_id = $_GET['job_id'] ?? 1;
$project = [
  'title' => 'Next.js E-commerce Platform',
  'status' => 'In Progress',
  'total_budget' => 1500,
  'funds_in_escrow' => 1500,
  'freelancer_name' => 'Momen Hussein',
  'client_name' => 'Sarah The Client',
  'deadline' => 'Nov 24, 2026'
];

$milestones = [
  [
    'id' => 1,
    'title' => 'UI/UX Design & Figma Mockups',
    'amount' => 500,
    'status' => 'Approved', // Paid
    'due_date' => 'Nov 01, 2026'
  ],
  [
    'id' => 2,
    'title' => 'Frontend Development (Next.js/Tailwind)',
    'amount' => 600,
    'status' => 'Submitted', // Waiting for client approval
    'due_date' => 'Nov 15, 2026'
  ],
  [
    'id' => 3,
    'title' => 'Supabase Auth & Backend Integration',
    'amount' => 400,
    'status' => 'Pending', // Not started yet
    'due_date' => 'Nov 24, 2026'
  ]
];
?>

<style>
  .fh-card {
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(26, 26, 46, 0.04);
  }

  .workspace-header {
    background: #1a1a2e;
    color: #fff;
    border-radius: 12px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
  }

  .milestone-card {
    border: 1.5px solid #e2dfd8;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: border-color 0.2s;
  }

  .milestone-card:hover {
    border-color: #1a1a2e;
  }

  .badge-pending {
    background: #f5f4f0;
    color: #6c757d;
  }

  .badge-submitted {
    background: rgba(232, 160, 69, 0.15);
    color: #d35400;
  }

  .badge-approved {
    background: rgba(46, 204, 113, 0.15);
    color: #27ae60;
  }

  .escrow-box {
    background: rgba(46, 204, 113, 0.05);
    border: 2px solid rgba(46, 204, 113, 0.2);
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
  }

  .escrow-amount {
    font-size: 2.2rem;
    font-weight: 800;
    color: #27ae60;
    line-height: 1;
    margin-top: 0.5rem;
  }

  .btn-fh-primary {
    background: #e8a045;
    color: #1a1a2e;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.6rem 1.2rem;
    transition: all 0.2s;
  }

  .btn-fh-primary:hover {
    background: #d4903a;
    transform: translateY(-1px);
    color: #1a1a2e;
  }

  .btn-fh-success {
    background: #27ae60;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.6rem 1.2rem;
    transition: all 0.2s;
  }

  .btn-fh-success:hover {
    background: #219653;
    transform: translateY(-1px);
  }
</style>

<!-- Breadcrumbs -->
<nav aria-label="breadcrumb" class="mb-3 mt-2">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/jobs/my-postings" class="text-muted text-decoration-none">My Projects</a></li>
    <li class="breadcrumb-item text-muted fw-bold" aria-current="page">Active Workspace</li>
  </ol>
</nav>

<!-- Workspace Header -->
<div class="workspace-header mb-4 shadow-sm">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <span class="badge bg-light text-dark mb-2 fw-bold"><i class="bi bi-circle-fill text-success me-1" style="font-size: 0.6rem;"></i> <?= htmlspecialchars($project['status']) ?></span>
      <h2 class="fw-bold mb-1" style="letter-spacing: -0.5px;"><?= htmlspecialchars($project['title']) ?></h2>
      <div class="text-white-50 small mt-2">
        <i class="bi bi-calendar-event me-1"></i> Final Deadline: <?= htmlspecialchars($project['deadline']) ?>
      </div>
    </div>
    <div class="d-flex gap-3">
      <button class="btn btn-outline-light border-2 fw-bold"><i class="bi bi-chat-dots me-2"></i> Message Room</button>
    </div>
  </div>
</div>

<div class="row g-4 mb-5">

  <!-- LEFT COLUMN: Milestones & Tasks -->
  <div class="col-lg-8">
    <div class="fh-card p-4 p-md-5 h-100">
      <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <h4 class="fw-bold mb-0" style="color: #1a1a2e;">Project Milestones</h4>
        <?php if ($_SESSION['role'] === 'Client'): ?>
          <button class="btn btn-sm btn-fh-outline"><i class="bi bi-plus-lg me-1"></i> Add Milestone</button>
        <?php endif; ?>
      </div>

      <!-- Milestone Loop -->
      <?php foreach ($milestones as $index => $ms): ?>
        <div class="milestone-card">
          <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
              <div class="text-muted small fw-bold mb-1">Milestone <?= $index + 1 ?></div>
              <h5 class="fw-bold mb-2" style="color: #1a1a2e;"><?= htmlspecialchars($ms['title']) ?></h5>
              <div class="text-muted small"><i class="bi bi-clock me-1"></i> Due: <?= htmlspecialchars($ms['due_date']) ?></div>
            </div>
            <div class="text-end">
              <h5 class="fw-bold mb-2" style="color: #1a1a2e;">$<?= number_format($ms['amount']) ?></h5>

              <!-- Status Badges -->
              <?php if ($ms['status'] === 'Approved'): ?>
                <span class="badge badge-approved px-3 py-2 rounded-pill"><i class="bi bi-check-all me-1"></i> Approved & Paid</span>
              <?php elseif ($ms['status'] === 'Submitted'): ?>
                <span class="badge badge-submitted px-3 py-2 rounded-pill"><i class="bi bi-hourglass-split me-1"></i> In Review</span>
              <?php else: ?>
                <span class="badge badge-pending px-3 py-2 rounded-pill"><i class="bi bi-circle me-1"></i> Pending Work</span>
              <?php endif; ?>
            </div>
          </div>

          <!-- Role-Based Action Buttons -->
          <div class="mt-4 pt-3 border-top bg-light p-3 rounded-3 d-flex justify-content-end gap-2">

            <?php if ($_SESSION['role'] === 'Freelancer'): ?>
              <!-- FREELANCER ACTIONS -->
              <?php if ($ms['status'] === 'Pending'): ?>
                <form action="/workspace/submit-work" method="POST">
                  <input type="hidden" name="milestone_id" value="<?= $ms['id'] ?>">
                  <button type="submit" class="btn btn-fh-primary btn-sm px-3"><i class="bi bi-cloud-upload me-1"></i> Submit Work</button>
                </form>
              <?php elseif ($ms['status'] === 'Submitted'): ?>
                <button class="btn btn-secondary btn-sm px-3 disabled">Waiting for Client...</button>
              <?php endif; ?>

            <?php elseif ($_SESSION['role'] === 'Client'): ?>
              <!-- CLIENT ACTIONS -->
              <?php if ($ms['status'] === 'Submitted'): ?>
                <form action="/workspace/approve" method="POST">
                  <input type="hidden" name="milestone_id" value="<?= $ms['id'] ?>">
                  <button type="button" class="btn btn-outline-danger btn-sm px-3 me-2">Request Revisions</button>
                  <button type="submit" class="btn btn-fh-success btn-sm px-3"><i class="bi bi-check-circle me-1"></i> Approve & Release Funds</button>
                </form>
              <?php elseif ($ms['status'] === 'Pending'): ?>
                <button class="btn btn-outline-secondary btn-sm px-3 disabled">Waiting for Freelancer...</button>
              <?php endif; ?>

            <?php endif; ?>

          </div>
        </div>
      <?php endforeach; ?>

    </div>
  </div>

  <!-- RIGHT COLUMN: Project Details & Escrow -->
  <div class="col-lg-4">

    <!-- Escrow Status Card -->
    <div class="fh-card p-4 mb-4">
      <h5 class="fw-bold mb-3" style="color: #1a1a2e;"><i class="bi bi-shield-lock me-2 text-success"></i>Escrow Security</h5>
      <div class="escrow-box">
        <div class="text-muted fw-bold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Total Funds Secured</div>
        <div class="escrow-amount">$<?= number_format($project['funds_in_escrow']) ?></div>
      </div>
      <p class="text-muted small mt-3 text-center mb-0">
        Funds are held securely by FreelanceHub and are only released when milestones are approved.
      </p>
    </div>
  </div>
</div>

<?php require_once '../../views/partials/footer.php'; ?>