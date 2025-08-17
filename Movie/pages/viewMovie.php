<?php
session_start();
require_once __DIR__ . '/../functions/Movie.php';
require_once __DIR__ . '/../functions/RateReview.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$movie = new Movie();
$m = $movie->getMovieById($_GET['id']); // Movie details from Movie.php

if (!$m) {
    die("Movie not found.");
}

$rate = new RateReview();
$reviews = $rate->getReviewsByMovie($m['id']); // Fetch all reviews
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($m['title']) ?> - Movie Recommender</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f0f0f;
            color: #f8f9fa;
        }

        header {
            background: rgba(24, 24, 27, 0.6);
            backdrop-filter: blur(8px);
        }

        .movie-poster {
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        .review-box {
            background: rgba(24, 24, 27, 0.6);
            border: 1px solid #2d2d2d;
            border-radius: 10px;
            padding: 1rem;
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <header class="border-bottom px-3 px-md-5 py-3 d-flex align-items-center justify-content-between">
        <h1 class="h4 fw-semibold mb-0">Movie Recommender</h1>
        <div>
            <a href="../index.php" class="btn btn-sm btn-outline-light">Back to Movies</a>
        </div>
    </header>

    <!-- MOVIE DETAILS -->
    <main class="container py-5">
        <div class="row g-4">
            <!-- Poster -->
            <div class="col-md-4">
                <img src="../<?= htmlspecialchars($m['poster_url']) ?>" alt="<?= htmlspecialchars($m['title']) ?>" class="w-100 movie-poster">
            </div>

            <!-- Info -->
            <div class="col-md-8">
                <h2><?= htmlspecialchars($m['title']) ?> (<?= htmlspecialchars($m['release_year']) ?>)</h2>
                <p>
                <p><strong>Country:</strong> <?= htmlspecialchars($m['country_name']) ?> |
                    <strong>Language:</strong> <?= htmlspecialchars($m['language_name']) ?>
                </p>

                <p><strong>Genres:</strong>
                    <?php
                    $genres = $movie->getGenresByMovie($m['id']);
                    echo !empty($genres) ? implode(", ", $genres) : "N/A";
                    ?>
                </p>
                <p class="text-secondary"><?= nl2br(htmlspecialchars($m['description'])) ?></p>

                <?php if (!empty($m['trailer_url'])): ?>
                    <div class="mt-4">
                        <?php
                        $trailerUrl = $m['trailer_url'];
                        // YouTube embed
                        if (strpos($trailerUrl, "youtube.com") !== false || strpos($trailerUrl, "youtu.be") !== false) {
                            if (preg_match('/(youtu\.be\/|v=)([^&]+)/', $trailerUrl, $matches)) {
                                $videoId = $matches[2];
                                echo '<div class="ratio ratio-16x9">
                      <iframe src="https://www.youtube.com/embed/' . htmlspecialchars($videoId) . '" 
                              title="Trailer" allowfullscreen></iframe>
                    </div>';
                            }
                        } else {
                            // Local video file (MP4 etc.)
                            echo '<video controls class="w-100 rounded" style="max-height:400px;">
                  <source src="../' . htmlspecialchars($trailerUrl) . '" type="video/mp4">
                  Your browser does not support the video tag.
                </video>';
                        }
                        ?>
                    </div>
                <?php endif; ?>




                <hr>

                <!-- RATING & REVIEW -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (!$rate->hasReviewed($_SESSION['user_id'], $m['id'])): ?>
                        <!-- Button to trigger modal -->
                        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#reviewModal">
                            ⭐ Leave a Review
                        </button>

                        <!-- Review Modal -->
                        <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content bg-dark text-light">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="reviewModalLabel">Leave a Review</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="../db/rateRequests.php" method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="movie_id" value="<?= $m['id'] ?>">
                                            <label class="form-label">Rating:</label>
                                            <select name="rating" class="form-select mb-3" required>
                                                <option value="">Select Rating</option>
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <option value="<?= $i ?>"><?= $i ?> ⭐</option>
                                                <?php endfor; ?>
                                            </select>
                                            <label class="form-label">Review:</label>
                                            <textarea name="review" rows="3" class="form-control" placeholder="Write your review..." required></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Submit Review</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php $userReview = $rate->getUserReview($_SESSION['user_id'], $m['id']); ?>
                        <!-- User’s own review pinned -->
                        <div class="review-box mt-3 border border-success">
                            <p><strong>Your Rating:</strong> <?= $userReview['rating'] ?> ⭐</p>
                            <p><strong>Your Review:</strong> <?= htmlspecialchars($userReview['review']) ?></p>
                            <p class="fst-italic small text-success">(Your review is highlighted above)</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-secondary">Please <a href="loginRegister.php">login</a> to leave a review.</p>
                <?php endif; ?>


                <!-- ALL REVIEWS -->
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
    </main>

    <!-- FOOTER -->
    <footer class="border-top text-center py-3">
        <p class="text-muted small mb-0">© <?= date('Y') ?> Movie Database</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>