<?php
include('session_config.php');
require_once('config.php');

class UserRegistration {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function registerUser($student_id, $password) {
        session_start();
        $register_error = "";

        $check_query = "SELECT * FROM login WHERE student_id='$student_id'";
        $check_result = $this->conn->query($check_query);
        if ($check_result && $check_result->num_rows > 0) {
            $register_error = "User with this Student ID is already registered";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $insert_login_query = "INSERT INTO login (student_id, password) VALUES ('$student_id', '$password_hash')";
            if ($this->conn->query($insert_login_query) === TRUE) {
                session_start();
                $_SESSION['user'] = $student_id;
                header("Location: index.php");
                exit;
            } else {
                $register_error = "Registration failed";
            }
        }

        return $register_error;
    }
}

$userRegistration = new UserRegistration($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $student_id = $_POST['student_id'];
        $password = $_POST['password'];

        $register_error = $userRegistration->registerUser($student_id, $password);
    }
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
    <title>User Registration</title>
</head>
<body>
<div class="container">
    <div class="image-container">
    <img src="/PHP-Projects/Student-Portal-with-Login/src/LoginSide.jpeg" alt="">
    <div class="overlay-text-1"><br><br></div>
        <div class="overlay-text-2"><br><br></div>
    </div>
    <div class="form-container">
        <h2>Register</h2>
        <p class="prompt-blank">Don't have an account? <span>Create your account</span>, it takes less than a minute.</p>
        <form action="" method="post">
            <input type="text" name="student_id" placeholder="Student ID" pattern="[0-9]+" title="Please enter only numbers" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="checkbox" id="agree-checkbox" required>
            <label>I agree to the terms and conditions</label><br>
            <input type="submit" name="register" value="Register">
            <p class="prompt-center">Already have an Account? <a href="index.php">Login here</a>.</p> 
            <p class="prompt-center"> Ready to apply?<a href="application-form.php"> Begin your application process here.</a></p>
            <h3></h3>
            <?php if(isset($register_error)) echo "<p>{$register_error}</p>"; ?>
        </form>
    </div>
</div>
</body>
</html>
