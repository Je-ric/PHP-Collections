<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../classes/Recommend.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$limit  = (int)($_GET['limit'] ?? $_POST['limit'] ?? 12);

function respond($success, $data = null, $message = null, $code = 200)
{
    http_response_code($code);
    echo json_encode([
        'success' => $success,
        'data'    => $data,
        'message' => $message
    ]);
    exit;
}

try {
    $rec = new Recommend();

    switch ($action) {
        case 'trending':
            respond(true, $rec->getTrending($limit));
            break;

        case 'latest':
            respond(true, $rec->getLatest($limit));
            break;

        case 'search':
            $term = trim($_GET['q'] ?? $_POST['q'] ?? '');
            $data = $term === '' ? [] : $rec->searchByTitle($term, $limit);
            respond(true, $data);
            break;

        case 'favGenres':
            if ($userId <= 0) respond(false, null, 'Login required', 401);
            respond(true, $rec->basedOnFavoriteGenres($userId, $limit));
            break;

        case 'favCountries':
            if ($userId <= 0) respond(false, null, 'Login required', 401);
            respond(true, $rec->basedOnFavoriteCountries($userId, $limit));
            break;

        case 'favLanguages':
            if ($userId <= 0) respond(false, null, 'Login required', 401);
            respond(true, $rec->basedOnFavoriteLanguages($userId, $limit));
            break;

        case 'favorites':
            if ($userId <= 0) respond(false, null, 'Login required', 401);
            respond(true, $rec->getFavorites($userId, $limit));
            break;

        case 'rated':
            if ($userId <= 0) respond(false, null, 'Login required', 401);
            respond(true, $rec->getRated($userId, $limit));
            break;

        case 'topGenres':
            $data = $userId > 0
                ? $rec->getUserTopGenres($userId, 5)
                : $rec->getTopGenresOverall(5);
            respond(true, $data);
            break;

        case 'byGenre':
            $genreId = (int)($_GET['genre_id'] ?? $_POST['genre_id'] ?? 0);
            if ($genreId <= 0) respond(false, null, 'Invalid genre', 400);
            respond(true, $rec->getByGenre($genreId, $userId, $limit));
            break;

        case 'favGenreCounts':
            if ($userId <= 0) respond(false, null, 'Login required', 401);
            respond(true, $rec->getFavCountsByGenre($userId));
            break;
        case 'favCountryCounts':
            if ($userId <= 0) respond(false, null, 'Login required', 401);
            respond(true, $rec->getFavCountsByCountry($userId));
            break;
        case 'favLanguageCounts':
            if ($userId <= 0) respond(false, null, 'Login required', 401);
            respond(true, $rec->getFavCountsByLanguage($userId));
            break;

        case 'ratedGenreCounts':
            if ($userId <= 0) respond(false, null, 'Login required', 401);
            respond(true, $rec->getRatedCountsByGenre($userId));
            break;
        case 'ratedCountryCounts':
            if ($userId <= 0) respond(false, null, 'Login required', 401);
            respond(true, $rec->getRatedCountsByCountry($userId));
            break;
        case 'ratedLanguageCounts':
            if ($userId <= 0) respond(false, null, 'Login required', 401);
            respond(true, $rec->getRatedCountsByLanguage($userId));
            break;

        case 'related':
            $movieId = (int)($_GET['movie_id'] ?? $_POST['movie_id'] ?? 0);
            if ($movieId <= 0) respond(false, null, 'Invalid movie', 400);
            respond(true, $rec->getRelatedToMovie($movieId, $limit));
            break;

        default:
            respond(false, null, 'Unknown action', 400);
    }
} catch (Throwable $e) {
    respond(false, null, $e->getMessage(), 500);
}
