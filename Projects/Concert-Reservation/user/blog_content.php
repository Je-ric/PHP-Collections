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
        .likes-dislikes {
            margin-bottom: 20px;
        }
        .likes-dislikes button {
            background-color: white;
            cursor: pointer;
        }
        .likes-dislikes button.liked {
            background-color: blue;
        }
        .likes-dislikes button.disliked {
            background-color: red;
        }
        .likes-dislikes button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
<div class="container">
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
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_SESSION['user_id'])) {
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
    } else {
        echo "Please log in to like or dislike.";
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
                <a class="navs" href="reservations.php">View Reservation</a>
                <div class="dropdown">
                    <a href="index.php" class="navs">Pages</a>
                    <div class="pages">
                    <a class="navs2" href="view_blogs.php">blogs</a>
                    <a class="navs2" href="contact_us.php">contact us</a>
                    <a class="navs2" href="about_us.php">about us</a>

                    </div>
                </div>
             </div>
             <div class="right-side">
                <div class="dropdown">
                    <a href="index.php" class="acc-btn"><img src="../images/acc-icon.png"></a>
                    <div class="acc">
                        <p>User name: <?php echo $usern ?></p>
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


        <?php
        echo '<div class="blog-pos">';

            if(isset($_SESSION['user_id'])) {
                $userID = $_SESSION['user_id'];
                $sql_user_action = "SELECT action FROM PostLikes WHERE postID='$postID' AND userID='$userID'";
                $result_user_action = $conn->query($sql_user_action);
                if ($result_user_action->num_rows > 0) {
                    $row_user_action = $result_user_action->fetch_assoc();
                    $user_action = $row_user_action['action'];
                    
                    echo "<div class='likes-dislikes'>";
                    echo "<button class='" . ($user_action == 'like' ? 'liked' : '') . "' onclick=\"likeDislike('$postID', 'like')\">Like</button>";
                    echo "<button class='" . ($user_action == 'dislike' ? 'disliked' : '') . "' onclick=\"likeDislike('$postID', 'dislike')\">Dislike</button>";
                    echo "</div>";
                } else {
                    
                    echo "<div class='likes-dislikes'>";
                    echo "<button onclick=\"likeDislike('$postID', 'like')\">Like</button>";
                    echo "<button onclick=\"likeDislike('$postID', 'dislike')\">Dislike</button>";
                    echo "</div>";
                }
            } else {
                
                echo "<div class='likes-dislikes'>";
                echo "<button onclick=\"likeDislike('$postID', 'like')\">Like</button>";
                echo "<button onclick=\"likeDislike('$postID', 'dislike')\">Dislike</button>";
                echo "</div>";
            }

            
            $sql_likes = "SELECT COUNT(*) AS likes FROM PostLikes WHERE postID='$postID' AND action='like'";
            $result_likes = $conn->query($sql_likes);
            $row_likes = $result_likes->fetch_assoc();
            $likes = $row_likes['likes'];

            $sql_dislikes = "SELECT COUNT(*) AS dislikes FROM PostLikes WHERE postID='$postID' AND action='dislike'";
            $result_dislikes = $conn->query($sql_dislikes);
            $row_dislikes = $result_dislikes->fetch_assoc();
            $dislikes = $row_dislikes['dislikes'];
        } else {
            echo "Blog post not found.";
        }
    } else {
        echo "Invalid request.";
    }

    echo ' ';

    echo ' </div> ';

    echo '<div class="blog-pos">';
    echo "<p style='margin-left: 10px;'>Likes: $likes | Dislikes: $dislikes</p>";

    echo ' </div> ';
?>
    <div class="blog-pos">
        <div style=" width: 1135px; text-align: end;">
        <a href="view_blogs.php" style="text-decoration: none;">
            <button>back</button>       
        </a> 
        </div>
    </div>

<script>
function likeDislike(postID, action) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) {
                
                window.location.reload();
            } else {
                
                alert("Error: " + this.responseText);
            }
        }
    };
    xhttp.open("POST", "<?php echo $_SERVER['PHP_SELF']; ?>", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("postID=" + postID + "&action=" + action);
}
</script>

</div>
</body>
</html>
