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
        $metrics = $this->marketplaceHealth->getDashboardMetrics();
        $contractsByStatus = $this->marketplaceHealth->getContractsByStatus();
        $escrowStats = $this->marketplaceHealth->getEscrowStats();
        $disputesByResolution = $this->marketplaceHealth->getDisputesByResolution();
        $weeklyTrends = $this->marketplaceHealth->getWeeklyTrends();
        $topCategories = $this->marketplaceHealth->getTopCategories();

        require_once __DIR__ . '/../../views/marketplace-health/index.php';
    }
}