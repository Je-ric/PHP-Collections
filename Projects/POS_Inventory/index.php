<?php
session_start();

$host = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "POS_Inventory";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';

function checkSession() {
    if (isset($_SESSION['admin_log'])) {
        header('location: modules/reports/index.php');
        exit();
    } elseif (isset($_SESSION['log_emp'])) {
        header('location: modules/pos/pos.php');
        exit();
    } elseif (isset($_SESSION['log_team_leader'])) {
        header('location: team_leader_dashboard.php');
        exit();
    }
}

function loginUser($conn, $username, $password) {
    $sql = "SELECT * FROM Users WHERE username=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log('Prepare failed: ' . htmlspecialchars($conn->error));
        return "Login failed. Please try again.";
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if ($row['status'] === 'inactive') {
            return '<span style="color: rgb(255, 109, 109);">Your account is inactive. Please contact admin.</span>';
        }

        if (password_verify($password, $row['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];

            if ($row['role'] == 'Admin') {
                $_SESSION['admin_log'] = true;
                header('location: modules/reports/index.php');
            } elseif ($row['role'] == 'Employee') {
                $_SESSION['log_emp'] = true;
                header('location: modules/pos/pos.php');
            } elseif ($row['role'] == 'Team Leader') {
                $_SESSION['log_team_leader'] = true;
                header('location: team_leader_dashboard.php');
            }
            exit();
        } else {
            return '<span style="color: rgb(255, 109, 109);">Password or username incorrect</span>';
        }
    } else {
        return '<span style="color: rgb(255, 109, 109);">Password or username incorrect</span>';
    }
}

function signupUser($conn, $username, $password) {
    $sql = "SELECT * FROM Users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Username already exists.";
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    $sql = "INSERT INTO Users (username, password, role, status) VALUES (?, ?, 'employee', 'active')";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log('Prepare failed: ' . htmlspecialchars($conn->error));
        return "Error creating user.";
    }

    $stmt->bind_param("ss", $username, $hashedPassword);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User created successfully";
        header('location: index.php'); 
        exit();
    } else {
        return "Error creating user: " . htmlspecialchars($stmt->error);
    }
}

checkSession();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $error = loginUser($conn, $username, $password);
}

if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $error = signupUser($conn, $username, $password);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Grail Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #F7F7F7;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 1000px;
            background: #F0F0F0;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            display: flex;
            overflow: hidden;
            margin-right:10px;
            margin-left:10px;
        }

        .form-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
        }
         .title-header {
            color: black;
            text-align: center;
            font-size: 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .title-infos{
            color: #222;
            text-align: center;
            font-size: 10px;
            font-weight: 50;
            letter-spacing: 1px;
        }

        .title-info{
            color: #222;
            text-align: center;
            font-size: 10px;
            font-weight: 50;
            margin-bottom: 35px;
            letter-spacing: 1px;
        }

        .tag-line p{
            font-weight: 500;
            color: #333;
            margin-bottom: 20px;
            font-size: 15px;
            text-align: center;
        }
        .label-username-password{
            color: #333;
            padding-bottom:5px;
            font-weight: 600;
            font-size: 15px;
        }
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        .input-group i {
            position: absolute;
            left: 15px; 
            top: 69%;
            transform: translateY(-50%);
            color: black;
        }
        .input-field {
            padding: 10px 15px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 12px;
            width: 100%;
        }
        .password-toggle {
            cursor: pointer;
            color: #999;
        }
        .button-container{
            display: flex;
            justify-content: center;
        }

        
        .login-button {
            padding: 8px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            width: 65%;
            cursor: pointer;
            transition: background-color 0.3s;

        }
        .login-button:hover {
            background-color: #999;
        }
        .error {
            color: rgb(255, 109, 109);
            text-align: center;
            margin-top: 10px;
        }
        .toggle-link {
            cursor: pointer;
            color: black;
            transition: color 0.3s;
        }
        .toggle-link:hover {
            color: #A6AEBF;
        }
        .UG-image { 
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            padding: 20px;
        }
        .UG-image img { 
            width: 100%;
            height: 450px;
            max-width: 500px;
            border-radius: 2px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .UG-image { 
                display: none;
            }
        }

        .form-check{
            padding-bottom: 20px;
            display: flex;
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
            color: #000;
        }

        .forgot-password-link{
           padding-left: 175px;
        }
        .form-check-label{
            padding-left: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Login Form Section -->
    <div class="form-section" id="login-section">
        <div class="title-header">Urban Grail Management System</div>
        
        <!-- <h3>Login</h3> -->
         <div class="tag-line"> 
            <p class="title-info" >Fill out the information below in order to have access.</p></div>
        <form action="index.php" method="POST">

            <div class="input-group">
                <i class="fas fa-user"></i>
                <label for="username" class="label-username-password">Username or Email</label>
                <input class="input-field" type="text" name="username" placeholder="Username"  required>
            </div>
            <div class="input-group">
                <label for="password" class="label-username-password">Password</label>
                <input class="input-field" type="password" name="password" id="login-password" placeholder="Password" required>
                <i class="fas fa-eye password-toggle" id="toggle-password-login" onclick="togglePassword('login-password', 'toggle-password-login')"></i>
            </div>
             <!-- Remember and Forgot pass Checkbox -->
             <!-- <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me" <?= isset($_COOKIE['username']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="remember_me">Remember Me</label>
                <div>
                     <a href="" class="forgot-password-link">Forgot Password?</a>
                </div>
            </div> -->
            <div class="button-container" >
            <button class="login-button" type="submit" name="login">LOG IN</button>
            <!-- <p class="text-center mt-3">
                <span class="toggle-link" id="show-signup">Sign up</span>
            </p>  -->
            </div>
        </form>
        <?php if ($error): ?>
            <span class="error"><?= $error ?></span>
        <?php endif; ?>

        
    </div>

    <!-- Signup Form Section 
    <div class="form-section" id="signup-section" style="display: none;">
        <div class="title-header">Urban Grail Management System</div>
        <h3>Sign Up</h3>
        <form action="index.php" method="POST">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input class="input-field" type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <input class="input-field" type="password" name="password" id="signup-password" placeholder="Password" required>
                <i class="fas fa-eye password-toggle" id="toggle-password-signup" onclick="togglePassword('signup-password', 'toggle-password-signup')"></i>
            </div>
            <button class="login-button" type="submit" name="signup">Sign Up</button>
            <p class="text-center mt-3">
                <span class="toggle-link" id="show-login">Already have an account? <span style="text-decoration: underline;">Log in</span></span>
            </p>
        </form>
        <?php if ($error): ?>
            <span class="error"><?= $error ?></span>
        <?php endif; ?>
    </div>-->

    <!-- UG Image Section -->
    <div class="UG-image d-none d-md-flex">
        <img src="assets/src/images/Logo.jpg" alt="UG Image">
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    
    function togglePassword(inputId, toggleId) {
        const inputField = document.getElementById(inputId);
        const toggleIcon = document.getElementById(toggleId);
        if (inputField.type === "password") {
            inputField.type = "text";
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            inputField.type = "password";
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>
</body>
</html>
