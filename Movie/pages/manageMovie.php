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

// Load JSON data for countries and languages
$countryJsonContent = file_get_contents(__DIR__ . '/../JSON/country.json');
$languageJsonContent = file_get_contents(__DIR__ . '/../JSON/language.json');

$countries = json_decode($countryJsonContent, true);
$languages = json_decode($languageJsonContent, true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?= $actionText ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/ui-darkness/jquery-ui.css">

  <style>
    :root {
      --imdb-black: #0f0f0f;
      --imdb-dark: #1a1a1a;
      --imdb-card: #2a2a2a;
      --imdb-yellow: #f5c518;
      --imdb-red: #dc2626;
      --imdb-white: #ffffff;
      --imdb-gray: #6b7280;
      --imdb-light-gray: #9ca3af;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, var(--imdb-black) 0%, var(--imdb-dark) 100%);
      color: var(--imdb-white);
      min-height: 100vh;
    }

    .imdb-header {
      background: rgba(26, 26, 26, 0.95);
      backdrop-filter: blur(10px);
      border-bottom: 2px solid var(--imdb-yellow);
    }

    .imdb-card {
      background: var(--imdb-card);
      border: 1px solid #404040;
      border-radius: 12px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .form-control, .form-select {
      background-color: var(--imdb-dark);
      border: 2px solid #404040;
      color: var(--imdb-white);
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--imdb-yellow);
      box-shadow: 0 0 0 0.2rem rgba(245, 197, 24, 0.25);
      background-color: var(--imdb-dark);
      color: var(--imdb-white);
    }

    .form-label {
      color: var(--imdb-yellow);
      font-weight: 600;
      margin-bottom: 8px;
    }

    .btn-imdb-primary {
      background: linear-gradient(135deg, var(--imdb-yellow) 0%, #e6b800 100%);
      border: none;
      color: var(--imdb-black);
      font-weight: 600;
      padding: 12px 24px;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .btn-imdb-primary:hover {
      background: linear-gradient(135deg, #e6b800 0%, #cc9900 100%);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(245, 197, 24, 0.3);
    }

    .btn-imdb-secondary {
      background: transparent;
      border: 2px solid var(--imdb-gray);
      color: var(--imdb-white);
      font-weight: 500;
      padding: 10px 22px;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .btn-imdb-secondary:hover {
      border-color: var(--imdb-yellow);
      color: var(--imdb-yellow);
    }

    /* People Management Styles */
    .people-section {
      background: var(--imdb-dark);
      border: 1px solid #404040;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
    }

    .people-section h5 {
      color: var(--imdb-yellow);
      font-weight: 600;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .person-badge {
      background: linear-gradient(135deg, var(--imdb-red) 0%, #b91c1c 100%);
      color: var(--imdb-white);
      padding: 8px 12px;
      border-radius: 20px;
      margin: 4px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .person-badge:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
    }

    .remove-person {
      background: none;
      border: none;
      color: var(--imdb-white);
      font-size: 16px;
      cursor: pointer;
      padding: 0;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
    }

    .remove-person:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    .people-input {
      background: var(--imdb-black);
      border: 2px solid #404040;
      color: var(--imdb-white);
      border-radius: 8px;
      padding: 12px 16px;
      transition: all 0.3s ease;
    }

    .people-input:focus {
      border-color: var(--imdb-red);
      box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.25);
      background-color: var(--imdb-black);
    }

    .people-input::placeholder {
      color: var(--imdb-gray);
    }

    .people-list {
      min-height: 60px;
      padding: 10px;
      background: var(--imdb-black);
      border-radius: 8px;
      border: 1px solid #404040;
    }

    .empty-state {
      color: var(--imdb-gray);
      font-style: italic;
      text-align: center;
      padding: 20px;
    }

    /* jQuery UI Autocomplete Styling */
    .ui-autocomplete {
      background: var(--imdb-card) !important;
      border: 1px solid #404040 !important;
      border-radius: 8px !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
    }

    .ui-menu-item {
      padding: 0 !important;
    }

    .ui-menu-item-wrapper {
      background: transparent !important;
      color: var(--imdb-white) !important;
      padding: 10px 15px !important;
      border: none !important;
      font-size: 14px !important;
    }

    .ui-menu-item-wrapper:hover,
    .ui-menu-item-wrapper.ui-state-active {
      background: var(--imdb-red) !important;
      color: var(--imdb-white) !important;
    }

    .form-check-input:checked {
      background-color: var(--imdb-red);
      border-color: var(--imdb-red);
    }

    .form-check-label {
      color: var(--imdb-white);
      font-weight: 500;
    }

    .genre-grid {
      background: var(--imdb-dark);
      border-radius: 10px;
      padding: 20px;
      border: 1px solid #404040;
    }
  </style>
</head>

<body>
  <!-- HEADER -->
  <header class="imdb-header px-3 px-md-5 py-4">
    <div class="d-flex align-items-center gap-3">
      <i class="bx bx-movie-play text-warning" style="font-size: 2rem;"></i>
      <h1 class="h3 fw-bold text-warning mb-0"><?= $actionText ?></h1>
    </div>
  </header>

  <!-- MAIN -->
  <main class="container py-5">
    <div class="imdb-card shadow-lg">
      <div class="card-body p-4 p-md-5">
        <form action="../db/movieRequests.php" method="POST" enctype="multipart/form-data" class="row g-4">
          <input type="hidden" name="action" value="<?= $submitAction ?>" />
          <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?= $movieData['id'] ?>" />
          <?php endif; ?>

          <!-- Title -->
          <div class="col-md-6">
            <label class="form-label">
              <i class="bx bx-movie me-2"></i>Title <span class="text-danger">*</span>
            </label>
            <input type="text" name="title" value="<?= htmlspecialchars($movieData['title']) ?>" required class="form-control">
          </div>

          <!-- Release Year -->
          <div class="col-md-6">
            <label class="form-label">
              <i class="bx bx-calendar me-2"></i>Release Year
            </label>
            <input type="number" name="release_year" value="<?= $movieData['release_year'] ?>" min="1960" max="<?= date('Y') ?>" class="form-control">
          </div>

          <!-- Country -->
          <div class="col-md-6">
            <label class="form-label">
              <i class="bx bx-world me-2"></i>Country <span class="text-danger">*</span>
            </label>
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
            <label class="form-label">
              <i class="bx bx-message me-2"></i>Language <span class="text-danger">*</span>
            </label>
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
            <label class="form-label">
              <i class="bx bx-image me-2"></i>Poster Image <?= $editing ? '' : '<span class="text-danger">*</span>' ?>
            </label>
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
            <label class="form-label">
              <i class="bx bx-play-circle me-2"></i>Trailer URL
            </label>
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
            <label class="form-label">
              <i class="bx bx-text me-2"></i>Description
            </label>
            <textarea name="description" rows="5" class="form-control" placeholder="Enter movie description..."><?= htmlspecialchars($movieData['description']) ?></textarea>
          </div>

          <!-- Directors Section -->
          <div class="col-12">
            <div class="people-section">
              <h5>
                <i class="bx bx-user-voice"></i>
                Directors
              </h5>
              <div id="directors-list" class="people-list mb-3">
                <div class="empty-state">No directors added yet</div>
              </div>
              <input type="text" id="director-input" class="form-control people-input" placeholder="Type director name and press Enter...">
            </div>
          </div>

          <!-- Actors Section -->
          <div class="col-12">
            <div class="people-section">
              <h5>
                <i class="bx bx-group"></i>
                Cast & Actors
              </h5>
              <div id="actors-list" class="people-list mb-3">
                <div class="empty-state">No actors added yet</div>
              </div>
              <input type="text" id="actor-input" class="form-control people-input" placeholder="Type actor name and press Enter...">
            </div>
          </div>

          <!-- Genres -->
          <div class="col-12">
            <div class="genre-grid">
              <label class="form-label mb-3">
                <i class="bx bx-category me-2"></i>Genres
              </label>
              <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
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
          </div>

          <!-- Actions -->
          <div class="col-12 d-flex flex-column flex-sm-row gap-3 pt-4">
            <button type="submit" class="btn btn-imdb-primary d-flex align-items-center gap-2">
              <i class="bx <?= $editing ? 'bx-save' : 'bx-plus' ?>"></i>
              <?= htmlspecialchars($actionText) ?>
            </button>
            <a href="../index.php" class="btn btn-imdb-secondary d-flex align-items-center gap-2">
              <i class="bx bx-arrow-back"></i> Back to Movies
            </a>
          </div>
        </form>
      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <footer class="text-center py-4 mt-5">
    <p class="text-muted small mb-0">© <?= date('Y') ?> Movie Database - Powered by IMDb-inspired Design</p>
  </footer>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const movieId = <?= $editing ? $movieData['id'] : 0 ?>;

    // Load people data on page ready
    $(document).ready(function() {
        if (movieId > 0) {
            loadPeople('Director');
            loadPeople('Cast');
        }
        setupAutocomplete();
    });

    // Load people from server (similar to loadStudents structure)
    function loadPeople(role) {
        $.ajax({
            url: "../db/peopleRequests.php",
            method: "POST",
            data: {
                "action": "fetch",
                "movie_id": movieId,
                "role": role
            },
            success: function(result) {
              // try {
              //     var people = JSON.parse(result);
              //     renderPeople(role, people);
                  
              // } catch (e) {
              //     console.error("Failed to parse JSON:", result);
              // }
              if (res.success) {
                      let container = role === 'Director' ? $("#director-list") : $("#actor-list");
                      container.empty();
                      res.data.forEach(function(person) {
                          container.append(`
                              <li>
                                  ${person.name}
                                  <button class="remove-btn" data-id="${person.id}">Remove</button>
                              </li>
                          `);
                      });
                  }
                // var people = JSON.parse(result);
                // renderPeople(role, people);
            },
            error: function(error) {
                console.error("Error loading " + role.toLowerCase() + "s:", error);
            }
        });
    }

    // Render people badges (similar to table rendering in loadStudents)
    function renderPeople(role, people) {
        const containerId = role === 'Director' ? '#directors-list' : '#actors-list';
        let html = '';
        
        if (people.length === 0) {
            html = '<div class="empty-state">No ' + role.toLowerCase() + 's added yet</div>';
        } else {
            people.forEach(function(person) {
                html += `<span class="person-badge">
                    <i class="bx ${role === 'Director' ? 'bx-user-voice' : 'bx-user'}"></i>
                    ${person.name}
                    <button type="button" class="remove-person" data-id="${person.id}" data-role="${role}">
                        <i class="bx bx-x"></i>
                    </button>
                </span>`;
            });
        }
        
        $(containerId).html(html);
    }

    // Add person (similar to add student structure)
    function addPerson(name, role) {
        if (name.trim() === '') return;
        
        $.ajax({
            url: "../db/peopleRequests.php",
            method: "POST",
            data: {
                "action": "add",
                "movie_id": movieId,
                "name": name.trim(),
                "role": role
            },
            success: function(result) {
              try {
            var people = JSON.parse(result);
            renderPeople(role, people);
        } catch (e) {
            console.error("Failed to parse JSON:", result);
        }
               
                // // Clear input
                const inputId = role === 'Director' ? '#director-input' : '#actor-input';
                $(inputId).val('');
            },
            error: function(error) {
                alert("Error adding " + role.toLowerCase() + "!");
                console.error(error);
            }
        });
    }

    // Remove person (similar to delete student structure)
    $(document).on('click', '.remove-person', function() {
        const id = $(this).data('id');
        const role = $(this).data('role');
        const personName = $(this).parent().text().trim();
        
        if (confirm(`Are you sure you want to remove this ${role.toLowerCase()}?\nName: ${personName}`)) {
            $.ajax({
                url: "../db/peopleRequests.php",
                method: "POST",
                data: {
                    "action": "remove",
                    "id": id,
                    "movie_id": movieId,
                    "role": role
                },
                success: function(result) {
                    var people = JSON.parse(result);
                    renderPeople(role, people);
                },
                error: function(error) {
                    alert("Error removing " + role.toLowerCase() + "!");
                    console.error(error);
                }
            });
        }
    });

    // Handle Enter key for adding people
    $('#director-input, #actor-input').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const role = $(this).attr('id') === 'director-input' ? 'Director' : 'Cast';
            const name = $(this).val();
            addPerson(name, role);
        }
    });

    // Setup autocomplete for people inputs
    function setupAutocomplete() {
        $('#director-input, #actor-input').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "../db/peopleRequests.php",
                    method: "POST",
                    data: {
                        "action": "search",
                        "query": request.term
                    },
                    success: function(result) {
                        var people = JSON.parse(result);
                        var names = people.map(function(person) {
                            return person.name;
                        });
                        response(names);
                    },
                    error: function(error) {
                        console.error("Autocomplete error:", error);
                        response([]);
                    }
                });
            },
            minLength: 2,
            delay: 300
        });
    }
  </script>
</body>

</html>
