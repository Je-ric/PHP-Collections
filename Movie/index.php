<?php
session_start();
require_once __DIR__ . '/functions/Movie.php';

$movie = new Movie();
$movies = $movie->getAllMovies();
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

      <h1>Movie List</h1>

    <?php if (isset($_SESSION['username'])): ?>
        <a href="pages/manageMovie.php">➕ Add Movie</a>
    <?php endif; ?>

    <table border="1" cellpadding="8">
        <tr>
            <th>Title</th>
            <th>Year</th>
            <th>Description</th>
            <th>Poster</th>
            <th>Trailer</th>
            <?php if (isset($_SESSION['username'])): ?><th>Actions</th><?php endif; ?>
        </tr>
        <?php foreach ($movies as $m): ?>
        <tr>
            <td><?= htmlspecialchars($m['title']) ?></td>
            <td><?= htmlspecialchars($m['release_year']) ?></td>
            <td><?= htmlspecialchars($m['description']) ?></td>
            <td><img src="<?= htmlspecialchars($m['poster_url']) ?>" width="50"></td>
            <td><a href="<?= htmlspecialchars($m['trailer_url']) ?>" target="_blank">Watch</a></td>
            <?php if (isset($_SESSION['username'])): ?>
            <td>
                <a href="pages/manageMovie.php?id=<?= $m['id'] ?>">✏ Edit</a>
                <form action="db/movieRequests.php" method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $m['id'] ?>">
                    <button type="submit" onclick="return confirm('Delete this movie?')">🗑 Delete</button>
                </form>
            </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
