<?php 
// Product 
$history_stmt = $conn->prepare("SELECT ih.timestamp, u.name, ih.details, i.name AS item_name, ih.action
                                FROM ItemHistory ih
                                JOIN Users u ON ih.user_id = u.user_id
                                JOIN Item i ON ih.item_id = i.item_id
                                ORDER BY ih.timestamp DESC");
$history_stmt->execute();
$history_result = $history_stmt->get_result();

// Product tab
$action_filter = isset($_POST['action_filter']) ? $_POST['action_filter'] : '';

$product_sql = "SELECT ih.timestamp, u.name, ih.details, i.name AS item_name, ih.action
                FROM ItemHistory ih
                JOIN Users u ON ih.user_id = u.user_id
                JOIN Item i ON ih.item_id = i.item_id";

$conditions = [];
$params = [];

if ($user_filter) {
    $conditions[] = "u.name LIKE ?";
    $params[] = "%$user_filter%";
}
if ($date_filter) {
    $conditions[] = "DATE(ih.timestamp) = ?";
    $params[] = $date_filter;
}
if ($action_filter) {
    $conditions[] = "ih.action = ?";
    $params[] = $action_filter;
}

if ($conditions) {
    $product_sql .= " WHERE " . implode(" AND ", $conditions);
}
$product_sql .= " ORDER BY ih.timestamp DESC";

$history_stmt = $conn->prepare($product_sql);
if ($params) {
    $history_stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$history_stmt->execute();
$history_result = $history_stmt->get_result();

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
    <input type="text" name="user_filter" placeholder="Filter by User..." value="<?php echo htmlspecialchars($_POST['user_filter'] ?? ''); ?>" onchange="this.form.submit()">
    <input type="date" name="date_filter" value="<?php echo htmlspecialchars($_POST['date_filter'] ?? ''); ?>" onchange="this.form.submit()">
    <select name="action_filter" onchange="this.form.submit()">
        <option value="">Select Action to filter</option>
        <option value="add" <?php echo ($action_filter === 'add') ? 'selected' : ''; ?>>Added Items</option>
        <option value="restock" <?php echo ($action_filter === 'restock') ? 'selected' : ''; ?>>Restocked</option>
        <option value="delete" <?php echo ($action_filter === 'delete') ? 'selected' : ''; ?>>Deleted</option>
        <option value="restore" <?php echo ($action_filter === 'restore') ? 'selected' : ''; ?>>Restored</option>
    </select>

    <?php if ($user_filter || $date_filter || $action_filter): ?>
        <button type="submit" name="reset_all" class="reset-button">
            <span class="reset-text">Reset</span>
            <span class="reset-icon"><i class="bx bx-trash"></i></span>
        </button>
    <?php endif; ?>
</form>

        </div>

        
        <div class="scrollable-history">
            <table class="history-table receipt-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>User</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody id="historyTableBody">
                    <?php while ($row = $history_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date('F j, Y', strtotime($row['timestamp'])); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['details']) . ": " . htmlspecialchars($row['item_name']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="no-records" style="display: none;">No records found</div>
        </div>