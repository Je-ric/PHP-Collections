<?php
session_start();
include 'config.php'; 

$query = "SELECT * FROM content WHERE status = 'published' ORDER BY created_at DESC LIMIT 6";
$result = $conn->query($query);

$featuredPost = null;
$latestPosts = [];
if ($result->num_rows > 0) {
    $featuredPost = $result->fetch_assoc();
    while ($row = $result->fetch_assoc()) {
        $latestPosts[] = $row;
    }
}

$user = isset($_SESSION['user_id']) ? [
    'id' => $_SESSION['user_id'],
    'name' => $_SESSION['name'] ?? 'Guest',
    'email' => $_SESSION['email'] ?? 'N/A',
    'picture' => $_SESSION['picture'] ?? 'default-profile.png'
] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.10/dist/full.css" rel="stylesheet">
</head>
<body class="bg-gray-100 pt-20">

<header class="bg-white shadow-md fixed top-0 left-0 w-full z-50">
    <div class="container mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <img src="assets/images/logo/YI_Logo.png" alt="Logo" class="h-14 sm:h-16 w-auto">
            <h1 class="text-base sm:text-lg font-bold text-gray-800 whitespace-nowrap">Youmanitarian International</h1>
        </div>

        <nav class="hidden lg:flex items-center space-x-6 text-base">
            <a href="index.php" class="text-gray-600 hover:text-blue-900">Home</a>
            <a href="/about" class="text-gray-600 hover:text-blue-900">News</a>
            <a href="/services" class="text-gray-600 hover:text-blue-900">Program</a>
            <a href="/contact" class="text-gray-600 hover:text-blue-900">Sponsor & Partnership</a>
            <a href="/about" class="text-gray-600 hover:text-blue-900">About Us</a>
            <a href="/team" class="text-gray-600 hover:text-blue-900">Meet the Team</a>

            <?php if (!$user): ?>
                <a href="google-login.php" class="btn bg-[#101529] text-white border-[#101529] hover:bg-[#1a2235]">Login</a>
                <a href="google-login.php" class="btn btn-outline border-[#101529] text-[#101529] hover:bg-[#101529] hover:text-white">Register</a>
            <?php else: ?>
                <a href="modules/accounts_roles/account_approval.php" class="btn bg-[#101529] text-white border-[#101529] hover:bg-[#1a2235]">Dashboard</a>
                <a href="logout.php" class="btn btn-outline border-[#101529] text-[#101529] hover:bg-[#101529] hover:text-white">Logout</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

    <section class="h-screen flex flex-col items-center justify-center text-center px-6">
        <h2 class="text-5xl font-extrabold text-gray-800 pt-6">Our Purpose</h2>
        <p class="mt-4 text-gray-600 text-lg max-w-2xl">Get the latest updates and deeper connection with us!</p>
        <?php if ($featuredPost): ?>
            <a href="website/view_content.php?id=<?= $featuredPost['id'] ?>" class="container mx-auto px-4 py-8 flex justify-center">
                <div class="w-full md:w-10/12 lg:w-8/12">
                    <?php if (!empty($featuredPost['image_content'])): ?>
                        <img src="uploads/content/<?= htmlspecialchars(basename($featuredPost['image_content'])) ?>"
                            alt="Featured Content Image" class="w-full h-96 object-cover rounded-2xl">
                    <?php endif; ?>
                    <div class="pt-6 pl-2 text-left">
                        <h2 class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($featuredPost['title']) ?></h2>
                        <p class="text-sm text-gray-500 mt-2">Published on: <?= date("F j, Y", strtotime($featuredPost['created_at'])) ?></p>
                    </div>
                </div>
            </a>
        <?php endif; ?>
    </section>

    <div class="container mx-auto px-4 py-6 bg-white flex justify-center">
        <div class="w-9/12">
            <?php if (!empty($latestPosts)): ?>
                <div class="grid gap-4">
                    <?php foreach ($latestPosts as $post): ?>
                        <hr class="border-gray-300">
                        <a href="website/view_content.php?id=<?= $post['id'] ?>" class="block w-11/12 mx-auto no-underline">
                            <div class="bg-white flex flex-col md:flex-row items-center w-full hover:bg-gray-200 transition duration-200">
                                <?php if (!empty($post['image_content'])): ?>
                                    <img src="uploads/content/<?= htmlspecialchars(basename($post['image_content'])) ?>"
                                        alt="Content Image" class="md:w-1/4 w-full h-40 object-cover">
                                <?php endif; ?>
                                <div class="p-4 flex flex-col justify-center md:w-3/4">
                                    <h2 class="text-lg font-bold text-gray-800"><?= htmlspecialchars($post['title']) ?></h2>
                                    <p class="text-xs text-gray-500 mt-1">Published on: <?= date("F j, Y", strtotime($post['created_at'])) ?></p>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-500">No published content available.</p>
            <?php endif; ?>
            <div class="flex justify-center mt-4">
                <a href="#" class="btn btn-outline border-[#101529] text-[#101529] hover:bg-[#101529] hover:text-white">
                    Load More
                </a>
            </div>
        </div>
    </div>

    <?php if ($user): ?>
        <div class="bg-white p-8 rounded-lg shadow-lg text-center">
            <img src="<?= htmlspecialchars($user['picture']) ?>" class="w-20 h-20 rounded-full mx-auto mb-4">
            <h1 class="text-2xl font-bold"><?= htmlspecialchars($user['name']) ?></h1>
            <p class="text-gray-600"><?= htmlspecialchars($user['email']) ?></p>
            <a href="logout.php" class="mt-4 block px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Logout</a>
        </div>
    <?php endif; ?>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
