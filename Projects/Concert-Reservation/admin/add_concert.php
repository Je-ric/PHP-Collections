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

include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $venueName = mysqli_real_escape_string($conn, $_POST['venueName']);
    $venueLocation = mysqli_real_escape_string($conn, $_POST['venueLocation']);
    $concertName = mysqli_real_escape_string($conn, $_POST['concertName']);
    $artist = mysqli_real_escape_string($conn, $_POST['artist']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $numberOfSeats = $_POST['numberOfSeats']; 
    $ticketPrice = $_POST['ticketPrice']; 
    $ticketPrice = number_format((float)$ticketPrice, 2, '.', ''); 

    $target_dir = "../concert-images/";
    $posterImage = $_FILES['posterImage']['name']; 
    $seatPlanImage = $_FILES['seatPlanImage']['name']; 
//1
    $target_file_poster = $target_dir . basename($_FILES["posterImage"]["name"]);
    $target_file_seatplan = $target_dir . basename($_FILES["seatPlanImage"]["name"]);

    if (move_uploaded_file($_FILES["posterImage"]["tmp_name"], $target_file_poster) &&
        move_uploaded_file($_FILES["seatPlanImage"]["tmp_name"], $target_file_seatplan)) {
   //2     
        
        $venueCheckQuery = "SELECT venueID FROM Venues WHERE venueName = '$venueName' AND venueLocation = '$venueLocation'";
        $venueCheckResult = mysqli_query($conn, $venueCheckQuery);
        
        if (mysqli_num_rows($venueCheckResult) == 0) {
            
            $insertVenueQuery = "INSERT INTO Venues (venueName, venueLocation) VALUES ('$venueName', '$venueLocation')";
            mysqli_query($conn, $insertVenueQuery);
            
            
            $venueID = mysqli_insert_id($conn);
        } else {
            
            $venueIDRow = mysqli_fetch_assoc($venueCheckResult);
            $venueID = $venueIDRow['venueID'];
        }

        
        $insertConcertQuery = "INSERT INTO Concerts (concertName, artist, date, venueID, image, seatPlanImage, numberOfSeats, ticketPrice) 
                               VALUES ('$concertName', '$artist', '$date $time', '$venueID', '$posterImage', '$seatPlanImage', '$numberOfSeats', '$ticketPrice')";
        mysqli_query($conn, $insertConcertQuery);
      //3  
        
        $concertID = mysqli_insert_id($conn);
        for ($i = 1; $i <= $numberOfSeats; $i++) {
            $insertSeatQuery = "INSERT INTO Seats (concertID, seatNumber, seatType, ticketPrice, totalTicketsAvailable, availability) 
                                VALUES ('$concertID', '$i', 'Regular', '$ticketPrice', '$numberOfSeats', 'available')";
            mysqli_query($conn, $insertSeatQuery);
        }
        
        header("Location: index.php");
        exit();
    } else {
        echo "Sorry, there was an error uploading your files.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <title>add concert</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
        <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/form.css">
        <link rel="stylesheet" href="../css/home.css">
    <script>
        function confirmSubmission() {
            return confirm("Are you sure you want to add this concert information?");
        }
    </script>
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
    
    <div class="form-pos">
        <form action="" method="POST" enctype="multipart/form-data" onsubmit="return confirmSubmission()">
        <div class="img-update">
            <div class="con-imgs">
                <input type='file' name='posterImage'>    
                <img class="img" src='../images/sample-template1.jpg' alt='Concert Poster' >
            </div>
               <div class="con-imgs">
                   <input type='file' name='seatPlanImage'>
                   <img class="img" src='../images/sample-template2.jpg' alt='Concert Seat Plan' >
               </div>
            </div>  

            <div class="f-update">
                <div class="ito">
                    <div class="content">
                        <img src="../images/mic-icon.png" class="img2">
                        <p><strong>Concert Name:</strong> <br> <input type='text' name='concertName' required></p>
                    </div>
                </div>
                <div class="ito">
                    <div class="content">
                        <img src="../images/mic-icon.png" class="img2">
                        <p><strong>Artist:</strong> <br> <input type='text' name='artist' required></p>
                    </div>
                </div>
                <div class="ito">
                    <div class="content">
                        <img src="../images/mic-icon.png" class="img2">
                        <p><strong>Date:</strong> <br> <input type='date' name='date'  required></p>
                    </div>
                </div>
                <div class="ito">
                    <div class="content">
                        <img src="../images/mic-icon.png" class="img2">
                        <p><strong>Venue Name:</strong> <br> <input type='text' name='venueName'  required></p>
                    </div>
                </div>
                <div class="ito">
                    <div class="content">
                        <img src="../images/mic-icon.png" class="img2">
                        <p><strong>Venue Location:</strong> <br> <input type='text' name='venueLocation' required></p>
                    </div>
                </div>
                <div class="ito">
                    <div class="content">
                        <img src="../images/mic-icon.png" class="img2">                           
                        <p><strong>Time:</strong> <br> <input type='time' name='time' required></p>
                    </div>
                </div>
                <div class="ito">
                    <div class="content">
                        <img src="../images/mic-icon.png" class="img2">
                        <p><strong>Number of Seats:</strong> <br> <input type='number' name='numberOfSeats' required></p>
                    </div>
                </div>
                <div class="ito">
                    <div class="content">
                        <img src="../images/mic-icon.png" class="img2">
                        <p><strong>Ticket Price:</strong> <br> <input type='number' name='ticketPrice' step='0.01' min='0' required></p>
                    </div>
                </div> 
            </div>

            <input class="update-btn" type="submit" value="Add Concert">
        </form>
       
    </div>
   
</body> 
</html>