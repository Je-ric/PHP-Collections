<?php
session_start();
include('../../config.php'); 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../google-login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $notes = $_POST['notes'];
    $requested_by = $_SESSION['user_id'];

    // Insert content request data into the content_requests table
    $stmt = $conn->prepare("INSERT INTO content_requests (title, description, requested_by, notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $title, $description, $requested_by, $notes);
    $stmt->execute();
    
    // Get the last inserted request ID
    $request_id = $stmt->insert_id;

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../../uploads/content_request/";
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $sanitized_title = preg_replace('/[^A-Za-z0-9\-]/', '_', $title);
        $new_filename = $sanitized_title . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $new_filename;
        $image_path = "uploads/content_request/" . $new_filename;

        // Move the uploaded image
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Insert image into content_request_images table
            $stmt_image = $conn->prepare("INSERT INTO content_request_images (request_id, image_url) VALUES (?, ?)");
            $stmt_image->bind_param("is", $request_id, $image_path);
            $stmt_image->execute();
        } else {
            $_SESSION['message'] = "Image upload failed!";
        }
    } else {
        $_SESSION['message'] = "Please upload an image!";
    }

    $_SESSION['message'] = "Content request created successfully!";
    header('Location: content_request_create.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Content Request</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@1.14.3/dist/full.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans">

<?php include('../../includes/sidebar.php'); ?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-400 to-purple-500">
    <div class="container bg-white p-8 rounded-xl shadow-md w-full max-w-3xl">
        <h2 class="text-3xl font-semibold text-center text-gray-800 mb-6">Create Content Request</h2>

        <!-- Message alert -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info mb-4">
                <span><?= $_SESSION['message']; ?></span>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Title Input -->
            <div>
                <label for="title" class="block text-lg font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" required class="input input-bordered w-full mt-2 p-3 text-gray-700 rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="Enter content title">
            </div>

            <!-- Description Input -->
            <div>
                <label for="description" class="block text-lg font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" required class="textarea textarea-bordered w-full mt-2 p-3 text-gray-700 rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="Enter content description"></textarea>
            </div>

            <!-- Notes Input -->
            <div>
                <label for="notes" class="block text-lg font-medium text-gray-700">Notes (Optional)</label>
                <textarea id="notes" name="notes" class="textarea textarea-bordered w-full mt-2 p-3 text-gray-700 rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="Add internal notes or feedback"></textarea>
            </div>

            <!-- Image Upload -->
            <div>
                <label for="image" class="block text-lg font-medium text-gray-700">Upload Image</label>
                <input type="file" id="image" name="image" accept="image/*" required class="file-input file-input-bordered file-input-primary w-full mt-2 transition-all">
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center">
                <button type="submit" class="btn btn-primary w-full py-3 mt-4 text-white text-lg font-semibold rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out">
                    Create Request
                </button>
            </div>
        </form>

    </div>
</div>

</body>
</html>
