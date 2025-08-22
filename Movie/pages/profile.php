<?php
session_start();
$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
if ($userId <= 0) { header('Location: ../pages/loginRegister.php'); exit; }
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8" />
  <title>My Profile — Personalized Recommendations</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.24/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>
<body class="min-h-screen text-base-content">
  <?php include __DIR__ . '/../partials/header.php'; ?>

  <main class="px-6 md:px-10 py-8 space-y-10">
    <h1 class="text-3xl font-bold">For You</h1>

    <section id="shelfFavGenres" class="space-y-3"></section>
    <section id="shelfFavCountries" class="space-y-3"></section>
    <section id="shelfFavLanguages" class="space-y-3"></section>
    <section id="shelfTopGenres" class="space-y-3"></section>
  </main>

  <?php include __DIR__ . '/../partials/footer.php'; ?>
  <script src="../src/js/jquery.mins.js"></script>
  <script>
    // Simple renderer: always show rating average (or N/A)
    function renderShelf($root, title, items) {
      const cards = (items || []).map(function(m) {
        const rating = (m.avg_rating && !isNaN(parseFloat(m.avg_rating))) ? Number(m.avg_rating).toFixed(1) : 'N/A';
        const reviews = m.total_reviews ? `(${m.total_reviews})` : '';
        const year = m.release_year ? m.release_year : '';
        const country = m.country_name || '';
        const poster = m.poster_path || m.poster || ''; // use if your API provides
        return `
          <a href="../pages/viewMovie.php?id=${m.id}" class="card bg-base-200 hover:bg-base-300 transition">
            <div class="card-body p-4">
              <div class="flex items-start justify-between gap-3">
                <h3 class="card-title text-base line-clamp-2">${m.title || 'Untitled'}</h3>
                <div class="badge badge-primary badge-outline">${rating} ${reviews}</div>
              </div>
              <p class="text-sm opacity-70">${[year, country].filter(Boolean).join(' • ')}</p>
            </div>
          </a>`;
      }).join('');
      $root.html(`
        <h2 class="text-2xl font-semibold">${title}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
          ${cards || '<div class="opacity-70">No items yet.</div>'}
        </div>
      `);
    }

    $(function () {
      // Personalized shelves from activity (favorites + ratings)
      $.getJSON('../db/recommendRequests.php', { action: 'favGenres', limit: 12 }, function (res) {
        if (res && res.success) renderShelf($('#shelfFavGenres'), 'Because you like these genres', res.data);
      });

      $.getJSON('../db/recommendRequests.php', { action: 'favCountries', limit: 12 }, function (res) {
        if (res && res.success) renderShelf($('#shelfFavCountries'), 'From countries you like', res.data);
      });

      $.getJSON('../db/recommendRequests.php', { action: 'favLanguages', limit: 12 }, function (res) {
        if (res && res.success) renderShelf($('#shelfFavLanguages'), 'In languages you like', res.data);
      });

      // User’s top genres -> individual genre shelves
      $.getJSON('../db/recommendRequests.php', { action: 'topGenres', limit: 3 }, function (res) {
        if (!res || !res.success || !Array.isArray(res.data)) return;
        const list = res.data.slice(0, 3); // [{id, name, cnt}]
        if (!list.length) return;
        const $wrap = $('#shelfTopGenres');
        $wrap.append('<h2 class="text-2xl font-semibold">Top genres for you</h2>');
        list.forEach(function (g) {
          const shelfId = 'genreShelf_' + g.id;
          $wrap.append(`<section id="${shelfId}" class="space-y-3"></section>`);
          $.getJSON('../db/recommendRequests.php', { action: 'byGenre', genreId: g.id, limit: 12 }, function (res2) {
            if (res2 && res2.success) renderShelf($('#' + shelfId), g.name, res2.data);
          });
        });
      });
    });
  </script>
</body>
</html>