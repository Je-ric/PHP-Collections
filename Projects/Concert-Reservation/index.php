<?php 
    session_start();
    if (isset($_SESSION['log-customer'])) {
        header('location: user/index.php');
        exit();
    } elseif (isset($_SESSION['log-admin'])) {
        header('location: admin/index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
      <title>Concert Reservation</title>
      <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
      <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="css/home.css">
  </head>

    <body>
        <!-- nav-bar -->
        <div class="header">
             <div class="left-side">
                <a href="index.php"> <img class="logo" src="images/LOGO.png" alt=""> </a>
                <a class="navs" href="login.php">Concerts</a>
                <a class="navs" href="login.php">View Reservation</a>
             </div>
             <div class="right-side">
                <div class="dropdown">
                    <a href="login.php" class="acc-btn"><img src="images/acc-icon2.png"></a>
                    <div class="acc">
                        <a href="login.php" class="log-in-btn" href="">Log in</a>
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
                    include 'user/config.php';
                    
                    $query = "SELECT c.date, c.concertName, c.image, v.venueLocation 
                    FROM Concerts c
                    JOIN Venues v ON c.venueID = v.venueID";
          
          
                    $result = mysqli_query($conn, $query);
  
                    while ($row = mysqli_fetch_assoc($result)) {
                        $formatted_date = date("F j, Y", strtotime($row['date']));

                        echo "<div class='concerts'>";

                        echo "<a href='login.php'>";
                        echo "
                        <div class='img-container'>
                            <img src='concert-images/".$row['image']."' alt='Concert Poster' class='image'>
                            <div class='middle'>
                            <div class='text'>Buy Ticket</div>
                            </div>
                        </div> ";
                        echo "<h3>" . $formatted_date . "</h3>";
                        echo "<p>" . $row['concertName'] . "</p>";
                        echo "<div class='loc-img'><img src='images/location-icon.png' style'width: 20px;'>";
                        echo "<p>" . $row['venueLocation'] . "</p> </div>";
                        echo "</a>";

                        echo "</div>";

                    }
                ?>
            </div>
        </div>
        
    </body>
</html>

