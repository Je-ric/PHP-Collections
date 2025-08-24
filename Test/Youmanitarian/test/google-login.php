<?php
session_start();
require 'vendor/autoload.php';
require 'config.php';

$client = new Google_Client();
$client->setClientId('125524050232-q575pl3hk75nnbkf9d4qs3v7q63684dq.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/php-projects/Youmanitarian/google-login.php'); 
$client->addScope("email");
$client->addScope("profile");


if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (isset($token['error'])) {
        die('Error fetching access token');
    }

    $client->setAccessToken($token);

    // Create Google OAuth service instance
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account = $google_oauth->userinfo->get();

    $google_id = $google_account->id;
    $name = $google_account->name;
    $email = $google_account->email;
    $profile_pic = $google_account->picture;

    $query = "SELECT * FROM users WHERE google_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $google_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['picture'] = $user['profile_pic'];
    } else {
        $insert = "INSERT INTO users (google_id, name, email, profile_pic) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert);
        $stmt->bind_param("ssss", $google_id, $name, $email, $profile_pic);
        $stmt->execute();

        // Log them in
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['picture'] = $profile_pic;
    }

    header("Location: index.php");
    exit();
}

// Generate Google OAuth URL
$login_url = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login with Google</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
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

    <div class="bg-white p-8 rounded-lg shadow-lg text-center">
        <h1 class="text-2xl font-bold mb-4">Login with Google</h1>
        <a href="<?= htmlspecialchars($login_url) ?>" class="px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600">
            Sign in with Google
        </a>
    </div>
</body>
</html>
