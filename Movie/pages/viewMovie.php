<?php
session_start();
require_once __DIR__ . '/../classes/Movie.php';
require_once __DIR__ . '/../classes/RateReview.php';
require_once __DIR__ . '/../classes/People.php';

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

$peopleObj = new People();
$directors = $peopleObj->getMoviePeople($m['id'], 'Director');
$actors = $peopleObj->getMoviePeople($m['id'], 'Cast');

// (same as index.php)
$ratingInfo   = $rate->getAverageRating($m['id']);
$avgRating    = $ratingInfo['avg'] ?? null;
$totalReviews = $ratingInfo['total'] ?? 0;
// $ratingLabel  = $avgRating ? number_format($avgRating, 1) . ' ⭐' : 'No ratings yet';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($m['title']) ?> - Movie Recommender</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <style>
        /* Added CSS root variables for easy color customization */
        :root {
            --primary-bg: #0f172a;
            --secondary-bg: #1e293b;
            --card-bg: #334155;
            --accent-color: #10b981;
            --accent-hover: #059669;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #64748b;
            --border-color: #475569;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --gradient-start: #0f172a;
            --gradient-end: #1e293b;
        }

        body {
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            color: var(--text-primary);
            font-family: "Oswald", sans-serif;
            min-height: 100vh;
        }

        /* Enhanced header with better spacing */
        .cinematic-header {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
        }

        .brand-title {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-hover));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        /* Fixed poster sizing and balanced height */
        .poster-container {
            height: 600px;
            display: flex;
            align-items: center;
        }

        .movie-poster {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .movie-poster:hover {
            transform: translateY(-5px);
            box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.7);
        }

        /* Balanced content height to match poster */
        .movie-details-container {
            height: 600px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

       

        /* Better meta information layout */
        .clm{
            color: var(--text-secondary);
        }

        /* Improved genre badges */
        .badge-genre {
            display: inline-flex;
            align-items: center;
            border-radius: 20px;
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            font-weight: 500;
            background-color: var(--secondary-bg);
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .badge-genre:hover {
            background-color: var(--accent-color);
            color: white;
            border-color: var(--accent-color);
        }

        /* Enhanced rating display */
        .rating-section {
            background: var(--secondary-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
        }

        .rating-stars i {
            font-size: 1.1rem;
            margin-right: 2px;
        }

        .rating-value {
            font-size: 1.1rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        /* Improved info sections */
        .info-section {
            background: var(--secondary-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.2rem;
            margin-bottom: 1rem;
        }

        .info-label {
            color: var(--accent-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-content {
            font-size: 0.95rem;
            line-height: 1.4;
        }

        /* Compact review section */
        .reviews-section {
            margin-top: 2rem;
        }

        .review-card {
            background: var(--secondary-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            transition: transform 0.2s ease;
        }

        .review-card:hover {
            transform: translateY(-1px);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .reviewer-name {
            font-weight: 600;
            color: var(--accent-color);
            font-size: 0.9rem;
        }

        .review-rating {
            background: var(--warning-color);
            color: #1f2937;
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .review-text {
            font-size: 0.9rem;
            line-height: 1.5;
            margin: 0;
        }

        /* Enhanced buttons */
        .btn-cinematic {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-hover));
            border: none;
            color: white;
            font-weight: 500;
            padding: 0.6rem 1.5rem;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-cinematic:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px -5px rgba(16, 185, 129, 0.4);
            color: white;
        }

        .btn-outline-cinematic {
            border: 1px solid var(--accent-color);
            color: var(--accent-color);
            background: transparent;
            font-weight: 500;
            padding: 0.6rem 1.5rem;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-outline-cinematic:hover {
            background: var(--accent-color);
            color: white;
        }

        /* User review highlight */
        .user-review-highlight {
            border: 2px solid var(--success-color) !important;
            background: rgba(16, 185, 129, 0.1);
        }

        .user-review-badge {
            background: var(--success-color);
            color: white;
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .poster-container,
            .movie-details-container {
                height: auto;
            }
            
            .movie-title {
                font-size: 1.8rem;
            }
            
            .movie-year {
                font-size: 1.2rem;
                margin-left: 0.5rem;
            }
        }
    </style>
</head>
<body>

    <header class="cinematic-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h4 brand-title mb-0">
                    <i class='bx bx-movie-play'></i> Movie Recommendation
                </h1>
                <a href="../index.php" class="btn btn-outline-cinematic">
                    <i class='bx bx-arrow-back'></i> Back to Movies
                </a>
            </div>
        </div>
    </header>

    <main class="container py-4">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="poster-container">
                    <img src="../<?= htmlspecialchars($m['poster_url']) ?>"
                        alt="<?= htmlspecialchars($m['title']) ?>"
                        class="movie-poster">
                </div>
            </div>

            <!-- Right: Balanced movie info container -->
            <div class="col-md-8">
                <div class="movie-details-container">
                    <div>
                        <h1 class="fw-bold mb-2 fs-1">
                            <?= htmlspecialchars($m['title']) ?>
                            <span class="ms-3" style="color:#A0A0A0;">
                                (<?= htmlspecialchars($m['release_year']) ?>)
                            </span>
                        </h1>

                        <div class="clm d-flex flex-wrap gap-2 mb-3 medium">
                            <span>
                                <?= htmlspecialchars($m['country_name']) ?>
                            </span>
                            <span>•</span>
                            <span>
                                <?= htmlspecialchars($m['language_name']) ?>
                            </span>
                        </div>


                        <!-- Improved genres section -->
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <?php if (!empty($genres)): ?>
                                <?php foreach ($genres as $genre): ?>
                                    <span class="badge-genre">
                                        <?= htmlspecialchars($genre) ?>
                                    </span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted">No genres available</span>
                            <?php endif; ?>
                        </div>

                        <!-- Improved director and rating layout -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <div class="info-section">
                                    <div class="info-label">
                                        <i class='bx bx-video'></i> Director(s)
                                    </div>
                                    <div class="info-content">
                                        <?= !empty($directors) ? implode(", ", array_column($directors, 'name')) : "N/A" ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="rating-section">
                                    <?php if ($avgRating): ?>
                                        <div class="rating-stars">
                                            <?php
                                            $filledStars = floor($avgRating);
                                            $halfStar = ($avgRating - $filledStars) >= 0.5;
                                            $emptyStars = 5 - $filledStars - ($halfStar ? 1 : 0);

                                            for ($i = 0; $i < $filledStars; $i++): ?>
                                                <i class='bx bxs-star text-warning'></i>
                                            <?php endfor; ?>

                                            <?php if ($halfStar): ?>
                                                <i class='bx bxs-star-half text-warning'></i>
                                            <?php endif; ?>

                                            <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                                                <i class='bx bx-star text-warning'></i>
                                            <?php endfor; ?>
                                        </div>
                                        <div class="rating-value">
                                            <?= number_format($avgRating, 1) ?>/5
                                            <small class="text-muted">(<?= $totalReviews ?>)</small>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-muted">No ratings yet</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Improved cast section -->
                        <div class="info-section">
                            <div class="info-label">
                                <i class='bx bxs-user'></i> Cast
                            </div>
                            <div class="info-content">
                                <?= !empty($actors) ? implode(", ", array_column($actors, 'name')) : "N/A" ?>
                            </div>
                        </div>
                    </div>

                    <!-- Better positioned action buttons -->
                    <div class="d-flex gap-2 flex-wrap">
                        <?php if (!empty($m['trailer_url'])): ?>
                            <a href="#trailer-section" class="btn btn-cinematic">
                                <i class='bx bx-play'></i> Watch Trailer
                            </a>
                        <?php endif; ?>
                        <button class="btn btn-outline-cinematic">
                            <i class='bx bx-heart'></i> Add to Favorites
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Improved overview section -->
         <section class="info-section mt-4">
          <h2 class="text-3xl font-bold mb-6"><i class='bx bx-detail'></i> Overview</h2>
          <p class="text-lg text-[#A0A0A0] leading-relaxed max-w-4xl"><?= nl2br(htmlspecialchars($m['description'])) ?></p>
        </section>

        <div class="info-section mt-4">
            <div class="info-label text-3xl font-bold mb-6">
                <i class='bx bx-detail'></i> Overview
            </div>
            <div class="info-content">
                <?= nl2br(htmlspecialchars($m['description'])) ?>
            </div>
        </div>

        <!-- Enhanced trailer section -->
        <?php if (!empty($m['trailer_url'])): ?>
            <div class="aspect-video max-w-4xl rounded-lg overflow-hidden bg-[#2C2C2C]">
                <h2 class="text-3xl font-bold mb-6">Trailer</h2>
                <?php
                $trailerUrl = $m['trailer_url'];
                if (
                    strpos($trailerUrl, "youtube.com") !== false ||
                    strpos($trailerUrl, "youtu.be") !== false
                ) {
                    preg_match(
                        '/(youtu\.be\/|v=)([^&]+)/',
                        $trailerUrl,
                        $matches
                    );
                    $videoId = $matches[2] ?? '';
                    if ($videoId) {
                        echo '<div class="trailer-container"><div class="ratio ratio-16x9"><iframe src="https://www.youtube.com/embed/' . htmlspecialchars($videoId) . '" allowfullscreen></iframe></div></div>';
                    }
                } else {
                    echo '<div class="trailer-container"><video controls class="w-100 rounded"><source src="../' . htmlspecialchars($trailerUrl) . '" type="video/mp4"></video></div>';
                }
                ?>
            </div>
        <?php endif; ?>

        <!-- Compact reviews section -->
        <div class="reviews-section">
            <h5 class="mb-3" style="color: var(--accent-color);">
                <i class='bx bx-message-dots'></i> User Reviews
            </h5>
            
            <!-- Review action section -->
            <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'user'): ?>
                <?php if (!$rate->hasReviewed($_SESSION['user_id'], $m['id'])): ?>
                    <button class="btn btn-cinematic mb-3" data-bs-toggle="modal" data-bs-target="#reviewModal">
                        <i class='bx bx-star'></i> Leave a Review
                    </button>
                <?php else: ?>
                    <?php $userReview = $rate->getUserReview($_SESSION['user_id'], $m['id']); ?>
                    <div class="review-card user-review-highlight mb-3">
                        <div class="review-header">
                            <span class="reviewer-name">Your Review</span>
                            <div class="d-flex gap-2 align-items-center">
                                <span class="review-rating"><?= $userReview['rating'] ?> ⭐</span>
                                <span class="user-review-badge">Your Review</span>
                            </div>
                        </div>
                        <p class="review-text"><?= htmlspecialchars($userReview['review']) ?></p>
                    </div>
                <?php endif; ?>
            <?php elseif (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'admin'): ?>
                <p class="text-muted mb-3">Admins cannot leave reviews.</p>
            <?php else: ?>
                <p class="text-muted mb-3">
                    Please <a href="loginRegister.php" style="color: var(--accent-color);">login</a> to leave a review.
                </p>
            <?php endif; ?>

            <!-- Compact review cards -->
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $r): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <span class="reviewer-name"><?= htmlspecialchars($r['username']) ?></span>
                            <span class="review-rating"><?= $r['rating'] ?> ⭐</span>
                        </div>
                        <p class="review-text"><?= htmlspecialchars($r['review']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="review-card">
                    <p class="review-text text-muted">No reviews yet. Be the first to review this movie!</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Enhanced modal styling maintained -->
    <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'user'): ?>
        <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="../db/rateRequests.php" method="POST" class="modal-content">
                    <input type="hidden" name="movie_id" value="<?= $m['id'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reviewModalLabel">⭐ Review: <?= htmlspecialchars($m['title']) ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating (1-5 stars)</label>
                            <select class="form-select" name="rating" id="rating" required>
                                <option value="" selected disabled>Select rating</option>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?> ⭐</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="review" class="form-label">Your Review</label>
                            <textarea class="form-control" name="review" id="review" rows="4" required placeholder="Share your thoughts about this movie..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-cinematic" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-cinematic">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Enhanced footer -->
    <footer class="cinematic-footer text-center py-4">
        <p class="text-muted small mb-0">© <?= date('Y') ?> Movie Database • Powered by Cinema Magic</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
