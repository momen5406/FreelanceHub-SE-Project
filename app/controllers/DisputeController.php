<?php
require_once __DIR__ . '/../models/Dispute.php';
require_once __DIR__ . '/../core/database.php';

class DisputeController
{
    private $disputeModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->disputeModel = new Dispute();
    }

    public function requireLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../auth/login.php');
            exit();
        }
    }

    public function canRaiseDisputeRole()
    {
        return isset($_SESSION['role']) && ($_SESSION['role'] === 'Client' || $_SESSION['role'] === 'Freelancer');
    }

    public function canModerate()
    {
        return isset($_SESSION['role']) && ($_SESSION['role'] === 'Dispute Mediator' || $_SESSION['role'] === 'Admin');
    }

    public function getActiveJobForDispute($jobId, $userId)
    {
        $db = new Database();
        $db->openConnection();
        $jobId = (int)$jobId;
        $userId = (int)$userId;

        $query = "SELECT j.*
                  FROM jobs j
                  WHERE j.id = $jobId
                    AND j.status = 'In Progress'
                    AND (j.client_id = $userId OR j.assigned_freelancer_id = $userId)
                  LIMIT 1";

        $result = $db->select($query);
        $db->closeConnection();
        return !empty($result) ? $result[0] : null;
    }

    public function handleRaise()
    {
        $this->requireLogin();
        if (!$this->canRaiseDisputeRole()) {
            header('Location: ../auth/login.php');
            exit();
        }

        $jobId = isset($_GET['job_id']) ? (int)$_GET['job_id'] : (int)($_POST['job_id'] ?? 0);
        $userId = (int)$_SESSION['user_id'];
        $job = $this->getActiveJobForDispute($jobId, $userId);
        $error = '';

        if (empty($job)) {
            header('Location: list.php');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reason = trim($_POST['reason'] ?? '');
            if ($reason === '') {
                $error = 'Please provide a reason for this dispute.';
            } else {
                $againstUserId = ($userId === (int)$job['client_id']) ? (int)$job['assigned_freelancer_id'] : (int)$job['client_id'];

                if ($againstUserId <= 0) {
                    $error = 'Unable to open dispute for this job.';
                } else {
                    $newId = $this->disputeModel->raiseDispute($reason, $jobId, $userId, $againstUserId);
                    if ($newId) {
                        header("Location: view.php?id=$newId");
                        exit();
                    }
                    $error = 'Failed to create dispute. Please try again.';
                }
            }
        }

        return ['job' => $job, 'job_id' => $jobId, 'error' => $error];
    }

    public function canAccessDispute($dispute, $userId)
    {
        if ($this->canModerate()) {
            return true;
        }
        return $userId === (int)$dispute['raised_by_id'] || $userId === (int)$dispute['against_user_id'];
    }

    public function handleView()
    {
        $this->requireLogin();
        $disputeId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($disputeId <= 0) {
            header('Location: list.php');
            exit();
        }

        $userId = (int)$_SESSION['user_id'];
        $dispute = $this->disputeModel->getDisputeById($disputeId);

        if (!$dispute || !$this->canAccessDispute($dispute, $userId)) {
            header('Location: list.php');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['send_message']) && !$this->canModerate()) {
                if ($dispute['status'] !== 'Resolved' && $dispute['status'] !== 'Dismissed') {
                    $message = trim($_POST['message'] ?? '');
                    if ($message !== '') {
                        $this->disputeModel->addMessage($disputeId, $userId, $message);
                    }
                }
                header("Location: view.php?id=$disputeId");
                exit();
            }

            if (isset($_POST['send_message']) && $this->canModerate()) {
                if ($dispute['status'] !== 'Resolved' && $dispute['status'] !== 'Dismissed') {
                    $message = trim($_POST['message'] ?? '');
                    if ($message !== '') {
                        $this->disputeModel->addMessage($disputeId, $userId, $message);
                    }
                }
                header("Location: view.php?id=$disputeId");
                exit();
            }

            if (isset($_POST['update_status']) && $this->canModerate()) {
                $status = $_POST['status'] ?? '';
                $notes = trim($_POST['resolution_notes'] ?? '');
                $allowed = ['Under Review', 'Resolved', 'Dismissed'];
                if (in_array($status, $allowed, true)) {
                    $this->disputeModel->updateStatus($disputeId, $status, $notes);
                }
                header("Location: view.php?id=$disputeId");
                exit();
            }
        }

        $messages = $this->disputeModel->getMessages($disputeId);
        $dispute = $this->disputeModel->getDisputeById($disputeId);

        return [
            'dispute' => $dispute,
            'messages' => $messages,
            'is_moderator' => $this->canModerate()
        ];
    }

    public function getListData()
    {
        $this->requireLogin();
        $userId = (int)$_SESSION['user_id'];
        $role = $_SESSION['role'] ?? '';

        if ($role === 'Admin' || $role === 'Dispute Mediator') {
            return $this->disputeModel->getAllDisputes();
        }
        return $this->disputeModel->getDisputesByUser($userId);
    }

    public function getMediatorDashboardData()
    {
        $this->requireLogin();
        if (!$this->canModerate()) {
            header('Location: ../auth/login.php');
            exit();
        }
        return $this->disputeModel->getAllDisputes();
    }
}
