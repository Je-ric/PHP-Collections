<?php 
session_start(); 
require_once __DIR__ . '/classes/Movie.php'; 
require_once __DIR__ . '/classes/RateReview.php';  

$movie = new Movie(); 
$movies = $movie->getAllMovies();  
$rate = new RateReview(); 
?> 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Movie Recommendation System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
  <style>
    body { font-family: "Oswald", sans-serif; }
  </style>
</head>
<body class="bg-gradient-to-br from-[#0a0a0a] to-[#1a1a1a] text-white min-h-screen antialiased">

  <!-- HEADER -->
  <header class="bg-black/90 backdrop-blur-md border-b border-green-500/50 px-6 md:px-10 py-4 flex items-center justify-between sticky top-0 z-50">
    <h1 class="text-xl md:text-2xl font-bold text-green-500">Movie Recommender</h1>

    <div class="flex items-center gap-3 text-sm">
      <?php if (isset($_SESSION['username'])): ?>
        <span class="hidden sm:inline text-gray-400">Hi, <?= htmlspecialchars($_SESSION['username']) ?> 👋</span>

        <form action="db/authRequests.php" method="POST" class="inline-flex">
          <input type="hidden" name="action" value="logout" />
          <button type="submit" class="flex items-center gap-1 px-3 py-1.5 rounded-md bg-neutral-800 hover:bg-neutral-700 text-gray-200 text-sm transition">
            <i class="bx bx-log-out"></i>
            <span class="hidden sm:inline">Logout</span>
          </button>
        </form>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <a href="pages/manageMovie.php" class="flex items-center gap-1 px-3 py-1.5 rounded-md bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 transition text-sm font-medium">
            <i class="bx bx-plus"></i>
            <span class="hidden sm:inline">Add Movie</span>
          </a>
        <?php endif; ?>
      <?php else: ?>
        <a href="pages/loginRegister.php" class="flex items-center gap-2 px-4 py-1.5 rounded-md bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 transition text-sm font-medium">
          <i class="bx bx-log-in"></i>
          Login&nbsp;/&nbsp;Register
        </a>
      <?php endif; ?>
    </div>
  </header>

  <!-- MAIN -->
  <main class="px-6 md:px-10 py-10">
    
    <!-- Search and filter -->
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-5">
      <div>
        <h2 class="text-3xl font-bold mb-1">Discover Movies</h2>
        <p class="text-gray-400 text-sm">Find your next favorite film from our curated collection</p>
      </div>
      <div class="flex items-center w-full md:w-80 bg-neutral-900 border border-neutral-700 rounded-lg overflow-hidden shadow-sm">
        <span class="px-3">
          <i class="bx bx-search text-gray-400 text-lg"></i>
        </span>
        <input type="text" placeholder="Search movies..." class="w-full bg-neutral-900 text-gray-200 placeholder-gray-500 focus:outline-none px-2 py-2 text-sm">
      </div>
    </div>

    <!-- Movie grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
      <?php foreach ($movies as $m): ?>
        <?php
          $ratingInfo = $rate->getAverageRating($m['id']);
          $avgRating = $ratingInfo['avg'];
          $totalReviews = $ratingInfo['total'];
          $ratingDisplay = $avgRating ? number_format($avgRating, 1) : "N/A";
        ?>
        <div class="group rounded-xl overflow-hidden bg-neutral-900 border border-neutral-800 hover:border-green-500/70 transition transform hover:-translate-y-2 hover:shadow-xl hover:shadow-green-500/20 flex flex-col">
          
          <!-- Poster -->
          <div class="relative">
            <a href="pages/viewMovie.php?id=<?= $m['id'] ?>">
              <img src="<?= htmlspecialchars($m['poster_url']) ?>" alt="<?= htmlspecialchars($m['title']) ?>" 
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

              <p class="text-gray-400 text-sm mb-3 leading-snug">
                <?= htmlspecialchars(substr($m['description'], 0, 90)) ?>
                <?= strlen($m['description']) > 90 ? '...' : '' ?>
              </p>

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
