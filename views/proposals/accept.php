<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Client') {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../app/core/database.php';
require_once __DIR__ . '/../../app/models/Escrow.php';

$proposalId = $_GET['id'] ?? 0;
$jobId = $_GET['job_id'] ?? 0;

$db = new Database();
$db->openConnection();

// Get client ID and accepted proposal amount
$jobResult = $db->select("
    SELECT j.budget, j.client_id, p.bid_amount
    FROM jobs j
    JOIN proposals p ON p.id = $proposalId
    WHERE j.id = $jobId
");
$jobBudget = $jobResult[0]['budget'] ?? 1000;
$acceptedAmount = $jobResult[0]['bid_amount'] ?? $jobBudget;
$contractAmount = $acceptedAmount > 0 ? $acceptedAmount : $jobBudget;
$clientId = $jobResult[0]['client_id'] ?? 0;

// Accept the proposal
$db->update("UPDATE proposals SET status = 'Accepted' WHERE id = $proposalId");

// Reject all other proposals for this job
$db->update("UPDATE proposals SET status = 'Rejected' WHERE job_id = $jobId AND id != $proposalId AND status = 'Pending'");

// Update job status and assign freelancer
$db->update("UPDATE jobs SET status = 'In Progress', assigned_freelancer_id = (SELECT freelancer_id FROM proposals WHERE id = $proposalId) WHERE id = $jobId");

// Create one full-payment milestone for the accepted contract amount
$db->insert("INSERT INTO milestones (title, deadline_date, status, job_id, amount) 
             VALUES ('Full Project Delivery', DATE_ADD(NOW(), INTERVAL 21 DAY), 'Pending', $jobId, $contractAmount)");
$milestoneId = $db->connection->insert_id;

// Lock the full contract amount in escrow. Freelancer receives this amount minus platform fees on approval.
$escrow = new Escrow();
$result = $escrow->lockFunds($milestoneId, $clientId, $contractAmount);

if (!$result['success']) {
    $_SESSION['error'] = $result['error'];
}

$db->closeConnection();

header("Location: list.php?job_id=$jobId");
exit();
