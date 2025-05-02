<?php 
    session_start();
    if(empty($_SESSION['log-customer'])) {
        header('location: ../login.php');
        exit();
    }
    if(isset($_SESSION['log-admin']) ) {
        header('location: ../login.php');
        exit();
    }   
    $usern = $_SESSION['username'];
    $email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Blog Posts</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/blog.css">
    <link rel="icon" href="../images/S LOGO.png" type="image/icon type">
</head>
<body>
    <!-- nav-bar -->
    <?php include('navbar.php'); ?>
    <div class="label-pos">
            <h2>Blogs</h2>
        </div>
        <div class="label-pos">
            <div class="line">line</div>
        </div>
    
    <?php

class BlogPostsPage {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function displayBlogPosts() {

        // Query to fetch blog posts with like and dislike counts
        $limit = 8;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $sql = "SELECT bp.*, 
                    COALESCE(SUM(CASE WHEN pl.action = 'like' THEN 1 ELSE 0 END), 0) AS likes,
                    COALESCE(SUM(CASE WHEN pl.action = 'dislike' THEN 1 ELSE 0 END), 0) AS dislikes
                FROM BlogPosts bp
                LEFT JOIN PostLikes pl ON bp.postID = pl.postID
                GROUP BY bp.postID
                LIMIT $offset, $limit";
        $result = $this->conn->query($sql);

        // Display blog posts
        if ($result->num_rows > 0) {
            echo '<div class="blog-pos"><div class="blog-grid">';
            while($row = $result->fetch_assoc()) {
                ?>
                <div class="blogs">
                    <a href="blog_content.php?id=<?php echo $row['postID']; ?>">
                        <img src="../blog_uploads/<?php echo $row['image']; ?>" alt="Blog Image">
                        <h3><?php echo $row['title']; ?></h3>
                        <div class="author">
                            <img style="width: 20px; height: 20px;" src="../images/acc-icon2.png" alt="">
                            <p>Author: <?php echo $row['author']; ?></p>
                        </div>
                        <div class="blog-cons">
                            <div class="author">
                                <img style="width: 22px; height: 22px;" src="../images/like-icon.png" alt="">
                                <span><?php echo $row['likes']; ?></span>
                            </div>
                            <div class="author">
                                <img style="width: 22px; height: 22px;" src="../images/dislike-icon.png" alt="">
                                <span><?php echo $row['dislikes']; ?></span>
                            </div>
                        </div>
                    </a>
                </div>
                <?php  
            }
            echo '</div></div>';
        } else {
            echo "No blog posts available.";
        }
        
        // Display pagination
        $sql = "SELECT COUNT(*) AS total FROM BlogPosts";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        $total_pages = ceil($row["total"] / $limit);

        echo "<div class='blog-pos'><div class='page-navs'>";
        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a href='?page=$i'>$i</a>";
        }
        echo "</div></div>";
    }
}

include 'config.php';

// Create BlogPostsPage object
$blogPostsPage = new BlogPostsPage($conn);
$blogPostsPage->displayBlogPosts();
?>

</body>
</html>
