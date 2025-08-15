<?php
// File: User.php
// Description: A class to handle user registration and login.

require_once 'Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Register a new user
    public function registerUser($username, $password) {
        // Check if username already exists
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);
        $row = $this->db->single();
        if ($row) {
            return false; // Username already exists
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $this->db->query('INSERT INTO users (username, password) VALUES (:username, :password)');
        $this->db->bind(':username', $username);
        $this->db->bind(':password', $hashed_password);

        return $this->db->execute();
    }

    // Log in a user
    public function loginUser($username, $password) {
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);
        $row = $this->db->single();

        if (!$row) {
            return false; // User not found
        }

        // Verify password
        if (password_verify($password, $row['password'])) {
            return $row;
        } else {
            return false; // Incorrect password
        }
    }
}
?>