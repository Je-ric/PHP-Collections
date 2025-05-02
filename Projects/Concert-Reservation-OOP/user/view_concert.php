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
<head>
      <title>Concert details</title>
      <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
      <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="../css/view-concert.css">
      <link rel="icon" href="../images/S LOGO.png" type="image/icon type">
</head>
<body>
     <!-- nav-bar -->
       <?php include('navbar.php'); ?>
    
       <?php

class ConcertDetailsPage {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function displayConcertDetails($concertID) {
        $query = "SELECT c.*, CONCAT(v.venueName, ', ', v.venueLocation) AS venue 
                  FROM Concerts c 
                  JOIN Venues v ON c.venueID = v.venueID 
                  WHERE c.concertID = '$concertID'";
        $result = mysqli_query($this->conn, $query);

        if(mysqli_num_rows($result) == 1) {
            $concert = mysqli_fetch_assoc($result);
            $formatted_date = date("F j, Y", strtotime($concert['date']));

            // Display concert details
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
                                <p><strong>Ticket Price:</strong><br>
                                <?php
                                $seatPlanPriceQuery = "SELECT seatPlanName, ticketPrice FROM SeatPlans WHERE concertID = '$concertID'";
                                $seatPlanPriceResult = mysqli_query($this->conn, $seatPlanPriceQuery);

                                while ($seatPlanPrice = mysqli_fetch_assoc($seatPlanPriceResult)) {
                                    echo "{$seatPlanPrice['seatPlanName']}: ₱{$seatPlanPrice['ticketPrice']}<br>";
                                }
                                ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php

            // Display seat plan
            ?>
            <div class="label-pos">
                <h2>Seat Plan</h2>
            </div>
            <div class="details-pos">
                <div class="seat-content">
                    <div class="seatplan"><img src="../concert-images/<?php echo $concert['seatPlanImage']; ?>" alt="Seat Plan"></div>
                </div>
            </div>
            <?php

            // Display available seats for each seat plan
            $seatPlanQuery = "SELECT seatPlanID, seatPlanName FROM SeatPlans WHERE concertID = '$concertID'";
            $seatPlanResult = mysqli_query($this->conn, $seatPlanQuery);

            while ($seatPlan = mysqli_fetch_assoc($seatPlanResult)) {
                $seatQuery = "SELECT seatID, seatNumber, availability FROM Seats WHERE concertID = '$concertID' AND seatPlanID = '{$seatPlan['seatPlanID']}'";
                $seatResult = mysqli_query($this->conn, $seatQuery);
                
                echo "<div class='details-pos' style='margin-bottom: 10px; '>";
                echo "<h3>Seat Plan: {$seatPlan['seatPlanName']}</h3>";
                echo "</div>";

                echo "<div class='details-pos'>";
                echo "<div class='seat-content'>";

                while ($seat = mysqli_fetch_assoc($seatResult)) {
                    $seatNumber = $seat['seatNumber'];
                    $availability = $seat['availability'];
                    $seatClass = ($availability == 'reserved') ? 'reserved' : 'available';
                    echo "<span class='seat $seatClass'>$seatNumber</span>";
                }

                echo "</div>";
                echo "</div>";
            }
            ?>

            <div class="details-pos">
                <div class="select-content">
                    <a class="select-seat-btn" href="select_seats.php?id=<?php echo $concert['concertID']; ?>">Select Seats</a>
                </div>
            </div>
            <?php

        } else {
            echo "Concert not found.";
        }
    }
}

include 'config.php';

if(isset($_GET['id'])) {
    $concertID = mysqli_real_escape_string($conn, $_GET['id']);

    $concertDetailsPage = new ConcertDetailsPage($conn);
    $concertDetailsPage->displayConcertDetails($concertID);
} else {
    echo "Invalid request.";
}
?>

</body>
</html>
