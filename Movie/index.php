<?php 
session_start(); 
require_once __DIR__ . '/classes/Movie.php'; 
require_once __DIR__ . '/classes/RateReview.php';  
require_once __DIR__ . '/classes/Recommend.php';  

$movie = new Movie(); 
$movies = $movie->getAllMovies();  
$rate = new RateReview(); 
$rec  = new Recommend();

// Search
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$searchResults = $q !== '' ? $rec->searchByTitle($q, 24) : [];

// Recommendations
$trending = $rec->getTrending(12);
$latest   = $rec->getLatest(12);

$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$recByGenres    = $userId ? $rec->basedOnFavoriteGenres($userId, 12) : [];
$recByCountries = $userId ? $rec->basedOnFavoriteCountries($userId, 12) : [];
$recByLanguages = $userId ? $rec->basedOnFavoriteLanguages($userId, 12) : [];
?> 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Movie Recommendation System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.24/dist/full.min.css" rel="stylesheet" type="text/css" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
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
          }
        }
      }
    }
  </script>
  <style>
    body { font-family: "Oswald", sans-serif; }
  </style>
</head>
<body class="bg-gradient-to-br from-[#0a0a0a] to-[#1a1a1a] text-white min-h-screen antialiased">

<?php include __DIR__ . '/partials/header.php'; ?>

  <main class="px-6 md:px-10 py-10">
    
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-5">
      <div>
        <h2 class="text-3xl font-bold mb-1">Discover Movies</h2>
        <p class="text-gray-400 text-sm">Find your next favorite film from our curated collection</p>
      </div>
      <form class="flex items-center w-full md:w-96 bg-neutral-900 border border-neutral-700 rounded-lg overflow-hidden shadow-sm" method="GET" action="index.php">
        <span class="px-3">
          <i class="bx bx-search text-gray-400 text-lg"></i>
        </span>
        <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search movies by title..." class="w-full bg-neutral-900 text-gray-200 placeholder-gray-500 focus:outline-none px-2 py-2 text-sm">
        <button type="submit" class="px-3 py-2 text-sm text-green-400 hover:text-white">Search</button>
      </form>
    </div>

    <?php if ($q !== ''): ?>
      <section class="mb-12">
        <div class="flex items-end justify-between mb-4">
          <h3 class="text-2xl font-semibold">Search results for "<?= htmlspecialchars($q) ?>"</h3>
          <span class="text-gray-400 text-sm"><?= count($searchResults) ?> result<?= count($searchResults) === 1 ? '' : 's' ?></span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
          <?php foreach ($searchResults as $m): ?>
            <?php
              $avgRating = isset($m['avg_rating']) ? (float)$m['avg_rating'] : ($rate->getAverageRating($m['id'])['avg'] ?? null);
              $totalReviews = isset($m['total_reviews']) ? (int)$m['total_reviews'] : ($rate->getAverageRating($m['id'])['total'] ?? 0);
              $ratingDisplay = $avgRating ? number_format($avgRating, 1) : "N/A";
              $poster = $m['poster_url'] ?: 'https://placehold.co/300x450?text=No+Poster';
            ?>
            <div class="group rounded-xl overflow-hidden bg-neutral-900 border border-neutral-800 hover:border-green-500/70 transition transform hover:-translate-y-2 hover:shadow-xl hover:shadow-green-500/20 flex flex-col">
              <div class="relative">
                <a href="pages/viewMovie.php?id=<?= $m['id'] ?>">
                  <img src="<?= htmlspecialchars($poster) ?>" alt="<?= htmlspecialchars($m['title']) ?>" class="w-full h-72 object-cover transition-transform duration-300 group-hover:scale-105">
                </a>
                <?php if ($avgRating): ?>
                  <div class="absolute top-2 right-2">
                    <span class="bg-green-600/90 text-white font-semibold text-xs px-2 py-1 rounded-md flex items-center gap-1">
                      <i class="bx bxs-star text-yellow-300"></i> <?= $ratingDisplay ?>
                    </span>
                  </div>
                <?php endif; ?>
              </div>
              <div class="p-4 flex flex-col flex-grow">
                <div class="flex-grow">
                  <h5 class="font-semibold text-base mb-1 text-white leading-tight">
                    <?= htmlspecialchars($m['title']) ?>
                    <small class="text-gray-400 font-normal">(<?= htmlspecialchars($m['release_year']) ?>)</small>
                  </h5>
                  <?php if ($totalReviews > 0): ?>
                    <div class="text-gray-500 text-xs mb-3 flex items-center gap-1">
                      <i class="bx bx-message-dots"></i>
                      <?= $totalReviews ?> review<?= $totalReviews > 1 ? 's' : '' ?>
                    </div>
                  <?php endif; ?>
                </div>
                <div class="flex flex-col gap-2 mt-auto">
                  <a href="<?= htmlspecialchars($m['trailer_url']) ?>" target="_blank" class="text-green-500 hover:text-green-400 flex items-center gap-2 text-sm font-medium">
                    <i class="bx bx-play-circle"></i> Watch Trailer
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

    <!-- Trending -->
    <section class="mb-12">
      <div class="flex items-end justify-between mb-4">
        <h3 class="text-2xl font-semibold">Trending now</h3>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
        <?php foreach ($trending as $m): ?>
          <?php
            $avgRating = isset($m['avg_rating']) ? (float)$m['avg_rating'] : ($rate->getAverageRating($m['id'])['avg'] ?? null);
            $totalReviews = isset($m['total_reviews']) ? (int)$m['total_reviews'] : ($rate->getAverageRating($m['id'])['total'] ?? 0);
            $ratingDisplay = $avgRating ? number_format($avgRating, 1) : "N/A";
            $poster = $m['poster_url'] ?: 'https://placehold.co/300x450?text=No+Poster';
          ?>
          <div class="group rounded-xl overflow-hidden bg-neutral-900 border border-neutral-800 hover:border-green-500/70 transition transform hover:-translate-y-2 hover:shadow-xl hover:shadow-green-500/20 flex flex-col">
            <div class="relative">
              <a href="pages/viewMovie.php?id=<?= $m['id'] ?>">
                <img src="<?= htmlspecialchars($poster) ?>" alt="<?= htmlspecialchars($m['title']) ?>" class="w-full h-72 object-cover transition-transform duration-300 group-hover:scale-105">
              </a>
              <?php if ($avgRating): ?>
                <div class="absolute top-2 right-2">
                  <span class="bg-green-600/90 text-white font-semibold text-xs px-2 py-1 rounded-md flex items-center gap-1">
                    <i class="bx bxs-star text-yellow-300"></i> <?= $ratingDisplay ?>
                  </span>
                </div>
              <?php endif; ?>
            </div>
            <div class="p-4 flex flex-col flex-grow">
              <div class="flex-grow">
                <h5 class="font-semibold text-base mb-1 text-white leading-tight">
                  <?= htmlspecialchars($m['title']) ?>
                  <small class="text-gray-400 font-normal">(<?= htmlspecialchars($m['release_year']) ?>)</small>
                </h5>
                <?php if ($totalReviews > 0): ?>
                  <div class="text-gray-500 text-xs mb-3 flex items-center gap-1">
                    <i class="bx bx-message-dots"></i>
                    <?= $totalReviews ?> review<?= $totalReviews > 1 ? 's' : '' ?>
                  </div>
                <?php endif; ?>
              </div>
              <div class="flex flex-col gap-2 mt-auto">
                <a href="<?= htmlspecialchars($m['trailer_url']) ?>" target="_blank" class="text-green-500 hover:text-green-400 flex items-center gap-2 text-sm font-medium">
                  <i class="bx bx-play-circle"></i> Watch Trailer
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- Latest Releases -->
    <section class="mb-12">
      <div class="flex items-end justify-between mb-4">
        <h3 class="text-2xl font-semibold">Latest releases</h3>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
        <?php foreach ($latest as $m): ?>
          <?php
            $avgRating = isset($m['avg_rating']) ? (float)$m['avg_rating'] : ($rate->getAverageRating($m['id'])['avg'] ?? null);
            $totalReviews = isset($m['total_reviews']) ? (int)$m['total_reviews'] : ($rate->getAverageRating($m['id'])['total'] ?? 0);
            $ratingDisplay = $avgRating ? number_format($avgRating, 1) : "N/A";
            $poster = $m['poster_url'] ?: 'https://placehold.co/300x450?text=No+Poster';
          ?>
          <div class="group rounded-xl overflow-hidden bg-neutral-900 border border-neutral-800 hover:border-green-500/70 transition transform hover:-translate-y-2 hover:shadow-xl hover:shadow-green-500/20 flex flex-col">
            <div class="relative">
              <a href="pages/viewMovie.php?id=<?= $m['id'] ?>">
                <img src="<?= htmlspecialchars($poster) ?>" alt="<?= htmlspecialchars($m['title']) ?>" class="w-full h-72 object-cover transition-transform duration-300 group-hover:scale-105">
              </a>
              <?php if ($avgRating): ?>
                <div class="absolute top-2 right-2">
                  <span class="bg-green-600/90 text-white font-semibold text-xs px-2 py-1 rounded-md flex items-center gap-1">
                    <i class="bx bxs-star text-yellow-300"></i> <?= $ratingDisplay ?>
                  </span>
                </div>
              <?php endif; ?>
            </div>
            <div class="p-4 flex flex-col flex-grow">
              <div class="flex-grow">
                <h5 class="font-semibold text-base mb-1 text-white leading-tight">
                  <?= htmlspecialchars($m['title']) ?>
                  <small class="text-gray-400 font-normal">(<?= htmlspecialchars($m['release_year']) ?>)</small>
                </h5>
                <?php if ($totalReviews > 0): ?>
                  <div class="text-gray-500 text-xs mb-3 flex items-center gap-1">
                    <i class="bx bx-message-dots"></i>
                    <?= $totalReviews ?> review<?= $totalReviews > 1 ? 's' : '' ?>
                  </div>
                <?php endif; ?>
              </div>
              <div class="flex flex-col gap-2 mt-auto">
                <a href="<?= htmlspecialchars($m['trailer_url']) ?>" target="_blank" class="text-green-500 hover:text-green-400 flex items-center gap-2 text-sm font-medium">
                  <i class="bx bx-play-circle"></i> Watch Trailer
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <?php if ($userId): ?>
      <?php if (!empty($recByGenres)): ?>
      <section class="mb-12">
        <div class="flex items-end justify-between mb-4">
          <h3 class="text-2xl font-semibold">Because you like these genres</h3>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
          <?php foreach ($recByGenres as $m): ?>
            <?php
              $avgRating = isset($m['avg_rating']) ? (float)$m['avg_rating'] : ($rate->getAverageRating($m['id'])['avg'] ?? null);
              $totalReviews = isset($m['total_reviews']) ? (int)$m['total_reviews'] : ($rate->getAverageRating($m['id'])['total'] ?? 0);
              $ratingDisplay = $avgRating ? number_format($avgRating, 1) : "N/A";
              $poster = $m['poster_url'] ?: 'https://placehold.co/300x450?text=No+Poster';
            ?>
            <div class="group rounded-xl overflow-hidden bg-neutral-900 border border-neutral-800 hover:border-green-500/70 transition transform hover:-translate-y-2 hover:shadow-xl hover:shadow-green-500/20 flex flex-col">
              <div class="relative">
                <a href="pages/viewMovie.php?id=<?= $m['id'] ?>">
                  <img src="<?= htmlspecialchars($poster) ?>" alt="<?= htmlspecialchars($m['title']) ?>" class="w-full h-72 object-cover transition-transform duration-300 group-hover:scale-105">
                </a>
                <?php if ($avgRating): ?>
                  <div class="absolute top-2 right-2">
                    <span class="bg-green-600/90 text-white font-semibold text-xs px-2 py-1 rounded-md flex items-center gap-1">
                      <i class="bx bxs-star text-yellow-300"></i> <?= $ratingDisplay ?>
                    </span>
                  </div>
                <?php endif; ?>
              </div>
              <div class="p-4 flex flex-col flex-grow">
                <div class="flex-grow">
                  <h5 class="font-semibold text-base mb-1 text-white leading-tight">
                    <?= htmlspecialchars($m['title']) ?>
                    <small class="text-gray-400 font-normal">(<?= htmlspecialchars($m['release_year']) ?>)</small>
                  </h5>
                </div>
                <div class="flex flex-col gap-2 mt-auto">
                  <a href="<?= htmlspecialchars($m['trailer_url']) ?>" target="_blank" class="text-green-500 hover:text-green-400 flex items-center gap-2 text-sm font-medium">
                    <i class="bx bx-play-circle"></i> Watch Trailer
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <?php if (!empty($recByCountries)): ?>
      <section class="mb-12">
        <div class="flex items-end justify-between mb-4">
          <h3 class="text-2xl font-semibold">From countries you favored</h3>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
          <?php foreach ($recByCountries as $m): ?>
            <?php
              $avgRating = isset($m['avg_rating']) ? (float)$m['avg_rating'] : ($rate->getAverageRating($m['id'])['avg'] ?? null);
              $poster = $m['poster_url'] ?: 'https://placehold.co/300x450?text=No+Poster';
            ?>
            <div class="group rounded-xl overflow-hidden bg-neutral-900 border border-neutral-800 hover:border-green-500/70 transition transform hover:-translate-y-2 hover:shadow-xl hover:shadow-green-500/20 flex flex-col">
              <div class="relative">
                <a href="pages/viewMovie.php?id=<?= $m['id'] ?>">
                  <img src="<?= htmlspecialchars($poster) ?>" alt="<?= htmlspecialchars($m['title']) ?>" class="w-full h-72 object-cover transition-transform duration-300 group-hover:scale-105">
                </a>
                <?php if ($avgRating): ?>
                  <div class="absolute top-2 right-2">
                    <span class="bg-green-600/90 text-white font-semibold text-xs px-2 py-1 rounded-md flex items-center gap-1">
                      <i class="bx bxs-star text-yellow-300"></i> <?= number_format($avgRating, 1) ?>
                    </span>
                  </div>
                <?php endif; ?>
              </div>
              <div class="p-4 flex flex-col flex-grow">
                <div class="flex-grow">
                  <h5 class="font-semibold text-base mb-1 text-white leading-tight">
                    <?= htmlspecialchars($m['title']) ?>
                    <small class="text-gray-400 font-normal">(<?= htmlspecialchars($m['release_year']) ?>)</small>
                  </h5>
                </div>
                <div class="flex flex-col gap-2 mt-auto">
                  <a href="<?= htmlspecialchars($m['trailer_url']) ?>" target="_blank" class="text-green-500 hover:text-green-400 flex items-center gap-2 text-sm font-medium">
                    <i class="bx bx-play-circle"></i> Watch Trailer
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <?php if (!empty($recByLanguages)): ?>
      <section class="mb-12">
        <div class="flex items-end justify-between mb-4">
          <h3 class="text-2xl font-semibold">In languages you enjoy</h3>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
          <?php foreach ($recByLanguages as $m): ?>
            <?php
              $avgRating = isset($m['avg_rating']) ? (float)$m['avg_rating'] : ($rate->getAverageRating($m['id'])['avg'] ?? null);
              $poster = $m['poster_url'] ?: 'https://placehold.co/300x450?text=No+Poster';
            ?>
            <div class="group rounded-xl overflow-hidden bg-neutral-900 border border-neutral-800 hover:border-green-500/70 transition transform hover:-translate-y-2 hover:shadow-xl hover:shadow-green-500/20 flex flex-col">
              <div class="relative">
                <a href="pages/viewMovie.php?id=<?= $m['id'] ?>">
                  <img src="<?= htmlspecialchars($poster) ?>" alt="<?= htmlspecialchars($m['title']) ?>" class="w-full h-72 object-cover transition-transform duration-300 group-hover:scale-105">
                </a>
                <?php if ($avgRating): ?>
                  <div class="absolute top-2 right-2">
                    <span class="bg-green-600/90 text-white font-semibold text-xs px-2 py-1 rounded-md flex items-center gap-1">
                      <i class="bx bxs-star text-yellow-300"></i> <?= number_format($avgRating, 1) ?>
                    </span>
                  </div>
                <?php endif; ?>
              </div>
              <div class="p-4 flex flex-col flex-grow">
                <div class="flex-grow">
                  <h5 class="font-semibold text-base mb-1 text-white leading-tight">
                    <?= htmlspecialchars($m['title']) ?>
                    <small class="text-gray-400 font-normal">(<?= htmlspecialchars($m['release_year']) ?>)</small>
                  </h5>
                </div>
                <div class="flex flex-col gap-2 mt-auto">
                  <a href="<?= htmlspecialchars($m['trailer_url']) ?>" target="_blank" class="text-green-500 hover:text-green-400 flex items-center gap-2 text-sm font-medium">
                    <i class="bx bx-play-circle"></i> Watch Trailer
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>
    <?php endif; ?>

    <!-- Movie grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
      <?php foreach ($movies as $m): ?>
        <?php
          $ratingInfo = $rate->getAverageRating($m['id']);
          $avgRating = $ratingInfo['avg'];
          $totalReviews = $ratingInfo['total'];
          $ratingDisplay = $avgRating ? number_format($avgRating, 1) : "N/A";
          $poster = $m['poster_url'] ?: 'https://placehold.co/300x450?text=No+Poster';
        ?>
        <div class="group rounded-xl overflow-hidden bg-neutral-900 border border-neutral-800 hover:border-green-500/70 transition transform hover:-translate-y-2 hover:shadow-xl hover:shadow-green-500/20 flex flex-col">
          
          <!-- Poster -->
          <div class="relative">
            <a href="pages/viewMovie.php?id=<?= $m['id'] ?>">
              <img src="<?= htmlspecialchars($poster) ?>" alt="<?= htmlspecialchars($m['title']) ?>" 
                   class="w-full h-72 object-cover transition-transform duration-300 group-hover:scale-105">
            </a>
            <?php if ($avgRating): ?>
              <div class="absolute top-2 right-2">
                <span class="bg-green-600/90 text-white font-semibold text-xs px-2 py-1 rounded-md flex items-center gap-1">
                  <i class="bx bxs-star text-yellow-300"></i> <?= $ratingDisplay ?>
                </span>
              </div>
            <?php endif; ?>
          </div>

          <!-- Info -->
          <div class="p-4 flex flex-col flex-grow">
            <div class="flex-grow">
              <h5 class="font-semibold text-base mb-1 text-white leading-tight">
                <?= htmlspecialchars($m['title']) ?>
                <small class="text-gray-400 font-normal">(<?= htmlspecialchars($m['release_year']) ?>)</small>
              </h5>

              <!-- <p class="text-gray-400 text-sm mb-3 leading-snug">
                <?= htmlspecialchars(substr($m['description'], 0, 90)) ?>
                <?= strlen($m['description']) > 90 ? '...' : '' ?>
              </p> -->

              <?php if ($totalReviews > 0): ?>
                <div class="text-gray-500 text-xs mb-3 flex items-center gap-1">
                  <i class="bx bx-message-dots"></i>
                  <?= $totalReviews ?> review<?= $totalReviews > 1 ? 's' : '' ?>
                </div>
              <?php endif; ?>
            </div>

            <div class="flex flex-col gap-2 mt-auto">
              <!-- <a href="pages/viewMovie.php?id=<?= $m['id'] ?>" class="text-green-500 hover:text-green-400 flex items-center gap-2 text-sm font-medium">
                <i class="bx bx-info-circle"></i> View Details
              </a> -->
              <a href="<?= htmlspecialchars($m['trailer_url']) ?>" target="_blank" class="text-green-500 hover:text-green-400 flex items-center gap-2 text-sm font-medium">
                <i class="bx bx-play-circle"></i> Watch Trailer
              </a>

              <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <div class="pt-3 mt-2 border-t border-neutral-800 flex flex-col gap-1">
                  <a href="pages/manageMovie.php?id=<?= $m['id'] ?>" class="text-gray-400 hover:text-white flex items-center gap-2 text-sm">
                    <i class="bx bx-edit"></i> Edit Movie
                  </a>
                  <form action="db/movieRequests.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this movie?')">
                    <input type="hidden" name="action" value="delete" />
                    <input type="hidden" name="id" value="<?= $m['id'] ?>" />
                    <button type="submit" class="text-red-500 hover:text-red-400 flex items-center gap-2 text-sm w-full text-left">
                      <i class="bx bx-trash"></i> Delete
                    </button>
                  </form>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
</body>
</html>
