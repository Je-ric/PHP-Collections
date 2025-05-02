<?php

include 'config.php';


function sanitize_input($conn, $data) {
    return mysqli_real_escape_string($conn, $data);
}

if(isset($_GET['id'])) {
    
    $concertID = sanitize_input($conn, $_GET['id']);

    
    if(isset($_GET['confirm']) && $_GET['confirm'] === 'true') {

        $deleteReservationsQuery = "DELETE FROM Reservations WHERE concertID = '$concertID'";
        if(mysqli_query($conn, $deleteReservationsQuery)) {
            
            $deleteSeatsQuery = "DELETE FROM Seats WHERE concertID = '$concertID'";
            if(mysqli_query($conn, $deleteSeatsQuery)) {
                
                $deleteConcertQuery = "DELETE FROM Concerts WHERE concertID = '$concertID'";
                if(mysqli_query($conn, $deleteConcertQuery)) {
                    
                    header("Location: index.php"); 
                    exit();
                } else {
                    echo "Error deleting concert: " . mysqli_error($conn);
                }
            } else {
                echo "Error deleting seats: " . mysqli_error($conn);
            }
        } else {
            echo "Error deleting reservations: " . mysqli_error($conn);
        }
    } else {
        
        echo "<form method='GET' action='delete_concert.php'>
                <input type='hidden' name='id' value='$concertID'>
                <input type='hidden' name='confirm' value='true'>
                <p>Are you sure you want to delete this concert?</p>
                <button type='submit'>Yes</button>
                <a href='index.php'>No</a>
            </form>";
    }
} else {
    echo "Invalid request.";
}
?>
