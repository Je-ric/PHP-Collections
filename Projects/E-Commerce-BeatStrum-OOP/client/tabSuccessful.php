<?php

class OrderManager {
    private $conn;
    private $clientId;

    public function __construct($conn, $clientId) {
        $this->conn = $conn;
        $this->clientId = $clientId;
    }

    public function getSuccessfulOrders() {
        $client_id = mysqli_real_escape_string($this->conn, $this->clientId);

        $sql_successful_orders = "SELECT orders.*, order_items.*, items.name, items.price, items.shipping_fee, items.item_image 
                                    FROM orders 
                                    INNER JOIN order_items ON orders.id = order_items.order_id 
                                    INNER JOIN items ON order_items.item_id = items.id 
                                    WHERE orders.client_id = $client_id AND orders.status = 'Received'
                                    ORDER BY orders.order_date DESC"; 
        $result_successful_orders = $this->conn->query($sql_successful_orders);

        if ($result_successful_orders === false) {
            return array(); 
        }

        
        return $result_successful_orders->fetch_all(MYSQLI_ASSOC);
    }
}

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$clientId = $_SESSION['client_id'];

$orderManager = new OrderManager($conn, $clientId);
$successfulOrders = $orderManager->getSuccessfulOrders();


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title> 
</head>
<body>

 <div class="cart-items">
        <?php
        $sql_successful_orders = "SELECT orders.*, order_items.*, items.name, items.price, items.shipping_fee, items.item_image 
                                FROM orders 
                                INNER JOIN order_items ON orders.id = order_items.order_id 
                                INNER JOIN items ON order_items.item_id = items.id 
                                WHERE orders.client_id = $client_id AND orders.status = 'Received'";
        $result_successful_orders = $conn->query($sql_successful_orders);
    
        if ($result_successful_orders->num_rows > 0) {
    
            while ($row = $result_successful_orders->fetch_assoc()) {
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
                echo "</div>";
            }
        } else {
            echo "<p>No successful orders found.</p>";
        }
        ?>
 </div>
</div>
</body>
</html>