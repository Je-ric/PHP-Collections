<?php
require_once __DIR__ . '/../functions/Movie.php';
$movie = new Movie();

$editing = false;
$movieData = [
    'id' => '',
    'title' => '',
    'description' => '',
    'release_year' => '',
    'poster_url' => '',
    'trailer_url' => ''
];

// If id is provided, we are editing
if (!empty($_GET['id'])) {
    $editing = true;
    $movieData = $movie->getMovieById($_GET['id']);
    if (!$movieData) {
        // Invalid id, redirect back
        header("Location: ../index.php");
        exit;
    }
}

$actionText = $editing ? "Update Movie" : "Add Movie";
$submitAction = $editing ? "update" : "add";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $actionText ?></title>
</head>
<body>
    <h1><?= $actionText ?></h1>

    <form action="../db/movieRequests.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="<?= $submitAction ?>">
    <?php if ($editing): ?>
        <input type="hidden" name="id" value="<?= $movieData['id'] ?>">
    <?php endif; ?>

    <label>Title:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($movieData['title']) ?>" required><br>

    <label>Description:</label>
    <textarea name="description"><?= htmlspecialchars($movieData['description']) ?></textarea><br>

    <label>Release Year:</label>
    <input type="number" name="release_year" value="<?= $movieData['release_year'] ?>"><br>

    <label>Poster Image:</label>
    <input type="file" name="poster_file" <?= $editing ? "" : "required" ?>><br>

    <label>Trailer URL:</label>
    <input type="text" name="trailer_url" value="<?= htmlspecialchars($movieData['trailer_url']) ?>"><br>

    <button type="submit"><?= $actionText ?></button>
</form>


    <p><a href="../index.php">⬅ Back to Movies</a></p>
</body>
</html>
