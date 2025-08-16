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
    $selectedGenres = $movie->getGenresByMovie($movieId);
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

if (!empty($_GET['id'])) {
    $editing = true;
    $movieData = $movie->getMovieById($_GET['id']);
    $movieId = $_GET['id'];
    $selectedGenres = $movie->getGenresByMovie($movieId);
    if (!$movieData) {
        header("Location: ../index.php");
        exit;
    }
}

$actionText = $editing ? "Update Movie" : "Add Movie";
$submitAction = $editing ? "update" : "add";

$countryJsonContent = file_get_contents(__DIR__ . '/../JSON/country.json');
$languageJsonContent = file_get_contents(__DIR__ . '/../JSON/language.json');

$countries = json_decode($countryJsonContent, true);
$languages = json_decode($languageJsonContent, true);
?>
<!DOCTYPE html>
<html lang="en">

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?= $actionText ?></title>

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="font-[Inter] bg-gray-950 text-gray-100 antialiased selection:bg-yellow-500 selection:text-gray-900">
  <!-- HEADER -->
  <header class="border-b border-gray-800 bg-gray-900/60 backdrop-blur-md px-4 md:px-8 py-4">
    <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-yellow-400"><?= $actionText ?></h1>
  </header>

  <!-- MAIN -->
  <main class="flex-1 max-w-5xl mx-auto px-4 md:px-8 py-10">
    <section class="bg-gray-900/60 border border-gray-800 shadow-xl rounded-xl p-6 md:p-10">
      <form action="../db/movieRequests.php" method="POST" enctype="multipart/form-data" class="grid gap-6 md:gap-8 grid-cols-1 md:grid-cols-2">
        <input type="hidden" name="action" value="<?= $submitAction ?>" />
        <?php if ($editing): ?>
          <input type="hidden" name="id" value="<?= $movieData['id'] ?>" />
        <?php endif; ?>

        <!-- Title -->
        <div>
          <label class="flex items-center gap-1 mb-2 font-medium text-yellow-400">
            Title <span class="text-red-500">*</span>
          </label>
          <input
            type="text"
            name="title"
            value="<?= htmlspecialchars($movieData['title']) ?>"
            required
            class="w-full rounded-md bg-gray-800 border border-gray-700 px-3 py-2 text-sm focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition"
          />
        </div>

        <!-- Release Year -->
        <div>
          <label class="mb-2 font-medium text-yellow-400">Release Year</label>
          <input
            type="number"
            name="release_year"
            value="<?= $movieData['release_year'] ?>"
            min="1960"
            max="<?= date('Y') ?>"
            class="w-full rounded-md bg-gray-800 border border-gray-700 px-3 py-2 text-sm focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition"
          />
        </div>

        <!-- Country -->
        <div>
          <label class="flex items-center gap-1 mb-2 font-medium text-yellow-400">
            Country <span class="text-red-500">*</span>
          </label>
          <select
            name="countryName"
            required
            class="w-full rounded-md bg-gray-800 border border-gray-700 px-3 py-2 text-sm focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition"
          >
            <option value="">-- Select Country --</option>
            <?php foreach ($countries as $country): ?>
              <option
                value="<?= htmlspecialchars($country['name']) ?>"
                <?= ($editing && $movieData['country_name'] === $country['name']) ? 'selected' : '' ?>
              >
                <?= htmlspecialchars($country['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Language -->
        <div>
          <label class="flex items-center gap-1 mb-2 font-medium text-yellow-400">
            Language <span class="text-red-500">*</span>
          </label>
          <select
            name="languageName"
            required
            class="w-full rounded-md bg-gray-800 border border-gray-700 px-3 py-2 text-sm focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition"
          >
            <option value="">-- Select Language --</option>
            <?php foreach ($languages as $language): ?>
              <option
                value="<?= htmlspecialchars($language['name']) ?>"
                <?= ($editing && $movieData['language_name'] === $language['name']) ? 'selected' : '' ?>
              >
                <?= htmlspecialchars($language['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Poster -->
        <div>
          <label class="mb-2 font-medium text-yellow-400">
            Poster Image <?= $editing ? '' : '<span class="text-red-500">*</span>' ?>
          </label>
          <input
            type="file"
            name="poster_file"
            <?= $editing ? '' : 'required' ?>
            class="w-full rounded-md bg-gray-800 border border-gray-700 file:bg-yellow-500 file:text-gray-900 file:font-medium file:border-0 file:px-4 file:py-2 file:rounded-l-md cursor-pointer text-sm"
          />
        </div>

        <!-- Trailer -->
        <div>
          <label class="mb-2 font-medium text-yellow-400">Trailer URL</label>
          <input
            type="text"
            name="trailer_url"
            value="<?= htmlspecialchars($movieData['trailer_url']) ?>"
            class="w-full rounded-md bg-gray-800 border border-gray-700 px-3 py-2 text-sm focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition"
          />
        </div>

        <!-- Description (full width) -->
        <div class="md:col-span-2">
          <label class="mb-2 font-medium text-yellow-400">Description</label>
          <textarea
            name="description"
            rows="5"
            class="w-full rounded-md bg-gray-800 border border-gray-700 px-3 py-2 text-sm focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition resize-y"
          ><?= htmlspecialchars($movieData['description']) ?></textarea>
        </div>

        <!-- Genres -->
        <div class="md:col-span-2">
          <label class="mb-2 font-medium text-yellow-400">Genres</label>
          <div class="grid gap-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            <?php foreach ($allGenres as $genre): ?>
              <label class="flex items-center gap-2 rounded-md border border-gray-700 bg-gray-800 px-3 py-2 text-sm hover:border-yellow-500 transition">
                <input
                  type="checkbox"
                  name="genres[]"
                  value="<?= $genre['id'] ?>"
                  <?= in_array($genre['id'], $selectedGenres) ? 'checked' : '' ?>
                  class="accent-yellow-500 w-4 h-4"
                />
                <span><?= htmlspecialchars($genre['name']) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Actions -->
        <div class="md:col-span-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
          <button
            type="submit"
            class="inline-flex justify-center items-center gap-2 rounded-md bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-semibold px-6 py-3 transition-colors"
          >
            <i data-lucide="<?= $editing ? 'save' : 'plus' ?>" class="w-4 h-4 stroke-[1.5]"></i>
            <?= htmlspecialchars($actionText) ?>
          </button>

          <a
            href="../index.php"
            class="inline-flex justify-center items-center gap-2 rounded-md border border-gray-700 hover:border-yellow-500 text-gray-300 hover:text-yellow-400 px-6 py-3 transition-colors"
          >
            <i data-lucide="arrow-left" class="w-4 h-4 stroke-[1.5]"></i>
            Back to Movies
          </a>
        </div>
      </form>
    </section>
  </main>

  <!-- FOOTER -->
  <footer class="border-t border-gray-800 bg-gray-900/60 backdrop-blur-md text-center py-4">
    <p class="text-sm text-gray-500">© <?= date('Y') ?> Movie Database</p>
  </footer>

  <script>
    lucide.createIcons()
  </script>
</body>
</html>