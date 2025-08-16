<?php
require_once __DIR__ . '/../db/config.php';

class Authentication {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
        session_start(); 
    }

    public function registerUser($username, $password) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return false; // username exists
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);

        return $stmt->execute();
    }

    public function loginUser($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                return true;
            }
        }
        return false;
    }

    public function logoutUser() {
        session_destroy();
    }
}
