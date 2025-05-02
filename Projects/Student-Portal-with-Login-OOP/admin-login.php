<?php
include('session_config.php');
session_start();

if (isset($_SESSION['admin'])) {
    header("Location: admin-R.php");
    exit;
}
class AdminLogin {
    private $conn;
    private $login_error;

    public function __construct($host, $username, $password, $database) {
        $this->conn = new mysqli($host, $username, $password, $database);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function login($username, $password) {
        if (isset($_SESSION['admin'])) {
            header("Location: admin-R.php");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $sql = "SELECT * FROM admin WHERE username = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $admin_username = $row['username'];
                $admin_password = $row['password'];

                if ($username === $admin_username && $password === $admin_password) {
                    $_SESSION['admin'] = $username;
                    header("Location: admin-R.php");
                    exit;
                } else {
                    $this->login_error = "Invalid username or password";
                }
            } else {
                $this->login_error = "Admin not found";
            }

            $stmt->close();
        }
    }

    public function getLoginError() {
        return $this->login_error;
    }

    public function closeConnection() {
        $this->conn->close();
    }
}

// Usage:
$host = "localhost:3307"; 
$username = "root"; 
$password = ""; 
$database = "studentDB";

$adminLogin = new AdminLogin($host, $username, $password, $database);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminLogin->login($_POST['username'], $_POST['password']);
}

$login_error = $adminLogin->getLoginError();

$adminLogin->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include 'src/style.css'; ?>
    </style>
    <title>Admin Login</title>
</head>
<body>
<div class="container">
    <div class="image-container">
    <img src="/PHP-Projects/Student-Portal-with-Login/src/LoginSide.jpeg" alt="">
    <div class="overlay-text-1"><br><br></div>
        <div class="overlay-text-2"><br><br></div>
    </div>
    <div class="form-container">
    <h2>Administrator Dashboard</h2>
    <p class="prompt-blank"><span>Welcome Back</span>, Admin</p>
    <form action="" method="post">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" value="Login">
    </form>
    <h2><?php echo isset($login_error) ? $login_error : ""; ?></h2>
    </div>
</div>
</body>
</html>
