<?php
require_once('config.php');
session_start();

class LoginManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login($username, $password) {
      $sql = "SELECT * FROM admin_accounts WHERE username = ?";
      $stmt = $this->conn->prepare($sql);
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();
  
      if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $admin_password = $row['password'];
  
          if ($password === $admin_password) {
              $_SESSION['admin_log'] = $username;
              $stmt->close(); 
              return true;
          } else {
              $stmt->close(); 
              return "Invalid username or password";
          }
      } else {
          $stmt->close(); 
          return "Admin not found";
      }
  }
  
  
}

$loginManager = new LoginManager($conn);

if (isset($_SESSION['admin_log'])) { 
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $loginResult = $loginManager->login($username, $password);

    if ($loginResult === true) {
        header("Location: index.php");
        exit;
    } else {
        $login_error = $loginResult;
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
       *,*:before,*:after{box-sizing:border-box}

body{
    margin: 0;
    padding: 0;
  min-height:100vh;
}


    @font-face {
        font-family: 'oswald';
        src: url(/PHP-Projects/E-Commerce-BeatStrum-OOP/src/1-Oswald-Font/Oswald-VariableFont_wght.ttf);
      }
      * {
       
        font-family: 'oswald';
      }

.container{
  position:absolute;
  width:100%;
  height:100%;
  overflow:hidden;
  transition: background-image 0.5s ease, opacity 0.5s ease;
  &:hover,&{
    background-image: url('/PHP-Projects/E-Commerce-BeatStrum-OOP/images/svg/logo-black.svg');
  background-position: center;
  background-size: cover;
  background-attachment: fixed;
  }

  &:hover,&:active{
    background-image: none;
    transition: background-image 0.5s ease, opacity 0.5s ease;
  transition-delay: 0.2s;
    .top, .bottom{
      &:before, &:after{
        margin-left: 200px;
        transform-origin: -200px 50%;
        transition-delay:0s;
      }
    }
    
    .center{
      opacity:1;
      transition-delay:0.2s;
    }
  }
}

.top, .bottom{
  &:before, &:after{
    content:'';
    display:block;
    position:absolute;
    width:200vmax;
    height:200vmax;
    top:50%;left:50%;
    margin-top:-100vmax;
    transform-origin: 0 50%;
    transition:all 0.5s cubic-bezier(0.445, 0.05, 0, 1);
    z-index:10;
    opacity:0.65;
    transition-delay:0.2s;
  }
}

.top{
  &:before{transform:rotate(45deg);background:#0D3580;}
  &:after{transform:rotate(135deg);background:#222222;}
}

.bottom{
  &:before{transform:rotate(-45deg);background:#222222;}
  &:after{transform:rotate(-135deg);background:#d43132;}
}

.center{
  position:absolute;
  width:400px;
  height:400px;
  top:50%;left:50%;
  margin-left:-200px;
  margin-top:-200px;
  display:flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding:30px;
  opacity:0;
  transition:all 0.5s cubic-bezier(0.445, 0.05, 0, 1);
  transition-delay:0s;
  color:#333;
  
  input{
    width:90%;
    padding:15px;
    margin:5px;
    border-radius:1px;
    border:1px solid #ccc;
    font-family:inherit;
  }
}     


    </style>
</head>
<body>
 <?php if(isset($login_error)) echo "<p>$login_error</p>"; ?>
    <form action="" method="post" class="container">
        <div class="top">
         </div>
        <div class="bottom">
             </div>
        <div class="center">
        <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="email" placeholder="email" required><br>
       
        <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="password" placeholder="password" required><br><br>
      
            <input type="submit" value="Login">
        </div>
    </form>
</body>

</html>
