<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Client') {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../app/core/database.php';
require_once __DIR__ . '/../../app/models/Escrow.php';

$id = $_GET['id'] ?? 0;
$milestoneId = $_GET['milestone_id'] ?? 0;
$jobId = $_GET['job_id'] ?? 0;

$db = new Database();
$db->openConnection();

// Get freelancer ID from deliverable
$deliverable = $db->select("SELECT freelancer_id FROM deliverables WHERE id = $id");
$freelancerId = $deliverable[0]['freelancer_id'] ?? 0;

// Update deliverable status
$db->update("UPDATE deliverables SET status = 'Approved', approved_at = NOW() WHERE id = $id");

// Update milestone status
$db->update("UPDATE milestones SET status = 'Completed' WHERE id = $milestoneId");

// RELEASE ESCROW FUNDS TO FREELANCER
$escrow = new Escrow();
$releaseResult = $escrow->releaseFunds($milestoneId, $freelancerId);

// Get all milestones for this job
$allMilestones = $db->select("SELECT status FROM milestones WHERE job_id = $jobId");

$allCompleted = true;
foreach ($allMilestones as $milestone) {
    if ($milestone['status'] != 'Completed') {
        $allCompleted = false;
        break;
    }
}

// If all milestones are completed, mark job as Completed
if ($allCompleted && !empty($allMilestones)) {
    $db->update("UPDATE jobs SET status = 'Completed' WHERE id = $jobId");
}

$db->closeConnection();

if ($releaseResult['success']) {
    $_SESSION['success'] = "Work approved! $" . number_format($releaseResult['amount'], 2) . " released to freelancer after $" . number_format($releaseResult['platform_fee'], 2) . " platform fee.";
} else {
    $_SESSION['error'] = $releaseResult['error'];
}

header("Location: pending-approvals.php?success=approved");
exit();
