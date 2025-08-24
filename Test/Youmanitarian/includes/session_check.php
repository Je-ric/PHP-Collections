<?php
session_start(); 
include('../../config.php'); 


if (!isset($_SESSION['user_id'])) {
    header('Location: ../../google-login.php');
    exit();
}


$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? '';


$allowed_roles = ['Admin']; 

if (!in_array($user_role, $allowed_roles)) {
    header('Location: ../../unauthorized.php'); 
    exit();
}
?>
