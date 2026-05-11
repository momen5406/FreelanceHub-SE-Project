<?php
require_once __DIR__ . '/../models/QAChecklist.php';

class MilestoneController
{
    private $qaChecklist;

    public function __construct()
    {
        $this->qaChecklist = new QAChecklist();
    }

    public function submitWork()
    {
        session_start();

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Freelancer') {
            header('Location: ../auth/login.php');
            exit();
        }

        $milestoneId = $_GET['milestone_id'] ?? 0;
        $jobId = $_GET['job_id'] ?? 0;
        $freelancerId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['save_checklist'])) {
                $result = $this->qaChecklist->saveChecklist($milestoneId, $jobId, $freelancerId, $_POST);

                if ($result['submission_allowed']) {
                    $_SESSION['checklist_success'] = 'Checklist completed! You can now submit your work.';
                } else {
                    $_SESSION['checklist_error'] = 'Please complete all ' . ($result['total_items'] - $result['completed_items']) . ' remaining items.';
                }

                header("Location: submit-work.php?milestone_id=$milestoneId&job_id=$jobId");
                exit();
            }

            if (isset($_POST['submit_work'])) {
                $canSubmit = $this->qaChecklist->canSubmitWork($milestoneId, $freelancerId);

                if ($canSubmit['can_submit']) {
                    // Process work submission here
                    header("Location: submission-success.php?milestone_id=$milestoneId");
                    exit();
                } else {
                    $_SESSION['checklist_error'] = $canSubmit['message'];
                    header("Location: submit-work.php?milestone_id=$milestoneId&job_id=$jobId");
                    exit();
                }
            }
        }

        include __DIR__ . '/../views/milestones/submit-work.php';
    }
}