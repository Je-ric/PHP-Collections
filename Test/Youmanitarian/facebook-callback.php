<?php
session_start();

$facebook_app_id = '888607986619915';
$facebook_app_secret = 'e67e180e55a5c4a3d72f1813132547ee';
$facebook_redirect_uri = 'http://localhost/php-projects/Youmanitarian/facebook-callback.php';

// Step 1: Get Access Token
if (isset($_GET['code'])) {
    $token_url = "https://graph.facebook.com/v18.0/oauth/access_token?" . http_build_query([
        'client_id' => $facebook_app_id,
        'client_secret' => $facebook_app_secret,
        'redirect_uri' => $facebook_redirect_uri,
        'code' => $_GET['code'],
    ]);

    $response = file_get_contents($token_url);
    $response = json_decode($response, true);

    if (!isset($response['access_token'])) {
        die("Error fetching access token.");
    }

    $access_token = $response['access_token'];

    // Step 2: Get User Data
    $user_url = "https://graph.facebook.com/me?fields=id,name,email,picture&access_token={$access_token}";
    $user_response = file_get_contents($user_url);
    $user = json_decode($user_response, true);

    if (isset($user['id'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'] ?? 'No email provided';
        $_SESSION['picture'] = $user['picture']['data']['url'];

        // Redirect to dashboard or home page
        header("Location: index.php");
        exit();
    } else {
        die("Error fetching user data.");
    }
} else {
    die("Authorization failed.");
}
?>
