<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#101529",
                    }
                }
            }
        };
    </script>
    <script defer>
        document.addEventListener("DOMContentLoaded", function () {
            // Toggle Sidebar
            document.querySelector("#menu-toggle").addEventListener("click", function () {
                document.querySelector("#sidebar").classList.toggle("-translate-x-full");
            });

            // Toggle Submenu
            document.querySelectorAll(".submenu-toggle").forEach(function (toggle) {
                toggle.addEventListener("click", function () {
                    this.nextElementSibling.classList.toggle("hidden");
                });
            });
        });
    </script>
</head>
<body class="flex bg-gray-100">

<!-- Sidebar -->
<div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-primary text-white p-5 transform -translate-x-full transition-transform md:translate-x-0">
    <h2 class="text-2xl font-bold text-center mb-5">Youmanitarian</h2>
    
    <ul class="space-y-2">
        <li>
            <a href="../../index.php" class="block px-4 py-2 rounded-lg hover:bg-gray-700">Website</a>
        </li>
        <li>
            <a href="../accounts_roles/manage_user_roles.php" class="block px-4 py-2 rounded-lg hover:bg-gray-700">Manage User Roles</a>
        </li>
        
        <li>
            <button class="submenu-toggle w-full text-left px-4 py-2 rounded-lg hover:bg-gray-700">Content Management ▼</button>
            <ul class="hidden pl-5 mt-2 space-y-1">
                <li><a href="../content/content.php" class="block px-3 py-2 rounded-lg hover:bg-gray-600">Manage Content</a></li>
                <li><a href="../content/content_blog.php" class="block px-3 py-2 rounded-lg hover:bg-gray-600">View Content</a></li>
                <li><a href="../content/content_request_create.php" class="block px-3 py-2 rounded-lg hover:bg-gray-600">Create Content Request</a></li>
                <li><a href="../content/content_editor.php" class="block px-3 py-2 rounded-lg hover:bg-gray-600">Create Content</a></li>
            </ul>
        </li>

        <li>
            <button class="submenu-toggle w-full text-left px-4 py-2 rounded-lg hover:bg-gray-700">Extra Features ▼</button>
            <ul class="hidden pl-5 mt-2 space-y-1">
                <li><a href="../chat/chat.php" class="block px-3 py-2 rounded-lg hover:bg-gray-600">Chat</a></li>
                <li><a href="../../chatbot/index.html" class="block px-3 py-2 rounded-lg hover:bg-gray-600">Chatbot</a></li>
                <li><a href="../../weather-forecast/index.html" class="block px-3 py-2 rounded-lg hover:bg-gray-600">Weather Forecast</a></li>
            </ul>
        </li>
    </ul>
</div>

<!-- Main Content -->
<div class="flex-1 p-6 md:ml-64 transition-all">
    <button id="menu-toggle" class="md:hidden fixed top-4 left-4 text-white bg-primary p-2 rounded-lg">
        ☰
    </button>
<!-- </div> -->

</body>
</html>
