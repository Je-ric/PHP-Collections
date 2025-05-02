<?php
session_start();
if(empty($_SESSION['log-admin'])) {
    header('location: ../login.php');
    exit();
}
if(isset($_SESSION['log-customer']) ) {
    header('location: ../login.php');
    exit();
}   
$email = $_SESSION['email'];

include 'config.php';

class BlogPostManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addBlogPost($title, $content, $author, $image) {
        $target_dir = "../blog_uploads/";
        $target_file = $target_dir . basename($image);

        if (move_uploaded_file($image, $target_file)) {
            $sql = "INSERT INTO BlogPosts (title, content, image, author) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssss", $title, $content, $target_file, $author);

            if ($stmt->execute()) {
                echo "<script>
                alert('Blog post added successfully');
                window.location.href = 'index.php';
              </script>";
            exit();
            } else {
                return "Error adding blog post: " . $stmt->error;
            }
        } else {
            return "Error uploading image";
        }
    }
}

$blogPostManager = new BlogPostManager($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];
    $image = isset($_FILES['image']) && $_FILES['image']['error'] == 0 ? $_FILES['image']['tmp_name'] : null;

    echo $blogPostManager->addBlogPost($title, $content, $author, $image);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Post Editor</title>
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
            selector: '#editor',
            plugins: 'lists link imagetools wordcount',
            toolbar: 'undo redo | bold italic | fontsizeselect | bullist numlist ',
            // wag burahin: thank you 
            // plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
            // toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
            height: 400
        });

        function confirmSubmission() {
            return confirm('Are you sure you want to add this blog post?');
        }
    </script>
</head>
<body style="background-color: white;  padding-bottom: 50px;">
         <!-- nav-bar -->
         <?php include('navbar.php'); ?>

        <div class="label-pos">
        <h2>Blog Post Editor</h2>
        </div>

    <div class="blog-pos2">
        <div class="con-form">
          <form action=" " method="post" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" required><br>
            <label for="content">Content:</label><br>
            <textarea id="editor" name="content" required></textarea><br>
            <label for="image">Image (required):</label><br>
            <input type="file" id="image" name="image" required><br>
            <!-- <label for="ratings">Ratings:</label><br>
            <input type="number" id="ratings" name="ratings" min="0" max="5"><br> -->
            <input type="submit" value="Submit">
          </form>
        </div>
    </div>

</body>
</html>
