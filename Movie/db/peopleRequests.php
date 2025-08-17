<?php
session_start();
require_once __DIR__ . '/../functions/People.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$movieId = isset($_POST['movie_id']) ? (int)$_POST['movie_id'] : 0;
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$role = isset($_POST['role']) ? trim($_POST['role']) : '';

$people = new People();

try {
    if ($action === 'load') {
        // Fetch existing people for a movie
        if ($movieId <= 0) throw new Exception('Invalid movie ID');
        $data = $people->getMoviePeople($movieId, $role);
        echo json_encode([
            'success' => true,
            'data' => $data,
            'message' => 'People loaded successfully'
        ]);
        exit;
    }

    if ($action === 'add') {
        // Add a person and attach to movie
        if ($movieId <= 0 || $name === '' || !in_array($role, ['Director', 'Cast'], true)) {
            throw new Exception('Invalid input data');
        }

        $personId = $people->addPerson($name);
        $attached = $people->attachToMovie($movieId, $personId, $role);
        
        if (!$attached) {
            echo json_encode([
                'success' => false,
                'message' => 'This person is already added as ' . strtolower($role)
            ]);
            exit;
        }

        echo json_encode([
            'success' => true,
            'message' => ucfirst(strtolower($role)) . ' added successfully',
            'person_id' => $personId
        ]);
        exit;
    }

    if ($action === 'remove') {
        // Remove person from movie
        // $castId = isset($_POST['cast_id']) ? (int)$_POST['cast_id'] : 0;
        $castId = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        if ($castId <= 0) throw new Exception('Invalid cast ID');
        
        $removed = $people->removeFromMovie($castId);
        
        if (!$removed) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to remove person'
            ]);
            exit;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Person removed successfully'
        ]);
        exit;
    }

    if ($action === 'search') {
        // Search for people names for autocomplete
        $query = isset($_POST['query']) ? trim($_POST['query']) : '';
        if ($query === '') {
            echo json_encode([
                'success' => true,
                'data' => []
            ]);
            exit;
        }

        // This would need to be implemented in your People class
        // For now, returning empty array
        echo json_encode([
            'success' => true,
            'data' => []
        ]);
        exit;
    }

    throw new Exception('Unknown action');

} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}
?>
