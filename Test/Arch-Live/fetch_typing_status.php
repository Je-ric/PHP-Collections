<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
    exit;
}

$research_id = intval($_POST['research_id']);
$user_id = $_SESSION['user_id'];

$query = "SELECT users.username FROM typing_status
          JOIN users ON typing_status.user_id = users.id
          WHERE typing_status.research_id = ? 
          AND typing_status.status = 'typing' 
          AND typing_status.user_id != ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $research_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$typing_users = [];
while ($row = $result->fetch_assoc()) {
    $typing_users[] = $row['username'];
}

header('Content-Type: application/json');
echo json_encode(["typing_users" => $typing_users]);
?>
