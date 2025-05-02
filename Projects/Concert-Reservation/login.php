<?php
session_start();

class LoginSystem
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function checkSession()
    {
        if (isset($_SESSION['log-customer'])) {
            header('location: user/index.php');
            exit();
        } elseif (isset($_SESSION['log-admin'])) {
            header('location: admin/index.php');
            exit();
        }
    }

    public function loginUser($username, $password)
    {
        $sql = "SELECT * FROM users WHERE username=? AND password=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['userType'] == 'customer') {
                $_SESSION['log-customer'] = true;
                $_SESSION['user_id'] = $row['userID'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                header('location: user/index.php');
                exit();
            } elseif ($row['userType'] == 'admin') {
                $_SESSION['log-admin'] = true;
                $_SESSION['email'] = $row['email'];
                header('location:admin/index.php');
                exit();
            }
        } else {
            return '<span style="color: rgb(255, 109, 109);">Password or username incorrect</span>';
        }
    }
}

include('user/config.php');

$loginSystem = new LoginSystem($conn);
$loginSystem->checkSession();

$error = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $error = $loginSystem->loginUser($username, $password);
}

?>

<!DOCTYPE html>
<html>

  <head>
      <title>Log in</title>
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
        <h3>Log in</h3>
        <form action="login.php" method="POST">
        <input class="input1" type="text" name="username" placeholder="username" required><br>
        <input class="input1" type="password" name="password" placeholder="password" required><br>
        <?php echo $error; ?> <br>
                    
        <input class="log-button" type="submit" name="login" value="Log in"> <br> <br>
        <a class="sign-in-a" href="signup.php">Sign up</a>
        </form>
      </div>
    </div>
  </div>

  </body>

</html>
