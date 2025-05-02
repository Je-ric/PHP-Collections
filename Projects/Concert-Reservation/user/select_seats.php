<?php
session_start();
if (empty($_SESSION['log-customer'])) {
    header('location: ../login.php');
    exit();
}
if (isset($_SESSION['log-admin'])) {
    header('location: ../login.php');
    exit();
}
$username = $_SESSION['username'];
$email = $_SESSION['email'];
?>

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

   <!-- nav-bar -->
   <div class="header">
             <div class="left-side">
                <a href="index.php"> <img class="logo" src="../images/LOGO.png" alt=""> </a>
                <a class="navs" href="index.php">Concerts</a>
                <a class="navs" href="reservations.php">View Reservation</a>
                <div class="dropdown">
                    <a href="index.php" class="navs">Pages</a>
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
                        <p>User name: <?php echo $username ?></p>
                        <p>E-mail: <?php echo $email ?></p> 
                        <a href="../logout.php" class="log-in-btn" href="">Log out</a>
                    </div>
                </div>
             </div>
        </div>

    <?php
    include 'config.php';

    if (isset($_GET['id'])) {

        $concertID = mysqli_real_escape_string($conn, $_GET['id']);

        $query = "SELECT c.*, CONCAT(v.venueName, ', ', v.venueLocation) AS venue FROM Concerts c JOIN Venues v ON c.venueID = v.venueID WHERE c.concertID = '$concertID'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $concert = mysqli_fetch_assoc($result);

            $formatted_date = date("F j, Y", strtotime($concert['date']));
    ?>
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
                        </div>
                    </div>
                </div>
            </div>

            <div class='label-pos'>
                <h2>Seat Plan</h2>
            </div>
            <div class='details-pos'>
                <div class='seat-content'>
                    <div class='seatplan'>
                        <img src='../concert-images/<?php echo $concert['seatPlanImage']; ?>' alt='Seat Plan'>
                    </div>
                </div>
            </div>

            <div class='label-pos'>
                <h2>Reserve Seat</h2>
            </div>

            <div class='details-pos'>
                <div class='select-content'>
                <p><strong>Instructions:</strong> Please select your desired seat(s) from the available options below. Only one seat can be selected at a time. Once you've made your selection, choose your preferred payment method and click on "Reserve Selected Seats" to proceed with the reservation.</p>
                    <!-- <form action='reserve_seats.php' method='post'> -->
                    <form id="seatSelectionForm" action='reserve_seats.php' method='post'>

                        <input type='hidden' name='concertID' value='<?php echo $concertID; ?>'>
                      <div class="seats">
                            <?php
                                for ($seat = 1; $seat <= $concert['numberOfSeats']; $seat++) {
                                    $availability = checkSeatAvailability($conn, $concertID, $seat);
                                    $color = ($availability == 'available') ? '#00235B' : '#E21818';
                                    $disabled = ($availability == 'reserved') ? 'disabled' : '';
                                    
                                    if ($availability == 'available') {
                            ?>
                                        <label class='checkboxsu' style='background-color: <?php echo $color; ?>;'>
                                            <input type='checkbox' name='selectedSeats[]' value='<?php echo $seat; ?>' <?php echo $disabled; ?> onclick='limitSeats(this)'> <?php echo $seat; ?>
                                        </label>
                            <?php
                                    } else {
                            ?>
                        <button type="button" class="checkboxsu" style="background-color: <?php echo $color; ?>; padding: 5px; margin: 5px; width: 35px; font-size: 14px; text-align: center; border-radius: 3px;" <?php echo $disabled; ?> onclick="reserveSeat(<?php echo $seat; ?>)"><?php echo $seat; ?></button>
                            <?php
                                    }
                                }
                            ?>
                      </div>

 <script>
        function reserveSeat(seat) {
            var selectedSeats = document.querySelectorAll('input[name="selectedSeats[]"]');
            selectedSeats.forEach(function(checkbox) {
                checkbox.checked = false;
            });
            document.getElementById('selectedSeat').value = seat;
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var checkboxes = document.querySelectorAll('.checkboxsu input[type="checkbox"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    var label = this.parentNode;
                    if (this.checked) {
                        label.style.backgroundColor = '#67F9EF';
                        label.style.color = 'black';
                    } else {
                        label.style.backgroundColor = '#00235B';
                        label.style.color = 'white';
                    }
                });
            });
        });
    </script>

    <script>
        function limitSeats(checkbox) {
            var checkboxes = document.getElementsByName('selectedSeats[]');
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i] !== checkbox) {
                    checkboxes[i].disabled = checkbox.checked;
                }
            }
        }

        document.getElementById('seatSelectionForm').onsubmit = function() {
            var checkedSeats = document.querySelectorAll('input[name="selectedSeats[]"]:checked');
            if (checkedSeats.length !== 1) {
                alert('Please select exactly one seat.');
                return false;
            }
            return confirm('Are you sure you want to reserve the selected seat?');
        };
    </script>
                        <h2 style="text-align: start;">Select Payment Method</h2> <br>

                        <div class='select-content'>
                            <div class='payment-method-container'>
                                <input type='radio' name='paymentMethod' id='credit_card' value='credit_card' required><label for='credit_card'> Credit Card</label>
                                <input type='radio' name='paymentMethod' id='debit_card' value='debit_card' required><label for='debit_card'> Debit Card</label>
                                <input type='radio' name='paymentMethod' id='paypal' value='paypal' required><label for='paypal'> PayPal</label>
                                <input type='radio' name='paymentMethod' id='bank_transfer' value='bank_transfer' required><label for='bank_transfer'> Bank Transfer</label>
                                <input type='radio' name='paymentMethod' id='cash' value='cash' required><label for='cash'> Cash</label>
                            </div>

                            <!-- Submit button -->
                            <div style="width: 1135px; text-align: center;">
                            <input class='rsrv-seat-btn' type='submit' value='Reserve Selected Seats'>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

           
    <?php
        } else {
            echo "Concert not found.";
        }
    } else {
        echo "Invalid request.";
    }

    function checkSeatAvailability($conn, $concertID, $seatNumber)
    {
        $query = "SELECT availability FROM Seats WHERE concertID = '$concertID' AND seatNumber = '$seatNumber'";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['availability'];
        } else {
            return null;
        }
    }
    ?>
</body>

</html>