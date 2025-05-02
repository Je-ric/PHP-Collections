<?php
include('config.php');

class SessionManager {


    public static function isLoggedIn() {
        return isset($_SESSION['client_id']);
    }

    public static function redirectToLogin() {
        header("Location: login.php");
        exit();
    }
}

if (!SessionManager::isLoggedIn()) {
    SessionManager::redirectToLogin();
}

$client_id = $_SESSION['client_id'];

if (isset($_POST['delete_item'])) {
    $item_id = $_POST['delete_item']; 
    echo "<script>
        if (confirm('Are you sure you want to delete this item from the cart?')) {
            
            window.location.href = 'shopping_cart.php?confirm_delete=1&item_id=$item_id';
        } else {
            
            window.location.href = 'shopping_cart.php';
        }
    </script>";
    exit(); 
}

if (isset($_GET['confirm_delete']) && $_GET['confirm_delete'] == 1) {
    $item_id = $_GET['item_id'];
    $sql_delete_item = "DELETE FROM shopping_cart WHERE client_id = $client_id AND item_id = $item_id";
    if ($conn->query($sql_delete_item) === TRUE) {
        echo "<script>alert('Item successfully deleted from the cart.')</script>";
        header("Location: shopping_cart.php");
        exit();
    } else {
        echo "Error deleting item from the cart: " . $conn->error;
    }
}


if (isset($_POST['place_order'])) {
    $selected_items = $_POST['selected_items'];

    if (!empty($selected_items)) {
        $total_price = 0;
        $total_shipping_fee = 0;
        $order_items = [];
        $out_of_stock_items = [];

        foreach ($selected_items as $item_id) {
            $sql_item = "SELECT * FROM items WHERE id = $item_id";
            $item_result = $conn->query($sql_item);

            if ($item_result->num_rows > 0) {
                $item_row = $item_result->fetch_assoc();
                $quantity_available = $item_row['quantity'];
                $price = $item_row['price'];
                $shipping_fee = $item_row['shipping_fee'];
                
                $quantity = isset($_POST['quantity'][$item_id]) ? $_POST['quantity'][$item_id] : 1;
                
                if ($quantity > $quantity_available) {
                    $quantity = $quantity_available;
                    if ($quantity_available == 0) {
                        $out_of_stock_items[] = $item_row['name'];
                    }
                }
                
                $total_price += $price * $quantity;
                $total_shipping_fee += $shipping_fee * $quantity;
                
                $order_items[] = [
                    'item_id' => $item_id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'shipping_fee' => $shipping_fee
                ];
            }
        }

        
        if (empty($out_of_stock_items)) {
            
            
            $sql_insert_order = "INSERT INTO orders (client_id, total_price, shipping_fee, status) VALUES (?, ?, ?, ?)";
            $status = 'Pending';
            $stmt = $conn->prepare($sql_insert_order);
            $stmt->bind_param("idds", $client_id, $total_price, $total_shipping_fee, $status);

            if ($stmt->execute()) {
                $order_id = $stmt->insert_id;
                $stmt->close();
                
                $sql_insert_order_item = "INSERT INTO order_items (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)";
                $stmt_item = $conn->prepare($sql_insert_order_item);
                $stmt_item->bind_param("iiid", $order_id, $item_id, $quantity, $price);

                foreach ($order_items as $order_item) {
                    $item_id = $order_item['item_id'];
                    $quantity = $order_item['quantity'];
                    $price = $order_item['price'];
                    $stmt_item->execute();

                    
                    $sql_update_quantity = "UPDATE items SET quantity = quantity - ?, sold = sold + ? WHERE id = ?";
                    $stmt_update_quantity = $conn->prepare($sql_update_quantity);
                    $stmt_update_quantity->bind_param("iii", $quantity, $quantity, $item_id);
                    $stmt_update_quantity->execute();
                }
                $stmt_item->close();

                
                $sql_clear_cart = "DELETE FROM shopping_cart WHERE client_id = $client_id AND item_id IN (" . implode(',', $selected_items) . ")";
                $conn->query($sql_clear_cart);

                
                header("Location: shopping_cart.php");
                exit();
            } else {
                echo "Error placing order: " . $conn->error;
            }
        } else {
            
            echo "<script>alert('The following items are out of stock and cannot be ordered: " . json_encode($out_of_stock_items) . "');</script>";
        }
    }
}


$sql_cart_items = "SELECT items.*, shopping_cart.quantity AS quantity FROM items
                    INNER JOIN shopping_cart ON items.id = shopping_cart.item_id
                    WHERE shopping_cart.client_id = $client_id";
$result_cart_items = $conn->query($sql_cart_items);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
</head>
<body>
<div class="cart_container_flex">
    <form method="POST" id="cartForm">
        <div class="cart-items">
            <?php
            if ($result_cart_items->num_rows > 0) {
                while ($row = $result_cart_items->fetch_assoc()) {
                    echo "<div class='cart-item'>";
                    echo "<input type='checkbox' class='item-checkbox' name='selected_items[]' value='" . $row['id'] . "'>";
                    $imagePath = '../uploads/' . $row['item_image'];
                    if (file_exists($imagePath)) {
                        echo "<img src='" . $imagePath . "' alt='" . $row['name'] . "' style='width: 150px; height: 150px;'>";
                    } else {
                        echo "Image not found";
                    }
                    echo "<h4>" . $row['name'] . "</h4>";
                    echo "<input type='number' name='quantity[" . $row['id'] . "]' value='" . $row['quantity'] . "' min='1' max='5' class='item-quantity' required>";
                  
                    echo "<p class='item-price'>Price: ₱" . $row['price'] . "</p>"; 
                    echo "<p class='item-shipping'>Shipping: ₱" . $row['shipping_fee'] . "</p>"; 
                    
                    
                    echo "<button type='submit' class='delete-item' name='delete_item' value='" . $row['id'] . "'>Delete</button>";
                    echo "</div>";
                }
            } else {
                echo "Your cart is empty.";
            }
            ?>
        </div>
        <!-- Order Summary -->
        <div id="orderSummary" class="orderSummary">
            <div class="summary-group">
                <h2>Order Summary</h2>
            </div>
            <div class="summary-group">
                <p id="totalItems">Total Items: 0</p>
                <p id="orderPrice">Price: ₱0</p> 
                <p id="orderShipping">Total Shipping Fee: ₱0</p> 
                <p id="orderTotal">Total Price: ₱0</p> 
            </div>
            <div class="summary-group">
                <button type="submit" name="place_order" class="place-order-btn">Place Order for Selected Items</button>
            </div>
        </div>
    </form>
</div>

<script src="/PHP-Projects/E-Commerce-BeatStrum-OOP/script/tabCart.js"></script>
</body>
</html>
