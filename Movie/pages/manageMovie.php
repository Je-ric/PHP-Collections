<?php
require_once __DIR__ . '/../functions/Movie.php';
$movie = new Movie();

$editing = false;
$movieData = [
  'id' => '',
  'title' => '',
  'description' => '',
  'release_year' => '',
  'poster_url' => '',
  'trailer_url' => ''
];

$allGenres = $movie->getAllGenres();
$selectedGenres = [];

// if may id, edit mode
if (!empty($_GET['id'])) {
  $editing = true;
  $movieData = $movie->getMovieById($_GET['id']);
  $movieId = $_GET['id'];
  $selectedGenres = $movie->getGenresByMovie($movieId, 'id');
  if (!$movieData) { // invalid id
    header("Location: ../index.php");
    exit;
  }
}

$actionText = $editing ? "Update Movie" : "Add Movie";
$submitAction = $editing ? "update" : "add";

// ==================================================
// References:
// https://stackoverflow.com/questions/19758954/get-data-from-json-file-with-php

$countryJsonContent = file_get_contents(__DIR__ . '/../JSON/country.json'); // Load the JSON content
$languageJsonContent = file_get_contents(__DIR__ . '/../JSON/language.json');

// Decode JSON into PHP array
$countries = json_decode($countryJsonContent, true); // true = associative array
$languages = json_decode($languageJsonContent, true);

// foreach ($countries as $country) {
//     echo $country['name'] . ' (' . $country['code'] . ')<br>';
// }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?= $actionText ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #0f0f0f;
      color: #f8f9fa;
    }

    header,
    footer {
      background: rgba(24, 24, 27, 0.6);
      backdrop-filter: blur(8px);
    }

    .form-control,
    .form-select {
      background-color: #1f1f1f;
      border: 1px solid #333;
      color: #f8f9fa;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: #ffc107;
      box-shadow: 0 0 0 .2rem rgba(255, 193, 7, .25);
    }

    .form-check-input:checked {
      background-color: #ffc107;
      border-color: #ffc107;
    }

    .form-check-label {
      color: #f8f9fa;
    }

    .card-dark {
      background: rgba(24, 24, 27, 0.6);
      border: 1px solid #2d2d2d;
    }
  </style>
</head>

<body>
  <!-- HEADER -->
  <header class="border-bottom px-3 px-md-5 py-3">
    <h1 class="h3 fw-semibold text-warning mb-0"><?= $actionText ?></h1>
  </header>

  <!-- MAIN -->
  <main class="container py-5">
    <div class="card card-dark shadow-lg rounded-3">
      <div class="card-body p-4 p-md-5">
        <form action="../db/movieRequests.php" method="POST" enctype="multipart/form-data" class="row g-4">
          <input type="hidden" name="action" value="<?= $submitAction ?>" />
          <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?= $movieData['id'] ?>" />
          <?php endif; ?>

          <!-- Title -->
          <div class="col-md-6">
            <label class="form-label text-warning">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" value="<?= htmlspecialchars($movieData['title']) ?>" required class="form-control">
          </div>

          <!-- Release Year -->
          <div class="col-md-6">
            <label class="form-label text-warning">Release Year</label>
            <input type="number" name="release_year" value="<?= $movieData['release_year'] ?>" min="1960" max="<?= date('Y') ?>" class="form-control">
          </div>

          <!-- Country -->
          <div class="col-md-6">
            <label class="form-label text-warning">Country <span class="text-danger">*</span></label>
            <select name="countryName" required class="form-select">
              <option value="">-- Select Country --</option>
              <?php foreach ($countries as $country): ?>
                <option value="<?= htmlspecialchars($country['name']) ?>" <?= ($editing && $movieData['country_name'] === $country['name']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($country['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Language -->
          <div class="col-md-6">
            <label class="form-label text-warning">Language <span class="text-danger">*</span></label>
            <select name="languageName" required class="form-select">
              <option value="">-- Select Language --</option>
              <?php foreach ($languages as $language): ?>
                <option value="<?= htmlspecialchars($language['name']) ?>" <?= ($editing && $movieData['language_name'] === $language['name']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($language['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Poster -->
          <div class="col-md-6">
            <label class="form-label text-warning">Poster Image <?= $editing ? '' : '<span class="text-danger">*</span>' ?></label>
            <input type="file" name="poster_file" <?= $editing ? '' : 'required' ?> class="form-control">

            <?php if ($editing && !empty($movieData['poster_url'])): ?>
              <div class="mt-3">
                <img src="../<?= htmlspecialchars($movieData['poster_url']) ?>"
                  alt="Current Poster"
                  class="img-fluid rounded shadow-sm border"
                  style="max-height: 300px; object-fit: cover;">
              </div>
            <?php endif; ?>
          </div>

          <!-- Trailer -->
          <div class="col-md-6">
            <label class="form-label text-warning">Trailer URL</label>
            <input type="text" name="trailer_url" value="<?= htmlspecialchars($movieData['trailer_url']) ?>" class="form-control">

            <?php if ($editing && !empty($movieData['trailer_url'])): ?>
              <div class="mt-3 ratio ratio-16x9">
                <?php
                $trailerUrl = $movieData['trailer_url'];
                if (strpos($trailerUrl, "youtube.com") !== false || strpos($trailerUrl, "youtu.be") !== false) {
                  if (preg_match('/(youtu\.be\/|v=)([^&]+)/', $trailerUrl, $matches)) {
                    $videoId = $matches[2];
                    echo '<iframe src="https://www.youtube.com/embed/' . htmlspecialchars($videoId) . '" 
                   title="Trailer" allowfullscreen></iframe>';
                  }
                } else {
                  echo '<video controls class="w-100 rounded shadow-sm">
                  <source src="' . htmlspecialchars($trailerUrl) . '" type="video/mp4">
                  Your browser does not support the video tag.
                </video>';
                }
                ?>
              </div>
            <?php endif; ?>
          </div>



          <!-- Description -->
          <div class="col-12">
            <label class="form-label text-warning">Description</label>
            <textarea name="description" rows="5" class="form-control"><?= htmlspecialchars($movieData['description']) ?></textarea>
          </div>

          <!-- Genres -->
          <div class="col-12">
            <label class="form-label text-warning">Genres</label>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-2">
              <?php foreach ($allGenres as $genre): ?>
                <div class="col">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="genres[]" value="<?= $genre['id'] ?>" <?= in_array($genre['id'], $selectedGenres) ? 'checked' : '' ?>>
                    <label class="form-check-label"><?= htmlspecialchars($genre['name']) ?></label>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Actions -->
          <div class="col-12 d-flex flex-column flex-sm-row gap-3 pt-3">
            <button type="submit" class="btn btn-warning text-dark fw-semibold d-flex align-items-center gap-2">
              <i class="bx <?= $editing ? 'bx-save' : 'bx-plus' ?>"></i>
              <?= htmlspecialchars($actionText) ?>
            </button>
            <a href="../index.php" class="btn btn-outline-secondary text-light d-flex align-items-center gap-2">
              <i class="bx bx-arrow-back"></i> Back to Movies
            </a>
          </div>
        </form>
      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <footer class="border-top text-center py-3">
    <p class="text-muted small mb-0">© <?= date('Y') ?> Movie Database</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>