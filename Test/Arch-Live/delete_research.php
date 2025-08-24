<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$research_id = $_POST['research_id'];

?>
