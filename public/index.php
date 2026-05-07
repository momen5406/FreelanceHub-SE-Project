<?php
session_start();
require_once __DIR__ . '/../app/core/database.php';

$route = isset($_GET['route']) ? $_GET['route'] : '';

if ($route == 'marketplace-health') {
    require_once __DIR__ . '/../app/controllers/MarketplaceHealthController.php';
    $controller = new MarketplaceHealthController();
    $controller->index();
    exit();
}

if ($route == 'api/marketplace-health/data') {
    require_once __DIR__ . '/../app/controllers/api/MarketplaceHealthApi.php';
    exit();
}

echo "Welcome to FreelanceHub SE Project";