<?php
require_once('session_config.php');
require_once('config.php');

session_start();

if (isset($_SESSION['user'])) {
    header("Location: read.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user_username = "username";
    $user_password = "username";

    if ($username === $user_username && $password === $user_password) {
        $_SESSION['user'] = $username;
        header("Location: read.php");
        exit;
    } else {
        
        $login_error = "Invalid username or password";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        <?php include 'src/login.css'; ?>
    </style>
</head>
<body>
  <div class="main-login">
    <div class="side-login">
      <div class="form-container">
        <h1>LOGIN</h1>  
        <br><br>
        <form action="" method="post">
          <div class="text-box">
            <input class="input-field" type="text" placeholder="Username" name="username" required><br>
          </div>
          <div class="text-box">
            <input class="input-field" type="password" placeholder="Password" name="password" required><br>
          </div>
          <input class="css-button-shadow-border-sliding--sky" type="submit" value="Login">
          <br><br>
          <!-- <p class="register">Don't have an account? <a href="register-form.php">Register here</a>.</p> -->
        </form>
        <?php if(isset($login_error)) echo "<p>$login_error</p>"; ?>
      </div>
    </div>
    </div>
    <!-- <div class="htmlmenu-container">
      <div class="htmlmenu-logo"></div>
      <ul>
        <li><a href="#">New</a></li>
        <li><a href="#">New</a></li>
        <li><a href="#">New</a></li>
      </ul>
    </div>
  </div> -->
</body>
</html>
