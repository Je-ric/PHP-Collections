<?php
$script = $_SERVER['SCRIPT_NAME'] ?? '';
$fromPages = strpos($script, '/pages/') !== false;

$homeHref   = $fromPages ? '../index.php' : 'index.php';
$loginHref  = $fromPages ? '../pages/loginRegister.php' : 'pages/loginRegister.php';
$manageHref = $fromPages ? '../pages/manageMovie.php' : 'pages/manageMovie.php';
$authHref   = $fromPages ? '../db/authRequests.php' : 'db/authRequests.php';

$currentFile = basename($script); 
$showBackToMovies = in_array($currentFile, ['viewMovie.php', 'manageMovie.php']);
?>

<header class="bg-black/90 backdrop-blur-md border-b border-green-500/50 px-6 md:px-10 py-4 flex items-center justify-between sticky top-0 z-50">
  <!-- Left: Branding -->
  <h1 class="text-xl md:text-2xl font-bold text-green-500 flex items-center">
    <i class="bx bx-movie-play mr-2"></i>Movie Recommender
  </h1>

  <!-- Right: Actions -->
  <div class="flex items-center gap-3 text-sm">
    <?php if ($showBackToMovies): ?>
      <a href="<?= htmlspecialchars($homeHref) ?>" class="flex items-center gap-1 px-3 py-1.5 rounded-md border border-green-500/50 text-green-400 hover:bg-green-600/20 transition">
        <i class="bx bx-arrow-back"></i>
        <span class="hidden sm:inline">Back to Movies</span>
      </a>
    <?php endif; ?>

    <?php if (isset($_SESSION['username'])): ?>
      <span class="hidden sm:inline text-gray-400">Hi, <?= htmlspecialchars($_SESSION['username']) ?> 👋</span>

      <form action="<?= htmlspecialchars($authHref) ?>" method="POST" class="inline-flex">
        <input type="hidden" name="action" value="logout" />
        <button type="submit" class="flex items-center gap-1 px-3 py-1.5 rounded-md bg-neutral-800 hover:bg-neutral-700 text-gray-200 text-sm transition">
          <i class="bx bx-log-out"></i>
          <span class="hidden sm:inline">Logout</span>
        </button>
      </form>

      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="<?= htmlspecialchars($manageHref) ?>" class="flex items-center gap-1 px-3 py-1.5 rounded-md bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 transition text-sm font-medium">
          <i class="bx bx-plus"></i>
          <span class="hidden sm:inline">Add Movie</span>
        </a>
      <?php endif; ?>

    <?php else: ?>
      <a href="<?= htmlspecialchars($loginHref) ?>" class="flex items-center gap-2 px-4 py-1.5 rounded-md bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 transition text-sm font-medium">
        <i class="bx bx-log-in"></i>
        Login&nbsp;/&nbsp;Register
      </a>
    <?php endif; ?>
  </div>
</header>
