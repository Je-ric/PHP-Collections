<?php
session_start();
include('../../config.php'); 

$content_id = $_GET['id'];

// Fetch content details
$query = "SELECT * FROM content WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $content_id);
$stmt->execute();
$result = $stmt->get_result();
$content = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($content['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@1.14.3/dist/full.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<?php include('../../includes/sidebar.php'); ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white p-6 shadow-lg rounded-lg">
        <!-- Display Image if Exists -->
        <?php if (!empty($content['image_content'])): ?>
            <img src="../../<?= htmlspecialchars($content['image_content']) ?>" alt="Content Image" class="w-full h-64 object-cover rounded-lg mb-4">
        <?php endif; ?>

        <h1 class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($content['title']) ?></h1>
        <p class="text-gray-500 mt-2">Published on <?= date("F j, Y", strtotime($content['created_at'])) ?></p>
        <hr class="my-4">
        <div class="prose max-w-none">
            <?= $content['body'] ?>  <!-- This ensures that HTML content is properly rendered -->
        </div>
    </div>
</div>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/daisyui@1.14.3/dist/full.js"></script>
</body>
</html>
