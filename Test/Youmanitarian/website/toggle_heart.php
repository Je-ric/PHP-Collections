<?php
session_start();
include('../config.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$content_id = $_POST['content_id'] ?? 0;
$user_id = $_SESSION['user_id'];

// Check if user already reacted
$check_query = "SELECT * FROM heart_reacts WHERE content_id = ? AND user_id = ?";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("ii", $content_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // If reacted, remove the reaction
    $delete_query = "DELETE FROM heart_reacts WHERE content_id = ? AND user_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("ii", $content_id, $user_id);
    $stmt->execute();
    $reacted = false;
} else {
    // If not reacted, add a reaction
    $insert_query = "INSERT INTO heart_reacts (content_id, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ii", $content_id, $user_id);
    $stmt->execute();
    $reacted = true;
}

// Get updated reaction count
$count_query = "SELECT COUNT(*) as total FROM heart_reacts WHERE content_id = ?";
$stmt = $conn->prepare($count_query);
$stmt->bind_param("i", $content_id);
$stmt->execute();
$count_result = $stmt->get_result();
$react_count = $count_result->fetch_assoc()['total'];

echo json_encode(["success" => true, "reacted" => $reacted, "count" => $react_count]);
?>
