<?php
session_start();
include('config.php');

if (isset($_SESSION['client_id'])) { 
    header("Location: index.php");
    exit();
}

$error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $check_query = "SELECT id FROM client_accounts WHERE username = '$username'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $error = "Username already exists";
    } else {
        
        $insert_query = "INSERT INTO client_accounts (username, password, name, age, phone, address) VALUES ('$username', '$password', '$name', '$age', '$phone', '$address')";
        if (mysqli_query($conn, $insert_query)) {
            
            header("Location: login.php");
            exit();
        } else {
            $error = "Registration failed";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
           <?php include('C:\xampp\htdocs\PHP-Projects\E-Commerce-BeatStrum\css\register_client.css'); ?>
   </style>
</head>
<body>
<?php 
    include ('1.header.php');
?> 
<div class="form-container">
    <h1>REGISTER</h1>  
    <form method="post" action="">
        <div class="form-inside">
        <div class="right-left">
    <div class="right">
        <div class="text-box">
            <label for="username">Username</label>
            <input class="input-field" type="text" id="username"  placeholder="Username" name="username" required>
        </div>
        <div class="text-box">
            <label for="password">Password</label>
            <input class="input-field" type="password" id="password" name="password" placeholder="Password" required>
        </div>
        <div class="text-box">
            <label for="name">Name</label>
            <input class="input-field" type="text" id="name" name="name" placeholder="Name" required>
        </div>
    </div>

    <div class="left">
        <div class="text-box">
            <label for="age">Age</label>
            <input class="input-field" type="number" id="age" name="age" placeholder="Age" required>
        </div>
        <div class="text-box">
            <label for="phone">Phone</label>
            <input class="input-field" type="tel" id="phone" name="phone" placeholder="Phone" required>
        </div>
        <div class="text-box">
            <label for="address">Address</label>
            <input class="input-field" type="text" id="address" name="address" placeholder="Address" required>
        </div>
    </div>
</div>

            <div class="text-box">
                <input class="login-button" type="submit" value="Register">
            </div>
            <?php if (!empty($error)) { ?>
                <p><?php echo $error; ?></p>
            <?php } ?>
            <div class="text-box">
            <p>Already have an account? <a href="login_client.php">Sign in here</a>.</p>
            </div>
        </div>
    </form>
</div>

</body>
</html>
