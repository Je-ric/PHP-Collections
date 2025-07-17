<?php
require_once 'config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    if ($stmt->rowCount() > 0) {
        header("Location: index.php?message=User deleted successfully");
    } else {
        header("Location: index.php?message=Error deleting user");
    }
} else {
    header("Location: index.php?message=Invalid request");
}
?>