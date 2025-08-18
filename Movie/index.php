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

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" />
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
    .movie-card {
      background: rgba(24, 24, 27, 0.6);
      border: 1px solid #2d2d2d;
      transition: all 0.3s ease;
    }
    .movie-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
    }
    .movie-poster {
      height: 260px;
      object-fit: cover;
    }
  </style>
</head>

<body class="antialiased">
  <!-- HEADER -->
  <header class="border-bottom px-3 px-md-5 py-3 d-flex align-items-center justify-content-between">
    <h1 class="h4 fw-semibold mb-0">Movie Recommender</h1>

    <div class="d-flex align-items-center gap-2 small">
      <?php if (isset($_SESSION['username'])): ?>
        <span class="text-secondary d-none d-sm-inline">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</span>

        <form action="db/authRequests.php" method="POST" class="d-inline-flex">
          <input type="hidden" name="action" value="logout" />
          <button type="submit" class="btn btn-sm btn-dark d-flex align-items-center gap-1">
            <i class="bx bx-log-out"></i>
            <span class="d-none d-sm-inline">Logout</span>
          </button>
        </form>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <a href="pages/manageMovie.php" class="btn btn-sm btn-primary d-flex align-items-center gap-1">
            <i class="bx bx-plus"></i>
            <span class="d-none d-sm-inline">Add Movie</span>
          </a>
        <?php endif; ?>
      <?php else: ?>
        <a href="pages/loginRegister.php" class="btn btn-sm btn-primary d-flex align-items-center gap-1">
          <i class="bx bx-log-in"></i>
          Login&nbsp;/&nbsp;Register
        </a>
      <?php endif; ?>
    </div>
  </header>

  <main class="container py-5">
    <div class="row g-4">
      <?php foreach ($movies as $m): ?>
        <?php
        // Get average rating and total reviews
        $ratingInfo = $rate->getAverageRating($m['id']);
        $avgRating = $ratingInfo['avg'];
        $totalReviews = $ratingInfo['total'];
        $ratingDisplay = $avgRating ? number_format($avgRating, 1) . " ⭐" : "No ratings yet";
        ?>
        <div class="col-sm-6 col-lg-4 col-xl-3">
          <div class="movie-card rounded-3 overflow-hidden h-100 d-flex flex-column">
            <img src="<?= htmlspecialchars($m['poster_url']) ?>" alt="<?= htmlspecialchars($m['title']) ?>" class="w-100 movie-poster">

            <div class="flex-grow-1 p-3 d-flex flex-column">
              <div>
                <h5 class="mb-1"><?= htmlspecialchars($m['title']) ?> (<?= htmlspecialchars($m['release_year']) ?>)</h5>
                <p class="text-secondary small mb-2">
                  <?= htmlspecialchars(substr($m['description'], 0, 100)) ?>
                  <?= strlen($m['description']) > 100 ? '...' : '' ?>
                </p>
                <div class="text-warning small mb-2">
                  <strong>Rating:</strong> <?= $ratingDisplay ?>
                  <?php if ($totalReviews > 0): ?>
                    (<?= $totalReviews ?> review<?= $totalReviews > 1 ? 's' : '' ?>)
                  <?php endif; ?>
                </div>
              </div>

              <div class="mt-auto d-flex flex-column gap-2 small">
                <a href="pages/viewMovie.php?id=<?= $m['id'] ?>" class="text-primary text-decoration-none d-flex align-items-center gap-1">
                  <i class="bx bx-info-circle"></i> View Details
                </a>

                <a href="<?= htmlspecialchars($m['trailer_url']) ?>" target="_blank" class="text-primary text-decoration-none d-flex align-items-center gap-1">
                  <i class="bx bx-play-circle"></i> Trailer
                </a>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                  <hr class="my-1 border-secondary">
                  <a href="pages/manageMovie.php?id=<?= $m['id'] ?>" class="text-secondary text-decoration-none d-flex align-items-center gap-1">
                    <i class="bx bx-pencil"></i> Edit
                  </a>
                  <form action="db/movieRequests.php" method="POST" onsubmit="return confirm('Delete this movie?')" class="d-inline">
                    <input type="hidden" name="action" value="delete" />
                    <input type="hidden" name="id" value="<?= $m['id'] ?>" />
                    <button type="submit" class="btn btn-link text-danger text-decoration-none p-0 d-flex align-items-center gap-1">
                      <i class="bx bx-trash"></i> Delete
                    </button>
                  </form>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>