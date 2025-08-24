<?php
include 'db_conn.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$updateQuery = "UPDATE users SET last_active = NOW() WHERE id = ?";
$stmt = $conn->prepare($updateQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$research_id = $_GET['id'];
$query = "SELECT content FROM research WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $research_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
echo json_encode($row);
?>
