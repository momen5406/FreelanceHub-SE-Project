<?php
require_once __DIR__ . '/../core/database.php';

class QAChecklist
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getChecklist($milestoneId, $freelancerId)
    {
        $this->db->openConnection();

        $result = $this->db->select("
            SELECT * FROM qa_checklists 
            WHERE milestone_id = $milestoneId AND freelancer_id = $freelancerId
            ORDER BY id DESC LIMIT 1
        ");

        $this->db->closeConnection();

        if (!empty($result)) {
            return $result[0];
        }

        return [
            'id' => 0,
            'milestone_id' => $milestoneId,
            'files_uploaded' => 0,
            'documentation_complete' => 0,
            'meets_requirements' => 0,
            'no_errors_found' => 0,
            'code_commented' => 0,
            'tests_passed' => 0,
            'submission_allowed' => 0
        ];
    }

    public function saveChecklist($milestoneId, $jobId, $freelancerId, $data)
    {
        $this->db->openConnection();

        $filesUploaded = isset($data['files_uploaded']) ? 1 : 0;
        $documentationComplete = isset($data['documentation_complete']) ? 1 : 0;
        $meetsRequirements = isset($data['meets_requirements']) ? 1 : 0;
        $noErrorsFound = isset($data['no_errors_found']) ? 1 : 0;
        $codeCommented = isset($data['code_commented']) ? 1 : 0;
        $testsPassed = isset($data['tests_passed']) ? 1 : 0;

        $totalChecked = $filesUploaded + $documentationComplete + $meetsRequirements + $noErrorsFound + $codeCommented + $testsPassed;
        $submissionAllowed = ($totalChecked == 6) ? 1 : 0;

        $checklistCompletedAt = ($submissionAllowed == 1) ? date('Y-m-d H:i:s') : 'NULL';

        $query = "INSERT INTO qa_checklists 
                  (milestone_id, job_id, freelancer_id, files_uploaded, documentation_complete, 
                   meets_requirements, no_errors_found, code_commented, tests_passed, 
                   submission_allowed, checklist_completed_at) 
                  VALUES 
                  ($milestoneId, $jobId, $freelancerId, $filesUploaded, $documentationComplete,
                   $meetsRequirements, $noErrorsFound, $codeCommented, $testsPassed,
                   $submissionAllowed, " . ($checklistCompletedAt == 'NULL' ? 'NULL' : "'$checklistCompletedAt'") . ")";

        $result = $this->db->insert($query);

        $this->db->closeConnection();

        return [
            'success' => true,
            'submission_allowed' => $submissionAllowed,
            'completed_items' => $totalChecked,
            'total_items' => 6
        ];
    }

    public function canSubmitWork($milestoneId, $freelancerId)
    {
        $this->db->openConnection();

        $result = $this->db->select("
            SELECT submission_allowed FROM qa_checklists 
            WHERE milestone_id = $milestoneId AND freelancer_id = $freelancerId
            ORDER BY id DESC LIMIT 1
        ");

        $this->db->closeConnection();

        if (!empty($result) && $result[0]['submission_allowed'] == 1) {
            return ['can_submit' => true, 'message' => 'Checklist completed. You can submit your work.'];
        }

        return ['can_submit' => false, 'message' => 'Please complete all checklist items before submitting.'];
    }

    public function getChecklistItems()
    {
        return [
            ['name' => 'files_uploaded', 'label' => 'All files have been uploaded', 'required' => true],
            ['name' => 'documentation_complete', 'label' => 'Documentation is complete', 'required' => true],
            ['name' => 'meets_requirements', 'label' => 'Work meets all milestone requirements', 'required' => true],
            ['name' => 'no_errors_found', 'label' => 'No errors or bugs found', 'required' => true],
            ['name' => 'code_commented', 'label' => 'Code is properly commented', 'required' => true],
            ['name' => 'tests_passed', 'label' => 'All tests passed successfully', 'required' => true]
        ];
    }
}