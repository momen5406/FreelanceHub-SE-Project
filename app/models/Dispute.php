<?php
require_once __DIR__ . '/../core/database.php';

class Dispute
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getRecentDisputes($limit = 5)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    d.id,
                    d.reason,
                    d.status,
                    d.created_at,
                    j.title as job_title,
                    u1.name as raised_by_name,
                    u2.name as against_name
                FROM Dispute d
                LEFT JOIN Jobs j ON d.job_id = j.id
                LEFT JOIN Users u1 ON d.raised_by_id = u1.id
                LEFT JOIN Users u2 ON d.against_user_id = u2.id
                ORDER BY d.created_at DESC
                LIMIT :limit
            ");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getRecentDisputes: " . $e->getMessage());
            return [];
        }
    }
}