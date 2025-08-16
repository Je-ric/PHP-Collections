<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movie Recommendation System</title>
</head>
<body>
    <h1>Movie Recommender</h1>

    <?php if (isset($_SESSION['username'])): ?>
        <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
        <form action="db/requests.php" method="POST">
            <input type="hidden" name="action" value="logout">
            <button type="submit">Logout</button>
        </form>
    <?php else: ?>
        <a href="pages/loginRegister.php">Login/Register</a>
    <?php endif; ?>
</body>
</html>
