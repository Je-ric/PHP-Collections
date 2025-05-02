<?php
session_start();
include('../../includes/config.php');


if (!isset($_SESSION['admin_log']) && !isset($_SESSION['log_emp']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

function calculateTotalPrice($discount_percentage = 0) {
    $total_price = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
    return $total_price * (1 - ($discount_percentage / 100));  
}

$total_price = calculateTotalPrice();
$discount_percentage = 0;
$discount_id = 0;
$error_message = "";  // To handle error messages

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment'])) {
    $payment = $_POST['payment'];
    $payment_method = $_POST['payment_method'];
    $discount_id = $_POST['discount_id'] ?? NULL;

    // Validate payment
    if ($payment <= 0) {
        $error_message = "Please enter a valid payment amount.";
    } elseif ($payment >= $total_price) {
        // Discount application logic
        if ($discount_id) {
            $discount_query = "SELECT dp.percentage FROM DiscountPercentages dp WHERE dp.discount_id = ?";
            $stmt = $conn->prepare($discount_query);
            $stmt->bind_param("i", $discount_id);
            $stmt->execute();
            $stmt->store_result();
        
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($discount_percentage);
                $stmt->fetch();
            } else {
                $discount_id = 0;  // No discount applied
            }
            $stmt->free_result();
        }
        
        $discount_amount = $total_price * ($discount_percentage / 100);
        $discounted_price = $total_price - $discount_amount;
        $changed = $payment - $discounted_price;

        // Insert sale record
        $insert_sale_query = "INSERT INTO Sales (user_id, original_total, discount_amount, total_amount, payment, changed, payment_method, discount_id, discount_percentage) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sale_query);
        $stmt->bind_param("idddddsii", 
            $_SESSION['user_id'],    
            $total_price,            
            $discount_amount,        
            $discounted_price,       
            $payment,                
            $changed,                
            $payment_method,         
            $discount_id,            
            $discount_percentage     
        );
        $stmt->execute();
        $sale_id = $stmt->insert_id;

        // Insert sale items
        foreach ($_SESSION['cart'] as $item_id => $item) {
            $insert_sale_item_query = "INSERT INTO Sale_Items (sale_id, item_id, quantity, price, discount_percentage) 
                                       VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sale_item_query);
            $stmt->bind_param("iiidi", $sale_id, $item_id, $item['quantity'], $item['price'], $discount_percentage);
            $stmt->execute();

            // Update item quantity in stock
            $update_item_query = "UPDATE Item SET quantity = quantity - ? WHERE item_id = ?";
            $stmt = $conn->prepare($update_item_query);
            $stmt->bind_param("ii", $item['quantity'], $item_id);
            $stmt->execute();
        }

        // Clear the cart after successful sale
        unset($_SESSION['cart']);

        // Redirect to receipt
        header("Location: receipt.php?sale_id=$sale_id");
        exit();
    } else {
        // Insufficient payment error
        $error_message = "Insufficient payment. Total is ₱" . number_format($total_price, 2) . ". Please enter a valid amount.";
    }
}

$discount_query = "SELECT d.id, d.name, dp.percentage FROM Discounts d 
                   LEFT JOIN DiscountPercentages dp ON d.id = dp.discount_id 
                   WHERE d.promo_status = 'Available' AND CURDATE() BETWEEN d.start_date AND d.end_date";
$discount_result = $conn->query($discount_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/body-container.css">
    <link rel="stylesheet" href="../../assets/css/payment.css">
</head>
<body>
<?php include('../../includes/sidebar.php'); ?>

<div class="container">
    <div class="cart-container">
        <div class="cart">
            <h2 class="cart-title">
                <i class="fas fa-shopping-cart"></i> CART LIST
            </h2>

            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>₱<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- <h4 class="total-price">Total: ₱<span id="total-price-total"><?php echo number_format($total_price, 2); ?></span></h4> -->
        </div>

        <div class="payment">
            <h2 class="pay-title" > Payment</h2>
            <form method="POST" class="payment-form" action="payment.php">
                <div>
                    <h4 class="total-pricep">Total: ₱<span id="total-price"><?php echo number_format($total_price, 2); ?></span></h4>
                    <h4 class="change-text">Change: ₱<span id="changed">₱0.00</span></h4>
                    <input type="number" name="payment" id="payment" step="0.01" required oninput="updateTotalPriceAndChange()">
    
                    <label for="payment_method">Payment Method:</label>
                    <div class="payment-method-buttons">
                        <label class="payment-method-option">
                            <input type="radio" name="payment_method" value="Cash" required checked>
                            <span>Cash</span>
                        </label>
                        <label class="payment-method-option">
                            <input type="radio" name="payment_method" value="Card">
                            <span>Card</span>
                        </label>
                        <label class="payment-method-option">
                            <input type="radio" name="payment_method" value="E-Wallet">
                            <span>E-Wallet</span>
                        </label>
                    </div>
    
                    <label for="discount">Discount:</label>
                    <select name="discount_id" id="discount" onchange="updateTotalPriceAndChange()">
                        <?php while ($discount = $discount_result->fetch_assoc()): ?>
                            <option value="<?php echo $discount['id']; ?>" data-percentage="<?php echo $discount['percentage']; ?>">
                                <?php echo htmlspecialchars($discount['name']) . ' (' . $discount['percentage'] . '%)'; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="pay-button">Pay</button>
            </form>
            
            <?php if ($error_message): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    let totalPrice = <?php echo $total_price; ?>;
    let discountPercentage = 0;
    
    function updateTotalPriceAndChange() {
        const selectedDiscount = document.getElementById("discount").selectedOptions[0];
        discountPercentage = parseFloat(selectedDiscount.dataset.percentage || 0);

        const discountedTotal = totalPrice * (1 - discountPercentage / 100);
        document.getElementById("total-price").textContent = discountedTotal.toFixed(2);

        const paymentAmount = parseFloat(document.getElementById("payment").value) || 0;
        const changeAmount = paymentAmount - discountedTotal;

        document.getElementById("changed").textContent = changeAmount >= 0 ? `₱${changeAmount.toFixed(2)}` : "₱0.00";
    }
</script>
</body>
</html>
