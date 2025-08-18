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
      background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
      color: #ffffff;
      min-height: 100vh;
    }
    
    header {
      background: rgba(0, 0, 0, 0.95);
      backdrop-filter: blur(12px);
      border-bottom: 2px solid #22c55e;
    }
    
    .movie-card {
      background: linear-gradient(145deg, #1a1a1a, #0f0f0f);
      border: 1px solid #2a2a2a;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      height: 100%;
    }
    
    .movie-card:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 20px 40px rgba(34, 197, 94, 0.2);
      border-color: #22c55e;
    }
    
    .movie-poster {
      height: 300px;
      object-fit: cover;
      transition: transform 0.3s ease;
    }
    
    .movie-card:hover .movie-poster {
      transform: scale(1.05);
    }
    
    .rating-badge {
      background: linear-gradient(45deg, #22c55e, #16a34a);
      color: white;
      font-weight: 600;
      font-size: 0.85rem;
    }
    
    .btn-primary {
      background: linear-gradient(45deg, #22c55e, #16a34a);
      border: none;
      font-weight: 500;
    }
    
    .btn-primary:hover {
      background: linear-gradient(45deg, #16a34a, #15803d);
      transform: translateY(-2px);
    }
    
    .text-primary {
      color: #22c55e !important;
    }
    
    .text-primary:hover {
      color: #16a34a !important;
    }
    
    .movie-title {
      color: #ffffff;
      font-weight: 600;
      font-size: 1.1rem;
      line-height: 1.3;
    }
    
    .movie-description {
      color: #a1a1aa;
      font-size: 0.9rem;
      line-height: 1.4;
    }
    
    .admin-actions {
      border-top: 1px solid #2a2a2a;
      padding-top: 0.75rem;
      margin-top: 0.75rem;
    }
    
    .movie-actions a, .movie-actions button {
      font-size: 0.85rem;
      padding: 0.25rem 0;
    }
    
    @media (max-width: 576px) {
      .movie-poster {
        height: 250px;
      }
      
      .movie-title {
        font-size: 1rem;
      }
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

  <main class="container-fluid px-3 px-md-4 py-4">
    <!-- Added search and filter section -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
          <div>
            <h2 class="h3 mb-1">Discover Movies</h2>
            <p class="text-secondary mb-0">Find your next favorite film</p>
          </div>
          <div class="d-flex gap-2">
            <div class="input-group" style="width: 300px;">
              <span class="input-group-text bg-dark border-secondary">
                <i class="bx bx-search text-secondary"></i>
              </span>
              <input type="text" class="form-control bg-dark border-secondary text-white" placeholder="Search movies...">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Improved movie grid layout -->
    <div class="row g-3 g-md-4">
      <?php foreach ($movies as $m): ?>
        <?php
        // Get average rating and total reviews
        $ratingInfo = $rate->getAverageRating($m['id']);
        $avgRating = $ratingInfo['avg'];
        $totalReviews = $ratingInfo['total'];
        $ratingDisplay = $avgRating ? number_format($avgRating, 1) : "N/A";
        ?>
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
          <div class="movie-card rounded-3 overflow-hidden">
            <div class="position-relative">
              <img src="<?= htmlspecialchars($m['poster_url']) ?>" 
                   alt="<?= htmlspecialchars($m['title']) ?>" 
                   class="w-100 movie-poster">
              
              <!-- Added rating badge overlay -->
              <?php if ($avgRating): ?>
                <div class="position-absolute top-0 end-0 m-2">
                  <span class="badge rating-badge px-2 py-1">
                    <i class="bx bx-star"></i> <?= $ratingDisplay ?>
                  </span>
                </div>
              <?php endif; ?>
            </div>

            <div class="p-3 d-flex flex-column" style="min-height: 180px;">
              <div class="flex-grow-1">
                <h5 class="movie-title mb-2">
                  <?= htmlspecialchars($m['title']) ?>
                  <small class="text-secondary">(<?= htmlspecialchars($m['release_year']) ?>)</small>
                </h5>
                
                <p class="movie-description mb-2">
                  <?= htmlspecialchars(substr($m['description'], 0, 80)) ?>
                  <?= strlen($m['description']) > 80 ? '...' : '' ?>
                </p>
                
                <!-- Improved review count display -->
                <?php if ($totalReviews > 0): ?>
                  <div class="text-secondary small mb-2">
                    <i class="bx bx-message-dots"></i>
                    <?= $totalReviews ?> review<?= $totalReviews > 1 ? 's' : '' ?>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Improved action buttons layout -->
              <div class="movie-actions d-flex flex-column gap-1">
                <a href="pages/viewMovie.php?id=<?= $m['id'] ?>" 
                   class="text-primary text-decoration-none d-flex align-items-center gap-2">
                  <i class="bx bx-info-circle"></i>
                  <span>View Details</span>
                </a>

                <a href="<?= htmlspecialchars($m['trailer_url']) ?>" 
                   target="_blank" 
                   class="text-primary text-decoration-none d-flex align-items-center gap-2">
                  <i class="bx bx-play-circle"></i>
                  <span>Watch Trailer</span>
                </a>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                  <div class="admin-actions">
                    <a href="pages/manageMovie.php?id=<?= $m['id'] ?>" 
                       class="text-secondary text-decoration-none d-flex align-items-center gap-2 mb-1">
                      <i class="bx bx-edit"></i>
                      <span>Edit Movie</span>
                    </a>
                    
                    <form action="db/movieRequests.php" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this movie?')" 
                          class="d-inline w-100">
                      <input type="hidden" name="action" value="delete" />
                      <input type="hidden" name="id" value="<?= $m['id'] ?>" />
                      <button type="submit" 
                              class="btn btn-link text-danger text-decoration-none p-0 d-flex align-items-center gap-2">
                        <i class="bx bx-trash"></i>
                        <span>Delete</span>
                      </button>
                    </form>
                  </div>
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
