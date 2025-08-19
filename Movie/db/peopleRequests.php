<?php
session_start();
require_once __DIR__ . '/../classes/People.php';

header('Content-Type: application/json; charset=utf-8');

$action  = $_POST['action'] ?? '';
$movieId = isset($_POST['movie_id']) ? (int)$_POST['movie_id'] : 0;
$role    = $_POST['role'] ?? '';
$name    = isset($_POST['name']) ? trim($_POST['name']) : '';
$id      = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$query   = isset($_POST['query']) ? trim($_POST['query']) : '';

$people = new People();

try {
    if ($action === 'load' || $action === 'fetch') {
        if ($movieId <= 0 || ($role !== 'Director' && $role !== 'Cast')) {
            throw new Exception('Invalid params');
        }
        $data = $people->getMoviePeople($movieId, $role); // returns array of ['id','name']
        echo json_encode($data); // frontend accepts array or {data:[]}
        exit;
    }

    if ($action === 'add') {
        if ($movieId <= 0 || $name === '' || !in_array($role, ['Director','Cast'], true)) {
            throw new Exception('Invalid input data');
        }
        $personId = $people->addPerson($name);
        $attached = $people->attachToMovie($movieId, $personId, $role);

        if (!$attached) {
            echo json_encode(['success' => false, 'message' => 'This person is already added as ' . strtolower($role)]);
            exit;
        }

        echo json_encode(['success' => true, 'message' => ucfirst(strtolower($role)) . ' added successfully', 'person_id' => $personId]);
        exit;
    }

    if ($action === 'remove') {
        if ($id <= 0) throw new Exception('Invalid cast ID');
        $removed = $people->removeFromMovie($id);
        if (!$removed) {
            echo json_encode(['success' => false, 'message' => 'Failed to remove person']);
            exit;
        }
        echo json_encode(['success' => true, 'message' => 'Person removed successfully']);
        exit;
    }

    if ($action === 'search') {
        // return [] for empty queries
        if ($query === '') {
            echo json_encode([]);
            exit;
        }

        // Use People::search (returns array of ['id','name'])
        $results = $people->search($query) ?? [];
        echo json_encode($results);
        exit;
    }

    throw new Exception('Unknown action');
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}
?>
