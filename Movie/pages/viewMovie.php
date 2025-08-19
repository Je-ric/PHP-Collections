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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
            color: #fff;
        }

        .cover_follow {
            position: relative;
            /* width: 100%; */
            height: 420px;
            background-size: cover;
            /* background-position: top; */
            /* background-position: center; */
            background-position: 50% 25%;
            overflow: hidden;
            opacity: .6;
        }

        .detail_page-infor {
            position: relative;
            max-width: 1160px;
            margin: 0 auto;
            padding: 2rem 1rem;
            z-index: 5;
            font-size: 14px;
            line-height: 1.6em;
        }

        .dp-i-c-poster {
            flex-shrink: 0;
            width: 240px;
            margin-right: 2rem;
        }

        .dp-i-c-poster .film-poster {
            width: 100%;
            padding-bottom: 148%;
            position: relative;
            overflow: hidden;
            border-radius: 0.5rem;
            box-shadow: 0 30px 30px rgba(0, 0, 0, .5);
        }

        .film-poster .film-poster-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dp-i-c-right {
            flex: 1;
            min-width: 0;
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
        
    <div class="mx-auto relative z-10">
        <?php if (!empty($m['background_url'])): ?>
            <div class="cover_follow mb-8 absolute top-0 left-0 w-full h-screen -z-10">
                <div class="absolute inset-0 bg-cover"
                    style="background-image: url('../<?= htmlspecialchars($m['background_url']) ?>');">
                </div>

                <div class="absolute inset-0 bg-gradient-to-t from-primary-bg via-primary-bg/80 to-transparent"></div>
            </div>
        <?php endif; ?>

        <div class="relative z-10 max-w-6xl mx-auto px-4 py-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
                <div class="dp-i-c-poster">
                    <div class="film-poster">
                        <img src="../<?= htmlspecialchars($m['poster_url']); ?>"
                            alt="<?= htmlspecialchars($m['title']); ?> poster"
                            class="film-poster-img">
                    </div>
                </div>
    
                <div class="lg:col-span-2 space-y-6">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold leading-tight">
                            <?= htmlspecialchars($m['title']) ?>
                            <span class="text-text-muted text-2xl md:text-3xl ml-2">
                                (<?= htmlspecialchars($m['release_year']) ?>)
                            </span>
                        </h1>

                        <div class="flex flex-wrap items-center gap-3 text-text-secondary text-base md:text-lg">
                            <span><?= htmlspecialchars($m['country_name']) ?></span>
                            <span class="text-accent">•</span>
                            <span><?= htmlspecialchars($m['language_name']) ?></span>
                        </div>
    
                    <div class="flex flex-wrap gap-2">
                            <?php if (!empty($genres)): ?>
                                <?php foreach ($genres as $genre): ?>
                                    <span class="px-3 py-1 bg-accent/20 text-accent rounded-full text-sm font-medium border border-accent/30">
                                        <?= htmlspecialchars($genre) ?>
                                    </span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-text-muted">No genres available</span>
                            <?php endif; ?>
                        </div>
    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="flex items-center gap-2 text-accent font-semibold text-lg mb-2">
                                    <i class='bx bx-video'></i> Director(s)
                                </h3>
                                <p class="text-text-secondary">
                                    <?= !empty($directors) ? implode(" • ", array_column($directors, 'name')) : "N/A" ?>
                                </p>
                            </div>

                            <div>
                                <h3 class="flex items-center gap-2 text-accent font-semibold text-lg mb-2">
                                    <i class='bx bxs-user'></i> Cast
                                </h3>
                                <p class="text-text-secondary">
                                    <?= !empty($actors) ? implode(" • ", array_column($actors, 'name')) : "N/A" ?>
                                </p>
                            </div>
                        </div>

                        <!-- Rating section with custom star display -->
                        <div class="bg-secondary-bg/80 backdrop-blur-sm rounded-lg p-6 border border-border-color">
                            <?php if ($avgRating): ?>
                                <div class="text-center space-y-3">
                                    <div class="star-rating justify-center">
                                        <?php
                                        $filledStars = floor($avgRating);
                                        $emptyStars = 5 - $filledStars;
                                        
                                        for ($i = 0; $i < $filledStars; $i++): ?>
                                            <i class="bx bxs-star star"></i>
                                        <?php endfor; ?>
                                        
                                        <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                                            <i class="bx bx-star star empty"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="text-xl font-bold text-accent">
                                        <?= number_format($avgRating, 1) ?>/5
                                    </div>
                                    <div class="text-sm text-text-muted">
                                        <?= $totalReviews ?> review<?= $totalReviews !== 1 ? 's' : '' ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-text-muted">No ratings yet</div>
                            <?php endif; ?>
                        </div>
                    </div>

    
                    <div class="flex flex-wrap gap-3">
                        <?php if (!empty($m['trailer_url'])): ?>
                            <a href="#trailer-section" class="btn btn-accent">
                                <i class='bx bx-play'></i> Watch Trailer
                            </a>
                        <?php endif; ?>
    
                        <?php if (!empty($_SESSION['user_id'])): ?>
                            <button id="favorite-btn" type="button"
                                class="btn <?= $isFavorited ? 'btn-accent' : 'btn-outline btn-accent' ?>"
                                data-movie-id="<?= (int)$m['id'] ?>"
                                data-favorited="<?= $isFavorited ? '1' : '0' ?>">
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
        </div>
    </div>



    <!-- <div class="max-w-6xl mx-auto px-4 py-10 relative z-10">
        <?php if (!empty($m['background_url'])): ?>
            <div class="cover_follow mb-8" style="background-image: url('../<?= htmlspecialchars($m['background_url']) ?>');"></div>
        <?php endif; ?>
        <?php if (!empty($m['background_url'])): ?>
            <div class="cover_follow mb-8 fixed top-0 left-0 w-full h-screen -z-10">
                <div class="absolute inset-0 bg-cover bg-center"
                    style="background-image: url('../<?= htmlspecialchars($m['background_url']) ?>');
                    
                    transform: scale(1.1);">
                </div>

                <div class="absolute inset-0 bg-gradient-to-t from-primary-bg via-primary-bg/80 to-transparent"></div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="dp-i-c-poster">
                <div class="film-poster">
                    <img src="../<?= htmlspecialchars($m['poster_url']); ?>"
                        alt="<?= htmlspecialchars($m['title']); ?> poster"
                        class="film-poster-img">
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <h1 class="text-4xl lg:text-5xl font-bold">
                    <?= htmlspecialchars($m['title']) ?>
                    <span class="text-text-muted text-3xl ml-2">
                        (<?= htmlspecialchars($m['release_year']) ?>)
                    </span>
                </h1>

                <div class="flex flex-wrap items-center gap-2 text-text-secondary text-lg">
                    <span><?= htmlspecialchars($m['country_name']) ?></span>
                    <span>•</span>
                    <span><?= htmlspecialchars($m['language_name']) ?></span>
                </div>

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

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <div class="card bg-card-bg border border-border-color p-4">
                            <div class="flex items-center gap-2 text-accent font-semibold mb-2">
                                <i class='bx bx-video'></i> Director(s)
                            </div>
                            <p class="text-text-secondary">
                                <?= !empty($directors) ? implode(" • ", array_column($directors, 'name')) : "N/A" ?>
                            </p>

                            <div class="flex items-center gap-2 text-accent font-semibold mb-2 mt-4">
                                <i class='bx bxs-user'></i> Cast
                            </div>
                            <p class="text-text-secondary">
                                <?= !empty($actors) ? implode(" • ", array_column($actors, 'name')) : "N/A" ?>
                            </p>
                        </div>
                    </div>

                    <div>
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

                <div class="flex flex-wrap gap-3">
                    <?php if (!empty($m['trailer_url'])): ?>
                        <a href="#trailer-section" class="btn btn-accent">
                            <i class='bx bx-play'></i> Watch Trailer
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['user_id'])): ?>
                        <button id="favorite-btn" type="button"
                            class="btn <?= $isFavorited ? 'btn-accent' : 'btn-outline btn-accent' ?>"
                            data-movie-id="<?= (int)$m['id'] ?>"
                            data-favorited="<?= $isFavorited ? '1' : '0' ?>">
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
    </div> -->


    <section class="relative z-10 max-w-7xl mx-auto px-4 pb-16 space-y-8">
        
        <div class="bg-secondary-bg/90 backdrop-blur-sm border border-border-color rounded-lg p-6 md:p-8">
            <h2 class="text-2xl md:text-3xl font-bold mb-6 flex items-center gap-3 text-accent">
                <i class='bx bx-detail'></i> Overview
            </h2>
            <p class="text-lg text-text-secondary leading-relaxed">
                <?= nl2br(htmlspecialchars($m['description'])) ?>
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <?php if (!empty($m['trailer_url'])): ?>
                <div id="trailer-section" class="bg-secondary-bg/90 backdrop-blur-sm border border-border-color rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-6 text-accent flex items-center gap-3">
                        <i class='bx bx-play-circle'></i> Trailer
                    </h2>
                    <!-- Smaller, more reasonable trailer size -->
                    <div class="aspect-video rounded-lg overflow-hidden bg-base-300 max-w-lg mx-auto">
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

            <div class="bg-secondary-bg/90 backdrop-blur-sm border border-border-color rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6 flex items-center gap-3 text-accent">
                    <i class='bx bx-message-dots'></i> User Reviews
                </h2>

                <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'user'): ?>
                    <?php if (!$rate->hasReviewed($_SESSION['user_id'], $m['id'])): ?>
                        <button class="btn btn-accent mb-6" onclick="review_modal.showModal()">
                            <i class='bx bx-star'></i> Leave a Review
                        </button>
                    <?php else: ?>
                        <?php $userReview = $rate->getUserReview($_SESSION['user_id'], $m['id']); ?>
                        <div class="bg-accent/10 border border-accent/30 rounded-lg p-4 mb-6">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="font-bold text-accent mb-2">Your Review</h3>
                                    <p class="text-text-secondary"><?= htmlspecialchars($userReview['review']) ?></p>
                                </div>
                                <div class="flex items-center gap-2 ml-4">
                                    <div class="star-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="bx <?= $i <= $userReview['rating'] ? 'bxs-star star' : 'bx-star star empty' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="text-accent font-semibold"><?= $userReview['rating'] ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php elseif (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'admin'): ?>
                    <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4 mb-6">
                        <span class="text-blue-400">Admins cannot leave reviews.</span>
                    </div>
                <?php else: ?>
                    <div class="bg-orange-500/10 border border-orange-500/30 rounded-lg p-4 mb-6">
                        <span class="text-orange-400">Please <a href="loginRegister.php" class="text-accent hover:underline">login</a> to leave a review.</span>
                    </div>
                <?php endif; ?>

                <div class="space-y-4 max-h-96 overflow-y-auto">
                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $r): ?>
                            <div class="bg-card-bg/50 border border-border-color rounded-lg p-4 hover:bg-card-bg/70 transition-colors">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="font-semibold text-accent"><?= htmlspecialchars($r['username']) ?></h4>
                                    <div class="flex items-center gap-2">
                                        <div class="star-rating">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="bx <?= $i <= $r['rating'] ? 'bxs-star star' : 'bx-star star empty' ?>" style="font-size: 1rem;"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="text-accent font-semibold text-sm"><?= $r['rating'] ?></span>
                                    </div>
                                </div>
                                <p class="text-text-secondary text-sm"><?= htmlspecialchars($r['review']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <p class="text-text-muted">No reviews yet. Be the first to review this movie!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'user'): ?>
        <?php include __DIR__ . '/../partials/rateReviewModal.php'; ?>
        <dialog id="review_modal" class="modal">
    <div class="modal-box bg-secondary-bg border border-border-color flex flex-col items-center">
        <!-- Close button -->
        <form method="dialog" class="self-end">
            <button class="btn btn-sm btn-circle btn-ghost">✕</button>
        </form>

        <!-- Modal title -->
        <h3 class="font-bold text-lg mb-4 text-accent text-center flex items-center gap-2">
            <i class='bx bx-star'></i> Review: <?= htmlspecialchars($m['title']) ?>
        </h3>

    <form id="review-form" action="../db/rateRequests.php" method="POST" class="w-full space-y-4">
            <input type="hidden" name="movie_id" value="<?= $m['id'] ?>">

            <div class="form-control">
                <label class="label text-center">
                    <span class="label-text">Rating (1-5 stars)</span>
                </label>
                <div class="rating rating-lg justify-center">
                    <!-- <?php for ($i = 1; $i <= 5; $i++): ?>
                        <input
                            type="radio"
                            name="rating"
                            value="<?= $i ?>"
                            class="mask mask-star-2 bg-base-200 hover:bg-warning checked:bg-warning"
                            required />
                    <?php endfor; ?> -->
                    <input type="radio" name="rating" value=1 class="mask mask-star bg-gray-200 hover:bg-yellow-500 checked:bg-warning" aria-label="1 star" />
                    <input type="radio" name="rating" value=2 class="mask mask-star bg-gray-200 hover:bg-yellow-500 checked:bg-warning" aria-label="2 star" />
                    <input type="radio" name="rating" value=3 class="mask mask-star bg-gray-200 hover:bg-yellow-500 checked:bg-warning" aria-label="3 star" />
                    <input type="radio" name="rating" value=4 class="mask mask-star bg-gray-200 hover:bg-yellow-500 checked:bg-warning" aria-label="4 star" />
                    <input type="radio" name="rating" value=5 class="mask mask-star bg-gray-200 hover:bg-yellow-500 checked:bg-warning" aria-label="5 star" />
                </div>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Your Review</span>
                </label>
                <textarea
                    name="review"
                    class="textarea textarea-bordered bg-card-bg border-border-color h-24"
                    placeholder="Share your thoughts about this movie..."
                    required></textarea>
            </div>

            <div class="modal-action justify-center gap-4">
                <button type="button" class="btn btn-outline" onclick="review_modal.close()">Cancel</button>
                <button type="submit" id="submit-review" class="btn btn-accent">Submit Review</button>
            </div>
        </form>
    </div>
</dialog>

<script>
$(function(){
    $('#review-form').on('submit', function(e){
        e.preventDefault(); 

        var $form = $(this);
        var $btn = $('#submit-review');
        $btn.prop('disabled', true).text('Submitting...');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json'
        })
        .done(function(res){
            console.log('Review response:', res);

            if(res && res.success){
                console.log('Review submitted successfully!');

                if(res.average){
                    $('#average-rating').text(res.average.avg.toFixed(1));
                    $('#total-reviews').text(res.average.total);
                }

                $form.find('textarea[name="review"]').val('');
                $form.find('input[name="rating"]').prop('checked', false);

                $('<p class="text-success mt-2">Thank you! Your review was submitted.</p>')
                    .appendTo($form)
                    .delay(3000)
                    .fadeOut(500, function(){ $(this).remove(); });

            } else {
                console.warn('Error submitting review:', (res && res.message) ? res.message : 'Unknown error');
            }
        })
    });
});


</script>
    <?php endif; ?>

    <script>
        $(function() {
            var $btn = $('#favorite-btn');
            if (!$btn.length) return;

            $btn.on('click', function() {
                $.post('../db/favoriteRequests.php', {
                        action: 'toggle',
                        movie_id: $btn.data('movie-id')
                    },
                    function(res) {
                        if (!res || !res.success) return alert((res && res.message) || 'Failed');
                        var fav = !!res.favorited;
                        $btn.toggleClass('btn-accent', fav).toggleClass('btn-outline', !fav);
                        $btn.find('i').attr('class', 'bx ' + (fav ? 'bxs-heart' : 'bx-heart'));
                        $btn.find('.fav-text').text(fav ? 'Favorited' : 'Add to Favorites');
                        $btn.find('.fav-count').text('(' + (res.count || 0) + ')');
                    },
                    'json'
                ).fail(function(xhr) {
                    alert(xhr.responseText || 'Request failed');
                });
            });
        });
    </script>
</body>

</html>