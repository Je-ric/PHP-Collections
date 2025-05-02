<?php
session_start();
include('../../includes/config.php'); 

$shop_query = "SELECT * FROM ShopInfo WHERE shop_id = 1"; 
$shop_result = $conn->query($shop_query);
$shop_info = $shop_result->fetch_assoc();

$shop_name = $shop_info['shop_name'];
$shop_address = $shop_info['shop_address'];
$shop_contact = $shop_info['shop_contact'];
$shop_email = isset($shop_info['shop_email']) ? $shop_info['shop_email'] : '';

if (!isset($_SESSION['admin_log']) && !isset($_SESSION['log_emp'])) {
    header("Location: ../index.php");
    exit();
}

$sale_id = isset($_GET['sale_id']) ? intval($_GET['sale_id']) : 0;
if ($sale_id <= 0) {
    header("Location: pos.php");
    exit();
}

$sale_query = "SELECT Sales.*, Users.name AS employee_name FROM Sales JOIN Users ON Sales.user_id = Users.user_id WHERE sale_id = ?";
$stmt = $conn->prepare($sale_query);
$stmt->bind_param("i", $sale_id);
$stmt->execute();
$sale_result = $stmt->get_result()->fetch_assoc();

$sale_items_query = "SELECT Sale_Items.*, Item.name FROM Sale_Items JOIN Item ON Sale_Items.item_id = Item.item_id WHERE Sale_Items.sale_id = ?";
$stmt = $conn->prepare($sale_items_query);
$stmt->bind_param("i", $sale_id);
$stmt->execute();
$sale_items_result = $stmt->get_result();

$is_voided = $sale_result['sale_status'] == 'voided';
$can_void = $sale_result['sale_status'] !== 'voided';

// Check if discount was applied
$discount_applied = $sale_result['discount_amount'] > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="../../assets/css/body-container.css">
    <link rel="stylesheet" href="../../assets/css/receipt.css">
</head>
<body>
    
<?php include('../../includes/sidebar.php'); ?>
<div class="container">
    <div class="receipt-container">
        <h1><?php echo $shop_name; ?></h1>
        <p><?php echo $shop_address; ?><br><?php echo $shop_contact; ?></p>
        <?php if (!empty($shop_email)): ?>
            <p>Email: <?php echo $shop_email; ?></p>
        <?php endif; ?>
    
        <h2>Receipt</h2>
        <p>Sale ID: <?php echo $sale_id; ?></p>
        <p>Date: <?php echo isset($sale_result['sale_date']) ? date('m/j/Y, h:i:s', strtotime($sale_result['sale_date'])) : 'N/A'; ?></p>
        <p>Cashier: <?php echo htmlspecialchars($sale_result['employee_name']); ?></p>
        <p>Payment Method: <?php echo htmlspecialchars($sale_result['payment_method']); ?></p> 

        <?php if ($discount_applied): ?>
            <div class="discount-section">
                <p>Discount Applied: <?php echo number_format($sale_result['discount_percentage'], 2) . '%'; ?></p>
                <p>Discount Amount: ₱<?php echo number_format($sale_result['discount_amount'], 2); ?></p>
            </div>
        <?php endif; ?>

        <h3>Items Purchased:</h3>
            <table class="receipt-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $sale_items_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>₱<?php echo number_format($item['price'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: right;"><strong>Original Total:</strong></td>
                        <td>₱<?php echo number_format($sale_result['original_total'], 2); ?></td>
                    </tr>
                </tfoot>
            </table>

    
        <div class="total-section">
            <p>Total Amount: ₱<?php echo number_format($sale_result['total_amount'], 2); ?></p>
            <p>Payment: ₱<?php echo number_format($sale_result['payment'], 2); ?></p>
            <p>Change: ₱<?php echo number_format($sale_result['changed'], 2); ?></p>
        </div>
        
        <div class="footer">
            <p>Thank you for shopping with us!</p>
        </div>
    </div>

    <?php if ($can_void && !$is_voided): ?>
        <form action="void.php" method="POST" style="margin-top: 15px;">
            <input type="hidden" name="sale_id" value="<?php echo $sale_id; ?>">
            <button type="submit" class="void-button">Void Sale</button>
        </form>
    <?php elseif ($is_voided): ?>
        <p>This sale has already been voided.</p>
    <?php endif; ?>


        <div class="footer">
            <p><a href="pos.php">New Order</a></p>
        </div>

</div>

</body>
</html>
