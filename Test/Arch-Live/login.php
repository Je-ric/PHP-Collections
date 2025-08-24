<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login & Register</title>
</head>
<body>

<h2>Register</h2>
<form method="POST" action="auth.php">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <select name="role" required>
        <option value="researcher">Researcher</option>
        <option value="adviser">Adviser</option>
        <option value="administrator">Administrator</option>
    </select><br>
    <button type="submit" name="register">Register</button>
</form>

<h2>Login</h2>
<form method="POST" action="auth.php">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit" name="login">Login</button>
</form>

</body>
</html>
