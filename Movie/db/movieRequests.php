<?php
require_once __DIR__ . '/../functions/Movie.php';
session_start();

$movie = new Movie();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add':
            $movie->addMovie(
                $_POST['title'],
                $_POST['description'],
                $_POST['release_year'],
                $_FILES['poster_file'],
                $_POST['trailer_url']
            );
            header("Location: ../index.php?success=movie_added");
            exit;

        case 'update':
            $movie->updateMovie(
                $_POST['id'],
                $_POST['title'],
                $_POST['description'],
                $_POST['release_year'],
                $_FILES['poster_file'],
                $_POST['trailer_url']
            );
            header("Location: ../index.php?success=movie_updated");
            exit;

        case 'delete':
            $movie->deleteMovie($_POST['id']);
            header("Location: ../index.php?success=movie_deleted");
            exit;
    }
}
