<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reservations</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
    <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
    <style>
        <?php 
            include '..\css\form.css'; 
        ?>
    </style>
</head>
<body>
<?php
include 'config.php';

class ConcertUpdater {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function sanitizeInput($data) {
        return mysqli_real_escape_string($this->conn, $data);
    }

    public function updateConcert($postData, $filesData) {
        $concertID = $this->sanitizeInput($postData['concertID']);
        $concertName = $this->sanitizeInput($postData['concertName']);
        $artist = $this->sanitizeInput($postData['artist']);
        $date = $this->sanitizeInput($postData['date']);
        $venueName = $this->sanitizeInput($postData['venueName']);
        $venueLocation = $this->sanitizeInput($postData['venueLocation']);
        $time = $this->sanitizeInput($postData['time']);

        $venueQuery = "SELECT venueID FROM Venues WHERE venueName = '$venueName' AND venueLocation = '$venueLocation'";
        $venueResult = mysqli_query($this->conn, $venueQuery);
        if(mysqli_num_rows($venueResult) == 0) {
            $insertVenueQuery = "INSERT INTO Venues (venueName, venueLocation) VALUES ('$venueName', '$venueLocation')";
            mysqli_query($this->conn, $insertVenueQuery);
        }
        $venueIDQuery = "SELECT venueID FROM Venues WHERE venueName = '$venueName' AND venueLocation = '$venueLocation'";
        $venueIDResult = mysqli_query($this->conn, $venueIDQuery);
        $venueIDRow = mysqli_fetch_assoc($venueIDResult);
        $venueID = $venueIDRow['venueID'];

        // Check if poster image is provided
        if (isset($filesData['image']['name']) && !empty($filesData['image']['name'])) {
            $target_dir = "../concert-images/";
            $posterImage = $this->sanitizeInput($filesData['image']['name']);
            $poster_target_file = $target_dir . basename($filesData["image"]["name"]);

            if (move_uploaded_file($filesData["image"]["tmp_name"], $poster_target_file)) {
                $updatePosterQuery = "UPDATE Concerts SET image = '$posterImage' WHERE concertID = '$concertID'";
                mysqli_query($this->conn, $updatePosterQuery);
            } else {
                echo "Sorry, there was an error uploading your poster image.";
                exit();
            }
        }

        // Check if seat plan image is provided
        if (isset($filesData['seatPlanImage']['name']) && !empty($filesData['seatPlanImage']['name'])) {
            $seatPlanImage = $this->sanitizeInput($filesData['seatPlanImage']['name']);
            $seatPlan_target_file = $target_dir . basename($filesData["seatPlanImage"]["name"]);

            if (move_uploaded_file($filesData["seatPlanImage"]["tmp_name"], $seatPlan_target_file)) {
                $updateSeatPlanImageQuery = "UPDATE Concerts SET seatPlanImage = '$seatPlanImage' WHERE concertID = '$concertID'";
                mysqli_query($this->conn, $updateSeatPlanImageQuery);
            } else {
                echo "Sorry, there was an error uploading your seat plan image.";
                exit();
            }
        }

        // Update other concert details
        $updateQuery = "UPDATE Concerts SET concertName = '$concertName', artist = '$artist', date = '$date $time', venueID = '$venueID' WHERE concertID = '$concertID'";
        mysqli_query($this->conn, $updateQuery); 

        // Updating seat plans
        foreach ($postData['seatPlanID'] as $key => $value) {
            $seatPlanID = $this->sanitizeInput($value);
            $seatPlanName = $this->sanitizeInput($postData['seatPlanName'][$key]);
            $ticketPrice = $this->sanitizeInput($postData['ticketPrice'][$key]);

            $updateSeatPlanQuery = "UPDATE SeatPlans SET seatPlanName = '$seatPlanName', ticketPrice = '$ticketPrice' WHERE seatPlanID = '$seatPlanID'";
            mysqli_query($this->conn, $updateSeatPlanQuery);
        }

        header("Location: concert_details.php?id=$concertID");
        exit();
    }

    public function getConcertByID($concertID) {
        $concertID = $this->sanitizeInput($concertID);
        $query = "SELECT c.*, v.venueName, v.venueLocation FROM Concerts c LEFT JOIN Venues v ON c.venueID = v.venueID WHERE concertID = '$concertID'";
        $result = mysqli_query($this->conn, $query);
        return ($result && mysqli_num_rows($result) == 1) ? mysqli_fetch_assoc($result) : null;
    }

    public function getSeatPlansForConcert($concertID) {
        $concertID = $this->sanitizeInput($concertID);
        $seatPlanQuery = "SELECT * FROM SeatPlans WHERE concertID = '$concertID'";
        $seatPlanResult = mysqli_query($this->conn, $seatPlanQuery);
        $seatPlans = [];
        while($row = mysqli_fetch_assoc($seatPlanResult)) {
            $seatPlans[] = $row;
        }
        return $seatPlans;
    }
}

$concertUpdater = new ConcertUpdater($conn);

if(isset($_POST['concertID'])) {
    $concertUpdater->updateConcert($_POST, $_FILES);
}

if(isset($_GET['id'])) {
    $concertID = $_GET['id'];
    $concert = $concertUpdater->getConcertByID($concertID);
    if($concert) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Concert</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
    <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
    <style>
        <?php include '..\css\form.css'; ?>
    </style>
</head>
<body>

<div class="form-pos">
    <h2>Update Concert</h2>
</div>
<div class="form-pos">
    <form id='updateForm' method='POST' enctype='multipart/form-data'>
        <div class="img-update">
            <div class="con-imgs">
                <input type='file' name='image'>    
                <img class="img" src='../concert-images/<?php echo $concert['image']; ?>' alt='Concert Poster' >
            </div>
            <div class="con-imgs">
                <input type='file' name='seatPlanImage'>
                <img class="img" src='../concert-images/<?php echo $concert['seatPlanImage']; ?>' alt='Concert Seat Plan' >
            </div>
        </div>
        
        <div class="f-update">
    <input type='hidden' name='concertID' value='<?php echo $concert['concertID']; ?>'>
    <div class="ito">
        <div class="content">
            <img src="../images/mic-icon.png" class="img2">
            <p><strong>Concert Name:</strong> <br> <input type='text' name='concertName' value='<?php echo $concert['concertName']; ?>' required></p>
        </div>
    </div>
    <div class="ito">
        <div class="content">
            <img src="../images/mic-icon.png" class="img2">
            <p><strong>Artist:</strong> <br> <input type='text' name='artist' value='<?php echo $concert['artist']; ?>' required></p>
        </div>
    </div>
    <div class="ito">
        <div class="content">
            <img src="../images/mic-icon.png" class="img2">
            <p><strong>Date:</strong> <br> <input type='date' name='date' value='<?php echo date('Y-m-d', strtotime($concert['date'])); ?>' required></p>
        </div>
    </div>
    <div class="ito">
        <div class="content">
            <img src="../images/mic-icon.png" class="img2">
            <p><strong>Venue Name:</strong> <br> <input type='text' name='venueName' value='<?php echo $concert['venueName']; ?>' required></p>
        </div>
    </div>
    <div class="ito">
        <div class="content">
            <img src="../images/mic-icon.png" class="img2">
            <p><strong>Venue Location:</strong> <br> <input type='text' name='venueLocation' value='<?php echo $concert['venueLocation']; ?>' required></p>
        </div>
    </div>
    <div class="ito">
        <div class="content">
            <img src="../images/mic-icon.png" class="img2">                           
            <p><strong>Time:</strong> <br> <input type='time' name='time' value='<?php echo date("H:i", strtotime($concert['date'])); ?>' required></p>
        </div>
    </div>
    <?php
    // Fetch seat plans for the concert
    $seatPlans = $concertUpdater->getSeatPlansForConcert($concertID);
    if(!empty($seatPlans)) {
        foreach ($seatPlans as $row) {
    ?>
            <div class="ito">
                <div class="content">
                    <img src="../images/mic-icon.png" class="img2">
                    <p><strong>Seat Plan Name:</strong> <br> <input type="text" name="seatPlanName[]" value="<?php echo $row['seatPlanName']; ?>" required></p>
                    <br>
                    <p><strong>Quantity:</strong> <input type="number" value="<?php echo $row['quantity']; ?>" readonly></p>
                    <br>
                    <p><strong>Ticket Price:</strong> <input type="number" name="ticketPrice[]" value="<?php echo $row['ticketPrice']; ?>" required></p>
                    <input type="hidden" name="seatPlanID[]" value="<?php echo $row['seatPlanID']; ?>">
                </div>
            </div>
    <?php
        }
    }
    ?>
</div>

       
        <button class="update-btn" type='button' onclick='confirmUpdate()'>Update</button>
        <a class="update-btn" href="concert_details.php?id=<?php echo  $concertID ?>" >back</a>

    </form>
    
    <script>
        function confirmUpdate() {
            if (confirm('Are you sure you want to update this concert?')) {
                document.getElementById('updateForm').submit();
            }
        }
    </script>
</div>

</body>
</html>

<?php
    } else {
        echo "Concert not found.";
    }
} else {
    echo "Invalid request.";
}
?>
