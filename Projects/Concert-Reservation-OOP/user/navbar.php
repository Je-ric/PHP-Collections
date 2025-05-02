
<?php

$email = $_SESSION['email']; 
$usern = $_SESSION['username'];

?>      

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
            <link rel="icon" href="../images/S LOGO.png" type="image/icon type">

    <style>
        <?php 
            include '..\css\home.css'; 
        ?>
    </style>
        </head>
        <body>
        <div class="header">
             <div class="left-side">
                <a href="index.php"> <img class="logo" src="../images/LOGO.png" alt=""> </a>
                <a class="navs" href="index.php">Concerts</a>
                <a class="navs" href="reservations.php">View Reservation</a>
                <div class="dropdown">
                    <a href="index.php" class="navs">Pages</a>
                    <div class="pages">
                    <a class="navs2" href="view_blogs.php">Blogs</a>
                    <a class="navs2" href="contact_us.php">Contact Us</a>
                    <a class="navs2" href="about_us.php">About Us</a>

                    </div>
                </div>
             </div>
             <div class="right-side">
                <div class="dropdown">
                    <a href="index.php" class="acc-btn"><img src="../images/acc-icon.png"></a>
                    <div class="acc">
                        <p>User name: <?php echo $usern      ?></p>
                        <p>E-mail: <?php echo $email ?></p> 
                        <a href="../logout.php" class="log-in-btn" href="">Log out</a>
                    </div>
                </div>
             </div>
        </div>

        </body>
        </html>