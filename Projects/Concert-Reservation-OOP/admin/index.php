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
      <link rel="icon" href="../images/S LOGO.png" type="image/icon type">
      <style>
        <?php 
            include '..\css\home.css';
        ?>
    </style>
    </head>
<body>
     <!-- nav-bar -->
     <?php include('navbar.php'); ?>

        <!-- BANNER -->
        <div class="banner-pos">
          <img src="../images/banner-black-admin.png" alt="">
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

