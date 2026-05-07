<div class="chart-container">
    <div class="chart-card">
        <h3>Contracts by Status</h3>
        <canvas id="contractsChart"></canvas>
    </div>

    <div class="chart-card">
        <h3>Escrow Statistics</h3>
        <canvas id="escrowChart"></canvas>
    </div>

    <div class="chart-card">
        <h3>Dispute Resolution Status</h3>
        <canvas id="disputesChart"></canvas>
    </div>

    <div class="chart-card">
        <h3>Weekly Job Trends</h3>
        <canvas id="trendsChart"></canvas>
    </div>

    <div class="chart-card">
        <h3>Top Job Categories</h3>
        <canvas id="categoriesChart"></canvas>
    </div>
</div>

<script>
const contractsData = <?php echo json_encode($contractsByStatus); ?>;
const escrowData = <?php echo json_encode($escrowStats); ?>;
const disputesData = <?php echo json_encode($disputesByResolution); ?>;
const weeklyData = <?php echo json_encode($weeklyTrends); ?>;
const categoriesData = <?php echo json_encode($topCategories); ?>;
</script>