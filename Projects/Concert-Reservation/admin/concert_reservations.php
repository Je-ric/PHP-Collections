
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Reservations</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
        <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/home.css">
        <link rel="stylesheet" href="../css/view-concert.css">
    </head>
    <body>

        <?php
    include 'config.php';

    if(isset($_GET['id'])) { 
        $concertID = mysqli_real_escape_string($conn, $_GET['id']);
        $query = "SELECT r.*, u.username, s.seatNumber 
                FROM Reservations r
                JOIN Users u ON r.userID = u.userID
                JOIN Seats s ON r.seatID = s.seatID
                WHERE r.concertID = '$concertID'";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) > 0) {
    ?>
        <div class="label-pos">
            <h2>Reservations</h2>
        </div>

            <div class="tab-pos">
                <table>
                    <tr>
                        <th>Username</th>
                        <th>Seat Number</th>
                        <th>Payment</th>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['seatNumber']; ?></td>
                            <td><?php echo $row['paymentMethod']; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
            <br>
            <div class="details-pos">
                <a class="select-seat-btn" href="concert_details.php?id=<?php echo  $concertID ?>" >back</a>
            </div>
        </div>
    <?php
        } else {
            echo "No reservations for this concert.";
        }
    } else {
        echo "Invalid request.";
    }
    ?>
        
    </body>
</html>


