<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Client') {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../app/core/database.php';

$proposalId = $_GET['id'] ?? 0;
$jobId = $_GET['job_id'] ?? 0;

$db = new Database();
$db->openConnection();

$db->update("UPDATE proposals SET status = 'Accepted' WHERE id = $proposalId");
$db->update("UPDATE proposals SET status = 'Rejected' WHERE job_id = $jobId AND id != $proposalId AND status = 'Pending'");
$db->update("UPDATE jobs SET status = 'In Progress', assigned_freelancer_id = (SELECT freelancer_id FROM proposals WHERE id = $proposalId) WHERE id = $jobId");

$db->closeConnection();

header("Location: list.php?job_id=$jobId");
exit();