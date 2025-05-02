<!DOCTYPE html>
<html lang="en">
  <head>
      <title>Blog Content</title>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="../css/blog.css">
      <link rel="stylesheet" href="../css/home.css">

      <style>
        * {
            color: white;
        }
      </style>
  </head>

  <body>
 
<?php
    session_start();
    if(empty($_SESSION['log-admin'])) {
        header('location: ../login.php');
        exit();
    }
    if(isset($_SESSION['log-customer'])) {
        header('location: ../login.php');
        exit();
    }
    $email = $_SESSION['email'];

    include 'config.php';

    //delete
    if(isset($_POST['delete_post'])) {
        $postID = $_POST['postID'];

        //delete postlike   
        $sql_delete_likes = "DELETE FROM PostLikes WHERE postID='$postID'";
        if ($conn->query($sql_delete_likes) === TRUE) {
            //delete post
            $sql_delete_post = "DELETE FROM BlogPosts WHERE postID='$postID'";
            if ($conn->query($sql_delete_post) === TRUE) {
                echo "<script>
                    alert('Blog post deleted successfully');
                    window.location.href = 'index.php';
                </script>";
                exit();
            } else {
                echo "Error deleting blog post: " . $conn->error;
            }
        } else {
            echo "Error deleting post likes: " . $conn->error;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $postID = $_POST['postID'];
        $action = $_POST['action']; 

        
        $userID = $_SESSION['user_id'];

        
        $sql_check = "SELECT * FROM PostLikes WHERE postID='$postID' AND userID='$userID'";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows == 0) {
            
            $sql_insert = "INSERT INTO PostLikes (postID, userID, action) VALUES ('$postID', '$userID', '$action')";
            if ($conn->query($sql_insert) === TRUE) {
                echo "Action submitted successfully";
            } else {
                echo "Error: " . $sql_insert . "<br>" . $conn->error;
            }
        } else {
            
            $sql_update = "UPDATE PostLikes SET action='$action' WHERE postID='$postID' AND userID='$userID'";
            if ($conn->query($sql_update) === TRUE) {
                echo "Action updated successfully";
            } else {
                echo "Error: " . $sql_update . "<br>" . $conn->error;
            }
        }
    }

    if(isset($_GET['id'])) {
        $postID = $_GET['id'];

        $sql = "SELECT * FROM BlogPosts WHERE postID='$postID'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $title = $row['title'];
            $author = $row['author'];
            $content = $row['content'];
            ?>
            
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
            
            <!-- DISPLAY CONTENT -->
            <div class="label-pos">
              <h2><?php echo $title; ?></h2>
            </div>

            <div class="label-pos">
              <div class="image-blog">
                <img src="../blog_uploads/<?php echo $row['image']; ?>" alt="Blog Image">
              </div>
            </div>

            <div class="label-pos">
                <div class="line">line</div>
            </div>

            <div class="blogC-pos">
                <h4>Author: <?php echo $author; ?></h4>
                <p><?php echo $content; ?></p>
            </div>
            <div class="blog-pos">
                  <!-- Form for deletion -->
                <form method="post" id="deleteForm">
                    <input type="hidden" name="postID" value="<?php echo $postID; ?>">
                    <input type="submit" name="delete_post" value="Delete" onclick="return confirmDelete();">
                </form>

                <a href="view_blogs.php" style="  text-decoration: none;">
                <button>back</button>       
                </a>     
            </div>

            <!-- Confirmation script -->
            <script>
                function confirmDelete() {
                    return confirm('Are you sure you want to delete this blog post?');
                }
            </script>

            <?php
        } else {
            echo "Blog post not found.";
        }
    } else {
        echo "Invalid request.";
    }
?>

 </body>
</html> 
