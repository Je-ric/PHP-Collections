<?php
session_start();
if (empty($_SESSION['log-admin'])) {
    header('location: ../login.php');
    exit();
}

include 'config.php';

class BlogPostManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function updateBlogPost($postID, $title, $content, $author, $image) {
        $target_dir = "../blog_uploads/";
        $target_file = $target_dir . basename($image);

        // Check if a new image is uploaded
        if (!empty($image)) {
            if (!move_uploaded_file($image, $target_file)) {
                return "Error uploading image";
            }
            // If successful, update the image path in the database
            $image_update_query = "UPDATE BlogPosts SET image = ? WHERE postID = ?";
            $stmt = $this->conn->prepare($image_update_query);
            $stmt->bind_param("si", $target_file, $postID);
            $stmt->execute();
            $stmt->close();
        }

        // Update title, content, and author
        $update_query = "UPDATE BlogPosts SET title = ?, content = ?, author = ? WHERE postID = ?";
        $stmt = $this->conn->prepare($update_query);
        $stmt->bind_param("sssi", $title, $content, $author, $postID);
        if ($stmt->execute()) {
            return "Blog post updated successfully";
        } else {
            return "Error updating blog post: " . $stmt->error;
        }
    }

    public function getBlogPost($postID) {
        $query = "SELECT * FROM BlogPosts WHERE postID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }
}

$blogPostManager = new BlogPostManager($conn);

$blogPost = null; // Initialize $blogPost variable

// Fetch the blog post data if the request method is GET and postID is set in the URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['postID'])) {
    $postID = $_GET['postID'];
    $blogPost = $blogPostManager->getBlogPost($postID);
}

// Handle the form submission for updating the blog post
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $postID = $_POST['postID'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];
    $image = isset($_FILES['image']) && $_FILES['image']['error'] == 0 ? $_FILES['image']['tmp_name'] : null;

    $result = $blogPostManager->updateBlogPost($postID, $title, $content, $author, $image);
    // Handle result
    echo "<script>alert('$result'); window.location.href = 'view_blogs.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Blog Post</title>
    <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
    <link rel="icon" href="../images/S LOGO.png" type="image/icon type">
    <style>
        <?php 
            include '..\css\blog.css';
        ?>
    </style>
    <script src="https://cdn.tiny.cloud/1/t4h2cnz3i28sm6q7qrrln7tinkzyzssfw5hn15h5ivgzkrhs/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
         tinymce.init({
        selector: '#content', // Make sure this matches the id of your textarea
        plugins: 'lists link imagetools wordcount',
        toolbar: 'undo redo | bold italic | fontsizeselect | bullist numlist ',
        fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
        height: 400
    });

        function confirmSubmission() {
            return confirm('Are you sure you want to update this blog post?');
        }
    </script>
</head>
<body  style="background-color: white;  padding-bottom: 20px;  padding-top: 50px;">
     <!-- nav-bar -->
     <?php include('navbar.php'); ?>

    <div class="label-pos">
    <h2>Update Blog Post</h2>
    </div>

    <div class="blog-pos2">
        <div class="con-form">
        <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="postID" value="<?php echo $postID; ?>">

        <input type="hidden" name="postID" value="<?php echo isset($blogPost['postID']) ? $blogPost['postID'] : ''; ?>">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo isset($blogPost['title']) ? $blogPost['title'] : ''; ?>" required>
        <label for="author">Author:</label>
        <input type="text" id="author" name="author" value="<?php echo isset($blogPost['author']) ? $blogPost['author'] : ''; ?>" required><br>
        <label for="content">Content:</label><br>
        <textarea id="content" name="content" required><?php echo isset($blogPost['content']) ? $blogPost['content'] : ''; ?></textarea><br>
        <label for="image">Current Image:</label><br>
        <?php if (isset($blogPost['image']) && !empty($blogPost['image'])): ?>
            <img src="<?php echo $blogPost['image']; ?>" alt="Current Image" style="max-width: 200px;"><br>
        <?php else: ?>
            <span>No image uploaded</span><br>
        <?php endif; ?>
        <label for="new_image">New Image:</label><br>
        <input type="file" id="new_image" name="image"><br>
        <input type="submit" name="update" value="Update" onclick="return confirmSubmission();"> 
        <a href="blog_content.php?id=<?php echo $postID; ?>">back</a>
        </form>
        </div>
       
    </div>

</body>
</html>
