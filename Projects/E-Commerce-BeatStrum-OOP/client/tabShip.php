<?php
class OrderManagement {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function cancelOrder($orderId) {
        $sql_update_status = "UPDATE orders SET status = 'Cancelled' WHERE id = ?";
        $stmt = $this->conn->prepare($sql_update_status);
        $stmt->bind_param("i", $orderId);

        if ($stmt->execute()) {
            return "Order cancelled successfully";
        } else {
            return "Error cancelling order: " . $this->conn->error;
        }
    }
}

include('config.php');

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['client_id'];

$orderManagement = new OrderManagement($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cancel_order_id'])) {
        $cancel_order_id = $_POST['cancel_order_id'];

        $result = $orderManagement->cancelOrder($cancel_order_id);
        echo $result;
        exit();
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
                
                
                echo "<button onclick='cancelOrder(" . $row['order_id'] . ")'>Cancel</button>";
    
                echo "</div>";
            }
        } else {
            echo "<p>No orders to ship.</p>";
        }
        ?>
    
   </div>
<script>
    
    function cancelOrder(orderId) {
        
        const confirmation = confirm("Are you sure you want to cancel this order?");
        if (confirmation) {
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo $_SERVER["PHP_SELF"]; ?>', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    
                    location.reload();
                } else {
                    console.error('Error cancelling order');
                }
            };
            
            xhr.send(`cancel_order_id=${orderId}`);
        }
    }
</script>

</body>
</html>
