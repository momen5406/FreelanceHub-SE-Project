<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace Health - FreelanceHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', system-ui, sans-serif;
        background: #f5f4f0;
        padding: 20px;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .dashboard-header {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2dfd8;
    }

    h1 {
        color: #1a1a2e;
        margin-bottom: 10px;
        font-size: 28px;
        font-weight: 700;
    }

    .last-updated {
        color: #6c757d;
        font-size: 12px;
    }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2dfd8;
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    .card h3 {
        color: #6c757d;
        font-size: 13px;
        text-transform: uppercase;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .card .value {
        font-size: 32px;
        font-weight: bold;
        color: #1a1a2e;
    }

    .card.success .value {
        color: #27ae60;
    }

    .card.info .value {
        color: #3498db;
    }

    .card.warning .value {
        color: #e8a045;
    }

    .card-icon {
        font-size: 40px;
        color: #e8a045;
        margin-bottom: 15px;
    }

    .section {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2dfd8;
        margin-bottom: 20px;
    }

    .section h2 {
        color: #1a1a2e;
        margin-bottom: 20px;
        font-size: 20px;
        font-weight: 700;
        border-left: 4px solid #e8a045;
        padding-left: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e2dfd8;
    }

    th {
        background: #f8f9fa;
        color: #1a1a2e;
        font-weight: 600;
        font-size: 13px;
    }

    tr:hover {
        background: #f8f9fa;
    }

    .status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
        color: white;
        display: inline-block;
    }

    .status-Open {
        background: #e74c3c;
    }

    .status-Under-Review {
        background: #f39c12;
    }

    .status-Resolved {
        background: #27ae60;
    }

    .status-Dismissed {
        background: #95a5a6;
    }

    .btn-refresh {
        background: #e8a045;
        color: #1a1a2e;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-refresh:hover {
        background: #d4903a;
    }

    @media (max-width: 768px) {
        .cards-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="bi bi-graph-up" style="color:#e8a045;"></i> Marketplace Health Dashboard</h1>
                    <p class="last-updated"><i class="bi bi-clock"></i> Last updated: <span
                            id="timestamp"><?php echo date('Y-m-d H:i:s'); ?></span></p>
                </div>
                <button class="btn-refresh" onclick="location.reload();">
                    <i class="bi bi-arrow-repeat"></i> Refresh
                </button>
            </div>
        </div>

        <div class="cards-grid">
            <div class="card success">
                <div class="card-icon"><i class="bi bi-briefcase"></i></div>
                <h3>Active Contracts</h3>
                <div class="value" id="active-contracts">
                    <?php echo htmlspecialchars($health['active_contracts'] ?? 0); ?></div>
            </div>
            <div class="card info">
                <div class="card-icon"><i class="bi bi-cash-stack"></i></div>
                <h3>Escrow Locked</h3>
                <div class="value">$<span
                        id="escrowed-value"><?php echo number_format($health['total_escrowed'] ?? 0, 2); ?></span></div>
            </div>
            <div class="card info">
                <div class="card-icon"><i class="bi bi-cash"></i></div>
                <h3>Escrow Released</h3>
                <div class="value">$<span
                        id="released-value"><?php echo number_format($health['total_released'] ?? 0, 2); ?></span></div>
            </div>
            <div class="card warning">
                <div class="card-icon"><i class="bi bi-exclamation-triangle"></i></div>
                <h3>Open Disputes</h3>
                <div class="value" id="open-disputes"><?php echo htmlspecialchars($health['open_disputes'] ?? 0); ?>
                </div>
            </div>
            <div class="card">
                <div class="card-icon"><i class="bi bi-people"></i></div>
                <h3>Total Users</h3>
                <div class="value" id="total-users"><?php echo htmlspecialchars($health['total_users'] ?? 0); ?></div>
            </div>
            <div class="card">
                <div class="card-icon"><i class="bi bi-briefcase-fill"></i></div>
                <h3>Jobs Posted</h3>
                <div class="value" id="total-jobs"><?php echo htmlspecialchars($health['total_jobs_posted'] ?? 0); ?>
                </div>
            </div>
        </div>

        <div class="section">
            <h2><i class="bi bi-chat-dots me-2"></i> Recent Disputes</h2>
            <?php if (!empty($recentDisputes)): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Job</th>
                            <th>Raised By</th>
                            <th>Against</th>
                            <th>Status</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentDisputes as $dispute): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($dispute['job_title'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($dispute['raised_by_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($dispute['against_name'] ?? 'N/A'); ?></td>
                            <td>
                                <span
                                    class="status status-<?php echo str_replace(' ', '-', $dispute['status'] ?? 'Unknown'); ?>">
                                    <?php echo htmlspecialchars($dispute['status'] ?? 'Unknown'); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars(substr($dispute['reason'] ?? '', 0, 50)); ?>...</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-4">
                <i class="bi bi-check-circle" style="font-size: 48px; color: #27ae60;"></i>
                <p class="mt-2 text-muted">No disputes found. Marketplace is healthy!</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function refreshData() {
        fetch('../public/index.php?route=api/marketplace-health/data')
            .then(response => response.json())
            .then(data => {
                if (data.metrics) {
                    document.getElementById('active-contracts').innerText = data.metrics.active_contracts || 0;
                    document.getElementById('escrowed-value').innerText = (data.metrics.total_escrowed || 0)
                        .toLocaleString();
                    document.getElementById('released-value').innerText = (data.metrics.total_released || 0)
                        .toLocaleString();
                    document.getElementById('open-disputes').innerText = data.metrics.open_disputes || 0;
                    document.getElementById('total-users').innerText = data.metrics.total_users || 0;
                    document.getElementById('total-jobs').innerText = data.metrics.total_jobs_posted || 0;
                    document.getElementById('timestamp').innerText = new Date().toLocaleString();
                }
            })
            .catch(error => console.error('Error:', error));
    }

    setInterval(refreshData, 30000);
    </script>
</body>

</html>