<?php
session_start();
include('../config.php'); 

$content_id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'] ?? null; 

// Content
$query = "SELECT * FROM content WHERE id = ? AND status = 'published'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $content_id);
$stmt->execute();
$result = $stmt->get_result();
$content = $result->fetch_assoc();

if (!$content) {
    echo "<h2 class='text-center text-red-500 text-xl mt-10'>Content not found or unpublished.</h2>";
    exit;
}

// Update View Count
$update_views = "UPDATE content SET views = views + 1 WHERE id = ?";
$stmt = $conn->prepare($update_views);
$stmt->bind_param("i", $content_id);
$stmt->execute();

// Gallery Images
$gallery_query = "SELECT image_path FROM content_images WHERE content_id = ?";
$gallery_stmt = $conn->prepare($gallery_query);
$gallery_stmt->bind_param("i", $content_id);
$gallery_stmt->execute();
$gallery_result = $gallery_stmt->get_result();
$gallery_images = $gallery_result->fetch_all(MYSQLI_ASSOC);

// Next & Previous 
$prev_query = "SELECT id FROM content WHERE id < ? AND status = 'published' ORDER BY id DESC LIMIT 1";
$next_query = "SELECT id FROM content WHERE id > ? AND status = 'published' ORDER BY id ASC LIMIT 1";

$prev_stmt = $conn->prepare($prev_query);
$prev_stmt->bind_param("i", $content_id);
$prev_stmt->execute();
$prev_result = $prev_stmt->get_result();
$prev_content = $prev_result->fetch_assoc();

$next_stmt = $conn->prepare($next_query);
$next_stmt->bind_param("i", $content_id);
$next_stmt->execute();
$next_result = $next_stmt->get_result();
$next_content = $next_result->fetch_assoc();

// Other Published Content
$other_query = "SELECT id, title FROM content WHERE id != ? AND status = 'published' ORDER BY created_at DESC LIMIT 10";
$other_stmt = $conn->prepare($other_query);
$other_stmt->bind_param("i", $content_id);
$other_stmt->execute();
$other_result = $other_stmt->get_result();
$other_contents = $other_result->fetch_all(MYSQLI_ASSOC);

// Heart React Count
$heart_count_query = "SELECT COUNT(*) as total FROM heart_reacts WHERE content_id = ?";
$heart_stmt = $conn->prepare($heart_count_query);
$heart_stmt->bind_param("i", $content_id);
$heart_stmt->execute();
$heart_result = $heart_stmt->get_result();
$heart_count = $heart_result->fetch_assoc()['total'];

// Check if user has reacted
$user_reacted = false;
if ($user_id) {
    $check_react_query = "SELECT * FROM heart_reacts WHERE content_id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_react_query);
    $check_stmt->bind_param("ii", $content_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $user_reacted = $check_result->num_rows > 0;
}

// Handle AJAX heart react
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_heart'])) {
    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Login required']);
        exit;
    }

    if ($user_reacted) {
        // Unlike
        $delete_heart_query = "DELETE FROM heart_reacts WHERE content_id = ? AND user_id = ?";
        $delete_stmt = $conn->prepare($delete_heart_query);
        $delete_stmt->bind_param("ii", $content_id, $user_id);
        $delete_stmt->execute();
        $heart_count--;
        $user_reacted = false;
    } else {
        // Like
        $insert_heart_query = "INSERT INTO heart_reacts (content_id, user_id) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_heart_query);
        $insert_stmt->bind_param("ii", $content_id, $user_id);
        $insert_stmt->execute();
        $heart_count++;
        $user_reacted = true;
    }

    echo json_encode(['status' => 'success', 'heart_count' => $heart_count, 'reacted' => $user_reacted]);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($content['title']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
        extend: {},
        },
        plugins: [daisyui],
    };
    </script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.10/dist/full.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="text-gray-900 bg-white">
    <?php include '../includes/navbar.php'; ?>

    <div class="container mx-auto px-6 py-8 mt-20 flex flex-col lg:flex-row gap-6">

        <div class="lg:w-2/3 bg-white text-gray-900 p-8">
            <a href="../index.php" class="btn btn-outline border-[#101529] text-[#101529] hover:bg-[#101529] hover:text-white">← Back</a>

            <?php if (!empty($content['image_content'])): ?>
                <div class="w-full flex justify-center mb-4">
                    <img src="../uploads/content/<?= htmlspecialchars(basename($content['image_content'])) ?>" alt="Content Image" class="rounded-lg max-h-[500px] object-cover">
                </div>
            <?php endif; ?>

            <h1 class="text-4xl font-bold"><?= htmlspecialchars($content['title']) ?></h1>
            <p class="text-gray-500 mt-2">Published on <?= date("F j, Y", strtotime($content['created_at'])) ?> • Views: <?= $content['views'] ?></p>
            <hr class="my-4 border-blue-400">
            <div class="prose prose-lg max-w-none text-gray-800"><?= $content['body'] ?></div>

            <?php if (!empty($gallery_images)): ?>
                <div class="mt-6 grid grid-cols-2 md:grid-cols-3 gap-2 relative">
                    <?php foreach ($gallery_images as $index => $image): ?>
                        <?php if ($index < 4): ?>
                            <div class="relative overflow-hidden rounded-lg cursor-pointer" onclick="openLightbox(<?= $index ?>)">
                                <img src="../<?= htmlspecialchars($image['image_path']) ?>" class="w-full h-32 object-cover" alt="Gallery Image">
                                <?php if ($index === 3 && count($gallery_images) > 4): ?>
                                    <div class="absolute inset-0 bg-black bg-opacity-50 flex justify-center items-center text-white text-xl font-bold">
                                        +<?= count($gallery_images) - 4 ?> more
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="mt-6 flex items-center">
                <button id="heartButton" class="text-2xl focus:outline-none <?= $user_reacted ? 'text-red-600' : 'text-gray-400' ?>">
                    <i class='bx bxs-heart'></i>
                </button>
                <span id="heartCount" class="ml-2 text-lg font-semibold"><?= $heart_count ?></span>
            </div>

            <div id="lightboxModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-80 flex justify-center items-center hidden z-50">
                <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-2xl">&times;</button>

                <button id="prevImage" onclick="changeImage(-1)" class="absolute left-4 text-white text-3xl bg-gray-800 px-3 py-2 rounded-full">❮</button>
                <img id="lightboxImage" class="max-w-full max-h-full rounded-lg">
                <button id="nextImage" onclick="changeImage(1)" class="absolute right-4 text-white text-3xl bg-gray-800 px-3 py-2 rounded-full">❯</button>
            </div>

            <div class="flex justify-between mt-6">
                <?php if ($prev_content): ?>
                    <a href="?id=<?= $prev_content['id'] ?>" class="btn btn-outline border-[#101529] text-[#101529] hover:bg-[#101529] hover:text-white">← Previous</a>
                <?php endif; ?>
                <?php if ($next_content): ?>
                    <a href="?id=<?= $next_content['id'] ?>" class="btn btn-outline border-[#101529] text-[#101529] hover:bg-[#101529] hover:text-white">Next →</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="lg:w-1/3 p-6 bg-gray-200 sticky top-0 h-screen">
            <h2 class="text-2xl font-semibold mb-4">Other Content</h2>
            <ul class="space-y-3">
                <?php foreach ($other_contents as $item): ?>
                    <li>
                        <a href="?id=<?= $item['id'] ?>" class="block p-4 bg-blue-800 text-white hover:bg-blue-900 transition-all duration-200 rounded-md">
                            <?= htmlspecialchars($item['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>



<script>
    let images = <?= json_encode(array_column($gallery_images, 'image_path')) ?>;
    let currentIndex = 0;

    function openLightbox(index) {
        currentIndex = index;
        updateLightbox();
        document.getElementById("lightboxModal").classList.remove("hidden");
        document.addEventListener("keydown", keyboardNavigation);
    }

    function updateLightbox() {
        document.getElementById("lightboxImage").src = "../" + images[currentIndex];
    }

    function changeImage(step) {
        currentIndex = (currentIndex + step + images.length) % images.length; // Loop
        updateLightbox();
    }

    function closeLightbox() {
        document.getElementById("lightboxModal").classList.add("hidden");
        document.removeEventListener("keydown", keyboardNavigation);
    }

    function keyboardNavigation(event) {
        if (event.key === "ArrowRight") { //(event.key === "ArrowRight" && currentIndex < images.length - 1)
            changeImage(1);
        } else if (event.key === "ArrowLeft") { //(event.key === "ArrowLeft" && currentIndex > 0)
            changeImage(-1);
        } else if (event.key === "Escape") {
            closeLightbox();
        }
    }
    
</script>

<script>
$(document).ready(function () {
    $("#heartButton").click(function () {
        $.post(window.location.href, { toggle_heart: true }, function (data) {
            let response = JSON.parse(data);
            if (response.status === "success") {
                $("#heartCount").text(response.heart_count);
                if (response.reacted) {
                    $("#heartButton").addClass("text-red-600").removeClass("text-gray-400");
                } else {
                    $("#heartButton").addClass("text-gray-400").removeClass("text-red-600");
                }
            } else {
                alert(response.message);
            }
        });
    });
});
</script>

</body>
</html>
