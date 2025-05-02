<?php
include 'config.php';

function sanitize_input($conn, $data) {
    return mysqli_real_escape_string($conn, $data);
}

if(isset($_GET['id'])) {
    $concertID = sanitize_input($conn, $_GET['id']);

    if(isset($_GET['confirm']) && $_GET['confirm'] === 'true') {
        $success = deleteConcert($conn, $concertID);
        if($success) {
            echo "<script>
                alert('Concert and associated data deleted successfully');
                window.location.href = 'index.php';
            </script>";
            exit();
        } else {
            echo "Error deleting concert. Please try again later.";
        }
    } else {
        displayConfirmationForm($concertID);
    }
} else {
    echo "Invalid request.";
}

function deleteConcert($conn, $concertID) {
    $deleteReservationsQuery = "DELETE FROM Reservations WHERE seatID IN (SELECT seatID FROM Seats WHERE concertID = '$concertID')";
    $deleteSeatsQuery = "DELETE FROM Seats WHERE concertID = '$concertID'";
    $deleteSeatPlansQuery = "DELETE FROM SeatPlans WHERE concertID = '$concertID'";
    $deleteConcertQuery = "DELETE FROM Concerts WHERE concertID = '$concertID'";
    
    $queries = [$deleteReservationsQuery, $deleteSeatsQuery, $deleteSeatPlansQuery, $deleteConcertQuery];
    foreach($queries as $query) {
        if(!mysqli_query($conn, $query)) {
            return false; 
        }
    }
    return true; 
}

function displayConfirmationForm($concertID) {
    ?>

    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Delete Concert</title>
            <link rel="stylesheet" href="../css/home.css">
            <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
            <link rel="icon" href="../images/S LOGO.png" type="image/icon type">
            <style>
                *{
                    color: white;
                }
            </style>
        </head>

        <body>
          <form method='GET' action='delete_concert.php'>
              <input type='hidden' name='id' value='<?php echo $concertID; ?>'>
              <input type='hidden' name='confirm' value='true'>
              <div class="banner-pos" style="text-align: center; margin-top: 150px;">
                <p>This action will delete all associated reservations, seats, and seat plans.</p>
                <p>Are you sure you want to delete this concert?</p> <br>
                <button class="yes-delete" type='submit'>Yes</button>
                <a class="no-delete" href='index.php'>No</a>
              </div>
          </form>
        </body>
    </html>
   


 <?php
}
?>
