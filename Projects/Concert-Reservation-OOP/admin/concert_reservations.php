<?php

include 'config.php';

class Reservation {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getReservationsByConcertID($concertID) {
        $concertID = mysqli_real_escape_string($this->conn, $concertID);
        $query = "SELECT r.*, u.username, s.seatNumber, sp.seatPlanName 
                  FROM Reservations r
                  JOIN Users u ON r.userID = u.userID
                  JOIN Seats s ON r.seatID = s.seatID
                  JOIN SeatPlans sp ON s.seatPlanID = sp.seatPlanID
                  WHERE r.concertID = '$concertID'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }
}

class ReservationRenderer {
    public function displayReservations($reservations, $concertID) {
?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>Reservations</title>
            <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
            <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
            <style>
                <?php include '..\css\home.css'; ?>
                <?php include '..\css\view-concert.css'; ?>
            </style>
        </head>
        <body>
            <div class="label-pos">
                <h2>Reservations</h2>
            </div>

            <div class="tab-pos">
                <table>
                    <tr>
                        <th>Username</th>
                        <th>Seat Number</th>
                        <th>Seat Plan</th>
                        <th>Payment</th>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($reservations)) {
                    ?>
                        <tr>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['seatNumber']; ?></td>
                            <td><?php echo $row['seatPlanName']; ?></td>
                            <td><?php echo $row['paymentMethod']; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
            <br>
            <div class="details-pos">
                <a class="select-seat-btn" href="concert_details.php?id=<?php echo $concertID; ?>">Back</a>
            </div>
        </body>
        </html>
<?php
    }
}

$reservation = new Reservation($conn);

if(isset($_GET['id'])) { 
    $concertID = $_GET['id'];
    $reservations = $reservation->getReservationsByConcertID($concertID);
    
    if(mysqli_num_rows($reservations) > 0) {
        $display = new ReservationRenderer();
        $display->displayReservations($reservations, $concertID);
    } else {
        echo "No reservations for this concert.";
    }
} else {
    echo "Invalid request.";
}
?>
