<?php
session_start();

class ReservationManager
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function cancelReservation($reservationID)
    {
        $reservationID = mysqli_real_escape_string($this->conn, $reservationID);

        $seatIDQuery = "SELECT seatID FROM Reservations WHERE reservationID = '$reservationID'";
        $seatIDResult = mysqli_query($this->conn, $seatIDQuery);
        $seatIDRow = mysqli_fetch_assoc($seatIDResult);
        $seatID = $seatIDRow['seatID'];

        $updateSeatQuery = "UPDATE Seats SET availability = 'available' WHERE seatID = '$seatID'";
        mysqli_query($this->conn, $updateSeatQuery);

        $cancelQuery = "DELETE FROM Reservations WHERE reservationID = '$reservationID'";
        mysqli_query($this->conn, $cancelQuery);
    }

    public function getUserReservations($userID)
    {
        $userID = mysqli_real_escape_string($this->conn, $userID);

        $query = "SELECT r.*, c.concertName, c.artist, c.date, CONCAT(v.venueName, ', ', v.venueLocation) AS venue, s.seatNumber, sp.seatPlanName
                  FROM Reservations r
                  JOIN Concerts c ON r.concertID = c.concertID
                  JOIN Venues v ON c.venueID = v.venueID
                  JOIN Seats s ON r.seatID = s.seatID
                  JOIN SeatPlans sp ON s.seatPlanID = sp.seatPlanID
                  WHERE r.userID = '$userID'";
        $result = mysqli_query($this->conn, $query);

        return $result;
    }
}

include 'config.php';

$reservationManager = new ReservationManager($conn);

if (isset($_POST['cancel_reservation'])) {
    $reservationID = $_POST['reservation_id'];
    $reservationManager->cancelReservation($reservationID);
}

$userID = $_SESSION['user_id'];
$reservations = $reservationManager->getUserReservations($userID);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reservation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
    <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/view-concert.css">
    <link rel="icon" href="../images/S LOGO.png" type="image/icon type">
</head>
<body>
    <!-- nav-bar -->
    <?php include('navbar.php'); ?>
    
    <div class="label-pos">
        <h2>Your reservations</h2>
    </div>

    <!-- reservation contents -->

    <?php
    if (mysqli_num_rows($reservations) > 0) {
        echo "<div class='tab-pos'> <table>";
        echo "<tr>
                <th>Concert Name</th>
                <th>Artist</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Seat Plan</th>
                <th>Seat Number</th>
                <th>Action</th>
            </tr>";

        while ($row = mysqli_fetch_assoc($reservations)) {
            echo "<tr>";
            echo "<td>" . $row['concertName'] . "</td>";
            echo "<td>" . $row['artist'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>" . $row['venue'] . "</td>";
            echo "<td>" . $row['seatPlanName'] . "</td>";
            echo "<td>" . $row['seatNumber'] . "</td>";
            echo "<td>
                    <form method='post' onsubmit='return confirm(\"Are you sure you want to cancel this reservation?\")'>
                        <input type='hidden' name='reservation_id' value='" . $row['reservationID'] . "'>
                        <button class='cancel-btn' type='submit' name='cancel_reservation'>Cancel</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        echo "</table> </div>";
    } else {
        echo " <div class='label-pos'>
        <p>no reservations yet</p>
       </div> ";
    }
    ?>
</body>
</html>
