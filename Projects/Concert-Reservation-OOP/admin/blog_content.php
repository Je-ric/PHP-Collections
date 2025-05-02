<!DOCTYPE html>
<html lang="en">
  <head>
      <title>Blog Content</title>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
    <style>
        <?php 
            include '..\css\blog.css';
        ?>
    </style>
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

    class BlogContent {
        private $conn;
    
        public function __construct($conn) {
            $this->conn = $conn;
        }
    
        public function deletePost($postID) {
            // Delete post likes
            $sql_delete_likes = "DELETE FROM PostLikes WHERE postID=?";
            $stmt = $this->conn->prepare($sql_delete_likes);
            $stmt->bind_param("i", $postID);
            if ($stmt->execute()) {
                // Delete blog post
                $sql_delete_post = "DELETE FROM BlogPosts WHERE postID=?";
                $stmt = $this->conn->prepare($sql_delete_post);
                $stmt->bind_param("i", $postID);
                if ($stmt->execute()) {
                    return true; // Successfully deleted post
                } else {
                    return "Error deleting blog post: " . $stmt->error;
                }
            } else {
                return "Error deleting post likes: " . $stmt->error;
            }
        }
    
        public function handlePostAction($postID, $userID, $action) {
            // Check if user has already liked the post
            $stmt = $this->conn->prepare("SELECT * FROM PostLikes WHERE postID=? AND userID=?");
            $stmt->bind_param("ii", $postID, $userID);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows == 0) {
                // User has not liked the post, insert new like
                $stmt = $this->conn->prepare("INSERT INTO PostLikes (postID, userID, action) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $postID, $userID, $action);
                if ($stmt->execute()) {
                    return "Action submitted successfully";
                } else {
                    return "Error: " . $stmt->error;
                }
            } else {
                // User has already liked the post, update existing like
                $stmt = $this->conn->prepare("UPDATE PostLikes SET action=? WHERE postID=? AND userID=?");
                $stmt->bind_param("sii", $action, $postID, $userID);
                if ($stmt->execute()) {
                    return "Action updated successfully";
                } else {
                    return "Error: " . $stmt->error;
                }
            }
        }
    }
    
    $blogManager = new BlogContent($conn);
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['delete_post'])) {
            $postID = $_POST['postID'];
            $deleteResult = $blogManager->deletePost($postID);
            if ($deleteResult === true) {
                echo "<script>
                        alert('Blog post deleted successfully');
                        window.location.href = 'index.php';
                    </script>";
                exit();
            } else {
                echo $deleteResult;
            }
        } else {
            $postID = $_POST['postID'];
            $action = $_POST['action'];
            $userID = $_SESSION['user_id'];
            echo $blogManager->handlePostAction($postID, $userID, $action);
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
        <?php include('navbar.php'); ?>
            
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

                    <!-- <form method="post" id="updateForm" action="blog_update.php"> -->

                    <form action="blog_update.php" method="GET">
                        <input type="hidden" name="postID" value="<?php echo $postID; ?>">
                        <input type="submit" value="Update Post">
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
                function confirmUpdate() {
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
