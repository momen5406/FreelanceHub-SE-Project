<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace Health - FreelanceHub</title>
    <link href="../../public/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f5f4f0;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: bold;
        color: #1a1a2e;
    }

    .stat-title {
        color: #6c757d;
        font-size: 13px;
        text-transform: uppercase;
    }

    .chart-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .chart-card h5 {
        color: #1a1a2e;
        border-left: 3px solid #e8a045;
        padding-left: 12px;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }
    </style>
</head>

<body>

    <?php require_once __DIR__ . '/../partials/header.php'; ?>

    <div class="container py-4">

        <div class="mb-3">
            <h3><i class="bi bi-graph-up me-2" style="color:#e8a045;"></i>Marketplace Health Dashboard</h3>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-title">Active Contracts</div>
                    <div class="stat-value" id="active-contracts">
                        <?php echo number_format($metrics['active_contracts'] ?? 0); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-title">Escrowed Value</div>
                    <div class="stat-value">$<span
                            id="escrowed-value"><?php echo number_format($metrics['total_escrowed_value'] ?? 0, 2); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-title">Dispute Rate</div>
                    <div class="stat-value"><span id="dispute-rate"><?php echo $metrics['dispute_rate'] ?? 0; ?></span>%
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-title">Completed Contracts</div>
                    <div class="stat-value" id="completed-contracts">
                        <?php echo number_format($metrics['completed_contracts'] ?? 0); ?></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-title">Total Freelancers</div>
                    <div class="stat-value" id="total-freelancers">
                        <?php echo number_format($metrics['total_freelancers'] ?? 0); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-title">Total Clients</div>
                    <div class="stat-value" id="total-clients">
                        <?php echo number_format($metrics['total_clients'] ?? 0); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-title">Average Job Value</div>
                    <div class="stat-value">$<span
                            id="avg-job-value"><?php echo number_format($metrics['average_job_value'] ?? 0, 2); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-title">Platform Fees</div>
                    <div class="stat-value">$<span
                            id="platform-fees"><?php echo number_format($metrics['platform_fees_collected'] ?? 0, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <div class="chart-card">
                    <h5>Contracts by Status</h5>
                    <table>
                        <tr>
                            <th>Status</th>
                            <th>Count</th>
                        </tr>
                        <?php foreach ($contractsByStatus as $status => $count): ?>
                        <tr>
                            <td><?php echo $status; ?></td>
                            <td><?php echo $count; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-card">
                    <h5>Escrow Statistics</h5>
                    <table>
                        <tr>
                            <th>Status</th>
                            <th>Count</th>
                            <th>Amount</th>
                        </tr>
                        <?php foreach ($escrowStats as $status => $data): ?>
                        <tr>
                            <td><?php echo $status; ?></td>
                            <td><?php echo $data['count']; ?></td>
                            <td>$<?php echo number_format($data['total_amount'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="chart-card">
                    <h5>Dispute Status</h5>
                    <table>
                        <tr>
                            <th>Status</th>
                            <th>Count</th>
                        </tr>
                        <?php foreach ($disputesByResolution as $status => $count): ?>
                        <tr>
                            <td><?php echo $status; ?></td>
                            <td><?php echo $count; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-card">
                    <h5>Weekly Job Trends</h5>
                    <table>
                        <tr>
                            <th>Date</th>
                            <th>New Jobs</th>
                        </tr>
                        <?php foreach ($weeklyTrends as $trend): ?>
                        <tr>
                            <td><?php echo $trend['date']; ?></td>
                            <td><?php echo $trend['new_jobs']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="../../public/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>