<?php
require_once('session_config.php');
include "config.php";

session_start();

if (isset($_SESSION['person'])) { // palitan mo nalang yung session variable at location.
    header("Location: read.php");
    exit;
}

//ilagay mo itong session start at !isset sa lahat ng iba mong file
// session_start();                           

// if (!isset($_SESSION['person'])) {
//     header("Location: index.php");
//     exit;
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user_username = "admin";
    $user_password = "admin";

    if ($username === $user_username && $password === $user_password) {
        $_SESSION['person'] = $username; 
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
        <p><span>Welcome back!</span> Let's get started! Login to continue accessing our services. Please enter your <span>username</span> and <span>password  </span>below.</p>
        <form action="" method="post">
          <div class="text-box">
            <input class="input-field" type="text" placeholder="Username" name="username" required><br>
          </div>
          <div class="text-box">
            <input class="input-field" type="password" placeholder="Password" name="password" required><br>
          </div>
          <input class="css-button-shadow-border-sliding--sky" type="submit" value="Login">
          <h2></h2>
          <!-- <p class="register">Don't have an account? <a href="register-form.php">Register here</a>.</p> -->
        </form>
        <?php if(isset($login_error)) echo "<p>$login_error</p>"; ?>       
      </div>
    </div>
    <!-- <div class="htmlmenu-container">
      <div class="htmlmenu-logo"></div>
      <ul>
        <li><a href="#">New</a></li>
        <li><a href="#">New</a></li>
        <li><a href="#">New</a></li>
      </ul>
    </div>-->
  </div> 

</body>
</html>
