<?php
session_start();

$user = isset($_SESSION['user_id']) ? [
    'id' => $_SESSION['user_id'],
    'name' => $_SESSION['name'] ?? 'Guest',
    'email' => $_SESSION['email'] ?? '',
    'picture' => $_SESSION['picture'] ?? 'default-profile.png'
] : null;
?>
<!-- separate navbar for index and website folder -->
<header class="bg-white shadow-md fixed top-0 left-0 w-full z-50">
    <div class="container mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <img src="../assets/images/logo/YI_Logo.png" alt="Logo" class="h-14 sm:h-16 w-auto">
            <h1 class="text-base sm:text-lg font-bold text-gray-800 whitespace-nowrap">Youmanitarian International</h1>
        </div>

        <nav class="hidden lg:flex items-center space-x-6 text-base">
            <a href="../index.php" class="text-gray-600 hover:text-blue-600">Home</a>
            <a href="/about" class="text-gray-600 hover:text-blue-600">News</a>
            <a href="/services" class="text-gray-600 hover:text-blue-600">Program</a>
            <a href="/contact" class="text-gray-600 hover:text-blue-600">Sponsor & Partnership</a>
            <a href="/about" class="text-gray-600 hover:text-blue-600">About Us</a>
            <a href="/team" class="text-gray-600 hover:text-blue-600">Meet the Team</a>

            <?php if (!$user): ?>
                <a href="google-login.php" class="btn bg-[#101529] text-white border-[#101529] hover:bg-[#1a2235]">Login</a>
                <a href="google-login.php" class="btn btn-outline border-[#101529] text-[#101529] hover:bg-[#101529] hover:text-white">Register</a>
            <?php else: ?>
                <a href="../modules/accounts_roles/account_approval.php" class="btn bg-[#101529] text-white border-[#101529] hover:bg-[#1a2235]">Dashboard</a>
                <a href="../logout.php" class="btn btn-outline border-[#101529] text-[#101529] hover:bg-[#101529] hover:text-white">Logout</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
