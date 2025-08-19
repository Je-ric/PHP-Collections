<?php
require_once __DIR__ . '/../classes/Movie.php';
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


// References:
// https://stackoverflow.com/questions/19758954/get-data-from-json-file-with-php
// https://stackoverflow.com/questions/412467/how-to-embed-youtube-videos-in-php
// https://stackoverflow.com/questions/19050890/find-youtube-link-in-php-string-and-convert-it-into-embed-code
// https://stackoverflow.com/questions/5830387/how-do-i-find-all-youtube-video-ids-in-a-string-using-a-regex
// https://stackoverflow.com/questions/19050890/find-youtube-link-in-php-string-and-convert-it-into-embed-code
// https://stackoverflow.com/questions/9656523/jquery-autocomplete-with-callback-ajax-json

$countryJsonContent = file_get_contents(__DIR__ . '/../JSON/country.json'); // load
$languageJsonContent = file_get_contents(__DIR__ . '/../JSON/language.json');

$countries = json_decode($countryJsonContent, true); // true = associative array
$languages = json_decode($languageJsonContent, true);

// foreach ($countries as $country) {
//     echo $country['name'] . ' (' . $country['code'] . ')<br>';
// }
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= $actionText ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.js"></script>
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
    .people-section { background: var(--imdb-dark); border: 1px solid #404040; border-radius: 10px; padding: 20px; margin-bottom: 20px; }
    .people-section h5 { color: var(--imdb-yellow); font-weight: 600; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; }
    .person-badge { background: linear-gradient(135deg, var(--imdb-red) 0%, #b91c1c 100%); color: var(--imdb-white); padding: 8px 12px; border-radius: 20px; margin: 4px; display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 500; }
    .remove-person { background: none; border: none; color: var(--imdb-white); font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .empty-state { color: var(--imdb-gray); font-style: italic; text-align: center; padding: 20px; }
  </style>
</head>
<body>
  <!-- HEADER -->
  <header class="bg-[rgba(26,26,26,0.95)] backdrop-blur-md border-b-2 border-[var(--imdb-yellow)] px-3 md:px-5 py-4">
    <div class="flex items-center gap-3">
      <i class="bx bx-movie-play text-[var(--imdb-yellow)] text-3xl"></i>
      <h1 class="text-xl font-bold text-[var(--imdb-yellow)] mb-0"><?= $actionText ?></h1>
    </div>
  </header>

  <!-- MAIN -->
  <main class="container mx-auto py-5 px-4">
    <div class="bg-[var(--imdb-card)] border border-[#404040] rounded-xl shadow-lg">
      <div class="p-6 md:p-10">
        <form action="../db/movieRequests.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <input type="hidden" name="action" value="<?= $submitAction ?>" />
          <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?= $movieData['id'] ?>" />
          <?php endif; ?>

          <!-- Title -->
          <div>
            <label class="text-[var(--imdb-yellow)] font-semibold mb-2 block">
              <i class="bx bx-movie mr-2"></i>Title <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title" value="<?= htmlspecialchars($movieData['title']) ?>" required class="input input-bordered w-full bg-[var(--imdb-dark)] text-white border-[#404040] focus:border-[var(--imdb-yellow)]" />
          </div>

          <!-- Release Year -->
          <div>
            <label class="text-[var(--imdb-yellow)] font-semibold mb-2 block">
              <i class="bx bx-calendar mr-2"></i>Release Year
            </label>
            <input type="number" name="release_year" value="<?= $movieData['release_year'] ?>" min="1960" max="<?= date('Y') ?>" class="input input-bordered w-full bg-[var(--imdb-dark)] text-white border-[#404040] focus:border-[var(--imdb-yellow)]" />
          </div>

          <!-- Country -->
          <div>
            <label class="text-[var(--imdb-yellow)] font-semibold mb-2 block">
              <i class="bx bx-world mr-2"></i>Country <span class="text-red-500">*</span>
            </label>
            <select name="countryName" required class="select select-bordered w-full bg-[var(--imdb-dark)] text-white border-[#404040] focus:border-[var(--imdb-yellow)]">
              <option value="">-- Select Country --</option>
              <?php foreach ($countries as $country): ?>
                <option value="<?= htmlspecialchars($country['name']) ?>" <?= ($editing && $movieData['country_name'] === $country['name']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($country['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Language -->
          <div>
            <label class="text-[var(--imdb-yellow)] font-semibold mb-2 block">
              <i class="bx bx-message mr-2"></i>Language <span class="text-red-500">*</span>
            </label>
            <select name="languageName" required class="select select-bordered w-full bg-[var(--imdb-dark)] text-white border-[#404040] focus:border-[var(--imdb-yellow)]">
              <option value="">-- Select Language --</option>
              <?php foreach ($languages as $language): ?>
                <option value="<?= htmlspecialchars($language['name']) ?>" <?= ($editing && $movieData['language_name'] === $language['name']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($language['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Poster -->
          <div>
            <label class="text-[var(--imdb-yellow)] font-semibold mb-2 block">
              <i class="bx bx-image mr-2"></i>Poster Image <?= $editing ? '' : '<span class="text-red-500">*</span>' ?>
            </label>
            <input type="file" name="poster_file" <?= $editing ? '' : 'required' ?> class="file-input file-input-bordered w-full bg-[var(--imdb-dark)] text-white border-[#404040]" />
            <?php if ($editing && !empty($movieData['poster_url'])): ?>
              <div class="mt-3">
                <img src="../<?= htmlspecialchars($movieData['poster_url']) ?>" alt="Current Poster" class="max-h-72 object-cover rounded-lg border shadow-md" />
              </div>
            <?php endif; ?>
          </div>

          <!-- Trailer -->
          <div>
            <label class="text-[var(--imdb-yellow)] font-semibold mb-2 block">
              <i class="bx bx-play-circle mr-2"></i>Trailer URL
            </label>
            <input type="text" name="trailer_url" value="<?= htmlspecialchars($movieData['trailer_url']) ?>" class="input input-bordered w-full bg-[var(--imdb-dark)] text-white border-[#404040]" />
            <?php if ($editing && !empty($movieData['trailer_url'])): ?>
              <div class="mt-3 aspect-video">
                <?php
                  $trailerUrl = $movieData['trailer_url'];
                  if (strpos($trailerUrl, "youtube.com") !== false || strpos($trailerUrl, "youtu.be") !== false) {
                    if (preg_match('/(youtu\.be\/|v=)([^&]+)/', $trailerUrl, $matches)) {
                      $videoId = $matches[2];
                      echo '<iframe src="https://www.youtube.com/embed/' . htmlspecialchars($videoId) . '" title="Trailer" class="w-full h-full rounded-lg" allowfullscreen></iframe>';
                    }
                  } else {
                    echo '<video controls class="w-full rounded-lg"><source src="' . htmlspecialchars($trailerUrl) . '" type="video/mp4"></video>';
                  }
                ?>
              </div>
            <?php endif; ?>
          </div>

          <!-- Description -->
          <div class="col-span-1 md:col-span-2">
            <label class="text-[var(--imdb-yellow)] font-semibold mb-2 block">
              <i class="bx bx-text mr-2"></i>Description
            </label>
            <textarea name="description" rows="5" class="textarea textarea-bordered w-full bg-[var(--imdb-dark)] text-white border-[#404040]" placeholder="Enter movie description..."><?= htmlspecialchars($movieData['description']) ?></textarea>
          </div>

          <!-- Directors -->
          <div class="col-span-1 md:col-span-2">
            <div class="people-section">
              <h5><i class="bx bx-user-voice"></i> Directors</h5>
              <div id="directors-list" class="people-list min-h-[60px] p-3 bg-[var(--imdb-black)] rounded-lg border border-[#404040] mb-3">
                <div class="empty-state">No directors added yet</div>
              </div>
              <input type="text" id="director-input" class="input input-bordered w-full bg-[var(--imdb-black)] text-white border-[#404040]" placeholder="Type director name and press Enter...">
            </div>
          </div>

          <!-- Actors -->
          <div class="col-span-1 md:col-span-2">
            <div class="people-section">
              <h5><i class="bx bx-group"></i> Cast & Actors</h5>
              <div id="actors-list" class="people-list min-h-[60px] p-3 bg-[var(--imdb-black)] rounded-lg border border-[#404040] mb-3">
                <div class="empty-state">No actors added yet</div>
              </div>
              <input type="text" id="actor-input" class="input input-bordered w-full bg-[var(--imdb-black)] text-white border-[#404040]" placeholder="Type actor name and press Enter...">
            </div>
          </div>

          <!-- Genres -->
          <div class="col-span-1 md:col-span-2">
            <div class="bg-[var(--imdb-dark)] rounded-lg border border-[#404040] p-5">
              <label class="text-[var(--imdb-yellow)] font-semibold mb-3 block">
                <i class="bx bx-category mr-2"></i>Genres
              </label>
              <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                <?php foreach ($allGenres as $genre): ?>
                  <label class="flex items-center gap-2">
                    <input type="checkbox" name="genres[]" value="<?= $genre['id'] ?>" class="checkbox checkbox-error" <?= in_array($genre['id'], $selectedGenres) ? 'checked' : '' ?>>
                    <span><?= htmlspecialchars($genre['name']) ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="col-span-1 md:col-span-2 flex flex-col sm:flex-row gap-4 pt-4">
            <button type="submit" class="btn bg-[var(--imdb-yellow)] hover:bg-yellow-500 text-black font-bold">
              <i class="bx <?= $editing ? 'bx-save' : 'bx-plus' ?> mr-2"></i> <?= htmlspecialchars($actionText) ?>
            </button>
            <a href="../index.php" class="btn border border-[var(--imdb-gray)] hover:border-[var(--imdb-yellow)] hover:text-[var(--imdb-yellow)]">
              <i class="bx bx-arrow-back mr-2"></i> Back to Movies
            </a>
          </div>
        </form>
      </div>
    </div>
  </main>


  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const movieId = <?= $editing ? $movieData['id'] : 0 ?>;

    $(document).ready(function() {
        if (movieId > 0) {
            loadPeople('Director');
            loadPeople('Cast');
        }
        setupAutocomplete();
    });

    // Load people from server
    function loadPeople(role) {
        $.ajax({
            url: "../db/peopleRequests.php",
            method: "POST",
            dataType: "json",
            data: {
                action: "fetch",
                movie_id: movieId,
                role: role
            },
            success: function(res) {
                let people = [];
                if (Array.isArray(res)) { // if array (ex. [ { id: 1, name: "John Doe" }, ... ])
                    people = res; 
                } else if (res && res.data) { // if object with data
                    people = res.data; // extract people from data (ex. [ { id: 1, name: "John Doe" }, ... ])
                } else { 
                    people = [];
                }
                renderPeople(role, people);
            },
            error: function(error) {
                console.error("Error loading " + role.toLowerCase() + "s:", error);
            }
        });
    }

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

    // the downside is that, working lang ang adding ng person if the movieId exists.
    // let's say movie is the parent, person is the child
    // we need a parent to have a child, getch?
    function addPerson(name, role) {
        const trimmed = name.trim();
        if (trimmed === '') {
            console.warn('[People] Add -> empty name, abort.');
            return;
        }

        const payload = {
            action: "add",
            movie_id: movieId, 
            name: trimmed,
            role: role
        };
        console.log('[People] Add -> request', payload);

        $.ajax({
            url: "../db/peopleRequests.php",
            method: "POST",
            dataType: "json",
            data: payload,
            success: function(res, textStatus, jqXHR) {
                console.log('[People] Add -> response', { res, status: textStatus, http: jqXHR.status });
                const inputId = role === 'Director' ? '#director-input' : '#actor-input';
                $(inputId).val('');
                loadPeople(role);
            },
            error: function(xhr, status, err) {
                console.error('[People] Add -> error', { status, err, http: xhr.status, responseText: xhr.responseText });
                alert("Error adding " + role.toLowerCase() + "!");
            }
        });
    }

    $(document).on('click', '.remove-person', function() {
        const id = $(this).data('id');
        const role = $(this).data('role');
        const personName = $(this).parent().text().trim();

        console.log('[People] Remove -> clicked', { id, role, personName });

        if (confirm(`Are you sure you want to remove this ${role.toLowerCase()}?\nName: ${personName}`)) {
            const payload = {
                action: "remove",
                id: id,
                movie_id: movieId,
                role: role
            };
            console.log('[People] Remove -> request', payload);

            $.ajax({
                url: "../db/peopleRequests.php",
                method: "POST",
                dataType: "json",
                data: payload,
                success: function(res, textStatus, jqXHR) {
                    console.log('[People] Remove -> response', { res, status: textStatus, http: jqXHR.status });
                    if (Array.isArray(res) || Array.isArray(res?.data)) {
                        const people = Array.isArray(res) ? res : res.data;
                        renderPeople(role, people);
                    } else {
                        loadPeople(role);
                    }
                },
                error: function(xhr, status, err) {
                    console.error('[People] Remove -> error', { status, err, http: xhr.status, responseText: xhr.responseText });
                    alert("Error removing " + role.toLowerCase() + "!");
                }
            });
        } else {
            console.log('[People] Remove -> cancelled');
        }
    });

    $('#director-input, #actor-input').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const role = $(this).attr('id') === 'director-input' ? 'Director' : 'Cast';
            const name = $(this).val();
            addPerson(name, role);
        }
    });

    // more like input search with autocomplete
    function setupAutocomplete() {
      $('#director-input, #actor-input').autocomplete({
        source: function(request, response) {
          console.log('[Autocomplete] query:', request.term);
          $.ajax({
            url: "../db/peopleRequests.php",
            method: "POST",
            dataType: "json",
            data: { action: "search", query: request.term },
            success: function(res) {
              console.log('[Autocomplete] results:', res);
              const people = Array.isArray(res) ? res : (res.data || []);
              response(people.map(p => p.name)); // or map to {label:p.name, value:p.name, id:p.id}
            },
            error: function(xhr, status, err) {
              console.error('[Autocomplete] error:', { status, err, http: xhr.status, responseText: xhr.responseText });
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
