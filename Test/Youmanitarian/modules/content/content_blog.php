<?php
session_start();
include('../../config.php'); 

$query = "SELECT * FROM content WHERE status = 'published' ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Published Content</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@1.14.3/dist/full.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<?php include('../../includes/sidebar.php'); ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-6">Latest Published Content</h1>

    <?php if ($result->num_rows > 0): ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card bg-white shadow-lg rounded-lg overflow-hidden">
                    <?php if (!empty($row['image_content'])): ?>
                        <img src="../../<?= htmlspecialchars($row['image_content']) ?>" alt="Content Image" class="w-full h-48 object-cover">
                    <?php endif; ?>

                    <div class="p-4">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($row['title']) ?></h2>
                        
                        <div class="text-gray-600 line-clamp-3 prose">
                            <?= substr(strip_tags($row['body']), 0, 150) ?>...
                        </div>

                        <p class="text-sm text-gray-500 mt-2">Published on: <?= date("F j, Y", strtotime($row['created_at'])) ?></p>

                        <a href="content_view.php?id=<?= $row['id'] ?>" class="btn btn-primary mt-4 w-full">Read More</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-gray-500">No published content available.</p>
    <?php endif; ?>
</div>

</body>
</html>
