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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Blog Posts</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/blog.css">
    <link rel="stylesheet" href="../css/home.css">
</head>
<body>
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
            <h2>Blogs</h2>
        </div>
        <div class="label-pos">
            <div class="line">line</div>
        </div>

    <!-- DISPLAY CONTENT -->

    <?php
    include 'config.php';   

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
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '
        <div class="blog-pos">
        <div class="blog-grid">';
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
        echo '</div>';
        echo '</div>';

    } else {
        echo "No blog posts available.";
    }

    $sql = "SELECT COUNT(*) AS total FROM BlogPosts";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_pages = ceil($row["total"] / $limit);

    echo "<div class='blog-pos'> <div class='page-navs'> ";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='?page=$i'>$i</a>";
    }
    echo " </div> </div>";
    ?>

</body>
</html>
