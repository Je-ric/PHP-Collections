<?php
// AJAX-first home page; data will be loaded via jQuery without page reloads
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
        <select id="sortSelect" class="select select-bordered select-sm bg-neutral-900 border-neutral-700 text-gray-2 00">
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

    <section id="searchSection" class="mb-10 hidden">
      <div class="border-t border-neutral-800 mb-6"></div>
      <div class="flex items-end justify-between mb-4">
        <h3 class="text-2xl font-semibold">Search results</h3>
        <span id="searchCount" class="text-gray-400 text-sm">0 results</span>
      </div>
      <div id="searchGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6"></div>
    </section>

    <section class="mb-12">
      <div class="border-t border-neutral-800 mb-6"></div>
      <div class="flex items-end justify-between mb-4">
        <h3 class="text-2xl font-semibold">Trending now</h3>
      </div>
      <div id="trendingGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6"></div>
    </section>

    <section class="mb-12">
      <div class="border-t border-neutral-800 mb-6"></div>
      <div class="flex items-end justify-between mb-4">
        <h3 class="text-2xl font-semibold">Latest releases</h3>
      </div>
      <div id="latestGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6"></div>
    </section>

    <div id="genreShelves"></div>

    <div id="personalShelves"></div>

    <div class="border-t border-neutral-800 my-10"></div>

    <section>
      <div class="flex items-end justify-between mb-4">
        <h3 class="text-2xl font-semibold">All movies</h3>
      </div>
      <div id="allGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6"></div>
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
  // Embed all movies so search/filter/sort are 100% client-side
  window.AppData = { allMovies: <?= json_encode($allMovies ?? []) ?> };

    (function($) {
      const state = {
        allMovies: (window.AppData && window.AppData.allMovies) || [],
        trending: [],
        latest: [],
        shelves: [],
        personal: [],
        genres: (window.AppConfig && window.AppConfig.genres) || [],
        userId: (window.AppConfig && window.AppConfig.userId) || 0,
      };

      // Helpers
      function buildCard(m) {
        const poster = m.poster_url && m.poster_url.length ? m.poster_url : 'https://placehold.co/300x450?text=No+Poster';
        const rating = m.avg_rating ? Number(m.avg_rating).toFixed(1) : '';
        const year = m.release_year ? `(${m.release_year})` : '';
        const country = m.country_name ? `<span class="px-2 py-0.5 rounded bg-neutral-800/70 border border-neutral-700">${escapeHtml(m.country_name)}</span>` : '';
        const language = m.language_name ? `<span class="px-2 py-0.5 rounded bg-neutral-800/70 border border-neutral-700">${escapeHtml(m.language_name)}</span>` : '';
        const ratingBadge = rating ? `<div class="absolute top-2 right-2"><span class="bg-green-600/90 text-white font-semibold text-xs px-2 py-1 rounded-md flex items-center gap-1"><i class='bx bxs-star text-yellow-300'></i>${rating}</span></div>` : '';

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

      // Real-time filter + sort (short and readable)
      function applySortAndFilter(list) {
        const q = $('#search').val().trim().toLowerCase();
        const year = $('#yearFilter').val();
        const country = $('#countryFilter').val();
        const sort = $('#sortSelect').val();

        let res = list;
        if (q) res = res.filter(m => String(m.title||'').toLowerCase().includes(q));
        if (year) res = res.filter(m => String(m.release_year||'') === String(year));
        if (country) res = res.filter(m => String(m.country_name||'') === country);

        if (sort === 'year_desc') res = res.slice().sort((a,b)=>(b.release_year||0)-(a.release_year||0));
        else if (sort === 'year_asc') res = res.slice().sort((a,b)=>(a.release_year||0)-(b.release_year||0));
        else if (sort === 'title_asc') res = res.slice().sort((a,b)=>String(a.title||'').localeCompare(String(b.title||'')));
        else if (sort === 'title_desc') res = res.slice().sort((a,b)=>String(b.title||'').localeCompare(String(a.title||'')));

        return res;
      }

      // Populate Year/Country filters based on data
      function populateFilters() {
        const years = [...new Set(state.allMovies.map(m=>m.release_year).filter(Boolean))].sort((a,b)=>b-a);
        const countries = [...new Set(state.allMovies.map(m=>m.country_name).filter(Boolean))].sort((a,b)=>String(a).localeCompare(String(b)));
        const $yf = $('#yearFilter').empty().append('<option value="">All Years</option>');
        const $cf = $('#countryFilter').empty().append('<option value="">All Countries</option>');
        years.forEach(y => $yf.append(`<option value="${y}">${y}</option>`));
        countries.forEach(c => $cf.append(`<option value="${escapeHtml(c)}">${escapeHtml(c)}</option>`));
      }

      // AJAX calls
  function fetchTrending(limit = 12) {
        return $.ajax({
    url: 'db/recommendRequests.php', // use relative path
            method: 'GET',
            data: {
              action: 'trending',
              limit
            }
          })
          .then(res => res && res.success ? res.data : []);
      }

      function fetchLatest(limit = 12) {
        return $.ajax({
    url: 'db/recommendRequests.php',
            method: 'GET',
            data: {
              action: 'latest',
              limit
            }
          })
          .then(res => res && res.success ? res.data : []);
      }

      function fetchSearch(q, limit = 24) {
        if (!q) return Promise.resolve([]);
        return $.ajax({
    url: 'db/recommendRequests.php',
            method: 'GET',
            data: {
              action: 'search',
              q,
              limit
            }
          })
          .then(res => res && res.success ? res.data : []);
      }

      function fetchTopGenres() {
        return $.ajax({
    url: 'db/recommendRequests.php',
            method: 'GET',
            data: {
              action: 'topGenres'
            }
          })
          .then(res => res && res.success ? res.data : []);
      }

      function fetchByGenre(genreId, limit = 6) {
        return $.ajax({
    url: 'db/recommendRequests.php',
            method: 'GET',
            data: {
              action: 'byGenre',
              genre_id: genreId,
              limit
            }
          })
          .then(res => res && res.success ? res.data : []);
      }

      function fetchFavShelves() {
        if (!state.userId) return Promise.resolve({
          genres: [],
          countries: [],
          languages: []
        });
        return Promise.all([
          $.ajax({
            url: 'db/recommendRequests.php',
            method: 'GET',
            data: {
              action: 'favGenres',
              limit: 12
            }
          }),
          $.ajax({
            url: 'db/recommendRequests.php',
            method: 'GET',
            data: {
              action: 'favCountries',
              limit: 12
            }
          }),
          $.ajax({
            url: 'db/recommendRequests.php',
            method: 'GET',
            data: {
              action: 'favLanguages',
              limit: 12
            }
          }),
        ]).then(([g, c, l]) => ({
          genres: g && g.success ? g.data : [],
          countries: c && c.success ? c.data : [],
          languages: l && l.success ? l.data : [],
        }));
      }

      // Build shelves
      function renderShelfSection($parent, title, list) {
        if (!list || list.length === 0) return;
        const section = $(`
      <section class="mb-12">
        <div class="border-t border-neutral-800 mb-6"></div>
        <div class="flex items-end justify-between mb-4">
          <h3 class="text-2xl font-semibold">${escapeHtml(title)}</h3>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6"></div>
      </section>
    `);
        renderGrid(section.find('.grid'), list);
        $parent.append(section);
      }

      // Load initial data
      function initialLoad() {
        const $trending = $('#trendingGrid');
        const $latest = $('#latestGrid');

        // render All grid immediately from embedded data
        populateFilters();
        renderAllGrid();

        // then fetch recommendation shelves
        $.when(fetchTrending(), fetchLatest(), fetchTopGenres())
          .done((tr, la, topG) => {
            state.trending = Array.isArray(tr) ? tr : [];
            state.latest = Array.isArray(la) ? la : [];

            renderGrid($trending, state.trending);
            renderGrid($latest, state.latest);

            // build genre shelves
            const topGenres = Array.isArray(topG) ? topG : [];
            const $genreShelves = $('#genreShelves').empty();
            const requests = topGenres.map(g => fetchByGenre(g.id, 6).then(list => ({
              name: g.name,
              list
            })));
            Promise.all(requests).then(rows => {
              rows.forEach(r => renderShelfSection($genreShelves, `Popular in ${r.name}`, r.list));
            });
          });

  // personalized shelves based on favorites
        fetchFavShelves().then(({
          genres,
          countries,
          languages
        }) => {
          const $personal = $('#personalShelves').empty();
          renderShelfSection($personal, 'Because you like these genres', genres);
          renderShelfSection($personal, 'From countries you favored', countries);
          renderShelfSection($personal, 'In languages you enjoy', languages);
        });

  // allMovies already from AppData
      }

      function dedupeById(list) {
        const map = new Map();
        list.forEach(m => {
          if (!map.has(m.id)) map.set(m.id, m);
        });
        return Array.from(map.values());
      }

      function renderAllGrid() {
        const filtered = applySortAndFilter(state.allMovies);
        renderGrid($('#allGrid'), filtered);
      }

      // Events (real-time)
      function wireEvents() {
        $('#search').on('input', renderAllGrid);
        $('#sortSelect, #genreFilter, #yearFilter, #countryFilter').on('change', renderAllGrid);
      }

      $(function() {
        initialLoad();
        wireEvents();
      });
    })(jQuery);

  </script>
</body>

</html>