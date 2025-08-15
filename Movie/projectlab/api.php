<?php
// File: api.php
// Description: The single-entry point for all AJAX requests.

session_start();
header('Content-Type: application/json');

require_once 'config.php';
require_once 'User.php';
require_once 'Movie.php';

$user = new User();
$movie = new Movie();

$response = ['success' => false, 'message' => 'Invalid action'];

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'register':
            $username = $_POST['username'];
            $password = $_POST['password'];
            if ($user->registerUser($username, $password)) {
                $response = ['success' => true, 'message' => 'Registration successful!'];
            } else {
                $response = ['success' => false, 'message' => 'Username already exists or registration failed.'];
            }
            break;

        case 'login':
            $username = $_POST['username'];
            $password = $_POST['password'];
            $userData = $user->loginUser($username, $password);
            if ($userData) {
                $_SESSION['user_id'] = $userData['id'];
                $_SESSION['username'] = $userData['username'];
                $response = ['success' => true, 'message' => 'Login successful!', 'user' => ['username' => $userData['username']]];
            } else {
                $response = ['success' => false, 'message' => 'Invalid username or password.'];
            }
            break;

        case 'logout':
            session_destroy();
            $response = ['success' => true, 'message' => 'Logged out successfully.'];
            break;

        case 'checkSession':
            if (isset($_SESSION['user_id'])) {
                $response = ['success' => true, 'isLoggedIn' => true, 'user' => ['username' => $_SESSION['username']]];
            } else {
                $response = ['success' => true, 'isLoggedIn' => false];
            }
            break;

        case 'getMovies':
            $movies = $movie->getAllMovies();
            $response = ['success' => true, 'data' => $movies];
            break;

        case 'searchMovies':
            $query = $_POST['query'];
            $movies = $movie->searchMovies($query);
            $response = ['success' => true, 'data' => $movies];
            break;

        case 'rateMovie':
            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];
                $movieId = $_POST['movie_id'];
                $rating = $_POST['rating'];
                $review = $_POST['review'];
                if ($movie->addRatingReview($userId, $movieId, $rating, $review)) {
                    $response = ['success' => true, 'message' => 'Rating/Review saved successfully.'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to save rating/review.'];
                }
            } else {
                $response = ['success' => false, 'message' => 'You must be logged in to rate movies.'];
            }
            break;

        case 'addFavorite':
            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];
                $movieId = $_POST['movie_id'];
                if ($movie->addFavorite($userId, $movieId)) {
                    $response = ['success' => true, 'message' => 'Added to favorites.'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to add to favorites.'];
                }
            } else {
                $response = ['success' => false, 'message' => 'You must be logged in to add favorites.'];
            }
            break;

        case 'getFavorites':
            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];
                $favorites = $movie->getUserFavorites($userId);
                $response = ['success' => true, 'data' => $favorites];
            } else {
                $response = ['success' => false, 'message' => 'You must be logged in to view favorites.'];
            }
            break;

        case 'getRecommendations':
            $recommendations = $movie->getRecommendations();
            $response = ['success' => true, 'data' => $recommendations];
            break;

        default:
            $response = ['success' => false, 'message' => 'Invalid action'];
            break;
    }
}

echo json_encode($response);
?>