
<?php
session_start();
if (empty($_SESSION['log-admin'])) {
    header('location: ../login.php');
    exit();
}
if (isset($_SESSION['log-customer'])) {
    header('location: ../login.php');
    exit();
}
$email = $_SESSION['email'];

include 'config.php';

class VenueManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insertOrUpdateVenue($venueName, $venueLocation) {
        $venueCheckQuery = "SELECT venueID FROM Venues WHERE venueName = ? AND venueLocation = ?";
        $stmt = $this->conn->prepare($venueCheckQuery);
        $stmt->bind_param("ss", $venueName, $venueLocation);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $insertVenueQuery = "INSERT INTO Venues (venueName, venueLocation) VALUES (?, ?)";
            $stmt = $this->conn->prepare($insertVenueQuery);
            $stmt->bind_param("ss", $venueName, $venueLocation);
            $stmt->execute();
            return $stmt->insert_id;
        } else {
            $venueIDRow = $result->fetch_assoc();
            return $venueIDRow['venueID'];
        }
    }
}


class ConcertManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insertConcert($concertName, $artist, $date, $time, $venueID, $posterImage, $seatPlanImage) {
        $insertConcertQuery = "INSERT INTO Concerts (concertName, artist, date, venueID, image, seatPlanImage) 
                               VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($insertConcertQuery);
        $stmt->bind_param("ssssss", $concertName, $artist, $date, $venueID, $posterImage, $seatPlanImage);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function insertSeatPlans($concertID, $planNames, $quantities, $prices) {
        $insertSeatPlanQuery = "INSERT INTO SeatPlans (concertID, seatPlanName, quantity, ticketPrice) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($insertSeatPlanQuery);

        for ($i = 0; $i < count($planNames); $i++) {
            $stmt->bind_param("isdd", $concertID, $planNames[$i], $quantities[$i], $prices[$i]);
            $stmt->execute();
        }
    }

    public function insertSeats($concertID, $seatPlanNames, $quantities, $ticketPrice) {
        $availability = "available";
        $insertSeatQuery = "INSERT INTO Seats (concertID, seatPlanID, seatNumber, ticketPrice, availability) 
                            VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($insertSeatQuery);

        $seatNumber = 1; // Initialize seat number

        foreach ($seatPlanNames as $key => $seatPlanName) {
            $seatPlanID = $this->getSeatPlanID($concertID, $seatPlanName);

            // Insert seats for each seat plan
            for ($i = 1; $i <= $quantities[$key]; $i++) {
                $stmt->bind_param("iiids", $concertID, $seatPlanID, $seatNumber, $ticketPrice, $availability);
                $stmt->execute();
                $seatNumber++; // Increment seat number
            }
        }

        $stmt->close();
    }

    public function getSeatPlanID($concertID, $seatPlanName) {
        $query = "SELECT seatPlanID FROM SeatPlans WHERE concertID = ? AND seatPlanName = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $concertID, $seatPlanName);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['seatPlanID'];
        } else {
            // Handle case where seat plan ID is not found
            return null;
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize connection
    $venueManager = new VenueManager($conn);
    $concertManager = new ConcertManager($conn);

    // Retrieve form data
    $venueName = mysqli_real_escape_string($conn, $_POST['venueName']);
    $venueLocation = mysqli_real_escape_string($conn, $_POST['venueLocation']);
    $concertName = mysqli_real_escape_string($conn, $_POST['concertName']);
    $artist = mysqli_real_escape_string($conn, $_POST['artist']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $ticketPrice = $_POST['ticketPrice'];
    $ticketPrice = number_format((float)$ticketPrice, 2, '.', '');

    // Handle file uploads
    $target_dir = "../concert-images/";
    $posterImage = $_FILES['posterImage']['name'];
    $seatPlanImage = $_FILES['seatPlanImage']['name'];
    $target_file_poster = $target_dir . basename($_FILES["posterImage"]["name"]);
    $target_file_seatplan = $target_dir . basename($_FILES["seatPlanImage"]["name"]);

    if (move_uploaded_file($_FILES["posterImage"]["tmp_name"], $target_file_poster) &&
        move_uploaded_file($_FILES["seatPlanImage"]["tmp_name"], $target_file_seatplan)) {

        // Insert or retrieve venue ID
        $venueID = $venueManager->insertOrUpdateVenue($venueName, $venueLocation);

        // Insert concert details
        $concertID = $concertManager->insertConcert($concertName, $artist, $date, $time, $venueID, $posterImage, $seatPlanImage);

        // Insert seat plans
        $concertManager->insertSeatPlans($concertID, $_POST['seatPlanName'], $_POST['seatPlanQuantity'], $_POST['seatPlanPrice']);

        // Insert seats
        $concertManager->insertSeats($concertID, $_POST['seatPlanName'], $_POST['seatPlanQuantity'], $ticketPrice);

        // Redirect to index page
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
    <title>Add Concert</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
    <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
    <link rel="icon" href="../images/S LOGO.png" type="image/icon type">
    <style>
        <?php 
            include '..\css\form.css';
        ?>
    </style>
    <script>
        function confirmSubmission() {
            return confirm("Are you sure you want to add this concert information?");
        }
        
        var seatPlanCount = 1;

    function addSeatPlan() {
        // Check if the maximum number of seat plans has been reached
        if (seatPlanCount >= 7) {
            alert("Maximum number of seat plans reached (7).");
            return;
        }

        var seatPlansDiv = document.getElementById('seatPlans');
        var newSeatPlan = document.createElement('div');
        newSeatPlan.classList.add('seatPlan');
        newSeatPlan.innerHTML = `
            <div class="ito">
                <div class="content">
                    <img src="../images/seat-icon.png" class="img2">                           
                    <p><strong>Seat Plan Name:</strong> <br> <input type="text" name="seatPlanName[]" required></p>
                </div>
            </div>
            <div class="ito">
                <div class="content">
                    <img src="../images/seat quan.png" class="img2">                           
                    <p><strong>Quantity:</strong> <br> <input type="number" name="seatPlanQuantity[]" required></p>
                </div>
            </div>
            <div class="ito">
                <div class="content">
                    <img src="../images/ticket-icon.png" class="img2">                           
                    <p><strong>Price:</strong> <br> <input type="number" name="seatPlanPrice[]" step="0.01" min="0" required></p>
                </div>
            </div>
            <button class="update-btn" type="button" onclick="removeSeatPlan(this)">Remove</button>
        `;
        seatPlansDiv.appendChild(newSeatPlan);
        
        // Increment the seat plan count
        seatPlanCount++;
    }

    function removeSeatPlan(button) {
        // Get the parent element of the button
        var seatPlanDiv = button.parentElement;

        // Check if the parent element has the id 'originalSeatPlan'
        if (seatPlanDiv.id === 'originalSeatPlan') {
            alert("The original seat plan cannot be removed.");
            return;
        }

        // Remove the seat plan div
        seatPlanDiv.parentElement.removeChild(seatPlanDiv);
        
        // Decrement the seat plan count
        seatPlanCount--;
    }
    </script>
</head>
<body>
    <!-- nav-bar -->
    <?php include('navbar.php'); ?>

    <div class="label-pos">
        <h2>Concerts</h2>
    </div>
    
    <div class="form-pos">
        <form action="" method="POST" enctype="multipart/form-data" onsubmit="return confirmSubmission()">
            <div class="img-update">
                <div class="con-imgs">
                    <input type='file' name='posterImage' required>    
                    <img class="img" src='../images/sample-template1.jpg' alt='Concert Poster' >
                </div>
                <div class="con-imgs">
                    <input type='file' name='seatPlanImage' required>
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
                        <img src="../images/artist.png" class="img2">
                        <p><strong>Artist:</strong> <br> <input type='text' name='artist' required></p>
                    </div>
                </div>
                <div class="ito">
                    <div class="content">
                        <img src="../images/date-icon.png" class="img2">
                        <p><strong>Date:</strong> <br> <input type='date' name='date'  required></p>
                    </div>
                </div>
                <div class="ito">
                    <div class="content">
                        <img src="../images/location-icon.png" class="img2">
                        <p><strong>Venue Name:</strong> <br> <input type='text' name='venueName'  required></p>
                    </div>
                </div>
                <div class="ito">
                    <div class="content">
                        <img src="../images/location-icon.png" class="img2">
                        <p><strong>Venue Location:</strong> <br> <input type='text' name='venueLocation' required></p>
                    </div>
                </div>
                <div class="ito">
                    <div class="content">
                        <img src="../images/time.png" class="img2">                           
                        <p><strong>Time:</strong> <br> <input type='time' name='time' required></p>
                    </div>
                </div>

                <div id="originalSeatPlan">
                    <div class="ito">
                        <div class="content">
                            <img src="../images/seat-icon.png" class="img2">
                            <p><strong>Seat Plan Name:</strong> <br> <input type="text" name="seatPlanName[]" required></p>
                        </div>
                    </div>
                    <div class="ito">
                        <div class="content">
                            <img src="../images/seat quan.png" class="img2">
                            <p><strong>Quantity:</strong> <br> <input type="number" name="seatPlanQuantity[]" required></p>
                        </div>
                    </div>
                    <div class="ito">
                        <div class="content">
                            <img src="../images/ticket-icon.png" class="img2">
                            <p><strong>Price:</strong> <br> <input type="number" name="seatPlanPrice[]" step="0.01" min="0" required></p>
                        </div>
                    </div>
                    <button class="update-btn" type="button" onclick="removeSeatPlan(this)">Remove</button>
                </div>

                    <div id="seatPlans"></div>  
                <div>
                    <button class="update-btn" type="button" onclick="addSeatPlan()">Add Seat Plan</button>
                </div>
            </div>

            <input class="update-btn" type="submit" value="Add Concert">
        </form>
    </div>
</body> 
</html>
