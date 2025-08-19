<?php
session_start();
require_once __DIR__ . '/../classes/Movie.php';
require_once __DIR__ . '/../classes/RateReview.php';
require_once __DIR__ . '/../classes/People.php';
require_once __DIR__ . '/../classes/Favorite.php';

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

$ratingInfo   = $rate->getAverageRating($m['id']);
$avgRating    = $ratingInfo['avg'] ?? null;
$totalReviews = $ratingInfo['total'] ?? 0;

$fav = new Favorite();
$isFavorited = !empty($_SESSION['user_id']) ? $fav->isFavorited($_SESSION['user_id'], $m['id']) : false;
$favCount = $fav->countByMovie($m['id']);
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($m['title']) ?> - Movie Recommender</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.24/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-bg': '#0f172a',
                        'secondary-bg': '#1e293b',
                        'card-bg': '#334155',
                        'accent': '#10b981',
                        'accent-hover': '#059669',
                        'text-primary': '#f8fafc',
                        'text-secondary': '#cbd5e1',
                        'text-muted': '#64748b',
                        'border-color': '#475569',
                    },
                    fontFamily: {
                        'oswald': ['Oswald', 'sans-serif'],
                        'anton': ['Anton', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            font-family: "Oswald", sans-serif;
        }
    </style>
</head>
<body class="min-h-screen text-text-primary">

    <!-- Header -->
    <header class="navbar bg-primary-bg/95 backdrop-blur-md border-b border-border-color sticky top-0 z-50">
        <div class="container mx-auto">
            <div class="navbar-start">
                <h1 class="text-xl font-bold bg-gradient-to-r from-accent to-accent-hover bg-clip-text text-transparent">
                    <i class='bx bx-movie-play mr-2'></i>Movie Recommendation
                </h1>
            </div>
            <div class="navbar-end">
                <a href="../index.php" class="btn btn-outline btn-accent">
                    <i class='bx bx-arrow-back'></i> Back to Movies
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <!-- Movie Details Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Movie Poster -->
            <div class="lg:col-span-1">
                <div class="card bg-secondary-bg border border-border-color shadow-2xl">
                    <figure class="px-4 pt-4">
                        <img src="../<?= htmlspecialchars($m['poster_url']) ?>"
                             alt="<?= htmlspecialchars($m['title']) ?>"
                             class="rounded-xl w-full h-96 object-cover hover:scale-105 transition-transform duration-300">
                    </figure>
                </div>
            </div>

            <!-- Movie Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title and Year -->
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold mb-2">
                        <?= htmlspecialchars($m['title']) ?>
                        <span class="text-text-muted text-3xl ml-4">
                            (<?= htmlspecialchars($m['release_year']) ?>)
                        </span>
                    </h1>
                    
                    <!-- Country and Language -->
                    <div class="flex flex-wrap items-center gap-2 text-text-secondary text-lg">
                        <span><?= htmlspecialchars($m['country_name']) ?></span>
                        <span>•</span>
                        <span><?= htmlspecialchars($m['language_name']) ?></span>
                    </div>
                </div>

                <!-- Genres -->
                <div class="flex flex-wrap gap-2">
                    <?php if (!empty($genres)): ?>
                        <?php foreach ($genres as $genre): ?>
                            <div class="badge badge-outline badge-lg hover:badge-accent transition-colors">
                                <?= htmlspecialchars($genre) ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="text-text-muted">No genres available</span>
                    <?php endif; ?>
                </div>

                <!-- Director and Rating -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Director -->
                    <div class="md:col-span-2">
                        <div class="card bg-card-bg border border-border-color p-4">
                            <div class="flex items-center gap-2 text-accent font-semibold mb-2">
                                <i class='bx bx-video'></i> Director(s)
                            </div>
                            <p class="text-text-secondary">
                                <?= !empty($directors) ? implode(" • ", array_column($directors, 'name')) : "N/A" ?>
                            </p>
                            <div class="flex items-center gap-2 text-accent font-semibold mb-2">
                        <i class='bx bxs-user'></i> Cast
                    </div>
                    <p class="text-text-secondary">
                        <?= !empty($actors) ? implode(" • ", array_column($actors, 'name')) : "N/A" ?>
                    </p>
                        </div>
                    </div>

                    <!-- Rating -->
                    <div class="md:col-span-1">
                        <div class="card bg-card-bg border border-border-color p-4 text-center">
                            <?php if ($avgRating): ?>
                                <div class="rating rating-lg mb-2">
                                    <?php
                                    $filledStars = floor($avgRating);
                                    $halfStar = ($avgRating - $filledStars) >= 0.5;
                                    $emptyStars = 5 - $filledStars - ($halfStar ? 1 : 0);

                                    for ($i = 0; $i < $filledStars; $i++): ?>
                                        <input type="radio" class="mask mask-star-2 bg-warning" disabled checked />
                                    <?php endfor; ?>

                                    <?php if ($halfStar): ?>
                                        <input type="radio" class="mask mask-star-2 bg-warning opacity-50" disabled />
                                    <?php endif; ?>

                                    <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                                        <input type="radio" class="mask mask-star-2 bg-base-300" disabled />
                                    <?php endfor; ?>
                                </div>
                                <div class="text-lg font-semibold">
                                    <?= number_format($avgRating, 1) ?>/5
                                    <div class="text-sm text-text-muted">(<?= $totalReviews ?> reviews)</div>
                                </div>
                            <?php else: ?>
                                <div class="text-text-muted">No ratings yet</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>


                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3">
                    <?php if (!empty($m['trailer_url'])): ?>
                        <a href="#trailer-section" class="btn btn-accent">
                            <i class='bx bx-play'></i> Watch Trailer
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['user_id'])): ?>
                        <button
                            id="favorite-btn"
                            type="button"
                            class="btn <?= $isFavorited ? 'btn-accent' : 'btn-outline btn-accent' ?>"
                            data-movie-id="<?= (int)$m['id'] ?>"
                            data-favorited="<?= $isFavorited ? '1' : '0' ?>"
                        >
                            <i class="bx <?= $isFavorited ? 'bxs-heart' : 'bx-heart' ?>"></i>
                            <span class="fav-text"><?= $isFavorited ? 'Favorited' : 'Add to Favorites' ?></span>
                            <span class="ml-1 text-sm opacity-70 fav-count">(<?= (int)$favCount ?>)</span>
                        </button>
                    <?php else: ?>
                        <a href="loginRegister.php" class="btn btn-outline btn-accent">
                            <i class='bx bx-heart'></i> Login to Favorite
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Overview Section -->
        <div class="card bg-secondary-bg border border-border-color p-6 mb-8">
            <h2 class="text-3xl font-bold mb-4 flex items-center gap-2 text-accent">
                <i class='bx bx-detail'></i> Overview
            </h2>
            <p class="text-lg text-text-secondary leading-relaxed">
                <?= nl2br(htmlspecialchars($m['description'])) ?>
            </p>
        </div>

        <!-- Trailer Section -->
        <?php if (!empty($m['trailer_url'])): ?>
            <div id="trailer-section" class="card bg-secondary-bg border border-border-color p-6 mb-8">
                <h2 class="text-3xl font-bold mb-6 text-accent">
                    <i class='bx bx-play-circle'></i> Trailer
                </h2>
                <div class="aspect-video rounded-lg overflow-hidden bg-base-300">
                    <?php
                    $trailerUrl = $m['trailer_url'];
                    if (strpos($trailerUrl, "youtube.com") !== false || strpos($trailerUrl, "youtu.be") !== false) {
                        preg_match('/(youtu\.be\/|v=)([^&]+)/', $trailerUrl, $matches);
                        $videoId = $matches[2] ?? '';
                        if ($videoId) {
                            echo '<iframe class="w-full h-full" src="https://www.youtube.com/embed/' . htmlspecialchars($videoId) . '" allowfullscreen></iframe>';
                        }
                    } else {
                        echo '<video controls class="w-full h-full"><source src="../' . htmlspecialchars($trailerUrl) . '" type="video/mp4"></video>';
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Reviews Section -->
        <div class="card bg-secondary-bg border border-border-color p-6">
            <h2 class="text-3xl font-bold mb-6 flex items-center gap-2 text-accent">
                <i class='bx bx-message-dots'></i> User Reviews
            </h2>
            
            <!-- Review Action -->
            <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'user'): ?>
                <?php if (!$rate->hasReviewed($_SESSION['user_id'], $m['id'])): ?>
                    <button class="btn btn-accent mb-6" onclick="review_modal.showModal()">
                        <i class='bx bx-star'></i> Leave a Review
                    </button>
                <?php else: ?>
                    <?php $userReview = $rate->getUserReview($_SESSION['user_id'], $m['id']); ?>
                    <div class="alert alert-success mb-6 border-2 border-accent">
                        <div class="flex justify-between items-start w-full">
                            <div>
                                <h3 class="font-bold text-accent">Your Review</h3>
                                <p class="mt-2"><?= htmlspecialchars($userReview['review']) ?></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="rating rating-sm">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <input type="radio" class="mask mask-star-2 <?= $i <= $userReview['rating'] ? 'bg-warning' : 'bg-base-300' ?>" disabled />
                                    <?php endfor; ?>
                                </div>
                                <span class="badge badge-warning"><?= $userReview['rating'] ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php elseif (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'admin'): ?>
                <div class="alert alert-info mb-6">
                    <span>Admins cannot leave reviews.</span>
                </div>
            <?php else: ?>
                <div class="alert alert-warning mb-6">
                    <span>Please <a href="loginRegister.php" class="link link-accent">login</a> to leave a review.</span>
                </div>
            <?php endif; ?>

            <!-- Review Cards -->
            <div class="space-y-4">
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $r): ?>
                        <div class="card bg-card-bg border border-border-color p-4 hover:shadow-lg transition-shadow">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="font-semibold text-accent"><?= htmlspecialchars($r['username']) ?></h4>
                                <div class="flex items-center gap-2">
                                    <div class="rating rating-sm">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <input type="radio" class="mask mask-star-2 <?= $i <= $r['rating'] ? 'bg-warning' : 'bg-base-300' ?>" disabled />
                                        <?php endfor; ?>
                                    </div>
                                    <span class="badge badge-warning"><?= $r['rating'] ?></span>
                                </div>
                            </div>
                            <p class="text-text-secondary"><?= htmlspecialchars($r['review']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="card bg-card-bg border border-border-color p-6 text-center">
                        <p class="text-text-muted">No reviews yet. Be the first to review this movie!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'user'): ?>
        <?php include __DIR__ . '/../partials/rateReviewModal.php'; ?>
    <?php endif; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function () {
            var $btn = $('#favorite-btn');
            if (!$btn.length) return;

            $btn.on('click', function () {
                $.post('../db/favoriteRequests.php',
                    { action: 'toggle', movie_id: $btn.data('movie-id') },
                    function (res) {
                        if (!res || !res.success) return alert((res && res.message) || 'Failed');
                        var fav = !!res.favorited;
                        $btn.toggleClass('btn-accent', fav).toggleClass('btn-outline', !fav);
                        $btn.find('i').attr('class', 'bx ' + (fav ? 'bxs-heart' : 'bx-heart'));
                        $btn.find('.fav-text').text(fav ? 'Favorited' : 'Add to Favorites');
                        $btn.find('.fav-count').text('(' + (res.count || 0) + ')');
                    },
                    'json'
                ).fail(function (xhr) {
                    alert(xhr.responseText || 'Request failed');
                });
            });
        });
    </script>
</body>
</html>