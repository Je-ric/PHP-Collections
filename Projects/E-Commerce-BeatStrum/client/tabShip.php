<?php
include('config.php');

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['client_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cancel_order_id'])) {
        $cancel_order_id = $_POST['cancel_order_id'];
        
        // Prepare and execute SQL to update the order status to "Cancelled"
        $sql_update_status = "UPDATE orders SET status = 'Cancelled' WHERE id = ?";
        $stmt = $conn->prepare($sql_update_status);
        $stmt->bind_param("i", $cancel_order_id);
        
        if ($stmt->execute()) {
            // If the update is successful, send a success message
            echo "Order cancelled successfully";
        } else {
            // If there's an error, send an error message
            echo "Error cancelling order: " . $conn->error;
        }
        exit(); // Stop further execution
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders To Ship</title>
</head>
<body>
   
   <div class="cart-items">
        <?php
        // Retrieve orders that are pending to ship for the current client
        $sql_to_ship_orders = "SELECT orders.*, order_items.*, items.name, items.price, items.shipping_fee, items.item_image 
                                FROM orders 
                                INNER JOIN order_items ON orders.id = order_items.order_id 
                                INNER JOIN items ON order_items.item_id = items.id 
                                WHERE orders.client_id = $client_id AND orders.status = 'Pending'";
        $result_to_ship_orders = $conn->query($sql_to_ship_orders);
    
        if ($result_to_ship_orders->num_rows > 0) {
    
            while ($row = $result_to_ship_orders->fetch_assoc()) {
                echo "<div class='cart-item'>";
                $imagePath = '../uploads/' . $row['item_image'];
                if (file_exists($imagePath)) {
                    echo "<img src='" . $imagePath . "' alt='" . $row['name'] . "' style='width: 150px; height: 150px;'>";
                } else {
                    echo "Image not found";
                }
                echo "<p>" . $row['name'] . "</p>";
                echo "<p>Price: ₱" . $row['price'] . "</p>";
                echo "<p>Quantity: " . $row['quantity'] . "</p>";
                echo "<p>Shipping Fee: ₱" . $row['shipping_fee'] . "</p>";
                echo "<p>Total: ₱" . ($row['price'] + $row['shipping_fee']) * $row['quantity'] . "</p>";
                
                // Button to cancel the order with JavaScript function call
                echo "<button onclick='cancelOrder(" . $row['order_id'] . ")'>Cancel</button>";
    
                echo "</div>";
            }
        } else {
            echo "<p>No orders to ship.</p>";
        }
        ?>
    
   </div>
<script>
    // JavaScript function to cancel the order
    function cancelOrder(orderId) {
        // Ask for confirmation before cancelling the order
        const confirmation = confirm("Are you sure you want to cancel this order?");
        if (confirmation) {
            // Send an AJAX request to cancel the order
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo $_SERVER["PHP_SELF"]; ?>', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Reload the page after successful cancellation
                    location.reload();
                } else {
                    console.error('Error cancelling order');
                }
            };
            // Send the order ID as data to cancel the specific order
            xhr.send(`cancel_order_id=${orderId}`);
        }
    }
</script>

</body>
</html>
