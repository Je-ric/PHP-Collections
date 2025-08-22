<?php
session_start();
require_once __DIR__ . '/classes/Movie.php';

$movie = new Movie();
$allGenres = $movie->getAllGenres();
$allMovies = $movie->getAllMovies();
$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$userRole = $_SESSION['role'] ?? '';
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
  <meta charset="UTF-8" />
  <title>Movie Recommendation System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.24/dist/full.min.css" rel="stylesheet" type="text/css" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'primary-bg': '#0f172a',
            'secondary-bg': '#1e293b',
            'card-bg': '#334155',
            'accent': '#10b981',
            'accent-hover': '#059669',
            'text-primary': '#f8fafc',
            'text-secondary': '#cbd5e1',
            'text-muted': '#64748b',
            'border-color': '#475569',
          },
          fontFamily: {
            'oswald': ['Oswald', 'sans-serif']
          }
        }
      }
    }
  </script>
  <style>
    body {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      font-family: "Oswald", sans-serif;
      color: #fff;
    }
  </style>
</head>

<body class="min-h-screen text-text-primary">
  <?php include __DIR__ . '/partials/header.php'; ?>

  <main class="px-6 md:px-10 py-10">
    <div class="mb-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
      <div>
        <h2 class="text-3xl font-bold mb-1">Discover Movies</h2>
        <p class="text-gray-400 text-sm">Find your next favorite film from our curated collection</p>
      </div>
      <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
        <label class="flex items-center w-full md:w-96 bg-neutral-900 border border-neutral-700 rounded-lg overflow-hidden shadow-sm">
          <span class="px-3"><i class="bx bx-search text-gray-400 text-lg"></i></span>
          <input id="search" type="text" placeholder="Search movies by title..." class="w-full bg-neutral-900 text-gray-200 placeholder-gray-500 focus:outline-none px-2 py-2 text-sm">
        </label>
        <select id="sortSelect" class="select select-bordered select-sm bg-neutral-900 border-neutral-700 text-gray-200">
          <option value="year_desc">Sort: Year (New → Old)</option>
          <option value="year_asc">Sort: Year (Old → New)</option>
          <option value="title_asc">Sort: Title (A→Z)</option>
          <option value="title_desc">Sort: Title (Z→A)</option>
        </select>
        <select id="genreFilter" class="select select-bordered select-sm bg-neutral-900 border-neutral-700 text-gray-200 min-w-48">
          <option value="">All Genres</option>
          <?php foreach ($allGenres as $g): ?>
            <option value="<?= (int)$g['id'] ?>"><?= htmlspecialchars($g['name']) ?></option>
          <?php endforeach; ?>
        </select>
        <select id="yearFilter" class="select select-bordered select-sm bg-neutral-900 border-neutral-700 text-gray-200 min-w-32">
          <option value="">All Years</option>
        </select>
        <select id="countryFilter" class="select select-bordered select-sm bg-neutral-900 border-neutral-700 text-gray-200 min-w-40">
          <option value="">All Countries</option>
        </select>
      </div>
    </div>

    <!-- Trending only -->
    <section id="trendingSection" class="mb-12">
      <div class="border-t border-neutral-800 mb-6"></div>
      <div class="flex items-end justify-between mb-4">
        <h3 class="text-2xl font-semibold">Trending now</h3>
      </div>
      <div id="trendingGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6"></div>
    </section>

    <!-- All movies -->
    <div class="border-t border-neutral-800 my-10"></div>
    <section>
      <div class="flex items-end justify-between mb-4">
        <h3 class="text-2xl font-semibold">All movies</h3>
      </div>
      <div id="allGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6"></div>
      <!-- Empty placeholder -->
      <div id="allEmpty" class="hidden py-16 text-center">
        <i class='bx bx-search-alt text-5xl text-gray-600 mb-3 block'></i>
        <p class="text-text-muted">No results found. Try adjusting your search or filters.</p>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/partials/footer.php'; ?>
  <script src="src/js/jquery.mins.js"></script>
  <script>
    window.AppConfig = {
      userId: <?= (int)$userId ?>,
      role: "<?= htmlspecialchars($userRole) ?>",
      genres: <?= json_encode($allGenres ?? []) ?>
    };

    window.AppData = {
      allMovies: <?= json_encode($allMovies ?? []) ?>
    };

    (function($) {
      const state = {
        allMovies: (window.AppData && window.AppData.allMovies) || [],
        trending: [],
        userId: (window.AppConfig && window.AppConfig.userId) || 0,
      };

      // Helpers
      function buildCard(m) {
        const poster = m.poster_url && m.poster_url.length ? m.poster_url : 'https://placehold.co/300x450?text=No+Poster';
        const rating = (m.avg_rating !== null && m.avg_rating !== undefined) ? Number(m.avg_rating).toFixed(1) : 'N/A';
        const year = m.release_year ? `(${m.release_year})` : '';
        const country = m.country_name ? `<span class="px-2 py-0.5 rounded bg-neutral-800/70 border border-neutral-700">${escapeHtml(m.country_name)}</span>` : '';
        const language = m.language_name ? `<span class="px-2 py-0.5 rounded bg-neutral-800/70 border border-neutral-700">${escapeHtml(m.language_name)}</span>` : '';
        const ratingBadge = `<div class="absolute top-2 right-2"><span class="bg-green-600/90 text-white font-semibold text-xs px-2 py-1 rounded-md flex items-center gap-1"><i class='bx bxs-star text-yellow-300'></i>${rating}</span></div>`;

        return `
      <div class="group rounded-xl overflow-hidden bg-neutral-900 border border-neutral-800 hover:border-green-500/70 transition transform hover:-translate-y-2 hover:shadow-xl hover:shadow-green-500/20 flex flex-col">
        <div class="relative">
          <a href="pages/viewMovie.php?id=${m.id}">
            <img src="${escapeHtml(poster)}" alt="${escapeHtml(m.title)}" class="w-full h-80 object-cover transition-transform duration-300 group-hover:scale-105">
          </a>
          ${ratingBadge}
        </div>
        <div class="p-4 flex flex-col flex-grow">
          <div class="flex-grow">
            <h5 class="font-semibold text-base mb-1 text-white leading-tight">
              ${escapeHtml(m.title)} <small class="text-gray-400 font-normal">${year}</small>
            </h5>
            <div class="text-gray-400 text-xs flex flex-wrap gap-2 mb-3">
              ${country}${language}
            </div>
          </div>
        </div>
      </div>`;
      }

      function renderGrid($container, list) {
        const html = list.map(buildCard).join('');
        $container.html(html);
      }

      function escapeHtml(str) {
        return String(str || '')
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;')
          .replace(/'/g, '&#039;');
      }

      // Real-time filter + sort
      function applySortAndFilter(list) {
        const q = $('#search').val().trim().toLowerCase();
        const year = $('#yearFilter').val();
        const country = $('#countryFilter').val();
        const genreId = $('#genreFilter').val();
        const sort = $('#sortSelect').val();

        let res = list;
        if (q) res = res.filter(m => String(m.title || '').toLowerCase().includes(q));
        if (year) res = res.filter(m => String(m.release_year || '') === String(year));
        if (country) res = res.filter(m => String(m.country_name || '') === country);
        if (genreId) {
          res = res.filter(m => {
            if (!m.genre_ids) return false;
            const ids = String(m.genre_ids).split(',').map(Number);
            return ids.includes(Number(genreId));
          });
        }

        if (sort === 'year_desc') res = res.slice().sort((a, b) => (b.release_year || 0) - (a.release_year || 0));
        else if (sort === 'year_asc') res = res.slice().sort((a, b) => (a.release_year || 0) - (b.release_year || 0));
        else if (sort === 'title_asc') res = res.slice().sort((a, b) => String(a.title || '').localeCompare(String(b.title || '')));
        else if (sort === 'title_desc') res = res.slice().sort((a, b) => String(b.title || '').localeCompare(String(a.title || '')));

        return res;
      }

      // Populate Year/Country filters based on data
      function populateFilters() {
        const years = [...new Set(state.allMovies.map(m => m.release_year).filter(Boolean))].sort((a, b) => b - a);
        const countries = [...new Set(state.allMovies.map(m => m.country_name).filter(Boolean))].sort((a, b) => String(a).localeCompare(String(b)));
        const $yf = $('#yearFilter').empty().append('<option value="">All Years</option>');
        const $cf = $('#countryFilter').empty().append('<option value="">All Countries</option>');
        years.forEach(y => $yf.append(`<option value="${y}">${y}</option>`));
        countries.forEach(c => $cf.append(`<option value="${escapeHtml(c)}">${escapeHtml(c)}</option>`));
      }

      // AJAX calls
      function fetchTrending(limit = 12) {
        return $.ajax({
          url: 'db/recommendRequests.php',
          method: 'GET',
          data: { action: 'trending', limit }
        }).then(res => res && res.success ? res.data : []).fail(() => []);
      }

      // UI: show/hide trending when any search/filter/sort is active
      function hasActiveFilters() {
        const q = $('#search').val().trim();
        const hasQ = q.length > 0;
        const hasYear = $('#yearFilter').val() !== '';
        const hasCountry = $('#countryFilter').val() !== '';
        const hasGenre = $('#genreFilter').val() !== '';
        const sortVal = $('#sortSelect').val();
        const hasSort = sortVal !== 'year_desc';
        return hasQ || hasYear || hasCountry || hasGenre || hasSort;
      }

      function updateTrendingVisibility() {
        $('#trendingSection').toggleClass('hidden', hasActiveFilters());
      }

      // Load initial data
      function initialLoad() {
        const $trending = $('#trendingGrid');

        // render All grid immediately from embedded data
        populateFilters();
        renderAllGrid();
        updateTrendingVisibility();

        // fetch Trending
        fetchTrending().then(list => {
          state.trending = Array.isArray(list) ? list : [];
          renderGrid($trending, state.trending);
          updateTrendingVisibility();
        });
      }

      function renderAllGrid() {
        const filtered = applySortAndFilter(state.allMovies);
        renderGrid($('#allGrid'), filtered);

        const $empty = $('#allEmpty');
        if (filtered.length === 0) {
          $empty.removeClass('hidden');
        } else {
          $empty.addClass('hidden');
        }
      }

      // Events (real-time)
      function wireEvents() {
        $('#search').on('input', function() {
          renderAllGrid();
          updateTrendingVisibility();
        });
        $('#sortSelect, #genreFilter, #yearFilter, #countryFilter').on('change', function() {
          renderAllGrid();
          updateTrendingVisibility();
        });
      }

      $(function() {
        initialLoad();
        wireEvents();
      });
    })(jQuery);
  </script>
</body>

</html>