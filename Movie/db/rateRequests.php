<?php
session_start();
require_once __DIR__ . '/../functions/RateReview.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/loginRegister.php");
    exit;
}

if (!isset($_SESSION['user_id']) || (isset($_SESSION['role']) && $_SESSION['role'] !== 'user')) {
    echo "Only users can submit reviews.";
    exit;
}

$rate = new RateReview();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movieId = $_POST['movie_id'];
    $rating = $_POST['rating'];
    $review = trim($_POST['review']);
    $userId = $_SESSION['user_id'];

    $rate->addReview($userId, $movieId, $rating, $review);
    header("Location: ../index.php?success=review_submitted");
    exit;
}
