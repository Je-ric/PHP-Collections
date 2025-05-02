<?php
class DatabaseConnector {
    private $host;
    private $username;
    private $password;
    private $database;
    private $conn;

    public function __construct($host, $username, $password, $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    public function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

// Usage
$host = "localhost:3307";
$username = "root";
$password = "";
$database = "studentDB";

$databaseConnector = new DatabaseConnector($host, $username, $password, $database);
$databaseConnector->connect();
$conn = $databaseConnector->getConnection();
?>
