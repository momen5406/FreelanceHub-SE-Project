<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace Health - FreelanceHub</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background: #f5f6fa;
        padding: 20px;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    h1 {
        color: #2c3e50;
        margin-bottom: 30px;
    }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .card h3 {
        color: #7f8c8d;
        font-size: 14px;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .card .value {
        font-size: 32px;
        font-weight: bold;
        color: #2c3e50;
    }

    .card.success .value {
        color: #27ae60;
    }

    .card.info .value {
        color: #3498db;
    }

    .card.warning .value {
        color: #f39c12;
    }

    .section {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .section h2 {
        color: #2c3e50;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ecf0f1;
    }

    th {
        background: #34495e;
        color: white;
    }

    tr:hover {
        background: #f8f9fa;
    }

    .status {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: bold;
        color: white;
        display: inline-block;
    }

    .status.open {
        background: #e74c3c;
    }

    .status.under-review {
        background: #f39c12;
    }

    .status.resolved {
        background: #27ae60;
    }

    .status.dismissed {
        background: #95a5a6;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>📊 Marketplace Health Dashboard</h1>

        <div class="cards-grid">
            <div class="card success">
                <h3>Active Contracts</h3>
                <div class="value"><?= htmlspecialchars($health['active_contracts'] ?? 0) ?></div>
            </div>
            <div class="card info">
                <h3>Escrow Locked</h3>
                <div class="value">$<?= number_format($health['total_escrowed'] ?? 0, 2) ?></div>
            </div>
            <div class="card info">
                <h3>Escrow Released</h3>
                <div class="value">$<?= number_format($health['total_released'] ?? 0, 2) ?></div>
            </div>
            <div class="card warning">
                <h3>Open Disputes</h3>
                <div class="value"><?= htmlspecialchars($health['open_disputes'] ?? 0) ?></div>
            </div>
            <div class="card">
                <h3>Total Users</h3>
                <div class="value"><?= htmlspecialchars($health['total_users'] ?? 0) ?></div>
            </div>
            <div class="card">
                <h3>Jobs Posted</h3>
                <div class="value"><?= htmlspecialchars($health['total_jobs_posted'] ?? 0) ?></div>
            </div>
        </div>

        <div class="section">
            <h2>⚠️ Recent Disputes</h2>
            <?php if (!empty($recentDisputes)): ?>
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
                        <td><?= htmlspecialchars($dispute['job_title'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($dispute['raised_by_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($dispute['against_name'] ?? 'N/A') ?></td>
                        <td><span
                                class="status <?= strtolower(str_replace(' ', '-', $dispute['status'] ?? '')) ?>"><?= htmlspecialchars($dispute['status'] ?? 'Unknown') ?></span>
                        </td>
                        <td><?= htmlspecialchars(substr($dispute['reason'] ?? '', 0, 50)) ?>...</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>✅ No disputes found. Marketplace is healthy!</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>