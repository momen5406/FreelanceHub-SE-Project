<?php
require_once __DIR__ . '/../core/database.php';

class JobWizard
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function createJob($clientId, $wizardData)
    {
        $this->db->openConnection();

        $title = $this->db->escapeString($wizardData['title']);
        $description = $this->db->escapeString($wizardData['description']);
        $niche = $this->db->escapeString($wizardData['niche']);
        $budget = (int)$wizardData['budget'];

        $dynamicFields = json_encode($wizardData['dynamic_fields']);

        $query = "
            INSERT INTO Jobs (title, description, niche, budget, client_id, dynamic_fields, status, created_at)
            VALUES ('$title', '$description', '$niche', $budget, $clientId, '$dynamicFields', 'Open', NOW())
        ";

        $jobId = $this->db->insert($query);

        $this->db->closeConnection();

        return [
            'success' => true,
            'job_id' => $jobId,
            'message' => 'Job posted successfully!'
        ];
    }

    public function getJobNiche($jobId)
    {
        $this->db->openConnection();
        $result = $this->db->select("SELECT niche, dynamic_fields FROM Jobs WHERE id = $jobId");
        $this->db->closeConnection();

        return [
            'niche' => $result[0]['niche'] ?? null,
            'fields' => json_decode($result[0]['dynamic_fields'] ?? '{}', true)
        ];
    }
}