<div class="metric-card">
    <div class="metric-icon">📊</div>
    <div class="metric-info">
        <h3>Active Contracts</h3>
        <p class="metric-value" id="active-contracts"><?php echo number_format($metrics['active_contracts']); ?></p>
    </div>
</div>

<div class="metric-card">
    <div class="metric-icon">💰</div>
    <div class="metric-info">
        <h3>Total Escrowed Value</h3>
        <p class="metric-value" id="escrowed-value">$<?php echo number_format($metrics['total_escrowed_value'], 2); ?>
        </p>
    </div>
</div>

<div class="metric-card">
    <div class="metric-icon">⚖️</div>
    <div class="metric-info">
        <h3>Dispute Rate</h3>
        <p class="metric-value" id="dispute-rate"><?php echo $metrics['dispute_rate']; ?>%</p>
    </div>
</div>

<div class="metric-card">
    <div class="metric-icon">✅</div>
    <div class="metric-info">
        <h3>Completed Contracts</h3>
        <p class="metric-value" id="completed-contracts"><?php echo number_format($metrics['completed_contracts']); ?>
        </p>
    </div>
</div>

<div class="metric-card">
    <div class="metric-icon">👥</div>
    <div class="metric-info">
        <h3>Total Freelancers</h3>
        <p class="metric-value" id="total-freelancers"><?php echo number_format($metrics['total_freelancers']); ?></p>
    </div>
</div>

<div class="metric-card">
    <div class="metric-icon">🏢</div>
    <div class="metric-info">
        <h3>Total Clients</h3>
        <p class="metric-value" id="total-clients"><?php echo number_format($metrics['total_clients']); ?></p>
    </div>
</div>

<div class="metric-card">
    <div class="metric-icon">📈</div>
    <div class="metric-info">
        <h3>Average Job Value</h3>
        <p class="metric-value" id="avg-job-value">$<?php echo number_format($metrics['average_job_value'], 2); ?></p>
    </div>
</div>

<div class="metric-card">
    <div class="metric-icon">💵</div>
    <div class="metric-info">
        <h3>Platform Fees Collected</h3>
        <p class="metric-value" id="platform-fees">$<?php echo number_format($metrics['platform_fees_collected'], 2); ?>
        </p>
    </div>
</div>