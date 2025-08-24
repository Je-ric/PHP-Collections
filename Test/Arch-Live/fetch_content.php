<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
    exit;
}

$research_id = intval($_POST['research_id']);
$last_fetch_time = $_POST['last_fetch_time'] ?? '1970-01-01 00:00:00';

$query = "SELECT content, last_updated FROM research WHERE id = ? AND last_updated > ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $research_id, $last_fetch_time);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    header('Content-Type: application/json');
    echo json_encode(["content" => $row['content'], "last_updated" => $row['last_updated']]);
} else {
    echo json_encode(["content" => null]);
}
?>
