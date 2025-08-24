<?php
include 'db_conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ensure required data is sent
if (!isset($_POST['research_id'], $_POST['title'], $_POST['content'])) {
    die("Missing required parameters.");
}

$research_id = intval($_POST['research_id']);
$title = trim($_POST['title']);
$content = $_POST['content'];

// Check if user has edit access
$accessQuery = "SELECT access_level FROM research_sharing WHERE research_id = ? AND user_id = ?";
$stmt = $conn->prepare($accessQuery);
$stmt->bind_param("ii", $research_id, $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($access_level);
$stmt->fetch();

if ($stmt->num_rows === 0 || $access_level !== 'edit') {
    die("You do not have permission to edit this research.");
}

// Update the research title and content
$updateQuery = "UPDATE research SET title = ?, content = ?, updated_at = NOW() WHERE id = ?";
$stmt = $conn->prepare($updateQuery);
$stmt->bind_param("ssi", $title, $content, $research_id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}
?>
