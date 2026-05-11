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

// Get client ID from job
$jobResult = $db->select("SELECT budget, client_id FROM jobs WHERE id = $jobId");
$jobBudget = $jobResult[0]['budget'] ?? 1000;
$clientId = $jobResult[0]['client_id'] ?? 0;

// Accept the proposal
$db->update("UPDATE proposals SET status = 'Accepted' WHERE id = $proposalId");

// Reject all other proposals for this job
$db->update("UPDATE proposals SET status = 'Rejected' WHERE job_id = $jobId AND id != $proposalId AND status = 'Pending'");

// Update job status and assign freelancer
$db->update("UPDATE jobs SET status = 'In Progress', assigned_freelancer_id = (SELECT freelancer_id FROM proposals WHERE id = $proposalId) WHERE id = $jobId");

// Calculate milestone amounts
$milestone1Amount = round($jobBudget * 0.3, 2);
$milestone2Amount = round($jobBudget * 0.3, 2);
$milestone3Amount = round($jobBudget * 0.4, 2);

// Create milestones and get their IDs
$db->insert("INSERT INTO milestones (title, deadline_date, status, job_id, amount) 
             VALUES ('Milestone 1: Planning & Setup', DATE_ADD(NOW(), INTERVAL 7 DAY), 'Pending', $jobId, $milestone1Amount)");
$milestone1Id = $db->connection->insert_id;

$db->insert("INSERT INTO milestones (title, deadline_date, status, job_id, amount) 
             VALUES ('Milestone 2: Development', DATE_ADD(NOW(), INTERVAL 14 DAY), 'Pending', $jobId, $milestone2Amount)");
$milestone2Id = $db->connection->insert_id;

$db->insert("INSERT INTO milestones (title, deadline_date, status, job_id, amount) 
             VALUES ('Milestone 3: Testing & Delivery', DATE_ADD(NOW(), INTERVAL 21 DAY), 'Pending', $jobId, $milestone3Amount)");
$milestone3Id = $db->connection->insert_id;

// LOCK ESCROW FUNDS FOR FIRST MILESTONE
$escrow = new Escrow();
$result = $escrow->lockFunds($milestone1Id, $clientId, $milestone1Amount);

if (!$result['success']) {
    $_SESSION['error'] = $result['error'];
}

$db->closeConnection();

header("Location: list.php?job_id=$jobId");
exit();