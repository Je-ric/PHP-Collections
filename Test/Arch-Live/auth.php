<?php
include 'db_conn.php'; 

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        registerUser();
    } elseif (isset($_POST['login'])) {
        loginUser();
    }
} elseif (isset($_GET['logout'])) {
    logoutUser();
}

function registerUser() {
    global $conn;

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $checkQuery = "SELECT id FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Username or email already taken!";
        return;
    }

    $query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    if ($stmt->execute()) {
        echo "Registration successful! You can now login.";
    } else {
        echo "Error: " . $stmt->error;
    }
}

function loginUser() {
    global $conn;

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT id, username, password, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashedPassword, $role);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashedPassword)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        $updateQuery = "UPDATE users SET last_active = NOW() WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("i", $id);
        $updateStmt->execute();

        if ($role === 'administrator') {
            header("Location: index.php");
        } elseif ($role === 'adviser') {
            header("Location: adviser_dashboard.php");
        } elseif ($role === 'researcher') {
            header("Location: index.php");
        }
        exit;
    } else {
        echo "Invalid username or password!";
    }
}

function logoutUser() {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
