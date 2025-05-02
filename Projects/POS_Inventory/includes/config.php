<?php

$host = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "POS_Inventory";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

