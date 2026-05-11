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
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

    th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .btn-pdf {
        background: #dc3545;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        cursor: pointer;
        margin-left: 10px;
    }

    .btn-pdf:hover {
        background: #c82333;
    }

    .no-print {
        print-color-adjust: exact;
    }

    @media print {
        .no-print {
            display: none !important;
        }

        body {
            padding: 20px;
            background: white;
        }

        .stat-card,
        .chart-card {
            box-shadow: none;
            border: 1px solid #ddd;
        }
    }
    </style>
</head>

<body>

    <?php require_once __DIR__ . '/../partials/header.php'; ?>

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3><i class="bi bi-graph-up me-2" style="color:#e8a045;"></i>Marketplace Health Dashboard</h3>
            <button onclick="window.print()" class="btn-pdf no-print">
                <i class="bi bi-file-pdf"></i> Export as PDF
            </button>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-title">Active Contracts</div>
                    <div class="stat-value" id="active-contracts">
                        <?php echo number_format($metrics['active_contracts'] ?? 0); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-title">Escrowed Value</div>
                    <div class="stat-value">$<span id="escrowed-value">
                            <?php
                            $escrowed = $metrics['total_escrowed_value'] ?? 0;
                            if (is_array($escrowed)) $escrowed = $escrowed['total'] ?? 0;
                            echo number_format($escrowed, 2);
                            ?>
                        </span></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-title">Dispute Rate</div>
                    <div class="stat-value"><span id="dispute-rate">
                            <?php
                            $disputeRate = $metrics['dispute_rate'] ?? 0;
                            if (is_array($disputeRate)) $disputeRate = $disputeRate['rate'] ?? 0;
                            echo $disputeRate;
                            ?>
                        </span>%</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-title">Completed Contracts</div>
                    <div class="stat-value" id="completed-contracts">
                        <?php echo number_format($metrics['completed_contracts'] ?? 0); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-title">Total Freelancers</div>
                    <div class="stat-value" id="total-freelancers">
                        <?php echo number_format($metrics['total_freelancers'] ?? 0); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-title">Total Clients</div>
                    <div class="stat-value" id="total-clients">
                        <?php echo number_format($metrics['total_clients'] ?? 0); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-title">Average Job Value</div>
                    <div class="stat-value">$<span id="avg-job-value">
                            <?php
                            $avgValue = $metrics['average_job_value'] ?? 0;
                            if (is_array($avgValue)) {
                                $avgValue = $avgValue['original'] ?? 0;
                            }
                            echo number_format($avgValue, 2);
                            ?>
                        </span></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-title">Platform Fees</div>
                    <div class="stat-value">$<span id="platform-fees">
                            <?php
                            $fees = $metrics['platform_fees_collected'] ?? 0;
                            if (is_array($fees)) $fees = $fees['total'] ?? 0;
                            echo number_format($fees, 2);
                            ?>
                        </span></div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <div class="chart-card">
                    <h5>Contracts by Status</h5>
                    <?php if (!empty($contractsByStatus)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($contractsByStatus as $status => $count): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($status); ?></td>
                                    <td><?php echo number_format($count); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted">No data available</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-card">
                    <h5>Escrow Statistics</h5>
                    <?php if (!empty($escrowStats)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Count</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($escrowStats as $status => $data): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($status); ?></td>
                                    <td><?php echo number_format($data['count'] ?? 0); ?></td>
                                    <td>$<?php echo number_format($data['total_amount'] ?? 0, 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted">No data available</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="chart-card">
                    <h5>Dispute Status</h5>
                    <?php if (!empty($disputesByResolution)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($disputesByResolution as $status => $count): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($status); ?></td>
                                    <td><?php echo number_format($count); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted">No disputes found</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-card">
                    <h5>Weekly Job Trends (Last 7 Days)</h5>
                    <?php if (!empty($weeklyTrends)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>New Jobs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($weeklyTrends as $trend): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($trend['date'] ?? $trend['full_date'] ?? '-'); ?>
                                    </td>
                                    <td><?php echo number_format($trend['new_jobs'] ?? 0); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted">No data available</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <script src="../../public/assets/js/bootstrap.bundle.min.js"></script>
    <script>
    function refreshData() {
        fetch('../../public/index.php?route=api/marketplace-health/data')
            .then(response => response.json())
            .then(data => {
                if (data.metrics) {
                    document.getElementById('active-contracts').innerText = data.metrics.active_contracts || 0;

                    let escrowed = data.metrics.total_escrowed_value || 0;
                    if (typeof escrowed === 'object') escrowed = escrowed.total || 0;
                    document.getElementById('escrowed-value').innerText = escrowed.toLocaleString();

                    let disputeRate = data.metrics.dispute_rate || 0;
                    if (typeof disputeRate === 'object') disputeRate = disputeRate.rate || 0;
                    document.getElementById('dispute-rate').innerText = disputeRate;

                    document.getElementById('completed-contracts').innerText = data.metrics.completed_contracts ||
                    0;
                    document.getElementById('total-freelancers').innerText = data.metrics.total_freelancers || 0;
                    document.getElementById('total-clients').innerText = data.metrics.total_clients || 0;

                    let avgValue = data.metrics.average_job_value || 0;
                    if (typeof avgValue === 'object') avgValue = avgValue.original || 0;
                    document.getElementById('avg-job-value').innerText = avgValue.toLocaleString();

                    let fees = data.metrics.platform_fees_collected || 0;
                    if (typeof fees === 'object') fees = fees.total || 0;
                    document.getElementById('platform-fees').innerText = fees.toLocaleString();
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Auto refresh every 30 seconds
    setInterval(refreshData, 30000);
    </script>
</body>

</html>