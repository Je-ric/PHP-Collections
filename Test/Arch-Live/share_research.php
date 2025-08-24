<?php
include 'db_conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];

// Validate input
if (!isset($_POST['research_id'], $_POST['username'], $_POST['access_level'])) {
    die("Missing parameters.");
}

$research_id = intval($_POST['research_id']);
$usernameOrEmail = trim($_POST['username']);
$access_level = $_POST['access_level']; // 'edit' or 'view'

// Ensure access level is valid
if (!in_array($access_level, ['edit', 'view'])) {
    die("Invalid access level.");
}

// Get user ID of the recipient
$userQuery = "SELECT id FROM users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
$stmt->execute();
$result = $stmt->get_result();
$recipient = $result->fetch_assoc();

if (!$recipient) {
    die("User not found.");
}

$recipient_id = $recipient['id'];

// Check if already shared
$checkQuery = "SELECT * FROM research_sharing WHERE research_id = ? AND user_id = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("ii", $research_id, $recipient_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    die("This research is already shared with this user.");
}

// Share the research
$insertQuery = "INSERT INTO research_sharing (research_id, user_id, access_level) VALUES (?, ?, ?)";
$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("iis", $research_id, $recipient_id, $access_level);

if ($stmt->execute()) {
    echo "Research shared successfully!";
} else {
    echo "Error sharing research.";
}
?>
