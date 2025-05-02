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

class ConcertDetailsPage {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function renderPage() {
        if(isset($_GET['id'])) {   
            $concertID = mysqli_real_escape_string($this->conn, $_GET['id']);

            $concertDetails = $this->getConcertDetails($concertID);

            if($concertDetails) {
                $this->displayConcertDetails($concertDetails);
                $this->displaySeatPlan($concertDetails);
                $this->displayAvailableSeats($concertID);

                $this->displayButtons($concertDetails['concertID']);
            } else {
                echo "Concert not found.";
            }
        } else {
            echo "Invalid request.";
        }
    }

    private function getConcertDetails($concertID) {
        $query = "SELECT c.*, CONCAT(v.venueName, ', ', v.venueLocation) AS venue FROM Concerts c JOIN Venues v ON c.venueID = v.venueID WHERE c.concertID = '$concertID'";
        $result = mysqli_query($this->conn, $query);
        return ($result && mysqli_num_rows($result) == 1) ? mysqli_fetch_assoc($result) : null;
    }

    private function displayConcertDetails($concert) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>Concert details</title>
            <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
            <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
            <style>
                <?php include '..\css\view-concert.css'; ?>
            </style>
        </head>
        <body>
            <!-- nav-bar -->
            <?php include('navbar.php'); ?>
            <div class="label-pos">
                <h2>Concerts Details</h2>
            </div>
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
                                <p><strong>Date:</strong> <br><?php echo date("F j, Y", strtotime($concert['date'])); ?></p>
                            </div>
                            <div class="content">
                                <img src="../images/location-icon.png" class="img2">
                                <p><strong>Venue:</strong> <br><?php echo $concert['venue']; ?></p>
                            </div>
                            <div class="content">
                                <img src="../images/ticket-icon.png" class="img2">
                                <p><strong>Ticket Price:</strong><br>
                                <?php
                                $seatPlanPriceQuery = "SELECT seatPlanName, ticketPrice FROM SeatPlans WHERE concertID = '{$concert['concertID']}'";
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
    }

    private function displaySeatPlan($concert) {
        ?>
            <div class="label-pos">
                <h2>Seat Plan</h2>
            </div>
            <div class="details-pos">
                <div class="seat-content">
                    <div class="seatplan">
                        <img src="../concert-images/<?php echo $concert['seatPlanImage']; ?>" alt="Seat Plan">
                    </div>
                </div>
            </div>
        <?php
    }

    private function displayAvailableSeats($concertID) {
        ?>
            <div class="label-pos">
                <h2>Available Seats</h2>
            </div>
        <?php
        $seatPlanQuery = "SELECT seatPlanID, seatPlanName FROM SeatPlans WHERE concertID = '$concertID'";
        $seatPlanResult = mysqli_query($this->conn, $seatPlanQuery);

        while ($seatPlan = mysqli_fetch_assoc($seatPlanResult)) {
            $seatQuery = "SELECT seatID, seatNumber, availability FROM Seats WHERE concertID = '$concertID' AND seatPlanID = '{$seatPlan['seatPlanID']}'";
            $seatResult = mysqli_query($this->conn, $seatQuery);
            $allSeats = [];

            while ($seatRow = mysqli_fetch_assoc($seatResult)) {
                $allSeats[$seatRow['seatID']] = $seatRow;
            }

            echo "<div class='details-pos' style='margin-bottom: 10px; '>";
            echo "<h3>Seat Plan: {$seatPlan['seatPlanName']}</h3>";
            echo "</div>";
            
            echo "<div class='details-pos'>";
            echo "<div class='seat-content'>";
            
            foreach ($allSeats as $seatID => $seat) {
                $seatNumber = $seat['seatNumber'];
                $availability = $seat['availability'];
                $seatClass = ($availability == 'reserved') ? 'reserved' : 'available';
                echo "<span class='seat $seatClass'>$seatNumber</span>";
            }

            echo "</div>";
            echo "</div>";
        }
    }

    private function displayButtons($concertID) {
        ?>
            <div class="details-pos">
                <div class="btn-pos">
                    <button class='rsrv-seat-btn' onclick="confirmDelete(<?php echo $concertID; ?>)">Delete</button>
                    <button class='rsrv-seat-btn' onclick="confirmUpdate(<?php echo $concertID; ?>)">Update</button>
                    <button class='rsrv-seat-btn' onclick="viewReservations(<?php echo $concertID; ?>)">View Reservations</button>
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
        </body>
        </html>
        <?php
    }
}

$page = new ConcertDetailsPage($conn);
$page->renderPage();
?>
