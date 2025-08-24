<?php
include('../../config.php'); 
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image_id = $_POST['image_id'];
    $image_path = "../../" . $_POST['image_path'];

    // Delete image record from database
    $query = "DELETE FROM content_images WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $image_id);
    
    if ($stmt->execute()) {
        // Delete file from server
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Database error"]);
    }
    exit();
}
?>
