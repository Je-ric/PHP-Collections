<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
    exit;
}

$research_id = intval($_POST['research_id']);
$new_content = $_POST['content'];
$last_updated = $_POST['last_updated']; // Timestamp from client-side

// Check if another user updated after the last known update
$query = "SELECT last_updated FROM research WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $research_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && strtotime($row['last_updated']) > strtotime($last_updated)) {
    echo json_encode(["status" => "conflict", "message" => "Newer content available"]);
    exit;
}

// Save if no conflicts
$query = "UPDATE research SET content = ?, last_updated = NOW() WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $new_content, $research_id);
$stmt->execute();

echo json_encode(["status" => "success", "last_updated" => date('Y-m-d H:i:s')]);
?>
