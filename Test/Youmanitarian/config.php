<?php

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST']; 
$user = $_ENV['DB_USERNAME']; 
$password = $_ENV['DB_PASSWORD'];
$database = $_ENV['DB_DATABASE'];

// echo "DB_HOST: " . $host . "<br>";
// echo "DB_USERNAME: " . $user . "<br>";
// echo "DB_PASSWORD: " . $password . "<br>";
// echo "DB_DATABASE: " . $database . "<br>";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
