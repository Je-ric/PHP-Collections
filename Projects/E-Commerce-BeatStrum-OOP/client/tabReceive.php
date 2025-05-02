<?php

class OrderHandler {
    private $conn;
    private $clientId;

    public function __construct($conn, $clientId) {
        $this->conn = $conn;
        $this->clientId = $clientId;
    }

    public function updateOrderStatus($orderId) {
        $orderId = mysqli_real_escape_string($this->conn, $orderId);

        $sql_update_status = "UPDATE orders SET status = 'Received' WHERE id = $orderId AND client_id = $this->clientId";
        if ($this->conn->query($sql_update_status) === TRUE) {
            return "Order status updated successfully";
        } else {
            return "Error updating order status: " . $this->conn->error;
        }
    }
}

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$clientId = $_SESSION['client_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {


    $orderHandler = new OrderHandler($conn, $clientId);

    $order_id = $_POST['order_id'];
    echo $orderHandler->updateOrderStatus($order_id);
    exit();
}

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
        $sql_to_receive_orders = "SELECT orders.*, order_items.*, items.name, items.price, items.shipping_fee, items.item_image 
                                FROM orders 
                                INNER JOIN order_items ON orders.id = order_items.order_id 
                                INNER JOIN items ON order_items.item_id = items.id 
                                WHERE orders.client_id = $client_id AND orders.status = 'Approved'";
        $result_to_receive_orders = $conn->query($sql_to_receive_orders);
    
        if ($result_to_receive_orders->num_rows > 0) {
            
            while ($row = $result_to_receive_orders->fetch_assoc()) {
                echo "<div class='cart-item'>";
                echo "<h3 data-order-id='" . $row['order_id'] . "'>Order ID: " . $row['order_id'] . "</h3>";
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
                if ($row['status'] == 'Approved') {
                    echo "<button onclick='moveToSuccessful(" . $row['order_id'] . ")'>Move to Successful</button>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>No items to receive.</p>";
        }
        ?>
   </div>

<script>
    function moveToSuccessful(orderId) {
        const orderElement = document.querySelector(`#toReceiveTab h3[data-order-id="${orderId}"]`);
        if (!orderElement) {
            console.error("Order element not found");
            return;
        }
        
        const itemHTML = orderElement.parentElement.innerHTML;
        const successfulTab = document.querySelector('#successfulTab');
        successfulTab.innerHTML += `<div class="order">${itemHTML}</div>`;
        orderElement.parentElement.remove();

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo $_SERVER["PHP_SELF"]; ?>', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
            } else {
                console.error('Error updating order status');
            }
        };
        xhr.send(`order_id=${orderId}`);
    }
</script>

</body>
</html>