<?php
include('session_config.php');
require_once('config.php');
session_start();

if (isset($_SESSION['user'])) {
    header("Location: student-records.php");
    exit;
}
class UserLogin {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function loginUser($student_id, $password) {
        $login_error = "";

        $query = "SELECT * FROM login WHERE student_id='$student_id'";
        $result = $this->conn->query($query);

        if ($result) {
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                
                if (password_verify($password, $row['password'])) {
                    session_start();
                    $_SESSION['user'] = $student_id;
                    header("Location: student-records.php");
                    exit;
                } else {
                    $login_error = "Invalid password"; 
                }
            } else {
                $login_error = "You need to register your account first"; 
            }
        } else {
            $login_error = "Database query failed: " . $this->conn->error; 
        }

        return $login_error;
    }
}

$userLogin = new UserLogin($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];

    $login_error = $userLogin->loginUser($student_id, $password);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include 'src/style.css'; ?>
    </style>
    <title>User Login</title>
</head>
<body>
<div class="container">
    <div class="image-container">
    <img src="/PHP-Projects/Student-Portal-with-Login/src/LoginSide.jpeg" alt="">
    <div class="overlay-text-1"><br><br></div>
    <div class="overlay-text-2"><br><br></div>
    </div>
    <div class="form-container">
        <h2>Login to Your Account</h2>
        <p class="prompt-blank"><span>Welcome back!</span> Log in to access your account.</p>
        <form action="" method="post">
        <input type="text" placeholder="Student ID" name="student_id" pattern="[0-9]+" title="Please enter only numbers" required><br>
            <input type="password" placeholder="Password" name="password" required><br>
            <input type="submit" value="Login">
            <p class="prompt-center">Don't have an account? <a href="user-registration.php">Register here</a>.</p>
            
            <p class="prompt-center"> Ready to apply?<a href="application-form.php"> Begin your application process here.</a></p>
            <h3></h3>
        </form>
        <?php if(isset($login_error)) echo "<p>$login_error</p>"; ?>
    </div>
</div>
</body>
</html>
