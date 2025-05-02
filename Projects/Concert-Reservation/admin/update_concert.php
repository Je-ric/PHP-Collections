<!DOCTYPE html>
<html lang="en">
    <head>
         <title>Reservations</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
        <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/form.css">
    </head>
    <body>
    <?php

        include 'config.php';

        function sanitize_input($conn, $data) {
            return mysqli_real_escape_string($conn, $data);
        }

        if(isset($_POST['concertID'])) {
            
            $concertID = sanitize_input($conn, $_POST['concertID']);

            
            $concertName = sanitize_input($conn, $_POST['concertName']);
            $artist = sanitize_input($conn, $_POST['artist']);
            $date = sanitize_input($conn, $_POST['date']);
            $venueName = sanitize_input($conn, $_POST['venueName']);
            $venueLocation = sanitize_input($conn, $_POST['venueLocation']);
            $time = sanitize_input($conn, $_POST['time']);
            $numberOfSeats = sanitize_input($conn, $_POST['numberOfSeats']);
            $ticketPrice = sanitize_input($conn, $_POST['ticketPrice']);

            
            $venueQuery = "SELECT venueID FROM Venues WHERE venueName = '$venueName' AND venueLocation = '$venueLocation'";
            $venueResult = mysqli_query($conn, $venueQuery);
            if(mysqli_num_rows($venueResult) == 0) {
                
                $insertVenueQuery = "INSERT INTO Venues (venueName, venueLocation) VALUES ('$venueName', '$venueLocation')";
                mysqli_query($conn, $insertVenueQuery);
            }

            
            $venueIDQuery = "SELECT venueID FROM Venues WHERE venueName = '$venueName' AND venueLocation = '$venueLocation'";
            $venueIDResult = mysqli_query($conn, $venueIDQuery);
            $venueIDRow = mysqli_fetch_assoc($venueIDResult);
            $venueID = $venueIDRow['venueID'];

            
            if(isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                
                $target_dir = "../concert-images/";
                $image = $_FILES['image']['name'];
                $target_file = $target_dir . basename($_FILES["image"]["name"]);

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    
                    $updateQuery = "UPDATE Concerts SET concertName = '$concertName', artist = '$artist', date = '$date $time', venueID = '$venueID', image = '$image', numberOfSeats = '$numberOfSeats', ticketPrice = '$ticketPrice' WHERE concertID = '$concertID'";
                    mysqli_query($conn, $updateQuery); 
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    exit();
                }
            } else {
                
                $updateQuery = "UPDATE Concerts SET concertName = '$concertName', artist = '$artist', date = '$date $time', venueID = '$venueID', numberOfSeats = '$numberOfSeats', ticketPrice = '$ticketPrice' WHERE concertID = '$concertID'";
                mysqli_query($conn, $updateQuery); 
            }

            
            if(isset($_FILES['seatPlanImage']['name']) && !empty($_FILES['seatPlanImage']['name'])) {
                
                $target_dir = "../concert-images/";
                $seatPlanImage = $_FILES['seatPlanImage']['name'];
                $target_file = $target_dir . basename($_FILES["seatPlanImage"]["name"]);

                if (move_uploaded_file($_FILES["seatPlanImage"]["tmp_name"], $target_file)) {
                    
                    $updateSeatPlanQuery = "UPDATE Concerts SET seatPlanImage = '$seatPlanImage' WHERE concertID = '$concertID'";
                    mysqli_query($conn, $updateSeatPlanQuery); 
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    exit();
                }
            }

            
            header("Location: concert_details.php?id=$concertID");
            exit();
        }


        if(isset($_GET['id'])) {
            
            $concertID = sanitize_input($conn, $_GET['id']);

            
            $query = "SELECT c.*, v.venueName, v.venueLocation FROM Concerts c LEFT JOIN Venues v ON c.venueID = v.venueID WHERE concertID = '$concertID'";
            $result = mysqli_query($conn, $query);

            if(mysqli_num_rows($result) == 1) {
                
                $concert = mysqli_fetch_assoc($result);

                ?>

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
                            <div class="ito">
                                <div class="content">
                                    <img src="../images/mic-icon.png" class="img2">
                                    <p><strong>Number of Seats:</strong> <br> <input type='number' name='numberOfSeats' value='<?php echo $concert['numberOfSeats']; ?>' required></p>
                                </div>
                            </div>
                            <div class="ito">
                                <div class="content">
                                    <img src="../images/mic-icon.png" class="img2">
                                    <p><strong>Ticket Price:</strong> <br> <input type='number' name='ticketPrice' step='0.01' min='0' value='<?php echo $concert['ticketPrice']; ?>' required></p>
                                </div>
                            </div> 
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
  