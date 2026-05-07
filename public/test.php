<?php
require_once __DIR__ . '/../app/models/MarketplaceHealth.php';

$health = new MarketplaceHealth();
$metrics = $health->getDashboardMetrics();

echo "<pre>";
print_r($metrics);
echo "</pre>";