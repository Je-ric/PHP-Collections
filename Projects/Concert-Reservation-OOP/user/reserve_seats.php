<?php

// Include necessary files
include 'config.php';
//change nalang yung directory
require 'C:\xampp\htdocs\PHP-Projects\Concert-Reservation-OOP\sendemail\phpmailer\src\Exception.php';
require 'C:\xampp\htdocs\PHP-Projects\Concert-Reservation-OOP\sendemail\phpmailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\PHP-Projects\Concert-Reservation-OOP\sendemail\phpmailer\src\SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Define ConcertReservation class
class ConcertReservation
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function reserveSeatsAndSendEmail($postData)
{
    if (isset($postData['selectedSeats'], $postData['concertID'], $postData['paymentMethod'])) {
        $concertID = mysqli_real_escape_string($this->conn, $postData['concertID']);
        $selectedSeats = $postData['selectedSeats'];
        $paymentMethod = mysqli_real_escape_string($this->conn, $postData['paymentMethod']);

        $seatPlanQuery = "SELECT sp.seatPlanID, sp.seatPlanName, sp.ticketPrice 
                            FROM SeatPlans sp 
                            INNER JOIN Seats s ON sp.seatPlanID = s.seatPlanID
                            WHERE s.concertID = '$concertID' AND s.seatNumber IN ('" . implode("','", $selectedSeats) . "')";

        $concertQuery = "SELECT c.*, CONCAT(v.venueName, ', ', v.venueLocation) AS venue 
                            FROM Concerts c 
                            JOIN Venues v ON c.venueID = v.venueID 
                            WHERE c.concertID = '$concertID'";

        $concertResult = mysqli_query($this->conn, $concertQuery);

        if (mysqli_num_rows($concertResult) == 1) {
            $concert = mysqli_fetch_assoc($concertResult);

            $userQuery = "SELECT * FROM Users WHERE userID = '".$_SESSION['user_id']."'";
            $userResult = mysqli_query($this->conn, $userQuery);
            $user = mysqli_fetch_assoc($userResult);

            $seatPlanResult = mysqli_query($this->conn, $seatPlanQuery);
            $seatPlan = mysqli_fetch_assoc($seatPlanResult);

            if ($seatPlan && isset($seatPlan['seatPlanID'])) {
                $ticketPrice = $seatPlan['ticketPrice'];
                $totalPrice = count($selectedSeats) * $ticketPrice;

                // Format date here
                $formattedDate = date("F j, Y", strtotime($concert['date']));

                $emailData = [
                    'concert' => $concert,
                    'selectedSeats' => $selectedSeats,
                    'paymentMethod' => $paymentMethod,
                    'user' => $user,
                    'seatPlan' => $seatPlan,
                    'totalPrice' => $totalPrice,
                    'formattedDate' => $formattedDate // Set the formatted date here
                ];

                // Send email
                $this->sendEmail($emailData);

                // Update seat availability status
                foreach ($selectedSeats as $seatNumber) {
                    $seatNumber = mysqli_real_escape_string($this->conn, $seatNumber);
                    $seatIDQuery = "SELECT seatID FROM Seats WHERE concertID = '$concertID' AND seatNumber = '$seatNumber'";
                    $seatIDResult = mysqli_query($this->conn, $seatIDQuery);
                    $seatIDRow = mysqli_fetch_assoc($seatIDResult);
                    $seatID = $seatIDRow['seatID'];

                    $reserveQuery = "INSERT INTO Reservations (userID, concertID, seatID, numberOfTickets, paymentMethod, totalPrice) 
                                    VALUES ('".$_SESSION['user_id']."', '$concertID', '$seatID', 1, '$paymentMethod', '$totalPrice')";
                    mysqli_query($this->conn, $reserveQuery);

                    $updateQuery = "UPDATE Seats SET availability = 'reserved' WHERE concertID = '$concertID' AND seatID = '$seatID'";
                    mysqli_query($this->conn, $updateQuery);
                }

                // Display reservation details
                $this->displayReservationDetails($emailData);

                return true;
            } else {
                return "Seat plan not found or missing ticket price.";
            }
        } else {
            return "Concert not found.";
        }
    } else {
        return "Invalid request.";
    }
}


    private function sendEmail($data)
    {
        
        $to = $data['user']['email'];
        $subject = 'Reservation Details';
        $message = "
            Your Reservation Details:<br>
            Concert: {$data['concert']['concertName']}<br>
            Artist: {$data['concert']['artist']}<br>
            Date: {$data['formattedDate']}<br>
            Venue: {$data['concert']['venue']}<br>";

        if ($data['seatPlan'] && isset($data['seatPlan']['seatPlanName'], $data['seatPlan']['ticketPrice'])) {
            $seatPlanName = $data['seatPlan']['seatPlanName'];
            $seatPlanPrice = $data['seatPlan']['ticketPrice'];
            $message .= "Seat Plan: $seatPlanName (₱$seatPlanPrice)<br>";
        } else {
            $message .= "Seat plan not found or missing ticket price.<br>";
        }

        $message .= "Reserved Seat(s): " . implode(', ', $data['selectedSeats']) . "<br>
            Payment Method: {$data['paymentMethod']}<br>
            Total Price: ₱{$data['totalPrice']}<br><br>

            Thank you for your reservation!";

        $mail = new PHPMailer(true);

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
            return true;
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    private function displayReservationDetails($data)
    {
        // Display reservation details HTML here
        echo "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Reservation Details</title>
                    <link href='https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap' rel='stylesheet'>
                    <link rel='stylesheet' href='../css/home.css'>
                    <link rel='stylesheet' href='../css/view-concert.css'>
                    <link rel='icon' href='../images/S LOGO.png' type='image/icon type'>
                </head>
                <body style='padding-top: -50px;'>";

        // Display reservation details content here
        echo "<div class='label-pos'>
                <h2>Reservation Details</h2>
            </div>";

        // Display reservation details content here
        echo "<div class='details-pos'>
                <div class='details-content'>
                    <div class='left-details'>
                        <img src='../concert-images/{$data['concert']['image']}' class='img'>
                    </div>

                    <div class='right-details'>
                        <h3>{$data['concert']['concertName']}</h3>
                        <div class='con-details'>
                            <div class='content'>
                                <img src='../images/mic-icon.png' class='img2'>
                                <p><strong>Artist: </strong> <br>{$data['concert']['artist']}</p>
                            </div>
                            <div class='content'>
                                <img src='../images/date-icon.png' class='img2'>
                                <p><strong>Date: </strong> <br>{$data['formattedDate']}</p>
                            </div>
                            <div class='content'>
                                <img src='../images/location-icon.png' class='img2'>
                                <p><strong>Venue: </strong> <br>{$data['concert']['venue']}</p>
                            </div>";

        // Check if seat plan details are available
        if ($data['seatPlan'] && isset($data['seatPlan']['seatPlanName'], $data['seatPlan']['ticketPrice'])) {
            echo "<div class='content'>
                    <img src='../images/seat-icon.png' class='img2'>
                    <p><strong>Seat Plan: </strong> <br>{$data['seatPlan']['seatPlanName']} (₱{$data['seatPlan']['ticketPrice']})</p>
                  </div>";
        } else {
            echo "<p>Seat plan not found or missing ticket price.</p>";
        }

        // Display reservation details content here
        echo "<div class='content'>
                <img src='../images/seat-icon.png' class='img2'>
                <p><strong>Reserved Seat:</strong> <br>" . implode(', ', $data['selectedSeats']) . "</p>
              </div>
              <div class='content'>
                <img src='../images/ticket-icon.png' class='img2'>
                <p><strong>Payment Method:</strong> <br>{$data['paymentMethod']}</p>
              </div>
            </div>
          </div>
          </div>
        </div>";

        // Display back button
        echo "<div class='label-pos'>
        <div style='width: 1135px; text-align: end;'>
                <a class='back-btn' href='view_concert.php?id={$data['concert']['concertID']}'>Back</a>
              </div>
              </div>";

        echo "
        </body>
            </html>";
    }
}

// Usage example
session_start();
$reservation = new ConcertReservation($conn);
$reservationStatus = $reservation->reserveSeatsAndSendEmail($_POST);

if ($reservationStatus === true) {
    echo "<script>alert('Email sent successfully.');</script>";
} else {
    echo "<script>alert('$reservationStatus');</script>";
}

?>