<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
    exit;
}

$research_id = intval($_POST['research_id']);
$user_id = $_SESSION['user_id'];
$status = $_POST['status']; // 'typing' or 'idle'

$query = "INSERT INTO typing_status (research_id, user_id, status) 
          VALUES (?, ?, ?) 
          ON DUPLICATE KEY UPDATE status = ?, last_updated = NOW()";

$stmt = $conn->prepare($query);
$stmt->bind_param("iiss", $research_id, $user_id, $status, $status);
$stmt->execute();
?>
