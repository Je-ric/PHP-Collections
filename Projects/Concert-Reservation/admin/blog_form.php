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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image']['name'];
        $target_dir = "../blog_uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    } else {
        $image = ""; 
    }

    $sql = "INSERT INTO BlogPosts (title, content, image, author) VALUES ('$title', '$content', '$image', '$author')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Blog post added successfully');
                window.location.href = 'index.php';
              </script>";
            exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Post Editor</title>
    <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/blog.css">
    <link rel="stylesheet" href="../css/home.css">
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
         <div class="header">
             <div class="left-side">
                <a href="index.php"> <img class="logo" src="../images/LOGO.png" alt=""> </a>
                <a class="navs" href="index.php">Concerts</a>
                <a class="navs" href="add_concert.php">Add Concert</a>
                <div class="dropdown">
                    <a href="index.php" class="navs">Pages</a>
                    <div class="pages">
                    <a class="navs2" href="blog_form.php">blog form</a> 
                    <a class="navs2" href="view_blogs.php">view blogs</a>
                    <a class="navs2" href="contact_us_messages.php">coontact us messages</a>
                    </div>
                </div>
             </div>
             <div class="right-side">
                <div class="dropdown">
                    <a href="index.php" class="acc-btn"><img src="../images/acc-icon.png"></a>
                    <div class="acc">
                        <p>welcome, Admin</p>
                        <p>E-mail: <?php echo $email ?></p> 
                        <a href="../logout.php" class="log-in-btn" href="">Log out</a>
                    </div>
                </div>
             </div>
        </div>
        <div class="label-pos">
        <h2>Blog Post Editor</h2>
        </div>

    <div class="blog-pos2">
        <div class="con-form">
          <form action=" " method="post" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title">
            <label for="author">Author:</label>
            <input type="text" id="author" name="author"><br>
            <label for="content">Content:</label><br>
            <textarea id="editor" name="content"></textarea><br>
            <label for="image">Image (optional):</label><br>
            <input type="file" id="image" name="image"><br>
            <!-- <label for="ratings">Ratings:</label><br>
            <input type="number" id="ratings" name="ratings" min="0" max="5"><br> -->
            <input type="submit" value="Submit">
          </form>
        </div>
    </div>

</body>
</html>
