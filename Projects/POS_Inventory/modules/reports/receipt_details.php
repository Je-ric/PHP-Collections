<?php
include('../../includes/config.php'); 
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['admin_log']) && !isset($_SESSION['log_emp'])) {
    header("Location: ../../index.php");
    exit();
}

$sale_id = isset($_GET['sale_id']) ? (int)$_GET['sale_id'] : 0;

$sale_stmt = $conn->prepare("SELECT s.sale_id, s.sale_date, s.total_amount, u.name AS salesperson, s.sale_status, 
                                    va.void_date, va.voided_by, uv.name AS voided_by_name,
                                    si.item_id, si.quantity, si.price, i.name AS item_name, i.brand, i.size, s.payment_method, 
                                    s.discount_amount, s.discount_percentage, s.original_total, d.name AS discount_name
                                    FROM Sales s
                                    JOIN Users u ON s.user_id = u.user_id
                                    LEFT JOIN Void_Actions va ON s.sale_id = va.sale_id
                                    LEFT JOIN Users uv ON va.voided_by = uv.user_id  
                                    JOIN Sale_Items si ON s.sale_id = si.sale_id
                                    JOIN Item i ON si.item_id = i.item_id
                                    LEFT JOIN Discounts d ON s.discount_id = d.id
                                    WHERE s.sale_id = ?");
$sale_stmt->bind_param("i", $sale_id);
$sale_stmt->execute();
$sale_result = $sale_stmt->get_result();

if ($sale_result->num_rows == 0) {
    echo "No details found for this sale.";
    exit();
}

$sale_details = $sale_result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Details</title>
    <link rel="stylesheet" href="../../assets/css/body-container.css">
    <link rel="stylesheet" href="../../assets/css/table.css">
    <link rel="stylesheet" href="../../assets/css/buttons.css">
    <style>
        .voided-status {
            border: 1px solid red;
            color: red;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .voided-status p{
            font-weight: 300;
            color: red;
        }

        .voided-status p strong{
            font-weight: 600;
            color: red;
        }

        .voided {
            background-color:#fef2f2;
            border: 1px solid #fecaca;
            color: #721c24;
            padding: 10px;
            margin-top: 20px;
        }

        .completed-status {
            border: 1px solid green;
            color: green;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .completed-status p{
            font-weight: 300;
            color: green;
        }

        .completed-status p strong{
            font-weight: 600;
            color: green;
        }


        .void-button {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            font-size: 1em;
            border-radius: 5px;
            cursor: pointer;
        }
        .void-button:hover {
            background-color: #c82333;
        }

       /* General Info Container */
       
.items-list-container {
    background-color: #ffffff;
    border-radius: 8px;
    border: 1px solid rgb(201, 201, 201);
    padding: 24px;
    margin-bottom: 24px;
}

.general-info-container {
    background-color: #ffffff;
    border-radius: 8px;
    border: 1px solid rgb(201, 201, 201);
    padding: 24px;
    margin-bottom: 24px;
}

.general-info-container h2 {
    color: #333;
    font-size: 20px;
    margin-bottom: 16px;
    border-bottom: 1px solid rgb(201, 201, 201);
    padding-bottom: 5px;
}

.general-info-container h2 {
    color: #333;
    font-size: 20px;
    margin-bottom: 16px;
    border-bottom: 1px solid rgb(201, 201, 201);
    padding-bottom: 5px;
}

.general-info-container h3 {
    font-size: 16px;
    margin-bottom: 8px;
}


.general-info-container strong {
    font-weight: 600;
    color: #333;
}

/* Button Styles */
.void-button, 
.back-button {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s, color 0.3s;
    cursor: pointer;
}

.void-button {
    background-color: #dc3545;
    color: white;
    border: none;
}

.void-button:hover {
    background-color: #c82333;
}

/* Container for buttons */
.button-container {
    margin-top: 24px;
    display: flex;
    justify-content: space-between;
}

.sale-info, .financial-summary {
    width: 48%;
}

.group {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid rgb(201, 201, 201);
    padding-bottom: 16px;
    margin-bottom: 16px;
}

.group div {
    margin-right: 20px;
}

.group i{
    font-size: larger;
}



    </style>
</head>
<body>

<?php include('../../includes/sidebar.php'); ?>

<div class="container">
    
<div class="general-info-container">
    <h2>Transaction Sale ID: <?php echo htmlspecialchars($sale_details[0]['sale_id']); ?></h2>
    <div class="group">
        <div class="sale-info">
            <h3>Sale Details</h3>
            <p><strong>Date:</strong> <?php echo date('F j, Y (g:i A)', strtotime($sale_details[0]['sale_date'])); ?></p>
            <p><strong>Salesperson:</strong> <?php echo htmlspecialchars($sale_details[0]['salesperson']); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($sale_details[0]['payment_method']); ?></p> 
        </div>

        <div class="financial-summary">
            <h3>Financial Summary</h3>
            <p><strong>Total Amount:</strong> ₱<?php echo number_format($sale_details[0]['total_amount'], 2); ?></p>
            <p><strong>Original Total:</strong> ₱<?php echo number_format($sale_details[0]['original_total'], 2); ?></p>
            <?php if ($sale_details[0]['discount_amount'] > 0): ?>
                <p><strong>Discount Applied:</strong> ₱<?php echo number_format($sale_details[0]['discount_amount'], 2); ?> (<?php echo $sale_details[0]['discount_percentage']; ?>%)</p>
                <p><strong>Discount Name:</strong> <?php echo htmlspecialchars($sale_details[0]['discount_name']); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($sale_details[0]['sale_status'] == 'Completed'): ?>
        <div class="completed-status">
            <p><i class='bx bx-check-circle'></i>&nbsp;&nbsp;&nbsp;<strong>Complete</strong></p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This transaction has been successfully completed.</p>
        </div>
    <?php elseif ($sale_details[0]['sale_status'] == 'Voided'): ?>
        <div class="voided-status">
            <p><i class='bx bx-error-circle'></i>&nbsp;&nbsp;&nbsp;<strong>Transaction Voided</strong></p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This transaction has been voided and is no longer valid.</p>
        </div>
        <div class="voided">
            <p><strong style="color: #9b2c2c;">Void Details</strong></p>
            <p><strong>Voided On:</strong> <?php echo date('F j, Y (g:i A)', strtotime($sale_details[0]['void_date'])); ?></p>
            <p><strong>Voided By:</strong> <?php echo htmlspecialchars($sale_details[0]['voided_by_name']); ?></p>
        </div>
    <?php endif; ?>
</div>

        <div class="items-list-container">
            <h3>Items Purchased</h3>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Brand</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($sale_details as $item): 
                    $subtotal = $item['price'] * $item['quantity']; 
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['brand']); ?></td>
                        <td><?php echo htmlspecialchars($item['size']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td>₱<?php echo number_format($item['price'], 2); ?></td>
                        <td>₱<?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="button-container">
        
            <a href="history.php" class="back-button">
                <i class="bx bx-arrow-back"></i> Back to List
            </a>
     
             <?php if ($sale_details[0]['sale_status'] != 'Voided'): ?>
                 <form action="../pos/void.php" method="POST" onsubmit="return confirmVoid()">
                     <input type="hidden" name="sale_id" value="<?php echo htmlspecialchars($sale_details[0]['sale_id']); ?>">
                     <button type="submit" class="void-button">Void Transaction</button>
                 </form>
             <?php endif; ?>
     
        </div>
        </div>
    
     
        
        </div>

<script>
    function confirmVoid() {
        return confirm('Are you sure you want to void this transaction? This action cannot be undone.');
    }
</script>

</body>
</html>
