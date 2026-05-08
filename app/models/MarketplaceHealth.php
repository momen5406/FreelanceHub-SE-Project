<?php

require_once __DIR__ . '/../core/database.php';

class MarketplaceHealth
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->db->openConnection();
    }

    public function getDashboardMetrics()
    {
        return [
            'active_contracts' => $this->getActiveContractsCount(),
            'total_escrowed_value' => $this->getTotalEscrowedValue(),
            'dispute_rate' => $this->getDisputeRate(),
            'completed_contracts' => $this->getCompletedContractsCount(),
            'total_freelancers' => $this->getTotalFreelancers(),
            'total_clients' => $this->getTotalClients(),
            'average_job_value' => $this->getAverageJobValue(),
            'platform_fees_collected' => $this->getPlatformFees()
        ];
    }

    public function getActiveContractsCount()
    {
        $query = "SELECT COUNT(*) as count FROM Jobs WHERE status = 'In Progress'";
        $result = $this->db->select($query);
        if ($result && count($result) > 0) {
            return $result[0]['count'] ?? 0;
        }
        return 0;
    }

    public function getTotalEscrowedValue()
    {
        $query = "SELECT COALESCE(SUM(amount), 0) as total FROM Escrow_Transactions WHERE status = 'Locked'";
        $result = $this->db->select($query);
        if ($result && count($result) > 0) {
            return $result[0]['total'] ?? 0;
        }
        return 0;
    }

    public function getDisputeRate()
    {
        $query = "SELECT 
                    COUNT(DISTINCT CASE WHEN d.status IN ('Open', 'Under Review') THEN d.job_id END) as disputed_jobs,
                    COUNT(DISTINCT j.id) as total_jobs
                  FROM Jobs j
                  LEFT JOIN Dispute d ON j.id = d.job_id
                  WHERE j.status IN ('In Progress', 'Completed')";
        $result = $this->db->select($query);
        if ($result && count($result) > 0) {
            $disputed = $result[0]['disputed_jobs'] ?? 0;
            $total = $result[0]['total_jobs'] ?? 0;

            if ($total == 0) {
                return 0;
            }

            return round(($disputed / $total) * 100, 2);
        }
        return 0;
    }

    public function getCompletedContractsCount()
    {
        $query = "SELECT COUNT(*) as count FROM Jobs WHERE status = 'Completed'";
        $result = $this->db->select($query);
        if ($result && count($result) > 0) {
            return $result[0]['count'] ?? 0;
        }
        return 0;
    }

    public function getTotalFreelancers()
    {
        $query = "SELECT COUNT(*) as count FROM Users WHERE LOWER(role) = 'freelancer'";
        $result = $this->db->select($query);
        if ($result && count($result) > 0) {
            return $result[0]['count'] ?? 0;
        }
        return 0;
    }

    public function getTotalClients()
    {
        $query = "SELECT COUNT(*) as count FROM Users WHERE LOWER(role) = 'client'";
        $result = $this->db->select($query);
        if ($result && count($result) > 0) {
            return $result[0]['count'] ?? 0;
        }
        return 0;
    }

    public function getAverageJobValue()
    {
        $query = "SELECT COALESCE(AVG(amount), 0) as average FROM Escrow_Transactions";
        $result = $this->db->select($query);
        if ($result && count($result) > 0) {
            return round($result[0]['average'] ?? 0, 2);
        }
        return 0;
    }

    public function getPlatformFees()
    {
        $query = "SELECT COALESCE(SUM(amount) * 0.1, 0) as total FROM Escrow_Transactions WHERE status = 'Released'";
        $result = $this->db->select($query);
        if ($result && count($result) > 0) {
            return $result[0]['total'] ?? 0;
        }
        return 0;
    }

    public function getContractsByStatus()
    {
        $query = "SELECT status, COUNT(*) as count FROM Jobs GROUP BY status";
        $result = $this->db->select($query);
        $data = [];
        if ($result && count($result) > 0) {
            foreach ($result as $row) {
                $data[$row['status']] = $row['count'];
            }
        }
        return $data;
    }

    public function getEscrowStats()
    {
        $query = "SELECT status, COUNT(*) as count, COALESCE(SUM(amount), 0) as total_amount FROM Escrow_Transactions GROUP BY status";
        $result = $this->db->select($query);
        $data = [];
        if ($result && count($result) > 0) {
            foreach ($result as $row) {
                $data[$row['status']] = [
                    'count' => $row['count'],
                    'total_amount' => $row['total_amount']
                ];
            }
        }
        return $data;
    }

    public function getDisputesByResolution()
    {
        $query = "SELECT status, COUNT(*) as count FROM Dispute GROUP BY status";
        $result = $this->db->select($query);
        $data = [];
        if ($result && count($result) > 0) {
            foreach ($result as $row) {
                $data[$row['status']] = $row['count'];
            }
        }
        return $data;
    }

    public function getWeeklyTrends()
    {
        $query = "SELECT COUNT(*) as total_jobs FROM Jobs";
        $result = $this->db->select($query);
        $data = [];
        if ($result && count($result) > 0) {
            $data[] = [
                'date' => date('Y-m-d'),
                'new_jobs' => $result[0]['total_jobs'] ?? 0
            ];
        } else {
            $data = [
                ['date' => date('Y-m-d'), 'new_jobs' => 0]
            ];
        }
        return $data;
    }

    public function getTopCategories()
    {
        $query = "SELECT 'Web Development' as category, COUNT(*) as job_count FROM Jobs UNION SELECT 'Mobile Development', 0 UNION SELECT 'Design', 0 UNION SELECT 'Writing', 0 UNION SELECT 'Marketing', 0 LIMIT 5";
        $result = $this->db->select($query);
        $data = [];
        if ($result && count($result) > 0) {
            foreach ($result as $row) {
                if ($row['job_count'] > 0 || $row['category'] == 'Web Development') {
                    $data[] = $row;
                }
            }
        }
        if (empty($data)) {
            $data = [
                ['category' => 'Web Development', 'job_count' => 0],
                ['category' => 'Mobile Development', 'job_count' => 0],
                ['category' => 'Design', 'job_count' => 0],
                ['category' => 'Writing', 'job_count' => 0],
                ['category' => 'Marketing', 'job_count' => 0]
            ];
        }
        return $data;
    }
}