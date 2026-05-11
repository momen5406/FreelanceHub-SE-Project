<?php
require_once __DIR__ . '/../models/MarketplaceHealth.php';

class MarketplaceHealthController
{
    private $marketplaceHealth;

    public function __construct()
    {
        $this->marketplaceHealth = new MarketplaceHealth();
    }

    public function index()
    {
        // Check if user is admin
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
            header('Location: /FreelanceHub-SE-Project/views/auth/login.php');
            exit();
        }

        $metrics = $this->marketplaceHealth->getDashboardMetrics();
        $contractsByStatus = $this->marketplaceHealth->getContractsByStatus();
        $escrowStats = $this->marketplaceHealth->getEscrowStats();
        $disputesByResolution = $this->marketplaceHealth->getDisputesByResolution();
        $weeklyTrends = $this->marketplaceHealth->getWeeklyTrends();
        $topCategories = $this->marketplaceHealth->getTopCategories();

        require_once __DIR__ . '/../../views/marketplace-health/index.php';
    }

    public function getApiData()
    {
        header('Content-Type: application/json');

        $metrics = $this->marketplaceHealth->getDashboardMetrics();

        echo json_encode([
            'success' => true,
            'metrics' => $metrics,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
}