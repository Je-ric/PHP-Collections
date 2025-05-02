<?php
session_start();
include('config.php');

class Authentication {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function loginUser($username, $password) {
        $myusername = mysqli_real_escape_string($this->conn, $username);
        $mypassword = mysqli_real_escape_string($this->conn, $password);

        $sql = "SELECT id, name, age, phone, address FROM client_accounts WHERE username = '$myusername' AND password = '$mypassword'";
        $result = mysqli_query($this->conn, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);

        if ($count == 1) {
            $_SESSION['client_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['age'] = $row['age'];
            $_SESSION['phone'] = $row['phone'];
            $_SESSION['address'] = $row['address'];
            header("location: index.php");
            exit();
        } else {
            return "Your Login Name or Password is invalid";
        }
    }
}

if (isset($_SESSION['client_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $auth = new Authentication($conn);
    $error = $auth->loginUser($_POST['username'], $_POST['password']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Item</title>
    <style>
        <?php include('C:\xampp\htdocs\PHP-Projects\E-Commerce-BeatStrum-OOP\css\login_client.css'); ?>
    </style>
</head>
<body>      
    <?php include ('1.header.php'); ?>

    <div class="form-container">
    <h1>LOGIN</h1> 
    <form method="post" action="">
        <div class="form-inside">
            <div class="text-box">
                <label class="labels" for="username">Username</label>
                <input class="input-field" type="text" id="username"  placeholder="Username" name="username" required>
            </div>
            <div class="text-box">
                <label class="labels" for="password">Password</label>
                <input class="input-field" type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="text-box">
                <input class="login-button" type="submit" value="Login">
            </div>
            <div class="text-box">
                <p>Don't have an account? <a href="register.php">Register here</a>.</p>
            </div>
        </div>
    </form>
</div>

</body>
</html>
