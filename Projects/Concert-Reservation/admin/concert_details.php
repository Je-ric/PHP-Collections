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
    <title>Concert details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
    <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/view-concert.css">
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
            <h2>Concerts Details</h2>
        </div>

   

<?php

include 'config.php';

if(isset($_GET['id'])) {
    
    $concertID = mysqli_real_escape_string($conn, $_GET['id']);

    
    $query = "SELECT c.*, CONCAT(v.venueName, ', ', v.venueLocation) AS venue FROM Concerts c JOIN Venues v ON c.venueID = v.venueID WHERE c.concertID = '$concertID'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1) {
        
        $concert = mysqli_fetch_assoc($result);
        $formatted_date = date("F j, Y", strtotime($concert['date']));
        
        $seatQuery = "SELECT seatID, seatNumber FROM Seats WHERE concertID = '$concertID'";
        $seatResult = mysqli_query($conn, $seatQuery);
        $allSeats = [];
        while ($seatRow = mysqli_fetch_assoc($seatResult)) {
            $allSeats[$seatRow['seatID']] = $seatRow['seatNumber'];
        }

        
        $reservedSeatIDs = [];
        $reservedSeatQuery = "SELECT seatID FROM Reservations WHERE concertID = '$concertID'";
        $reservedSeatResult = mysqli_query($conn, $reservedSeatQuery);
        while ($reservedSeatRow = mysqli_fetch_assoc($reservedSeatResult)) {
            $reservedSeatIDs[] = $reservedSeatRow['seatID'];
        } 
        $_SESSION['conID'] = $concert['concertID'];
        ?>
        <div class="details-pos">
            <div class="details-content">
                <div class="left-details">
                    <img src="../concert-images/<?php echo $concert['image']; ?>" class="img">
                </div>
                <div class="right-details">
                    <h3><?php echo $concert['concertName']; ?></h3>
                    <div class="con-details">
                        <div class="content">
                            <img src="../images/mic-icon.png" class="img2">
                            <p><strong>Artist:</strong> <br><?php echo $concert['artist']; ?></p>
                        </div>
                        <div class="content">
                            <img src="../images/date-icon.png" class="img2">
                            <p><strong>Date:</strong> <br><?php echo $formatted_date; ?></p>
                        </div>
                        <div class="content">
                            <img src="../images/location-icon.png" class="img2">
                            <p><strong>Venue:</strong> <br><?php echo $concert['venue']; ?></p>
                        </div>
                        <div class="content">
                            <img src="../images/ticket-icon.png" class="img2">
                            <p><strong>Ticket Price:</strong> <br>₱<?php echo $concert['ticketPrice']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="label-pos">
            <h2>Seat Plan</h2>
        </div>
        
        <div class="details-pos">
            <div class="seat-content">
                <div class="seatplan"><img src="../concert-images/<?php echo $concert['seatPlanImage']; ?>" alt="Seat Plan"></div>
            </div>
        </div>
        
        <div class="label-pos">
            <h2>Available Seats</h2>
        </div>
        
        <div class="details-pos">
            <div class="seat-content">
                <?php
                // Display seats with correct status
                foreach ($allSeats as $seatID => $seatNumber) {
                    $seatClass = (in_array($seatID, $reservedSeatIDs)) ? 'reserved' : 'available';
                    echo "<span class='seat $seatClass'>$seatNumber</span>";
                }
                ?>
            </div>
        </div>

        <div class="details-pos">
            <div class="btn-pos">
                <button class='rsrv-seat-btn' onclick="confirmDelete(<?php echo $concert['concertID']; ?>)">Delete</button>
                <button class='rsrv-seat-btn' onclick="confirmUpdate(<?php echo $concert['concertID']; ?>)">Update</button>
                <button class='rsrv-seat-btn' onclick="viewReservations(<?php echo $concert['concertID']; ?>)">View Reservations</button>
            </div>
        </div>
        
        
        
        <script>
        function confirmDelete(concertID) {
            if (confirm('Are you sure you want to delete this concert?')) {
                window.location.href = 'delete_concert.php?id=' + concertID;
            }
        }
        function confirmUpdate(concertID) {
            if (confirm('Are you sure you want to update this concert?')) {
                window.location.href = 'update_concert.php?id=' + concertID;
            }
        }
        function viewReservations(concertID) {
            window.location.href = 'concert_reservations.php?id=' + concertID;
        }
        </script>
    <?php
        
    } else {
        echo "Concert not found.";
    }
} else {
    echo "Invalid request.";
}
?>
</body>
</html>
