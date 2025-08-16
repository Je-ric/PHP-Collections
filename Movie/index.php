<?php
session_start();
require_once __DIR__ . '/functions/Movie.php';
require_once __DIR__ . '/functions/RateReview.php';

$movie = new Movie();
$movies = $movie->getAllMovies();

$rate = new RateReview();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Movie Recommendation System</title>

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="font-[Inter] bg-gray-950 text-gray-100 antialiased selection:bg-indigo-600 selection:text-white">
  <!-- HEADER -->
  <header class="border-b border-gray-800 bg-gray-900/60 backdrop-blur-md px-4 md:px-8 py-4 flex items-center justify-between">
    <h1 class="text-xl sm:text-2xl font-semibold tracking-tight">Movie Recommender</h1>

    <div class="flex items-center gap-4 text-sm">
      <?php if (isset($_SESSION['username'])): ?>
        <span class="text-gray-400 hidden sm:inline">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</span>

        <!-- Logout -->
        <form action="db/authRequests.php" method="POST" class="inline-flex">
          <input type="hidden" name="action" value="logout" />
          <button type="submit" class="inline-flex items-center gap-1 px-3 py-2 rounded-md bg-gray-800 hover:bg-gray-700 transition-colors">
            <i data-lucide="log-out" class="w-4 h-4 stroke-[1.5]"></i>
            <span class="hidden sm:inline">Logout</span>
          </button>
        </form>

        <!-- Add Movie -->
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <a href="pages/manageMovie.php" class="inline-flex items-center gap-1 px-3 py-2 rounded-md bg-indigo-600 hover:bg-indigo-700 transition-colors">
            <i data-lucide="plus" class="w-4 h-4 stroke-[1.5]"></i>
            <span class="hidden sm:inline">Add Movie</span>
          </a>
        <?php endif; ?>
      <?php else: ?>
        <a href="pages/loginRegister.php" class="inline-flex items-center gap-1 px-3 py-2 rounded-md bg-indigo-600 hover:bg-indigo-700 transition-colors">
          <i data-lucide="log-in" class="w-4 h-4 stroke-[1.5]"></i>
          Login&nbsp;/&nbsp;Register
        </a>
      <?php endif; ?>
    </div>
  </header>

  <!-- MOVIES GRID -->
  <main class="max-w-7xl mx-auto px-4 md:px-8 py-10">
    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      <?php foreach ($movies as $m): ?>
        <div class="flex flex-col rounded-xl bg-gray-900/60 border border-gray-800 shadow-lg overflow-hidden transition hover:shadow-xl hover:-translate-y-1">
          <img src="<?= htmlspecialchars($m['poster_url']) ?>" alt="<?= htmlspecialchars($m['title']) ?>" class="w-full h-64 object-cover" />

          <div class="flex flex-col flex-1 p-5 space-y-4">
            <div>
              <h3 class="text-base font-semibold tracking-tight"><?= htmlspecialchars($m['title']) ?> (<?= htmlspecialchars($m['release_year']) ?>)</h3>
              <p class="mt-1 text-xs text-gray-400 leading-relaxed">
                <?= htmlspecialchars(substr($m['description'], 0, 100)) ?>
                <?= strlen($m['description']) > 100 ? '...' : '' ?>
              </p>
            </div>

            <div class="mt-auto flex flex-col gap-3 text-sm">
              <a href="<?= htmlspecialchars($m['trailer_url']) ?>" target="_blank" class="inline-flex items-center gap-1 text-indigo-400 hover:text-indigo-300 transition-colors">
                <i data-lucide="play-circle" class="w-4 h-4 stroke-[1.5]"></i> Trailer
              </a>

              <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (!$rate->hasReviewed($_SESSION['user_id'], $m['id'])): ?>
                  <form action="db/rateRequests.php" method="POST" class="mt-2">
                    <input type="hidden" name="movie_id" value="<?= $m['id'] ?>">
                    <label>Rating:</label>
                    <select name="rating" class="mt-1 mb-2">
                      <option value="">Select Rating</option>
                      <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?> ⭐</option>
                      <?php endfor; ?>
                    </select>
                    <br>
                    <label>Review:</label>
                    <textarea name="review" rows="2" placeholder="Write your review..."></textarea>
                    <br>
                    <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-indigo-600 hover:bg-indigo-700 transition-colors text-sm">Submit</button>
                  </form>
                <?php else: ?>
                  <?php
                  $userReview = $rate->getUserReview($_SESSION['user_id'], $m['id']);
                  ?>
                  <div class="mt-2 text-gray-400 text-sm">
                    <p><strong>Your Rating:</strong> <?= $userReview['rating'] ?> ⭐</p>
                    <p><strong>Your Review:</strong> <?= htmlspecialchars($userReview['review']) ?></p>
                    <p class="mt-1 text-xs italic">(You cannot review this movie again)</p>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
              <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?> 
                <span class="h-4 w-px bg-gray-700"></span> <!-- Edit --> 
                <a href="pages/manageMovie.php?id=<?= $m['id'] ?>" class="inline-flex items-center gap-1 text-gray-400 hover:text-gray-200 transition-colors"> 
                  <i data-lucide="pencil" class="w-4 h-4 stroke-[1.5]"></i> Edit 
                </a> 
                <!-- Delete -->
                <form action="db/movieRequests.php" method="POST" onsubmit="return confirm('Delete this movie?')"> 
                  <input type="hidden" name="action" value="delete" /> 
                  <input type="hidden" name="id" value="<?= $m['id'] ?>" /> 
                  <button type="submit" class="inline-flex items-center gap-1 text-red-500 hover:text-red-400 transition-colors"> 
                    <i data-lucide="trash" class="w-4 h-4 stroke-[1.5]"></i> Delete 
                  </button> 
                </form> 
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <script>
    lucide.createIcons();
  </script>
</body>

</html>