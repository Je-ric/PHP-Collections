<?php
session_start();
include 'config.php';

if (isset($_SESSION['user'])) {
    header("Location: modules/accounts_roles/account_approval.php");
    exit();
}

// Function to sanitize input
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Registration
if (isset($_POST['register'])) {
    $name = clean_input($_POST['name']);
    $email = clean_input($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $_SESSION['auth_message'] = "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, status) VALUES (?, ?, ?, 'pending')");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $_SESSION['auth_message'] = "Registration successful! Please wait for admin approval.";
        } else {
            $_SESSION['auth_message'] = "Error: " . $conn->error;
        }
    }
    header("Location: auth.php");
    exit();
}

// Login
if (isset($_POST['login'])) {
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password, status FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $hashed_password, $status);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            if ($status == 'approved') {
                $role_query = "SELECT r.role_name FROM user_roles ur 
                            JOIN roles r ON ur.role_id = r.id
                            WHERE ur.user_id = ?";
                $role_stmt = $conn->prepare($role_query);
                $role_stmt->bind_param("i", $id);
                $role_stmt->execute();
                $role_stmt->store_result();
                $role_stmt->bind_result($role_name);
                
                if ($role_stmt->num_rows > 0) {
                    $role_stmt->fetch();
                    
                    $_SESSION['user'] = [
                        'id' => $id,
                        'name' => $name,
                        'role' => $role_name
                    ];

                    header("Location: modules/accounts_roles/account_approval.php");
                    exit();
                } else {
                    $_SESSION['auth_message'] = "Role not found.";
                }
            } else {
                $_SESSION['auth_message'] = "Your account is pending approval.";
            }
        } else {
            $_SESSION['auth_message'] = "Incorrect password.";
        }
    } else {
        $_SESSION['auth_message'] = "User not found.";
    }

    header("Location: auth.php");
    exit();
}
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 text-center">User Authentication</h2>

        <?php if (isset($_SESSION['auth_message'])): ?>
            <p class="text-red-600 text-center mb-4"><?= $_SESSION['auth_message'] ?></p>
            <?php unset($_SESSION['auth_message']); ?>
        <?php endif; ?>

        <div class="flex justify-center mb-4">
            <button onclick="showForm('register')" class="mx-2 px-4 py-2 border rounded bg-blue-500 text-white">Register</button>
            <button onclick="showForm('login')" class="mx-2 px-4 py-2 border rounded bg-gray-500 text-white">Login</button>
        </div>

        <!-- Registration Form -->
        <form id="register-form" method="post" class="hidden">
            <input type="text" name="name" placeholder="Full Name" required class="w-full px-3 py-2 border rounded mb-2">
            <input type="email" name="email" placeholder="Email" required class="w-full px-3 py-2 border rounded mb-2">
            <input type="password" name="password" placeholder="Password" required class="w-full px-3 py-2 border rounded mb-4">
            <button type="submit" name="register" class="w-full bg-blue-500 text-white py-2 rounded">Register</button>
        </form>

        <!-- Login Form -->
        <form id="login-form" method="post">
            <input type="email" name="email" placeholder="Email" required class="w-full px-3 py-2 border rounded mb-2">
            <input type="password" name="password" placeholder="Password" required class="w-full px-3 py-2 border rounded mb-4">
            <button type="submit" name="login" class="w-full bg-gray-500 text-white py-2 rounded">Login</button>
        </form>
    </div>

    <script>
        function showForm(form) {
            document.getElementById('register-form').classList.add('hidden');
            document.getElementById('login-form').classList.add('hidden');
            document.getElementById(form + '-form').classList.remove('hidden');
        }
    </script>

</body>
</html>
