<?php
// include('session_config.php');
// session_start();
// include('config.php');

// if (isset($_SESSION['admin'])) {
//     header("Location: admin-R.php");
//     exit;
// }

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $username = $_POST['username'];
//     $password = $_POST['password'];
    
//     $admin_username = "admin";
//     $admin_password = "adminpassword";

//     if ($username === $admin_username && $password === $admin_password) {
//         $_SESSION['admin'] = $username;
//         header("Location: admin-R.php");
//         exit;
//     } else {
//         $login_error = "Invalid username or password";
//     }
// }
?>

<?php
include('session_config.php');
session_start();
include('config.php');

if (isset($_SESSION['admin'])) {
    header("Location: admin-R.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM admin WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $admin_username = $row['username'];
        $admin_password = $row['password'];

        if ($username === $admin_username && $password === $admin_password) {
            $_SESSION['admin'] = $username;
            header("Location: admin-R.php");
            exit;
        } else {
            $login_error = "Invalid username or password";
        }
    } else {
        $login_error = "Admin not found";
    }

    $conn->close();
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
    <title>Admin Login</title>
</head>
<body>
<div class="container">
    <div class="image-container">
    <img src="/PHP-Projects/Student-Portal-with-Login/src/LoginSide.jpeg" alt="">
    <div class="overlay-text-1">C.R.U.D<br>with<br>LOGIN</div>
        <div class="overlay-text-2">Jeric J. Dela Cruz <br> BSIT_2-2 <br> INTECH 2201 </div>
    </div>
    <div class="form-container">
    <h2>Administrator Dashboard</h2>
    <p class="prompt-blank"><span>Welcome Back</span>, Admin</p>
    <form action="" method="post">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" value="Login">
    </form>
    <h2></h2>
    <?php if(isset($login_error)) echo "<p>$login_error</p>"; ?>
    </div>
</div>
</body>
</html>
