<?php
session_start();
require_once __DIR__ . '/../classes/People.php';

if (!isset($_GET['person_id']) || !ctype_digit($_GET['person_id'])) {
    header('Location: ../index.php');
    exit;
}

$personId = (int)$_GET['person_id'];
$people = new People();
$person = $people->getPersonById($personId);
if (!$person) {
    http_response_code(404);
    die('Person not found');
}

$movies = $people->getMoviesByPerson($personId);
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($person['name']) ?> - Filmography</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.24/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-bg': '#0f172a',
                        'secondary-bg': '#1e293b',
                        'card-bg': '#334155',
                        'accent': '#10b981',
                        'border-color': '#475569',
                    },
                }
            }
        }
    </script>
</head>
<body class="min-h-screen text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">

    <?php include __DIR__ . '/../partials/header.php'; ?>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-accent flex items-center gap-3">
                <i class='bx bxs-user-detail'></i>
                <?= htmlspecialchars($person['name']) ?>
            </h1>
            <p class="text-sm text-gray-300 mt-2">Filmography</p>
        </div>

        <?php if (empty($movies)): ?>
            <div class="bg-secondary-bg/90 border border-border-color rounded-lg p-6">
                <p class="text-gray-300">No movies found for this person.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                <?php foreach ($movies as $m): ?>
                    <?php
                        $poster = !empty($m['poster_url']) ? '../' . $m['poster_url'] : 'https://placehold.co/300x450?text=No+Poster';
                        $year = $m['release_year'] ? '(' . htmlspecialchars($m['release_year']) . ')' : '';
                        $role = htmlspecialchars($m['role']);
                    ?>
                    <div class="group rounded-xl overflow-hidden bg-neutral-900 border border-neutral-800 hover:border-accent/70 transition transform hover:-translate-y-2 hover:shadow-xl hover:shadow-green-500/20 flex flex-col">
                        <div class="relative">
                            <a href="viewMovie.php?id=<?= (int)$m['id'] ?>">
                                <img src="<?= htmlspecialchars($poster) ?>" alt="<?= htmlspecialchars($m['title']) ?> poster" class="w-full h-80 object-cover transition-transform duration-300 group-hover:scale-105" />
                            </a>
                            <div class="absolute top-2 left-2">
                                <span class="bg-black/60 text-white text-xs px-2 py-1 rounded-md border border-white/20"><?= $role ?></span>
                            </div>
                        </div>
                        <div class="p-4 flex flex-col flex-grow">
                            <div class="flex-grow">
                                <h5 class="font-semibold text-base mb-1 text-white leading-tight">
                                    <?= htmlspecialchars($m['title']) ?> <small class="text-gray-400 font-normal"><?= $year ?></small>
                                </h5>
                                <div class="text-gray-400 text-xs flex flex-wrap gap-2 mb-1">
                                    <?php if (!empty($m['country_name'])): ?><span class="px-2 py-0.5 rounded bg-neutral-800/70 border border-neutral-700"><?= htmlspecialchars($m['country_name']) ?></span><?php endif; ?>
                                    <?php if (!empty($m['language_name'])): ?><span class="px-2 py-0.5 rounded bg-neutral-800/70 border border-neutral-700"><?= htmlspecialchars($m['language_name']) ?></span><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>

</body>
</html>