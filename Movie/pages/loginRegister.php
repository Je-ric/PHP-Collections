<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id="register-form-container" class="form-container">
    <h2>Register</h2>
    <form action="../db/requests.php" method="POST">
        <input type="hidden" name="action" value="register">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="submit-btn">Register</button>
    </form>
</div>

<div id="login-form-container" class="form-container">
    <h2>Login</h2>
    <form action="../db/requests.php" method="POST">
        <input type="hidden" name="action" value="login">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="submit-btn">Login</button>
    </form>
</div>

</body>
</html>
