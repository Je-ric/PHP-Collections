<?php
session_start();
require 'vendor/autoload.php';
require 'config.php';


$facebook_app_id = $_ENV['FACEBOOK_APP_ID'];
$facebook_redirect_uri = $_ENV['FACEBOOK_REDIRECT_URI'];
$facebook_auth_url = "https://www.facebook.com/v18.0/dialog/oauth?client_id={$facebook_app_id}&redirect_uri={$facebook_redirect_uri}&scope=email,public_profile";


$client = new Google_Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
$client->addScope("email");
$client->addScope("profile");

// If user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Handle manual login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (!empty($user['password']) && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['picture'] = $user['profile_pic'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "User not found.";
    }
}

// Handle manual registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $check_query = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email already exists!";
        } else {
            $insert = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert);
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            $stmt->execute();

            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            header("Location: index.php");
            exit();
        }
    }
}

// Handle Google login
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
        die('Error fetching access token');
    }

    $client->setAccessToken($token);
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account = $google_oauth->userinfo->get();

    $google_id = $google_account->id;
    $name = $google_account->name;
    $email = $google_account->email;
    $profile_pic = $google_account->picture;

    // Check if user exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Update Google ID if logging in for the first time via Google
        if (empty($user['google_id'])) {
            $update = "UPDATE users SET google_id = ?, profile_pic = ? WHERE email = ?";
            $stmt = $conn->prepare($update);
            $stmt->bind_param("sss", $google_id, $profile_pic, $email);
            $stmt->execute();
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['picture'] = $user['profile_pic'];
    } else {
        $insert = "INSERT INTO users (google_id, name, email, profile_pic) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert);
        $stmt->bind_param("ssss", $google_id, $name, $email, $profile_pic);
        $stmt->execute();
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['picture'] = $profile_pic;
    }

    header("Location: index.php");
    exit();
}

$login_url = $client->createAuthUrl();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.10/dist/full.css" rel="stylesheet">
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">

<a href="index.php" class="fixed top-4 left-4 rounded-full shadow-md">
    <img src="assets/images/logo/YI_Logo.png" alt="Home" class="w-12 h-12 object-cover rounded-full">
</a>



    <div class="flex bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-7xl">

    <div class="hidden md:flex md:w-1/2 justify-center items-center">
        <img src="assets/images/logo/YI_Logo.png" alt="Login Image" class="w-90 h-90 object-contain">
    </div>


        <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
            <h2 class="text-2xl font-bold text-center text-black">YOUMANITARIAN INTERNATIONAL</h2>
            <p class="text-center text-gray-600">Fill out the information below to continue.</p>

            <?php if (isset($error)): ?>
                <div class="mb-4 p-3 text-red-600 bg-red-200 rounded"><?= $error; ?></div>
            <?php endif; ?>

            <div id="login-form">
                <form method="post" class="space-y-4">
                    <div class="form-control">
                        <label for="email" class="label"><span class="label-text">Email Address</span></label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required class="input input-bordered w-full">
                    </div>
                    <div class="form-control">
                        <label for="password" class="label"><span class="label-text">Password</span></label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required class="input input-bordered w-full">
                    </div>
                    <button type="submit" name="login" class="btn btn-neutral w-full text-white">LOGIN</button>
                </form>

                <p class="mt-4 text-center">Don't have an account? 
                    <button onclick="toggleForms()" class="text-blue-500 hover:underline">Register</button>
                </p>
            </div>

            <div id="register-form" class="hidden">
                <form method="post" class="space-y-2">
                    <div class="form-control">
                        <label for="name" class="label"><span class="label-text">Full Name</span></label>
                        <input type="text" name="name" placeholder="Full Name" required class="input input-bordered w-full">
                    </div>
                    <div class="form-control">
                        <label for="email" class="label"><span class="label-text">Email Address</span></label>
                        <input type="email" name="email" placeholder="Email" required class="input input-bordered w-full">
                    </div>
                    <div class="flex gap-4">
                        <div class="form-control w-1/2">
                            <label for="password" class="label"><span class="label-text">Password</span></label>
                            <input type="password" name="password" placeholder="Password" required class="input input-bordered w-full">
                        </div>
                        <div class="form-control w-1/2">
                            <label for="confirm_password" class="label"><span class="label-text">Confirm Password</span></label>
                            <input type="password" name="confirm_password" placeholder="Confirm Password" required class="input input-bordered w-full">
                        </div>
                    </div>
                    <button type="submit" name="register" class="btn btn-neutral w-full text-white">REGISTER</button>
                </form>

                <p class="mt-4 text-center">Already have an account? 
                    <button onclick="toggleForms()" class="text-blue-500 hover:underline">Login</button>
                </p>
            </div>

            <div class="divider my-6">OR</div>

            <div class="flex flex-col md:flex-row gap-4 items-center justify-center">
                <a href="<?= htmlspecialchars($login_url) ?>" class="btn btn-outline flex items-center gap-2 px-4 py-2 rounded-lg w-full md:w-auto">
                    <img src="assets/images/icons/google-100.png" alt="Google Logo" class="w-6 h-6">
                    Sign in with Google
                </a>
                <a href="<?= $facebook_auth_url ?>" class="btn btn-outline flex items-center gap-2 px-4 py-2 rounded-lg w-full md:w-auto">
                    <img src="assets/images/icons/facebook-100.png" alt="Facebook Logo" class="w-6 h-6">
                    Sign in with Facebook
                </a>
            </div>

        </div>
    </div>

    <script>
        function toggleForms() {
            document.getElementById("login-form").classList.toggle("hidden");
            document.getElementById("register-form").classList.toggle("hidden");
        }
    </script>

</body>
</html>
