<?php

// $host = "localhost:3307"; 
$host = "localhost";
$username = "root"; 
$password = ""; 
$database = "ecommerce";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
    
?>

