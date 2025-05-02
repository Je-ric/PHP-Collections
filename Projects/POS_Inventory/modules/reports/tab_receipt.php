<?php 
// Receipt
$sales_stmt = $conn->prepare("SELECT s.sale_id, s.sale_date, u.name, s.total_amount, s.sale_status, s.payment_method
                                FROM Sales s
                                JOIN Users u ON s.user_id = u.user_id
                                WHERE s.sale_status IN ('Completed', 'Voided')
                                ORDER BY s.sale_date DESC");

$sales_stmt->execute();
$sales_result = $sales_stmt->get_result();

// Receipt tab
$user_filter = isset($_POST['user_filter']) ? $_POST['user_filter'] : '';
$date_filter = isset($_POST['date_filter']) ? $_POST['date_filter'] : '';
$sale_status_filter = isset($_POST['sale_status_filter']) ? $_POST['sale_status_filter'] : '';
$payment_method_filter = isset($_POST['payment_method_filter']) ? $_POST['payment_method_filter'] : '';  
$min_price = isset($_POST['min_price']) ? (float)$_POST['min_price'] : 0;  
$max_price = isset($_POST['max_price']) ? (float)$_POST['max_price'] : 0; 

$receipt_sql = "SELECT s.sale_id, s.sale_date, u.name, s.total_amount, s.sale_status, s.payment_method
                FROM Sales s
                JOIN Users u ON s.user_id = u.user_id
                WHERE s.sale_status IN ('Completed', 'Voided')";

$conditions = [];
if ($user_filter) {
    $conditions[] = "u.name LIKE '%$user_filter%'";
}
if ($date_filter) {
    $conditions[] = "DATE(s.sale_date) = '$date_filter'";
}
if ($sale_status_filter) {
    $conditions[] = "s.sale_status = '$sale_status_filter'";
}
if ($payment_method_filter) {
    $conditions[] = "s.payment_method = '$payment_method_filter'";  
}
if ($min_price) {
    $conditions[] = "s.total_amount >= $min_price";  
}
if ($max_price) {
    $conditions[] = "s.total_amount <= $max_price";  
}

if ($conditions) {
    $receipt_sql .= " AND " . implode(" AND ", $conditions);
}

$receipt_sql .= " ORDER BY s.sale_date DESC";
$sales_stmt = $conn->prepare($receipt_sql);
$sales_stmt->execute();
$sales_result = $sales_stmt->get_result();

// -------------------------------------------------------------------------------
// Reset all filters
if (isset($_POST['reset_all'])) {
    unset($_POST['user_filter']);
    unset($_POST['date_filter']);
    unset($_POST['action_filter']);
    unset($_POST['search_filter']);
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

?>


            <div class="filter-section">
                   <form method="POST" class="filter-form">
                        <input type="text" name="user_filter" placeholder="Filter by User..." value="<?php echo isset($_POST['user_filter']) ? $_POST['user_filter'] : ''; ?>" onchange="this.form.submit()">
                        <input type="date" name="date_filter" value="<?php echo isset($_POST['date_filter']) ? $_POST['date_filter'] : ''; ?>" onchange="this.form.submit()">
                        <select name="sale_status_filter" onchange="this.form.submit()">
                            <option value="">Select Sale Status</option>
                            <option value="Completed" <?php echo (isset($_POST['sale_status_filter']) && $_POST['sale_status_filter'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="Voided" <?php echo (isset($_POST['sale_status_filter']) && $_POST['sale_status_filter'] == 'Voided') ? 'selected' : ''; ?>>Voided</option>
                        </select>
                        <select name="payment_method_filter" onchange="this.form.submit()"> 
                            <option value="">Select Payment Method</option>
                            <option value="Cash" <?php echo (isset($_POST['payment_method_filter']) && $_POST['payment_method_filter'] == 'Cash') ? 'selected' : ''; ?>>Cash</option>
                            <option value="E-Wallet" <?php echo (isset($_POST['payment_method_filter']) && $_POST['payment_method_filter'] == 'E-Wallet') ? 'selected' : ''; ?>>E-Wallet</option>
                            <option value="Card" <?php echo (isset($_POST['payment_method_filter']) && $_POST['payment_method_filter'] == 'Card') ? 'selected' : ''; ?>>Card</option>
                        </select>
                        <input type="number" name="min_price" placeholder="Min Price" value="<?php echo isset($_POST['min_price']) ? $_POST['min_price'] : ''; ?>" onchange="this.form.submit()">
                        <input type="number" name="max_price" placeholder="Max Price" value="<?php echo isset($_POST['max_price']) ? $_POST['max_price'] : ''; ?>" onchange="this.form.submit()">
                        
                        <?php if ($user_filter || $date_filter || $sale_status_filter || $payment_method_filter || $min_price || $max_price): ?>
                        <button type="submit" name="reset_all" class="reset-button">
                                <span class="reset-text">Reset</span>
                                <span class="reset-icon">
                                    <i class="bx bx-trash"></i> 
                                </span>
                        </button>
                        <?php endif; ?>
                        
                    </form>
                       
                   
            </div>

        <div class="scrollable-history">
            <table class="history-table receipt-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Date</th>
                        <th>User</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Payment Method</th> 
                    </tr>
                </thead>
                <tbody id="salesHistoryTableBody">
                    <?php while ($sale_row = $sales_result->fetch_assoc()): ?>
                        <tr>
                            <td style="display: flex; justify-content: space-between;">
                                <?php #echo htmlspecialchars($sale_row['sale_id']); ?>
                                <a href="receipt_details.php?sale_id=<?php echo htmlspecialchars($sale_row['sale_id']); ?>" class="view-details-button">View </a>
                            </td>
                            <!-- <td>
                                <a href="receipt_details.php?sale_id=<?php echo htmlspecialchars($sale_row['sale_id']); ?>" class="view-details-button">
                                    <?php echo htmlspecialchars($sale_row['sale_id']); ?> <i class='bx bx-show-alt'></i> View
                                </a>
                            </td> -->
                            <td><?php echo date('F j, Y (g:i A)', strtotime($sale_row['sale_date'])); ?></td>
                            <td><?php echo htmlspecialchars($sale_row['name']); ?></td>
                            <td>₱<?php echo number_format($sale_row['total_amount'], 2); ?></td>
                            <td class="<?php echo isset($sale_row['sale_status']) 
                                ? ($sale_row['sale_status'] === 'Completed' ? 'status-completed' 
                                : ($sale_row['sale_status'] === 'Voided' ? 'status-voided' : '')) 
                                : ''; ?>">
                                <?php 
                                    echo isset($sale_row['sale_status']) 
                                        ? ($sale_row['sale_status'] === 'Completed' || $sale_row['sale_status'] === 'Voided' 
                                            ? '<span>' . htmlspecialchars($sale_row['sale_status']) . '</span>' 
                                            : htmlspecialchars($sale_row['sale_status'])) 
                                        : 'Unknown Status'; 
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($sale_row['payment_method']); ?></td>  
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="no-records" style="display: none;">No sales records found</div>
        </div>
