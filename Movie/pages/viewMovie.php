<?php
session_start();
require_once __DIR__ . '/../functions/Movie.php';
require_once __DIR__ . '/../functions/RateReview.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$movie = new Movie();
$m = $movie->getMovieById($_GET['id']);
if (!$m) die("Movie not found.");

$rate = new RateReview();
$reviews = $rate->getReviewsByMovie($m['id']);
$genres = $movie->getGenresByMovie($m['id'], 'name'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($m['title']) ?> - Movie Recommender</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { background: #0f0f0f; color: #f8f9fa; font-family: 'Inter', sans-serif; }
    .movie-poster { max-height: 400px; object-fit: cover; border-radius: 10px; }
    .review-box { background: rgba(24,24,27,0.6); border: 1px solid #2d2d2d; border-radius: 10px; padding: 1rem; }
</style>
</head>
<body>

<header class="border-bottom px-3 py-3 d-flex justify-content-between align-items-center">
    <h1 class="h4 fw-semibold mb-0">Movie Recommender</h1>
    <a href="../index.php" class="btn btn-sm btn-outline-light">Back to Movies</a>
</header>

<main class="container py-5">
    <div class="row g-4">
        <div class="col-md-4">
            <img src="../<?= htmlspecialchars($m['poster_url']) ?>" alt="<?= htmlspecialchars($m['title']) ?>" class="w-100 movie-poster">
        </div>

        <div class="col-md-8">
            <h2><?= htmlspecialchars($m['title']) ?> (<?= htmlspecialchars($m['release_year']) ?>)</h2>
            <p><strong>Country:</strong> <?= htmlspecialchars($m['country_name']) ?> | <strong>Language:</strong> <?= htmlspecialchars($m['language_name']) ?></p>
            <p><strong>Genres:</strong> <?= !empty($genres) ? implode(", ", $genres) : "N/A" ?></p>
            <p class="text-secondary"><?= nl2br(htmlspecialchars($m['description'])) ?></p>

            <?php if (!empty($m['trailer_url'])): ?>
                <div class="mt-4">
                    <?php
                    $trailerUrl = $m['trailer_url'];
                    if (strpos($trailerUrl,"youtube.com")!==false || strpos($trailerUrl,"youtu.be")!==false) {
                        preg_match('/(youtu\.be\/|v=)([^&]+)/', $trailerUrl, $matches);
                        $videoId = $matches[2] ?? '';
                        if ($videoId) {
                            echo '<div class="ratio ratio-16x9"><iframe src="https://www.youtube.com/embed/'.htmlspecialchars($videoId).'" allowfullscreen></iframe></div>';
                        }
                    } else {
                        echo '<video controls class="w-100 rounded"><source src="../'.htmlspecialchars($trailerUrl).'" type="video/mp4"></video>';
                    }
                    ?>
                </div>
            <?php endif; ?>

            <hr>

            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (!$rate->hasReviewed($_SESSION['user_id'], $m['id'])): ?>
                    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#reviewModal">⭐ Leave a Review</button>
                    <!-- Modal code here -->
                <?php else: ?>
                    <?php $userReview = $rate->getUserReview($_SESSION['user_id'], $m['id']); ?>
                    <div class="review-box mt-3 border border-success">
                        <p><strong>Your Rating:</strong> <?= $userReview['rating'] ?> ⭐</p>
                        <p><strong>Your Review:</strong> <?= htmlspecialchars($userReview['review']) ?></p>
                        <p class="fst-italic small text-success">(Your review is highlighted above)</p>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-secondary">Please <a href="loginRegister.php">login</a> to leave a review.</p>
            <?php endif; ?>

            <div class="mt-5">
                <h4>User Reviews</h4>
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $r): ?>
                        <div class="review-box mb-3">
                            <p><strong><?= htmlspecialchars($r['username']) ?></strong> - <?= $r['rating'] ?> ⭐</p>
                            <p><?= htmlspecialchars($r['review']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-secondary">No reviews yet. Be the first to review!</p>
                <?php endif; ?>
            </div>

        </div>
    </div>
</main>

<footer class="border-top text-center py-3">
    <p class="text-muted small mb-0">© <?= date('Y') ?> Movie Database</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
