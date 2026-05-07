let refreshInterval;
let contractsChart, escrowChart, disputesChart, trendsChart, categoriesChart;

document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    startAutoRefresh();
});

function initializeCharts() {
    const ctx1 = document.getElementById('contractsChart')?.getContext('2d');
    const ctx2 = document.getElementById('escrowChart')?.getContext('2d');
    const ctx3 = document.getElementById('disputesChart')?.getContext('2d');
    const ctx4 = document.getElementById('trendsChart')?.getContext('2d');
    const ctx5 = document.getElementById('categoriesChart')?.getContext('2d');
    
    if (ctx1 && contractsData) {
        contractsChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: Object.keys(contractsData),
                datasets: [{
                    label: 'Number of Contracts',
                    data: Object.values(contractsData),
                    backgroundColor: '#3498db',
                    borderColor: '#2980b9',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    }
    
    if (ctx2 && escrowData) {
        const escrowAmounts = Object.values(escrowData).map(item => item.total_amount);
        escrowChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: Object.keys(escrowData),
                datasets: [{
                    data: escrowAmounts,
                    backgroundColor: ['#2ecc71', '#f39c12', '#e74c3c', '#95a5a6']
                }]
            },
            options: { responsive: true }
        });
    }
    
    if (ctx3 && disputesData) {
        disputesChart = new Chart(ctx3, {
            type: 'doughnut',
            data: {
                labels: Object.keys(disputesData),
                datasets: [{
                    data: Object.values(disputesData),
                    backgroundColor: ['#e74c3c', '#f39c12', '#2ecc71']
                }]
            },
            options: { responsive: true }
        });
    }
    
    if (ctx4 && weeklyData) {
        trendsChart = new Chart(ctx4, {
            type: 'line',
            data: {
                labels: weeklyData.map(item => item.date),
                datasets: [{
                    label: 'New Jobs',
                    data: weeklyData.map(item => item.new_jobs),
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    }
    
    if (ctx5 && categoriesData) {
        categoriesChart = new Chart(ctx5, {
            type: 'horizontalBar',
            data: {
                labels: categoriesData.map(item => item.category),
                datasets: [{
                    label: 'Job Count',
                    data: categoriesData.map(item => item.job_count),
                    backgroundColor: '#9b59b6'
                }]
            },
            options: {
                responsive: true,
                scales: { x: { beginAtZero: true } }
            }
        });
    }
}

function fetchRealTimeData() {
    fetch('/api/marketplace-health/data')
        .then(response => response.json())
        .then(data => {
            updateMetrics(data.metrics);
            updateCharts(data);
            document.getElementById('timestamp').innerText = data.timestamp;
        })
        .catch(error => console.error('Error fetching data:', error));
}

function updateMetrics(metrics) {
    if (document.getElementById('active-contracts')) {
        document.getElementById('active-contracts').innerText = numberFormat(metrics.active_contracts);
        document.getElementById('escrowed-value').innerText = '$' + numberFormat(metrics.total_escrowed_value, 2);
        document.getElementById('dispute-rate').innerText = metrics.dispute_rate + '%';
        document.getElementById('completed-contracts').innerText = numberFormat(metrics.completed_contracts);
        document.getElementById('total-freelancers').innerText = numberFormat(metrics.total_freelancers);
        document.getElementById('total-clients').innerText = numberFormat(metrics.total_clients);
        document.getElementById('avg-job-value').innerText = '$' + numberFormat(metrics.average_job_value, 2);
        document.getElementById('platform-fees').innerText = '$' + numberFormat(metrics.platform_fees_collected, 2);
    }
}

function updateCharts(data) {
    if (contractsChart && data.contracts_by_status) {
        contractsChart.data.labels = Object.keys(data.contracts_by_status);
        contractsChart.data.datasets[0].data = Object.values(data.contracts_by_status);
        contractsChart.update();
    }
    
    if (escrowChart && data.escrow_stats) {
        const escrowAmounts = Object.values(data.escrow_stats).map(item => item.total_amount);
        escrowChart.data.datasets[0].data = escrowAmounts;
        escrowChart.update();
    }
    
    if (disputesChart && data.disputes_by_resolution) {
        disputesChart.data.datasets[0].data = Object.values(data.disputes_by_resolution);
        disputesChart.update();
    }
    
    if (trendsChart && data.weekly_trends) {
        trendsChart.data.labels = data.weekly_trends.map(item => item.date);
        trendsChart.data.datasets[0].data = data.weekly_trends.map(item => item.new_jobs);
        trendsChart.update();
    }
    
    if (categoriesChart && data.top_categories) {
        categoriesChart.data.labels = data.top_categories.map(item => item.category);
        categoriesChart.data.datasets[0].data = data.top_categories.map(item => item.job_count);
        categoriesChart.update();
    }
}

function numberFormat(num, decimals = 0) {
    return Number(num).toLocaleString('en-US', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    });
}

function startAutoRefresh() {
    refreshInterval = setInterval(fetchRealTimeData, 30000);
}

function stopAutoRefresh() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
}