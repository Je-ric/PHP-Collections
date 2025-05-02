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
    <?php
    session_start();
    include 'config.php';

    if(isset($_POST['selectedSeats'], $_POST['concertID'], $_POST['paymentMethod'])) {
        $concertID = mysqli_real_escape_string($conn, $_POST['concertID']);
        $selectedSeats = $_POST['selectedSeats'];
        $paymentMethod = mysqli_real_escape_string($conn, $_POST['paymentMethod']);

        $concertQuery = "SELECT c.*, CONCAT(v.venueName, ', ', v.venueLocation) AS venue FROM Concerts c JOIN Venues v ON c.venueID = v.venueID WHERE c.concertID = '$concertID'";
        $concertResult = mysqli_query($conn, $concertQuery);

        if(mysqli_num_rows($concertResult) == 1) {
            $concert = mysqli_fetch_assoc($concertResult);
            $formatted_date = date("F j, Y", strtotime($concert['date']));

            $userID = $_SESSION['user_id'];
            $userQuery = "SELECT * FROM Users WHERE userID = '$userID'";
            $userResult = mysqli_query($conn, $userQuery);
            $user = mysqli_fetch_assoc($userResult);

            $ticketPriceQuery = "SELECT ticketPrice FROM Concerts WHERE concertID = '$concertID'";
            $ticketPriceResult = mysqli_query($conn, $ticketPriceQuery);
            $ticketPriceRow = mysqli_fetch_assoc($ticketPriceResult);
            $ticketPrice = $ticketPriceRow['ticketPrice'];
            $totalPrice = count($selectedSeats) * $ticketPrice;

            foreach ($selectedSeats as $seatNumber) {
                $seatNumber = mysqli_real_escape_string($conn, $seatNumber);
                $seatIDQuery = "SELECT seatID FROM Seats WHERE concertID = '$concertID' AND seatNumber = '$seatNumber'";
                $seatIDResult = mysqli_query($conn, $seatIDQuery);
                $seatIDRow = mysqli_fetch_assoc($seatIDResult);
                $seatID = $seatIDRow['seatID'];

                $reserveQuery = "INSERT INTO Reservations (userID, concertID, seatID, numberOfTickets, paymentMethod, totalPrice) 
                                VALUES ('$userID', '$concertID', '$seatID', 1, '$paymentMethod', '$totalPrice')";
                mysqli_query($conn, $reserveQuery);

                $updateQuery = "UPDATE Seats SET availability = 'reserved' WHERE concertID = '$concertID' AND seatID = '$seatID'";
                mysqli_query($conn, $updateQuery);
            }
            ?>
            
            <div class='label-pos'>
                <h2>Reservation Details</h2>
            </div>

            <div class='details-pos'>
                <div class='details-content'>
                    <div class='left-details'>
                        <img src='../concert-images/<?php echo $concert['image']; ?>' class='img'>
                    </div>

                    <div class='right-details'>
                        <h3><?php echo $concert['concertName']; ?></h3>
                        <div class='con-details'>
                            <div class='content'>
                                <img src='../images/mic-icon.png' class='img2'>
                                <p><strong>Artist: </strong> <br><?php echo $concert['artist']; ?></p>
                            </div>
                            <div class='content'>
                                <img src='../images/date-icon.png' class='img2'>
                                <p><strong>Date: </strong> <br><?php echo $formatted_date; ?></p>
                            </div>
                            <div class='content'>
                                <img src='../images/location-icon.png' class='img2'>
                                <p><strong>Venue: </strong> <br><?php echo $concert['venue']; ?></p>
                            </div>
                            <div class='content'>
                                <img src='../images/ticket-icon.png' class='img2'>
                                <p><strong>Ticket Price: </strong> <br> ₱<?php echo $concert['ticketPrice']; ?></p>
                            </div>
                            <div class='content'>
                                <img src='../images/ticket-icon.png' class='img2'>
                                <p><strong>Reserved Seat:</strong> <br> <?php echo implode(', ', $selectedSeats); ?></p>
                            </div>
                            <div class='content'>
                                <img src='../images/ticket-icon.png' class='img2'>
                                <p><strong>Payment Method:</strong> <br> <?php echo $paymentMethod; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class='label-pos'>
                <div style="width: 1135px; text-align: end;">
                <a class="back-btn" href="view_concert.php?id=<?php echo $concertID; ?>">Back</a>
                </div>
            </div>

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


<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require 'C:\xampp\htdocs\sendemail\phpmailer\src\Exception.php';
// require 'C:\xampp\htdocs\sendemail\phpmailer\src\PHPMailer.php';
// require 'C:\xampp\htdocs\sendemail\phpmailer\src\SMTP.php';

//change nalang yung directory
require 'C:\xampp\htdocs\Concert-Reservation\sendemail\phpmailer\src\Exception.php';
require 'C:\xampp\htdocs\Concert-Reservation\sendemail\phpmailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\Concert-Reservation\sendemail\phpmailer\src\SMTP.php';

if (isset($_POST['selectedSeats'], $_POST['concertID'], $_POST['paymentMethod'])) {
    $concertID = mysqli_real_escape_string($conn, $_POST['concertID']);
    $selectedSeats = $_POST['selectedSeats'];
    $paymentMethod = mysqli_real_escape_string($conn, $_POST['paymentMethod']);

    
    $concertQuery = "SELECT c.*, CONCAT(v.venueName, ', ', v.venueLocation) AS venue FROM Concerts c JOIN Venues v ON c.venueID = v.venueID WHERE c.concertID = '$concertID'";
    $concertResult = mysqli_query($conn, $concertQuery);

    if (mysqli_num_rows($concertResult) == 1) {
        $concert = mysqli_fetch_assoc($concertResult);
        $formatted_date = date("F j, Y", strtotime($concert['date']));

        
        $userID = $_SESSION['user_id'];
        $userQuery = "SELECT * FROM Users WHERE userID = '$userID'";
        $userResult = mysqli_query($conn, $userQuery);
        $user = mysqli_fetch_assoc($userResult);

        
        $ticketPriceQuery = "SELECT ticketPrice FROM Concerts WHERE concertID = '$concertID'";
        $ticketPriceResult = mysqli_query($conn, $ticketPriceQuery);
        $ticketPriceRow = mysqli_fetch_assoc($ticketPriceResult);
        $ticketPrice = $ticketPriceRow['ticketPrice'];
        $totalPrice = count($selectedSeats) * $ticketPrice;

        
        foreach ($selectedSeats as $seatNumber) {
            $seatNumber = mysqli_real_escape_string($conn, $seatNumber);
            $seatIDQuery = "SELECT seatID FROM Seats WHERE concertID = '$concertID' AND seatNumber = '$seatNumber'";
            $seatIDResult = mysqli_query($conn, $seatIDQuery);
            $seatIDRow = mysqli_fetch_assoc($seatIDResult);
            $seatID = $seatIDRow['seatID'];

            $reserveQuery = "INSERT INTO Reservations (userID, concertID, seatID, numberOfTickets, paymentMethod, totalPrice) 
                            VALUES ('$userID', '$concertID', '$seatID', 1, '$paymentMethod', '$totalPrice')";
            mysqli_query($conn, $reserveQuery);

            $updateQuery = "UPDATE Seats SET availability = 'reserved' WHERE concertID = '$concertID' AND seatID = '$seatID'";
            mysqli_query($conn, $updateQuery);
        }

        
        $to = $user['email']; 
        $subject = 'Reservation Details';
        $message = "
        Your Reservation Details:<br>
        Concert: {$concert['concertName']}<br>
        Artist: {$concert['artist']}<br>
        Date: {$formatted_date}<br>
        Venue: {$concert['venue']}<br>
        Ticket Price: ₱{$concert['ticketPrice']}<br>
        Reserved Seat(s): " . implode(', ', $selectedSeats) . "<br>
        Payment Method: {$paymentMethod}<br>
        Total Price: ₱{$totalPrice}<br><br>

        Thank you for your reservation!
        ";
        
        $mail = new PHPMailer(true);
        // $mail->SMTPDebug = 2; 0 for no output
        $mail->SMTPDebug = 0;
        $mail->SMTPAutoTLS = false;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        try {
            
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'jericjdelacruz@gmail.com'; 
            $mail->Password   = 'ivxhidyrloajrple'; 
            $mail->SMTPSecure = 'ssl'; 
            $mail->Port       = 465; 

            $mail->setFrom('jericjdelacruz@gmail.com', 'Your Name'); 
            $mail->addAddress($to); 
            
            $mail->isHTML(true); 
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            echo "<script>alert('Email sent successfully.');</script>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"; 
        }

    } else {
        echo "Concert not found.";
    }
} else {
    echo "Invalid request.";
}
?>