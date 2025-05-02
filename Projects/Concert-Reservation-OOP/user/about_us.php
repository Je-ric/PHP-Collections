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
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/about-us.css">
    <link rel="icon" href="../images/S LOGO.png" type="image/icon type">
  </head>
  <body>
     <!-- nav-bar -->
     <?php include('navbar.php'); ?>
     
     <div class="label-pos">
         <h2>About Us</h2>
     </div>

    <div class="abt-us-pos">
        <div class="abt-us-grid">
          <img src="../images/BG2.jpg" alt="">

          <div class="abt-cont">
            <h4>WHY CHOOSE  US?</h4>
            <p>
              At Ticket Now, we offer much more than just a ticket booking platform - 
              we're your gateway to unforgettable live music experiences. Our user-friendly 
              website provides you with effortless access to a diverse range of concerts and 
              live events, all from the comfort of your own home. We work directly with trusted 
              venues and event organizers to ensure that the tickets you purchase are always authentic 
              and valid for entry. When you choose Ticket Now, you're not just buying a ticket - you're
              investing in an unforgettable musical experience backed by convenience, variety, security, 
              reliability, and exceptional customer support.
            </p>
            <a href="index.php">Buy ticket now</a>
          </div>
        </div>
     </div>

     <br><br>

     <div class="abt-us-pos">
        <div class="abt-us-grid">
          <div class="abt-cont">
            <h4>WHO  ARE  WE?</h4>
            <p>
            We are Ticket Now, your premier destination for hassle-free concert ticket bookings. 
            We provide a user-friendly platform and a wide selection of events, we cater to music enthusiasts of all tastes. 
            Our commitment to security, reliability, and exceptional customer support ensures that your concert-going experience 
            is nothing short of unforgettable.
            </p>
            <a href="contact_us.php">Contact us</a>
          </div>
          <div class="content2">
          <img src="../images/BG1.jpg" alt="">
          </div>
        </div>
     </div>
    

     <div class="label-pos">
         <h2>Developers</h2>
     </div>


     <div class="devs-pos">
        <div class="devs">
            <img src="../images/dev-jeric.jpg" alt="">
            <div>
              <h4>Jeric Dela Cruz</h4>
              <p>Back-end Developer</p>
            </div>
        </div>

        <div class="devs">
            <img src="../images/dev-kiel.jpg" alt="">
            <div>
              <h4>Kiel Palaad</h4>
              <p>Front-end Developer</p>
            </div>
        </div>

        <div class="devs">
            <img src="../images/dev-menard.jpg" alt="">
            <div>
              <h4>Menard Macaraeg</h4>
              <p>Graphic designer/UI-UX tester</p>
            </div>
        </div>

        <div class="devs">
            <img src="../images/dev-gwen.jpg" alt="">
            <div>
              <h4>Gwyneth De Guzman</h4>
              <p>Graphic designer/UI-UX tester</p>
            </div>
        </div>

        <div class="devs">
            <img src="../images/dev-jodi.jpg" alt="">
            <div>
              <h4>Jodilyn Sarmiento</h4>
              <p>Graphic designer/UI-UX tester</p>
            </div>
        </div>
     </div>

  </body>
</html>