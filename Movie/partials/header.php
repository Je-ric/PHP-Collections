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

<header class="bg-primary-bg/90 backdrop-blur-md border-b border-accent/50 px-6 md:px-10 py-4 flex items-center justify-between sticky top-0 z-50">

    <h1 class="text-xl md:text-2xl font-bold text-accent flex items-center font-oswald">
        <i class="bx bx-movie-play mr-2"></i>Movie Recommender
    </h1>

    <div class="flex items-center gap-3 text-sm">
        <?php if ($showBackToMovies): ?>
            <a href="<?= htmlspecialchars($homeHref) ?>" class="flex items-center gap-1 px-3 py-1.5 rounded-md border border-accent/50 text-accent hover:bg-accent-hover/20 transition">
                <i class="bx bx-arrow-back"></i>
                <span class="hidden sm:inline">Back to Movies</span>
            </a>
        <?php endif; ?>

        <?php if (isset($_SESSION['username'])): ?>
            <span class="hidden sm:inline text-text-secondary">Hi, <?= htmlspecialchars($_SESSION['username']) ?> 👋</span>

            <form action="<?= htmlspecialchars($authHref) ?>" method="POST" class="inline-flex">
                <input type="hidden" name="action" value="logout" />
                <button type="submit" class="flex items-center gap-1 px-3 py-1.5 rounded-md bg-secondary-bg hover:bg-card-bg text-text-primary text-sm transition">
                    <i class="bx bx-log-out"></i>
                    <span class="hidden sm:inline">Logout</span>
                </button>
            </form>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="<?= htmlspecialchars($manageHref) ?>" class="flex items-center gap-1 px-3 py-1.5 rounded-md bg-accent hover:bg-accent-hover transition text-sm font-medium text-text-primary">
                    <i class="bx bx-plus"></i>
                    <span class="hidden sm:inline">Add Movie</span>
                </a>
            <?php endif; ?>

        <?php else: ?>
            <a href="<?= htmlspecialchars($loginHref) ?>" class="flex items-center gap-2 px-4 py-1.5 rounded-md bg-accent hover:bg-accent-hover transition text-sm font-medium text-text-primary">
                <i class="bx bx-log-in"></i>
                Login&nbsp;/&nbsp;Register
            </a>
        <?php endif; ?>
    </div>
</header>
