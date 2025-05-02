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
      <title>Admin</title>
      <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
      <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
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
            <h2>Concerts</h2>
        </div>

        <!-- dsiplay ng content -->
        <div class="content-pos">
            <div class="home-content">
                <?php 
                    include 'config.php';
                    
                    $query = "SELECT * FROM Concerts c
                    JOIN Venues v ON c.venueID = v.venueID";
          
          
                    $result = mysqli_query($conn, $query);
  
                    while ($row = mysqli_fetch_assoc($result)) {
                        $formatted_date = date("F j, Y", strtotime($row['date']));

                        echo "<div class='concerts'>";

                        echo "<a href='concert_details.php?id=".$row['concertID']."'>";
                        echo "
                        <div class='img-container'>
                            <img src='../concert-images/".$row['image']."' alt='Concert Poster' class='image'>
                            <div class='middle'>
                            <div class='text'>view details</div>
                            </div>
                        </div> ";
                        echo "<h3>" . $formatted_date . "</h3>";
                        echo "<p>" . $row['concertName'] . "</p>";
                        echo "<div class='loc-img'><img src='../images/location-icon.png' style'width: 20px;'>";
                        echo "<p>" . $row['venueLocation'] . "</p> </div>";
                        echo "</a>";

                        echo "</div>";

                    }
                ?>
            </div>
        </div>
</body>
</html>

