<?php
require_once __DIR__ . '/../core/database.php';

class Dispute
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function raiseDispute($reason, $jobId, $raisedById, $againstUserId)
    {
        $this->db->openConnection();
        $safeReason = $this->db->connection->real_escape_string($reason);
        $jobId = (int)$jobId;
        $raisedById = (int)$raisedById;
        $againstUserId = (int)$againstUserId;

        $query = "INSERT INTO dispute (reason, status, job_id, raised_by_id, against_user_id, created_at)
                  VALUES ('$safeReason', 'Open', $jobId, $raisedById, $againstUserId, NOW())";
        $result = $this->db->insert($query);
        $this->db->closeConnection();
        return $result;
    }

    public function getDisputeById($disputeId)
    {
        $this->db->openConnection();
        $disputeId = (int)$disputeId;

        $query = "SELECT d.*, j.title as job_title,
                         rb.name as raised_by_name,
                         au.name as against_user_name,
                         j.client_id,
                         j.assigned_freelancer_id
                  FROM dispute d
                  LEFT JOIN jobs j ON d.job_id = j.id
                  LEFT JOIN users rb ON d.raised_by_id = rb.id
                  LEFT JOIN users au ON d.against_user_id = au.id
                  WHERE d.id = $disputeId
                  LIMIT 1";
        $result = $this->db->select($query);
        $this->db->closeConnection();
        return !empty($result) ? $result[0] : null;
    }

    public function getDisputesByUser($userId)
    {
        $this->db->openConnection();
        $userId = (int)$userId;

        $query = "SELECT d.*, j.title as job_title,
                         rb.name as raised_by_name,
                         au.name as against_user_name
                  FROM dispute d
                  LEFT JOIN jobs j ON d.job_id = j.id
                  LEFT JOIN users rb ON d.raised_by_id = rb.id
                  LEFT JOIN users au ON d.against_user_id = au.id
                  WHERE d.raised_by_id = $userId OR d.against_user_id = $userId
                  ORDER BY
                      CASE d.status
                          WHEN 'Open' THEN 1
                          WHEN 'Under Review' THEN 2
                          WHEN 'Resolved' THEN 3
                          WHEN 'Dismissed' THEN 4
                          ELSE 5
                      END,
                      d.created_at DESC";
        $result = $this->db->select($query);
        $this->db->closeConnection();
        return $result ? $result : [];
    }

    public function getAllDisputes()
    {
        $this->db->openConnection();
        $query = "SELECT d.*, j.title as job_title,
                         rb.name as raised_by_name,
                         au.name as against_user_name
                  FROM dispute d
                  LEFT JOIN jobs j ON d.job_id = j.id
                  LEFT JOIN users rb ON d.raised_by_id = rb.id
                  LEFT JOIN users au ON d.against_user_id = au.id
                  ORDER BY
                      CASE d.status
                          WHEN 'Open' THEN 1
                          WHEN 'Under Review' THEN 2
                          WHEN 'Resolved' THEN 3
                          WHEN 'Dismissed' THEN 4
                          ELSE 5
                      END,
                      d.created_at DESC";
        $result = $this->db->select($query);
        $this->db->closeConnection();
        return $result ? $result : [];
    }

    public function addMessage($disputeId, $userId, $message)
    {
        $this->db->openConnection();
        $disputeId = (int)$disputeId;
        $userId = (int)$userId;
        $safeMessage = $this->db->connection->real_escape_string($message);

        $query = "INSERT INTO dispute_messages (dispute_id, user_id, message, created_at)
                  VALUES ($disputeId, $userId, '$safeMessage', NOW())";
        $result = $this->db->insert($query);
        $this->db->closeConnection();
        return $result;
    }

    public function getMessages($disputeId)
    {
        $this->db->openConnection();
        $disputeId = (int)$disputeId;

        $query = "SELECT dm.*, u.name as sender_name
                  FROM dispute_messages dm
                  LEFT JOIN users u ON dm.user_id = u.id
                  WHERE dm.dispute_id = $disputeId
                  ORDER BY dm.created_at ASC";
        $result = $this->db->select($query);
        $this->db->closeConnection();
        return $result ? $result : [];
    }

    public function updateStatus($disputeId, $status, $resolutionNotes = '')
    {
        $this->db->openConnection();
        $disputeId = (int)$disputeId;
        $safeStatus = $this->db->connection->real_escape_string($status);
        $safeNotes = $this->db->connection->real_escape_string($resolutionNotes);

        if ($safeStatus === 'Resolved' || $safeStatus === 'Dismissed') {
            $query = "UPDATE dispute
                      SET status = '$safeStatus',
                          resolution_notes = '$safeNotes',
                          resolved_at = NOW()
                      WHERE id = $disputeId";
        } else {
            $query = "UPDATE dispute
                      SET status = '$safeStatus'
                      WHERE id = $disputeId";
        }

        $result = $this->db->update($query);
        $this->db->closeConnection();
        return $result !== false;
    }

    public function createAppeal($disputeId, $requestedById, $reason)
    {
        $this->db->openConnection();
        $disputeId = (int)$disputeId;
        $requestedById = (int)$requestedById;
        $safeReason = $this->db->connection->real_escape_string($reason);

        $query = "INSERT INTO dispute_appeals (dispute_id, requested_by_id, reason, status, created_at)
                  VALUES ($disputeId, $requestedById, '$safeReason', 'Pending', NOW())";
        $result = $this->db->insert($query);
        $this->db->closeConnection();
        return $result;
    }

    public function getAppealsByDispute($disputeId)
    {
        $this->db->openConnection();
        $disputeId = (int)$disputeId;
        $query = "SELECT da.*, u.name as requested_by_name, du.name as decided_by_name
                  FROM dispute_appeals da
                  LEFT JOIN users u ON da.requested_by_id = u.id
                  LEFT JOIN users du ON da.decided_by_id = du.id
                  WHERE da.dispute_id = $disputeId
                  ORDER BY da.created_at DESC";
        $result = $this->db->select($query);
        $this->db->closeConnection();
        return $result ? $result : [];
    }
}