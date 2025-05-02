
<?php
$email = $_SESSION['email']; 
?>    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
            <a class="navs" href="add_concert.php">Add Concert</a>
                <div class="dropdown">
                        <a href="index.php" class="navs">Pages</a>
                        <div class="pages">
                        <a class="navs2" href="blog_form.php">Blog Form</a> 
                        <a class="navs2" href="view_blogs.php">view Blogs</a>
                        <a class="navs2" href="contact_us_messages.php">Contact Us Messages</a>
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
</body>
</html>