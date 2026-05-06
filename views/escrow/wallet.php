<?php require_once '../../views/partials/header.php'; ?>

<?php
$transactions = [
  [
    'description' => 'Funded Milestone 1 for "Next.js E-commerce"',
    'amount' => -500.00,
    'status' => 'Released'
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

  .metric-card {
    padding: 1.5rem;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 100%;
  }

  .metric-primary {
    background: #1a1a2e;
    color: #fff;
    border: 2px solid #1a1a2e;
  }

  .metric-secondary {
    background: rgba(232, 160, 69, 0.1);
    border: 2px solid rgba(232, 160, 69, 0.3);
  }

  .metric-tertiary {
    background: #f5f4f0;
    border: 2px solid #e2dfd8;
  }

  .metric-label {
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
    opacity: 0.8;
  }

  .metric-value {
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1;
    letter-spacing: -1px;
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

  .table-fh tr:last-child td {
    border-bottom: none;
  }

  .table-fh tr:hover td {
    background-color: rgba(232, 160, 69, 0.03);
  }

  .amount-positive {
    color: #27ae60;
    font-weight: 700;
  }

  .amount-negative {
    color: #1a1a2e;
    font-weight: 700;
  }

  .btn-fh-primary {
    background: #e8a045;
    color: #1a1a2e;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.8rem 1.5rem;
    transition: all 0.2s;
  }

  .btn-fh-primary:hover {
    background: #d4903a;
    transform: translateY(-1px);
    color: #1a1a2e;
  }
</style>

<!-- Breadcrumbs -->
<nav aria-label="breadcrumb" class="mb-3 mt-2">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/" class="text-muted text-decoration-none">Home</a></li>
    <li class="breadcrumb-item text-muted fw-bold" aria-current="page">Wallet & Escrow</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-end mb-4">
  <div>
    <h2 style="color: #1a1a2e; font-weight: 800; letter-spacing: -0.5px;">My Wallet</h2>
    <p class="text-muted mb-0">Manage your funds, view escrow holds, and track transactions.</p>
  </div>
  <div>
    <!-- Role-Based Action Button -->
    <?php if ($_SESSION['role'] === 'Freelancer'): ?>
      <button class="btn btn-fh-primary shadow-sm"><i class="bi bi-cash-stack me-2"></i> Withdraw Funds</button>
    <?php endif; ?>
  </div>
</div>


<!-- TRANSACTION HISTORY -->
<div class="fh-card overflow-hidden mb-5">
  <div class="p-4 border-bottom bg-white d-flex justify-content-between align-items-center">
    <h5 class="fw-bold mb-0" style="color: #1a1a2e;">Recent Transactions</h5>
    <button class="btn btn-sm btn-outline-secondary fw-bold border-2"><i class="bi bi-download me-1"></i> Export PDF</button>
  </div>

  <div class="table-responsive">
    <table class="table table-fh mb-0">
      <thead>
        <tr>
          <th style="width: 20%;">Status</th>
          <th style="width: 50%;">Description</th>
          <th style="width: 30%; text-align: right;">Amount</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($transactions)): ?>
          <tr>
            <td colspan="5" class="text-center py-5 text-muted">
              <i class="bi bi-receipt fs-1 d-block mb-2 opacity-50"></i>
              No transactions found.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($transactions as $trx): ?>
            <tr>
              <td>
                <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-50"><?= htmlspecialchars($trx['status']) ?></span>
              </td>
              <td><span class="text-truncate d-inline-block" style="max-width: 100%;"><?= htmlspecialchars($trx['description']) ?></span></td>
              <td class="text-end">
                <!-- Format positive/negative amounts -->
                <?php if ($trx['amount'] > 0): ?>
                  <span class="amount-positive">+$<?= number_format($trx['amount'], 2) ?></span>
                <?php else: ?>
                  <span class="amount-negative">-$<?= number_format(abs($trx['amount']), 2) ?></span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once '../../views/partials/footer.php'; ?>