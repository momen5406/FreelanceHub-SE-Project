<?php
require_once __DIR__ . '/../../models/MarketplaceHealth.php';

header('Content-Type: application/json');

$marketplaceHealth = new MarketplaceHealth();

$data = [
    'metrics' => $marketplaceHealth->getDashboardMetrics(),
    'contracts_by_status' => $marketplaceHealth->getContractsByStatus(),
    'escrow_stats' => $marketplaceHealth->getEscrowStats(),
    'disputes_by_resolution' => $marketplaceHealth->getDisputesByResolution(),
    'weekly_trends' => $marketplaceHealth->getWeeklyTrends(),
    'top_categories' => $marketplaceHealth->getTopCategories(),
    'timestamp' => date('Y-m-d H:i:s')
];

echo json_encode($data);