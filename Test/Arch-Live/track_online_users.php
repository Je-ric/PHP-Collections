<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$research_id = isset($_POST['research_id']) ? intval($_POST['research_id']) : 0;
$user_id = $_SESSION['user_id'];

// ✅ Step 1: Update user's last activity in `users` table
$updateUserQuery = "UPDATE users SET last_active = NOW() WHERE id = ?";
$stmt = $conn->prepare($updateUserQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();

// ✅ Step 2: Track active users in `active_users` table
$trackUserQuery = "INSERT INTO active_users (research_id, user_id, last_active) 
                   VALUES (?, ?, NOW()) 
                   ON DUPLICATE KEY UPDATE last_active = NOW()";
$stmt = $conn->prepare($trackUserQuery);
$stmt->bind_param("ii", $research_id, $user_id);
$stmt->execute();

// ✅ Step 3: Remove inactive users (after 60 seconds instead of 30)
$conn->query("DELETE FROM active_users WHERE last_active < NOW() - INTERVAL 5 SECOND");

// ✅ Step 4: Fetch **Active Users**
$activeUsersQuery = "SELECT users.username FROM active_users 
                     JOIN users ON active_users.user_id = users.id 
                     WHERE active_users.research_id = ?";
$stmt = $conn->prepare($activeUsersQuery);
$stmt->bind_param("i", $research_id);
$stmt->execute();
$result = $stmt->get_result();

$active_users = [];
while ($row = $result->fetch_assoc()) {
    $active_users[] = $row['username'];
}

// ✅ Step 5: Fetch **All Users with Access**
$allUsersQuery = "SELECT users.username FROM research_sharing 
                  JOIN users ON research_sharing.user_id = users.id 
                  WHERE research_sharing.research_id = ?";
$stmt = $conn->prepare($allUsersQuery);
$stmt->bind_param("i", $research_id);
$stmt->execute();
$result = $stmt->get_result();

$all_users = [];
while ($row = $result->fetch_assoc()) {
    $all_users[] = $row['username'];
}

// ✅ Step 6: Remove active users from "All Users with Access" list
$inactive_users = array_diff($all_users, $active_users);

// ✅ Step 7: Return JSON response
$response = [
    "active_users" => array_values($active_users),
    "inactive_users" => array_values($inactive_users)
];

// ✅ Debugging: Log output
error_log("Active Users: " . json_encode($active_users));
error_log("Inactive Users: " . json_encode($inactive_users));

header('Content-Type: application/json');
echo json_encode($response);
?>
