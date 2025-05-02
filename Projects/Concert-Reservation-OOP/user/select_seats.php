<?php
session_start();

class ConcertReservation
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function loadConcertDetails($concertID)
    {
        $concertID = mysqli_real_escape_string($this->conn, $concertID);

        $query = "SELECT c.*, CONCAT(v.venueName, ', ', v.venueLocation) AS venue FROM Concerts c JOIN Venues v ON c.venueID = v.venueID WHERE c.concertID = '$concertID'";
        $result = mysqli_query($this->conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            return mysqli_fetch_assoc($result);
        } else {
            return null;
        }
    }

    public function loadSeatPlanDetails($concertID)
    {
        $concertID = mysqli_real_escape_string($this->conn, $concertID);

        $seatPlanDetails = array();

        $seatPlanQuery = "SELECT * FROM SeatPlans WHERE concertID = '$concertID'";
        $seatPlanResult = mysqli_query($this->conn, $seatPlanQuery);

        if ($seatPlanResult && mysqli_num_rows($seatPlanResult) > 0) {
            while ($seatPlan = mysqli_fetch_assoc($seatPlanResult)) {
                $seatPlanID = $seatPlan['seatPlanID'];
                $seatPlanName = $seatPlan['seatPlanName'];
                $quantity = $seatPlan['quantity'];
                $ticketPrice = $seatPlan['ticketPrice'];

                $seatPlanDetails[] = array(
                    'seatPlanID' => $seatPlanID,
                    'seatPlanName' => $seatPlanName,
                    'quantity' => $quantity,
                    'ticketPrice' => $ticketPrice
                );
            }
        }

        return $seatPlanDetails;
    }

    public function reserveSeat($concertID, $selectedSeat, $selectedSeatPlanID, $selectedSeatPlanName, $ticketPrice, $paymentMethod)
    {
        // Implement your code for reserving a seat
    }

    public function checkSeatAvailability($concertID, $seatNumber)
    {
        $query = "SELECT availability FROM Seats WHERE concertID = '$concertID' AND seatNumber = '$seatNumber'";
        $result = mysqli_query($this->conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['availability'];
        } else {
            return null;
        }
    }
}

include 'config.php';

$reservation = new ConcertReservation($conn);

if (isset($_GET['id'])) {
    $concertID = $_GET['id'];
    $concert = $reservation->loadConcertDetails($concertID);

    if ($concert) {
        $formatted_date = date("F j, Y", strtotime($concert['date']));
        $venue = $concert['venue'];

        $seatPlanDetails = $reservation->loadSeatPlanDetails($concertID);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Concert details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
    <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/view-concert.css">
    <link rel="icon" href="../images/S LOGO.png" type="image/icon type">
</head>

<body>

    <!-- nav-bar -->
    <?php include('navbar.php'); ?>

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
                        <p><strong>Venue: </strong> <br><?php echo $venue; ?></p>
                    </div>
                    <div class="content">
                        <img src="../images/ticket-icon.png" class="img2">
                        <p><strong>Ticket Price:</strong><br>
                            <?php
                            foreach ($seatPlanDetails as $seatPlan) {
                                echo "{$seatPlan['seatPlanName']}: ₱{$seatPlan['ticketPrice']}<br>";
                            }
                            ?>
                        </p>
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
            <p><strong>Instructions:</strong> Please select your desired seat(s) from the available options below. Only one seat can be selected at a time. Once you've made your selection, choose your preferred payment method and click on "Reserve Selected Seats" to proceed with the reservation.</p> <br>
           
            <form id="seatSelectionForm" action='reserve_seats.php' method='post'>
                <input type="hidden" name="selectedSeat" id="selectedSeat" value="">
                <input type="hidden" name="selectedSeatPlanID" id="selectedSeatPlanID" value="">
                <input type="hidden" name="selectedSeatPlanName" id="selectedSeatPlanName" value="">
                <input type="hidden" name="ticketPrice" id="ticketPrice" value="">
                <input type='hidden' name='concertID' value='<?php echo $concertID; ?>'>
                <?php
                
                foreach ($seatPlanDetails as $seatPlan) {
                    $seatPlanID = $seatPlan['seatPlanID'];
                    $seatPlanName = $seatPlan['seatPlanName'];

                    echo "<h3>Seat Plan: $seatPlanName</h3>";
                    echo "<div class='seats'>";
                    $seatQuery = "SELECT * FROM Seats WHERE concertID = '$concertID' AND seatPlanID = '$seatPlanID'";
                    $seatResult = mysqli_query($conn, $seatQuery);
                    echo ' <div class="sel-border"> ';
                    if ($seatResult && mysqli_num_rows($seatResult) > 0) {
                        while ($seat = mysqli_fetch_assoc($seatResult)) {
                            $seatNumber = $seat['seatNumber'];
                            $availability = $reservation->checkSeatAvailability($concertID, $seatNumber);
                            $color = ($availability == 'available') ? '#00235B' : '#E21818';
                            $disabled = ($availability == 'reserved') ? 'disabled' : '';

                            if ($availability == 'available') {
                                echo "<label class='checkboxsu' style='background-color: $color;'><input type='checkbox' name='selectedSeats[]' value='$seatNumber' data-seatid='{$seat['seatID']}' data-seatnumber='$seatNumber' data-seatplanid='$seatPlanID' data-concertid='$concertID' $disabled onclick='limitSeats(this)'>$seatNumber</label>";
                            } else {
                                echo "<button type='button' class='checkboxsu' style='background-color: $color; padding: 5px; margin: 5px; width: 35px; font-size: 14px; text-align: center; border-radius: 3px;' $disabled onclick='reserveSeat($seatNumber, $seatPlanID, \"$seatPlanName\", $concertID)'>$seatNumber</button>";
                            }
                        } echo ' </div> ';
                    } else {
                        echo "<p>No seats found for this seat plan.</p>";
                    }
                    echo "</div><br>";
                }
                ?>
                <h2 style="text-align: start;">Select Payment Method</h2> <br>
                    <div class='payment-method-container'>
                        <input type='radio' name='paymentMethod' id='credit_card' value='credit_card' required><label for='credit_card'> Credit Card</label>
                        <input type='radio' name='paymentMethod' id='debit_card' value='debit_card' required><label for='debit_card'> Debit Card</label>
                        <input type='radio' name='paymentMethod' id='paypal' value='paypal' required><label for='paypal'> PayPal</label>
                        <input type='radio' name='paymentMethod' id='bank_transfer' value='bank_transfer' required><label for='bank_transfer'> Bank Transfer</label>
                        <input type='radio' name='paymentMethod' id='cash' value='cash' required><label for='cash'> Cash</label>
                    </div>
                    <input class='rsrv-seat-btn' type='submit' value='Reserve Selected Seats'>
            </form>
        </div>
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

    
<script>
    function reserveSeat(seat, seatPlanID, seatPlanName, ticketPrice, concertID) {
    // Update the hidden inputs with the selected values
    document.getElementById('selectedSeat').value = seat;
    document.getElementById('selectedSeatPlanID').value = seatPlanID;
    document.getElementById('selectedSeatPlanName').value = seatPlanName;
    document.getElementById('ticketPrice').value = ticketPrice;
    document.getElementById('concertID').value = concertID;

    // Update the global variables (optional)
    window.selectedSeatPlanID = seatPlanID;
    window.selectedSeatPlanName = seatPlanName;
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
</body>
</html>
