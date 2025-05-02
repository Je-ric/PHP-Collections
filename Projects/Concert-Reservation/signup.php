<?php 
include('user/config.php');
$error = '';  
$success_message = '';

if(isset($_POST['signup'])){
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE username='$username'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);
  if ($row) {
    $error = '<span style="color: rgb(255, 109, 109);">Username already exists</span>';
  } else {
    $sql = "INSERT INTO users(username, email, password) VALUES('$username', '$email', '$password')";
    mysqli_query($conn, $sql);
    $success_message = '<script>alert("Signup successful!");</script>';
  }
}
?>

<!DOCTYPE html>
<html>

  <head>
      <title>Sign up</title>
      <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
      <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="css/log-sign.css">
  </head>

  <body>
    
  <div class="form-size">
    <div class="form-pos">
      <div class="form-img">
        <a href="index.php">
        <img src="images/BG1 W LOGO.png" alt="">
        </a>
      </div>

      <div class="form-form">
        <h3>Sign in</h3>
        <form action="signup.php" method="POST">
          <input class="input1" type="text" name="username" placeholder="username" required><br>
          <input class="input1" type="text" name="email" placeholder="email" required><br>
          <input class="input1" type="password" name="password" placeholder="password" required><br>
          <?php echo $error; ?> <br>

          <input class="log-button" type="submit" name="signup" value="Sign in" onclick="return confirm('Confirm submit?')">
          <br><br>

          <a class="sign-in-a" href="login.php">Log in</a>
          </form>
          <?php echo $success_message; ?>
      </div>
    </div>
  </div>

  </body>

</html>

