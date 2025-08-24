<?php
session_start();
include('../../config.php'); 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../google-login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? '';


// Check if editing existing content
$is_edit = isset($_GET['id']); 
$content_id = $is_edit ? $_GET['id'] : null;

// Fetch content request details if coming from a request
$is_from_request = isset($_GET['request_id']);
$request_id = $is_from_request ? $_GET['request_id'] : null;
$request_data = null;
$request_data_images = null;

if ($is_from_request) {
    $query = "SELECT * FROM content_requests WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $request_data = $result->fetch_assoc();

    $query = "SELECT * FROM content_request_images WHERE request_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $request_data_images = $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch content if editing
if ($is_edit){
    $query = "SELECT * FROM content WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $content_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $content = $result->fetch_assoc();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $status = $_POST['status'];
    $type = $_POST['type'] ?? 'news';
    $image_path = null;

    $sanitized_title = preg_replace('/[^A-Za-z0-9\-]/', '_', $title);

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../../uploads/content/"; 

        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_filename = $sanitized_title . "." . $file_extension;
        $target_file = $target_dir . $new_filename;
        $image_path = "uploads/content/" . $new_filename;

        // If editing, delete old image before saving new one
        if ($is_edit) {
            $query = "SELECT image_content FROM content WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $content_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if (!empty($row['image_content'])) {
                $old_image_path = "../../" . $row['image_content'];
                if (file_exists($old_image_path)) {
                    unlink($old_image_path); // Delete old image
                }
            }
        }

        // Move the new uploaded file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $_SESSION['message'] = "Error uploading image.";
            header('Location: content_editor.php');
            exit();
        }
    }

    // Insert or update content 
    if ($is_edit) { //update content 
        if (empty($image_path)) {
            $query = "SELECT image_content FROM content WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $content_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $image_path = $row['image_content']; // Get the old image
        }
    
        // Update existing content
        
        // $query = "UPDATE content SET title = ?, body = ?, status = ?, image_content = ?, type = ?, updated_at = NOW() WHERE id = ?";
        // $stmt = $conn->prepare($query);
        // $stmt->bind_param("sssssi", $title, $body, $status, $image_path, $type, $content_id);
        // $stmt->execute();
        if ($status == "published") {
            $query = "UPDATE content SET title = ?, body = ?, status = ?, image_content = ?, type = ?, updated_at = NOW() WHERE id = ?";
        } else {
            $query = "UPDATE content SET title = ?, body = ?, status = ?, image_content = ?, type = ? WHERE id = ?";
        }
        
        $stmt = $conn->prepare($query);
        
        if ($status == "published") {
            $stmt->bind_param("sssssi", $title, $body, $status, $image_path, $type, $content_id);
        } else {
            $stmt->bind_param("sssssi", $title, $body, $status, $image_path, $type, $content_id);
        }
        
        $stmt->execute();
        
    }else { //insert content 
        // Insert new content first
        // function generateSlug($title) {
        //     return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
        // }

        $created_by = $_SESSION['user_id'];
        $query = "INSERT INTO content (title, body, created_by, status, image_content, type) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssisss", $title, $body, $created_by, $status, $image_path, $type);
        $stmt->execute();
        // $slug = generateSlug($title);
        // $query = "INSERT INTO content (title, body, slug, created_by, status, image_content, type) VALUES (?, ?, ?, ?, ?, ?, ?)";
        // $stmt = $conn->prepare($query);
        // $stmt->bind_param("sssisss", $title, $body, $slug, $created_by, $status, $image_path, $type);
        // $stmt->execute();

        $content_id = $conn->insert_id; 
    }

    // Multiple Gallery Images 
    if (!empty($_FILES['gallery_images']['name'][0])) {
        $target_dir = "../../uploads/content_gallery/";

        foreach ($_FILES['gallery_images']['name'] as $key => $name) {
            $file_extension = pathinfo($name, PATHINFO_EXTENSION);
            $new_filename = $sanitized_title . "_" . time() . "_" . $key . "." . $file_extension;
            $target_file = $target_dir . $new_filename;
            $image_path = "uploads/content_gallery/" . $new_filename;

            if (move_uploaded_file($_FILES['gallery_images']['tmp_name'][$key], $target_file)) {
                // Insert in content_images 
                $query = "INSERT INTO content_images (content_id, image_path) VALUES (?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("is", $content_id, $image_path);
                $stmt->execute();
            }
        }
    }

    if ($status == "published") {
        // If content comes from a request, change the request status as completed
        if ($is_from_request) {
            $query = "UPDATE content_requests SET status = 'completed' WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $request_id);
            $stmt->execute();
        }
    
        header('Location: content.php');
    } else {
        header('Location: content_editor.php?id=' . ($content_id ?? $conn->insert_id));
    }
    exit();
}


$tinymceApiKey = $_ENV['TINYMCE_API'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create/Edit Content</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/<?php echo htmlspecialchars($tinymceApiKey); ?>/tinymce/5/tinymce.min.js"></script>
    <script>
    tinymce.init({
        selector: '#body',
        plugins: 'advlist autolink lists link image charmap print preview anchor autoresize',
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | outdent indent | link image',
        menubar: false,
        autoresize_bottom_margin: 20, 
        autoresize_min_height: 300,   
        autoresize_max_height: 800,   
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });
</script>

</head>
<body class="bg-gray-100">
<?php include('../../includes/sidebar.php'); ?>
<div class="max-w-5xl mx-auto p-4 bg-white rounded shadow-md mt-8">
    <h2 class="text-3xl font-bold mb-4 text-center"><?= $is_edit ? 'Edit Content' : 'Create Content' ?></h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="p-4 mb-4 text-white bg-green-500 rounded">
            <?= $_SESSION['message']; ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if ($is_from_request && $request_data): ?>
    <div class="flex p-4 mb-4 bg-blue-100 border-l-4 border-blue-500">
        <div>
            <h3 class="text-xl font-bold text-blue-800">Request Details</h3>
            <p><strong>Title:</strong> <?= htmlspecialchars($request_data['title']) ?></p>
            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($request_data['description'])) ?></p>
            <p><strong>Requested By:</strong> <?= htmlspecialchars($request_data['requested_by']) ?></p>
            <p><strong>Notes:</strong> <?= htmlspecialchars($request_data['notes']) ?></p>
            <p><strong>Created At:</strong> <?= date("F j, Y (g:i a)", strtotime($request_data['created_at'])) ?></p>
    
        </div>
        <?php if (!empty($request_data_images)): ?>
    <div class="mt-2">
        <p><strong>Requested Images:</strong></p>
        <div class="flex gap-2">
            <?php foreach ($request_data_images as $image): ?>
                <div>
                    <img src="../../<?= htmlspecialchars($image['image_url']) ?>" alt="Requested Image" 
                        class="w-40 h-40 object-cover border rounded-md shadow-md">
                        <a href="../../<?= htmlspecialchars($image['image_url']) ?>" download class="btn btn-sm btn-primary mt-2">Download Image (HD)</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

    </div>
<?php endif; ?>


    <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label for="title" class="block text-lg font-medium text-gray-700">Title</label>
            <input type="text" id="title" name="title" value="<?= $is_edit ? htmlspecialchars($content['title']) : '' ?>" required class="mt-2 p-2 w-full border border-gray-300 rounded-md">
        </div>

        <div>
            <label for="body" class="block text-lg font-medium text-gray-700">Body</label>
            <textarea id="body" name="body" required class="mt-2 w-full rounded-md border border-gray-300"><?= $is_edit ? htmlspecialchars($content['body']) : '' ?></textarea>
        </div>

        <div>
    <label class="block text-lg font-medium text-gray-700">Content Type</label>
    <div class="mt-2 flex gap-4">
        <label class="cursor-pointer flex items-center gap-2">
            <input type="radio" name="type" value="news" <?= !$is_edit || $content['type'] == 'news' ? 'checked' : '' ?> class="radio radio-primary">
            <span>News</span>
        </label>
        <label class="cursor-pointer flex items-center gap-2">
            <input type="radio" name="type" value="program" <?= $is_edit && $content['type'] == 'program' ? 'checked' : '' ?> class="radio radio-primary">
            <span>Program</span>
        </label>
    </div>
</div>

<div>
    <label for="image" class="block text-lg font-medium text-gray-700">Upload Image</label>
    <input type="file" id="image" name="image" accept="image/*" class="mt-2 p-2 w-full border border-gray-300">
    
    <?php 
    $image_to_display = $is_edit ? $content['image_content'] : ($is_from_request && isset($request_data['image']) ? $request_data['image'] : '');
    if (!empty($image_to_display)): ?>
        <img id="previewImage" src="../../<?= htmlspecialchars($image_to_display) ?>" alt="Selected Image" class="mt-4 w-full h-48 object-cover rounded-md shadow-md">
    <?php endif; ?>
</div>

        <!-- <div>
            <label for="image" class="block text-lg font-medium text-gray-700">Upload Image</label>
            <input type="file" id="image" name="image" accept="image/*" class="mt-2 p-2 w-full border border-gray-300">
            <?php if ($is_edit && !empty($content['image_content'])): ?>
                <img src="../../<?= htmlspecialchars($content['image_content']) ?>" alt="Current Image" class="mt-4 w-full h-48 object-cover rounded-md shadow-md">
            <?php endif; ?>
        </div> -->

        <div>
    <label for="gallery_images" class="block text-lg font-medium text-gray-700">Upload Gallery Images</label>
    <input type="file" id="gallery_images" name="gallery_images[]" accept="image/*" multiple class="mt-2 p-2 w-full border border-gray-300">

    <?php if ($is_edit): ?>
        <div class="mt-4 grid grid-cols-3 gap-2">
            <?php 
            $query = "SELECT image_path FROM content_images WHERE content_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $content_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()): ?>
                <img src="../../<?= htmlspecialchars($row['image_path']) ?>" alt="Gallery Image" class="w-24 h-24 object-cover rounded-md shadow-md">
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>
        <div class="text-center space-x-2 mt-4">
        <?php if ($is_edit): ?>
            <?php if ($content['status'] == 'draft'): ?>
                <button type="submit" name="status" value="draft" class="px-6 py-2 bg-gray-600 text-white rounded-md">Save as Draft</button>
                <button type="submit" name="status" value="published" class="px-6 py-2 bg-green-600 text-white rounded-md">Publish</button>
            <?php elseif ($content['status'] == 'published'): ?>
                <button type="submit" name="status" value="draft" class="px-6 py-2 bg-red-600 text-white rounded-md">Unpublish</button>
            <?php endif; ?>
        <?php else: ?>
            <button type="submit" name="status" value="draft" class="px-6 py-2 bg-gray-600 text-white rounded-md">Save as Draft</button>
            <button type="submit" name="status" value="published" class="px-6 py-2 bg-green-600 text-white rounded-md">Publish</button>
        <?php endif; ?>
        </div>
    </form>
</div>

</body>
</html>
