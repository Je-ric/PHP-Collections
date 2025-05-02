<?php
session_start();
include 'config.php';
// BlogContent class for handling blog content and user interactions
class BlogContent
{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    // Method to handle user actions (like or dislike) on a blog post
    public function handleUserAction($postID, $action)
    {
        $conn = $this->conn; // Access the database connection from the class property
    
        if (isset($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
    
            $sql_check = "SELECT * FROM PostLikes WHERE postID='$postID' AND userID='$userID'";
            $result_check = $conn->query($sql_check);
    
            if ($result_check->num_rows == 0) {
                $sql_insert = "INSERT INTO PostLikes (postID, userID, action) VALUES ('$postID', '$userID', '$action')";
                if ($conn->query($sql_insert) === TRUE) {
                    return "Action submitted successfully";
                } else {
                    return "Error: " . $sql_insert . "<br>" . $conn->error;
                }
            } else {
                $sql_update = "UPDATE PostLikes SET action='$action' WHERE postID='$postID' AND userID='$userID'";
                if ($conn->query($sql_update) === TRUE) {
                    return "Action updated successfully";
                } else {
                    return "Error: " . $sql_update . "<br>" . $conn->error;
                }
            }
        } else {
            return "Please log in to like or dislike.";
        }
    }
    

    // Method to fetch blog post details
    public function fetchBlogPost($postID)
    {
        $sql = "SELECT * FROM BlogPosts WHERE postID='$postID'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    // Method to fetch likes and dislikes count for a blog post
    public function fetchLikesDislikesCount($postID)
    {
        $likesSql = "SELECT COUNT(*) AS likes FROM PostLikes WHERE postID='$postID' AND action='like'";
        $likesResult = $this->conn->query($likesSql);
        $likes = $likesResult->fetch_assoc()['likes'];

        $dislikesSql = "SELECT COUNT(*) AS dislikes FROM PostLikes WHERE postID='$postID' AND action='dislike'";
        $dislikesResult = $this->conn->query($dislikesSql);
        $dislikes = $dislikesResult->fetch_assoc()['dislikes'];

        return ['likes' => $likes, 'dislikes' => $dislikes];
    }
}

// Create an instance of BlogContent class
// $blogContent = new BlogContent();
$blogContent = new BlogContent($conn);


// Check if user is logged in as a customer
if (empty($_SESSION['log-customer'])) {
    header('location: ../login.php');
    exit();
}

// Check if user is logged in as an admin
if (isset($_SESSION['log-admin'])) {
    header('location: ../login.php');
    exit();
}

// Get user details from session
$usern = $_SESSION['username'];
$email = $_SESSION['email'];

// Check if form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle user action (like or dislike) on a blog post
    $postID = $_POST['postID'];
    $action = $_POST['action'];
    $resultMessage = $blogContent->handleUserAction($postID, $action);
    echo $resultMessage;
    exit();
}

// Fetch blog post details based on postID from URL parameter
if (isset($_GET['id'])) {
    $postID = $_GET['id'];
    $blogPost = $blogContent->fetchBlogPost($postID);

    if ($blogPost) {
        $title = $blogPost['title'];
        $author = $blogPost['author'];
        $content = $blogPost['content'];
        $image = $blogPost['image'];

        // Fetch likes and dislikes count for the blog post
        $likesDislikesCount = $blogContent->fetchLikesDislikesCount($postID);
        $likes = $likesDislikesCount['likes'];
        $dislikes = $likesDislikesCount['dislikes'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Blog Content</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/blog.css">
    <style>
        /* Additional styles for like/dislike buttons */
        * {
        color: white;
        }
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
        <!-- nav-bar -->
        <?php include('navbar.php'); ?>
            
        <!-- DISPLAY CONTENT -->
        <div class="label-pos">
            <h2><?php echo $title; ?></h2>
        </div>
        <div class="label-pos">
            <div class="image-blog">
                <img src="../blog_uploads/<?php echo $image; ?>" alt="Blog Image">
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
        // Render like/dislike buttons and count
        echo '<div class="blog-pos">';
        if (isset($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
            $sql_user_action = "SELECT action FROM PostLikes WHERE postID='$postID' AND userID='$userID'";
            // $result_user_action = $blogContent->conn->query($sql_user_action);
            $result_user_action = $blogContent->getConnection()->query($sql_user_action);
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
        echo '</div>';

        // Render likes/dislikes count
        echo '<div class="blog-pos">';
        echo "<p style='margin-left: 10px;'>Likes: $likes | Dislikes: $dislikes</p>";
        echo '</div>';
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
<?php
    } else {
        echo "Blog post not found.";
    }
} else {
    echo "Invalid request.";
}
?>
