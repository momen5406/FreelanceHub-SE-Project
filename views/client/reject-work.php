<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Client') {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../app/core/database.php';

$id = $_GET['id'] ?? 0;
$milestoneId = $_GET['milestone_id'] ?? 0;

$db = new Database();
$db->openConnection();

$db->update("UPDATE deliverables SET status = 'Rejected', rejected_at = NOW() WHERE id = $id");
$db->update("UPDATE milestones SET status = 'Pending' WHERE id = $milestoneId");

$db->closeConnection();

header("Location: pending-approvals.php");
exit();