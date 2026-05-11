<?php
require_once __DIR__ . '/../core/database.php';

class Escrow
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function lockFunds($milestoneId, $clientId, $amount)
    {
        $this->db->openConnection();

        $balance = $this->db->select("SELECT wallet_balance FROM users WHERE id = $clientId");
        if (empty($balance) || $balance[0]['wallet_balance'] < $amount) {
            $this->db->closeConnection();
            return ['success' => false, 'error' => 'Insufficient funds'];
        }

        $this->db->update("UPDATE users SET wallet_balance = wallet_balance - $amount WHERE id = $clientId");

        $query = "INSERT INTO escrow_transactions (amount, status, milestone_id, created_at) 
                  VALUES ($amount, 'Locked', $milestoneId, NOW())";
        $escrowId = $this->db->insert($query);

        $this->db->update("UPDATE milestones SET escrow_id = $escrowId WHERE id = $milestoneId");

        $this->db->closeConnection();

        return ['success' => true, 'escrow_id' => $escrowId];
    }

    public function releaseFunds($milestoneId, $freelancerId)
    {
        $milestoneId = (int) $milestoneId;
        $freelancerId = (int) $freelancerId;

        $this->db->openConnection();

        $escrow = $this->db->select("
            SELECT e.id, e.amount FROM escrow_transactions e
            WHERE e.milestone_id = $milestoneId AND e.status = 'Locked'
        ");

        if (empty($escrow)) {
            $this->db->closeConnection();
            return ['success' => false, 'error' => 'No locked funds found'];
        }

        $grossAmount = (float) $escrow[0]['amount'];
        $freelancer = $this->db->select("SELECT total_earned FROM users WHERE id = $freelancerId");
        $lifetimeValue = (float) ($freelancer[0]['total_earned'] ?? 0);

        $feeTier = $this->db->select("
            SELECT fee_percentage
            FROM fee_tiers
            WHERE $lifetimeValue >= min_lifetime_value
              AND $lifetimeValue < max_lifetime_value
            ORDER BY min_lifetime_value DESC
            LIMIT 1
        ");

        $feePercentage = (float) ($feeTier[0]['fee_percentage'] ?? 10);
        $platformFee = round($grossAmount * ($feePercentage / 100), 2);
        $netAmount = round($grossAmount - $platformFee, 2);

        $this->db->update("
            UPDATE users
            SET wallet_balance = wallet_balance + $netAmount,
                total_earned = total_earned + $netAmount
            WHERE id = $freelancerId
        ");
        $this->db->update("UPDATE escrow_transactions SET status = 'Released', released_at = NOW() WHERE id = {$escrow[0]['id']}");

        $this->db->closeConnection();

        return [
            'success' => true,
            'amount' => $netAmount,
            'gross_amount' => $grossAmount,
            'platform_fee' => $platformFee,
            'fee_percentage' => $feePercentage
        ];
    }

    public function isFundsLocked($milestoneId)
    {
        $this->db->openConnection();
        $result = $this->db->select("
            SELECT e.status FROM escrow_transactions e
            WHERE e.milestone_id = $milestoneId AND e.status = 'Locked'
        ");
        $this->db->closeConnection();
        return !empty($result);
    }

    public function getLockedAmount($milestoneId)
    {
        $this->db->openConnection();
        $result = $this->db->select("
            SELECT e.amount FROM escrow_transactions e
            WHERE e.milestone_id = $milestoneId AND e.status = 'Locked'
        ");
        $this->db->closeConnection();
        return $result[0]['amount'] ?? 0;
    }
}
