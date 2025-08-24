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

if ($action === 'load' || $action === 'fetch') {
    if ($movieId <= 0 || ($role !== 'Director' && $role !== 'Cast')) {
        respond(false, null, 'Invalid params', 400);
    }
    $data = $people->getMoviePeople($movieId, $role);
    respond(true, $data);
}

if ($action === 'add') {
    if ($movieId <= 0 || $name === '' || !in_array($role, ['Director','Cast'], true)) {
        respond(false, null, 'Invalid input data', 400);
    }
    $personId = $people->addPerson($name);
    $attached = $people->attachToMovie($movieId, $personId, $role);
    if (!$attached) {
        respond(false, null, 'This person is already added as ' . strtolower($role));
    }
    respond(true, ['person_id' => $personId], ucfirst(strtolower($role)) . ' added successfully');
}

if ($action === 'remove') {
    if ($id <= 0) {
        respond(false, null, 'Invalid cast ID', 400);
    }
    $removed = $people->removeFromMovie($id);
    if (!$removed) {
        respond(false, null, 'Failed to remove person');
    }
    respond(true, null, 'Person removed successfully');
}

if ($action === 'search') {
    if ($query === '') {
        respond(true, []);
    }
    $results = $people->search($query) ?? [];
    respond(true, $results);
}

respond(false, null, 'Unknown action', 400);
?>
