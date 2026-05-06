<?php require_once '../../views/partials/header.php'; ?>

<?php
// --- CRITICAL SECURITY CHECK ---
// If a Client or Freelancer tries to type this URL manually, kick them out!
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
  echo "<div class='container mt-5'><div class='alert alert-danger'>Access Denied. Admin privileges required.</div></div>";
  require_once '../../views/partials/footer.php';
  exit();
}

$recentUsers = [
  ['id' => 1042, 'name' => 'Alice Johnson', 'role' => 'Client', 'joined' => 'Today, 10:30 AM', 'status' => 'Active'],
  ['id' => 1043, 'name' => 'Bob Smith', 'role' => 'Freelancer', 'joined' => 'Today, 09:15 AM', 'status' => 'Pending Review'],
  ['id' => 1044, 'name' => 'Charlie Davis', 'role' => 'Freelancer', 'joined' => 'Yesterday', 'status' => 'Active'],
  ['id' => 1045, 'name' => 'Diana Prince', 'role' => 'Client', 'joined' => 'Yesterday', 'status' => 'Suspended'],
];

$disputes = [
  ['job_id' => 88, 'title' => 'React Native App UI', 'amount' => 1200, 'freelancer' => 'Momen H.', 'client' => 'TechCorp', 'status' => 'Requires Mediation'],
  ['job_id' => 92, 'title' => 'Logo Design', 'amount' => 150, 'freelancer' => 'Sarah A.', 'client' => 'Startup Inc', 'status' => 'Investigating'],
];
?>

<style>
  .fh-card {
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(26, 26, 46, 0.04);
  }

  .admin-metric-card {
    padding: 1.5rem;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid #e2dfd8;
    background: #fff;
    transition: all 0.2s;
  }

  .admin-metric-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(26, 26, 46, 0.08);
    border-color: #1a1a2e;
  }

  .metric-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
  }

  .icon-users {
    background: rgba(26, 26, 46, 0.1);
    color: #1a1a2e;
  }

  .icon-jobs {
    background: rgba(232, 160, 69, 0.15);
    color: #d35400;
  }

  .icon-money {
    background: rgba(46, 204, 113, 0.15);
    color: #27ae60;
  }

  .icon-danger {
    background: rgba(231, 76, 60, 0.15);
    color: #c0392b;
  }

  .table-fh th {
    background-color: #f5f4f0;
    color: #6c757d;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    padding: 1rem;
    border-bottom: 2px solid #e2dfd8;
  }

  .table-fh td {
    padding: 1rem;
    vertical-align: middle;
    color: #1a1a2e;
    font-weight: 500;
    border-bottom: 1px solid #e2dfd8;
  }

  .badge-role-client {
    background: rgba(26, 26, 46, 0.1);
    color: #1a1a2e;
  }

  .badge-role-freelancer {
    background: rgba(232, 160, 69, 0.2);
    color: #d35400;
  }

  .dispute-item {
    border-left: 4px solid #c0392b;
    background: rgba(231, 76, 60, 0.03);
    padding: 1rem;
    border-radius: 0 8px 8px 0;
    margin-bottom: 1rem;
  }
</style>

<!-- Breadcrumbs -->
<nav aria-label="breadcrumb" class="mb-3 mt-2">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/" class="text-muted text-decoration-none">Home</a></li>
    <li class="breadcrumb-item text-muted fw-bold" aria-current="page">Admin Control Panel</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-end mb-4 pb-3 border-bottom">
  <div>
    <h2 style="color: #1a1a2e; font-weight: 800; letter-spacing: -0.5px;">System Dashboard</h2>
    <p class="text-muted mb-0">Platform overview, user moderation, and financial health.</p>
  </div>
  <div>
    <button class="btn btn-outline-dark fw-bold border-2"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Export System Report</button>
  </div>
</div>

<!-- LOWER SPLIT SCREEN -->
<div class="row g-4 mb-5">

  <!-- LEFT: Recent Users Table -->
  <div class="col-xl-8">
    <div class="fh-card h-100 overflow-hidden">
      <div class="p-4 border-bottom bg-white d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0" style="color: #1a1a2e;">Recent Registrations</h5>
        <a href="/admin/users" class="btn btn-sm btn-outline-secondary fw-bold">View All Users</a>
      </div>
      <div class="table-responsive">
        <table class="table table-fh mb-0">
          <thead>
            <tr>
              <th>User ID</th>
              <th>Name</th>
              <th>Role</th>
              <th>Joined</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recentUsers as $user): ?>
              <tr>
                <td class="text-muted fw-bold">#<?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td>
                  <span class="badge <?= $user['role'] === 'Client' ? 'badge-role-client' : 'badge-role-freelancer' ?> px-2 py-1">
                    <?= $user['role'] ?>
                  </span>
                </td>
                <td class="text-muted small"><?= $user['joined'] ?></td>
                <td>
                  <?php if ($user['status'] === 'Active'): ?>
                    <i class="bi bi-circle-fill text-success small me-1"></i> Active
                  <?php elseif ($user['status'] === 'Pending Review'): ?>
                    <i class="bi bi-circle-fill text-warning small me-1"></i> Pending
                  <?php else: ?>
                    <i class="bi bi-circle-fill text-danger small me-1"></i> Suspended
                  <?php endif; ?>
                </td>
                <td>
                  <button class="btn btn-sm btn-light border shadow-sm"><i class="bi bi-three-dots-vertical"></i></button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- RIGHT: Action Center & Disputes -->
  <div class="col-xl-4">
    <div class="fh-card h-100 p-4">
      <h5 class="fw-bold mb-4" style="color: #1a1a2e;">
        <i class="bi bi-shield-exclamation text-danger me-2"></i>Urgent Disputes
      </h5>

      <?php if (empty($disputes)): ?>
        <div class="text-center p-4">
          <i class="bi bi-check-circle text-success fs-1 mb-2 d-block"></i>
          <h6 class="fw-bold text-muted">All clear!</h6>
          <p class="small text-muted mb-0">There are no active disputes to mediate.</p>
        </div>
      <?php else: ?>
        <?php foreach ($disputes as $dispute): ?>
          <div class="dispute-item">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h6 class="fw-bold mb-0" style="color: #1a1a2e;">Job #<?= $dispute['job_id'] ?></h6>
              <span class="badge bg-danger">Escrow Locked: $<?= number_format($dispute['amount']) ?></span>
            </div>
            <div class="small text-muted mb-2">
              <strong><?= $dispute['client'] ?></strong> vs <strong><?= $dispute['freelancer'] ?></strong>
            </div>
            <p class="small fw-bold text-danger mb-3"><i class="bi bi-info-circle me-1"></i> <?= $dispute['status'] ?></p>
            <button class="btn btn-dark btn-sm w-100 fw-bold" style="background: #1a1a2e;">Mediate Case</button>
          </div>
        <?php endforeach; ?>
        <a href="/admin/disputes" class="btn btn-outline-danger w-100 fw-bold mt-2 border-2">View Dispute Queue</a>
      <?php endif; ?>
    </div>
  </div>

</div>

<?php require_once '../../views/partials/footer.php'; ?>