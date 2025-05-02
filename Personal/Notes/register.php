<?php
session_start();

// Database connection
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username already exists
    $sql_check_username = "SELECT * FROM users WHERE username='$username'";
    $result_check_username = mysqli_query($conn, $sql_check_username);

    if (mysqli_num_rows($result_check_username) > 0) {
        $error = "Username already exists. Please choose a different username.";
    } else {
        // Insert new user into the database
        $sql_register = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

        if (mysqli_query($conn, $sql_register)) {
            $_SESSION['username'] = $username;
            header("Location: notes.php");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
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
    <link rel="stylesheet" href="form.css">
</head>
<body>
    <div class="container login-container">
        <h2>Register</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Register">
        </form>
        <?php if(isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
