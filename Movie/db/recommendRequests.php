<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../classes/Recommend.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$limit  = isset($_GET['limit']) ? (int)$_GET['limit'] : (isset($_POST['limit']) ? (int)$_POST['limit'] : 12);

try {
    $rec = new Recommend();

    switch ($action) {
        case 'trending':
            $data = $rec->getTrending($limit);
            echo json_encode(['success' => true, 'data' => $data]);
            break;
        case 'latest':
            $data = $rec->getLatest($limit);
            echo json_encode(['success' => true, 'data' => $data]);
            break;
        case 'search':
            $term = trim($_GET['q'] ?? $_POST['q'] ?? '');
            $data = $term === '' ? [] : $rec->searchByTitle($term, $limit);
            echo json_encode(['success' => true, 'data' => $data]);
            break;
        case 'favGenres':
            if ($userId <= 0) { http_response_code(401); echo json_encode(['success' => false, 'message' => 'Login required']); break; }
            $data = $rec->basedOnFavoriteGenres($userId, $limit);
            echo json_encode(['success' => true, 'data' => $data]);
            break;
        case 'favCountries':
            if ($userId <= 0) { http_response_code(401); echo json_encode(['success' => false, 'message' => 'Login required']); break; }
            $data = $rec->basedOnFavoriteCountries($userId, $limit);
            echo json_encode(['success' => true, 'data' => $data]);
            break;
        case 'favLanguages':
            if ($userId <= 0) { http_response_code(401); echo json_encode(['success' => false, 'message' => 'Login required']); break; }
            $data = $rec->basedOnFavoriteLanguages($userId, $limit);
            echo json_encode(['success' => true, 'data' => $data]);
            break;
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Unknown action']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
