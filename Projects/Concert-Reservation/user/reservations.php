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
    $usern = $_SESSION['username'];
    $email = $_SESSION['email'];

    include 'config.php';

if(isset($_POST['cancel_reservation'])) {
    $reservationID = mysqli_real_escape_string($conn, $_POST['reservation_id']);

    $seatIDQuery = "SELECT seatID FROM Reservations WHERE reservationID = '$reservationID'";
    $seatIDResult = mysqli_query($conn, $seatIDQuery);
    $seatIDRow = mysqli_fetch_assoc($seatIDResult);
    $seatID = $seatIDRow['seatID'];

    // Update seat stauts
    $updateSeatQuery = "UPDATE Seats SET availability = 'available' WHERE seatID = '$seatID'";
    mysqli_query($conn, $updateSeatQuery);

    // Delete the reservationsssssssssss
    $cancelQuery = "DELETE FROM Reservations WHERE reservationID = '$reservationID'";
    mysqli_query($conn, $cancelQuery);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reservation</title>
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
                <a class="navs" href="reservations.php">View Reservation</a>
                <div class="dropdown">
                    <a href="#" class="navs">Pages</a>
                    <div class="pages">
                    <a class="navs2" href="view_blogs.php">blogs</a>
                    <a class="navs2" href="contact_us.php">contact us</a>
                    <a class="navs2" href="about_us.php">about us</a>

                    </div>
                </div>
             </div>
             <div class="right-side">
                <div class="dropdown">
                    <a href="index.php" class="acc-btn"><img src="../images/acc-icon.png"></a>
                    <div class="acc">
                        <p>User name: <?php echo $usern ?></p>
                        <p>E-mail: <?php echo $email ?></p> 
                        <a href="../logout.php" class="log-in-btn" href="">Log out</a>
                    </div>
                </div>
             </div>
        </div>
    <div class="label-pos">
        <h2>Your reservations</h2>
    </div>

    <!-- reservation contents -->

    <?php
    $userID = mysqli_real_escape_string($conn, $_SESSION['user_id']);

    $query = "SELECT r.*, c.concertName, c.artist, c.date, CONCAT(v.venueName, ', ', v.venueLocation) AS venue, s.seatNumber
              FROM Reservations r
              JOIN Concerts c ON r.concertID = c.concertID
              JOIN Venues v ON c.venueID = v.venueID
              JOIN Seats s ON r.seatID = s.seatID
              WHERE r.userID = '$userID'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        echo "<div class='tab-pos'> <table>";
        echo "<tr>
                <th>Concert Name</th>
                <th>Artist</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Seat Number</th>
                <th>Action</th>
            </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['concertName'] . "</td>";
            echo "<td>" . $row['artist'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>" . $row['venue'] . "</td>";
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
