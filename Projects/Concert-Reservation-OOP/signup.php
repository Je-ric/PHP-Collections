<?php

include('user/config.php');

class SignupManager
{
    private $conn;
    private $error;
    private $successMessage;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->error = '';
        $this->successMessage = '';
    }

    public function signup()
    {
        if (isset($_POST['signup'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $sql = "SELECT * FROM users WHERE username='$username'";
            $result = mysqli_query($this->conn, $sql);
            $row = mysqli_fetch_array($result);
            if ($row) {
                $this->error = '<span style="color: rgb(255, 109, 109);">Username already exists</span>';
            } else {
                $sql = "INSERT INTO users(username, email, password) VALUES('$username', '$email', '$password')";
                mysqli_query($this->conn, $sql);
                $this->successMessage = '<script>alert("Signup successful!");</script>';
            }
        }
    }

    public function getError()
    {
        return $this->error;
    }

    public function getSuccessMessage()
    {
        return $this->successMessage;
    }
}

$signupManager = new SignupManager($conn);
$signupManager->signup();
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
                    <?php echo $signupManager->getError(); ?> <br>

                    <input class="log-button" type="submit" name="signup" value="Sign in"
                        onclick="return confirm('Confirm submit?')">
                    <br><br>

                    <a class="sign-in-a" href="login.php">Log in</a>
                </form>
                <?php echo $signupManager->getSuccessMessage(); ?>
            </div>
        </div>
    </div>

</body>

</html>
