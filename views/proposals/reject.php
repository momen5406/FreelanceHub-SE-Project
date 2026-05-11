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
$db->update("UPDATE proposals SET status = 'Rejected' WHERE id = $proposalId");
$db->closeConnection();

header("Location: list.php?job_id=$jobId");
exit();