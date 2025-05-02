<?php 
// Deleted Items Display
$deleted_items_stmt = $conn->prepare("SELECT i.*, GROUP_CONCAT(DISTINCT c.color_name SEPARATOR ',') AS colors
                                        FROM Item i
                                        LEFT JOIN ItemColors ic ON i.item_id = ic.item_id
                                        LEFT JOIN Colors c ON ic.color_id = c.color_id
                                        WHERE i.isActive = 0
                                        GROUP BY i.item_id");
$deleted_items_stmt->execute();
$deleted_items_result = $deleted_items_stmt->get_result();


// Restoring item
if (isset($_POST['restore']) && isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];
    
    $restoreQuery = $conn->prepare("UPDATE Item SET isActive = 1 WHERE item_id = ?");
    $restoreQuery->bind_param("i", $item_id);
    $restoreQuery->execute();

    if ($restoreQuery->affected_rows > 0) {
        
        $details = "Restored item with ID: $item_id";
        $historyQuery = $conn->prepare("INSERT INTO ItemHistory (item_id, action, user_id, details) VALUES (?, 'restore', ?, ?)");
        $historyQuery->bind_param("iis", $item_id, $user_id, $details);
        $historyQuery->execute();


        if ($historyQuery->affected_rows > 0) {
            echo "<script>alert('Item successfully restored!');</script>";
        } else {
            echo "<script>alert('Error logging history.');</script>";
        }
    } else {
        echo "<script>alert('Error restoring item.');</script>";
    }
}

$category_query = $conn->prepare("SELECT * FROM Categories");
$category_query->execute();
$category_result = $category_query->get_result();

// Deleted Items Tab 
$search_filter = isset($_POST['search_filter']) ? $_POST['search_filter'] : '';
$quantity_filter = isset($_POST['quantity_filter']) ? $_POST['quantity_filter'] : '';
$category_filter = isset($_POST['category_filter']) ? $_POST['category_filter'] : '';


$deleted_items_sql = "SELECT i.*, GROUP_CONCAT(DISTINCT c.color_name SEPARATOR ',') AS colors
                        FROM Item i
                        LEFT JOIN ItemColors ic ON i.item_id = ic.item_id
                        LEFT JOIN Colors c ON ic.color_id = c.color_id
                        WHERE i.isActive = 0";

$conditions = [];
if ($search_filter) {
    $conditions[] = "(i.name LIKE '%$search_filter%' OR i.brand LIKE '%$search_filter%')";
}
if ($quantity_filter !== '') {
    $conditions[] = "i.quantity = $quantity_filter";
}
if ($category_filter) {
    $conditions[] = "i.category_id = '$category_filter'";
}

if ($conditions) {
    $deleted_items_sql .= " AND " . implode(" AND ", $conditions);
}

$deleted_items_sql .= " GROUP BY i.item_id";
$deleted_items_stmt = $conn->prepare($deleted_items_sql);
$deleted_items_stmt->execute();
$deleted_items_result = $deleted_items_stmt->get_result();

// Calculate Deleted Items Count
$count_query = "SELECT COUNT(*) AS item_count FROM Item WHERE isActive = 0";
$count_result = $conn->query($count_query);
$item_count = $count_result->fetch_assoc()['item_count'];

// -------------------------------------------------------------------------------
// Reset all filters
if (isset($_POST['reset_all'])) {
    unset($_POST['user_filter']);
    unset($_POST['date_filter']);
    unset($_POST['action_filter']);
    unset($_POST['search_filter']);
    unset($_POST['category_filter']);
    unset($_POST['quantity_filter']);
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>


            <div class="filter-section">
                <form method="POST" class="filter-form">
                    <p><strong>Total Deleted Items: </strong><?php echo $item_count; ?></p>
                    <input type="text" name="search_filter" placeholder="Search by Item Name or Brand..." value="<?php echo isset($_POST['search_filter']) ? $_POST['search_filter'] : ''; ?>" onchange="this.form.submit()">
                
                    <input type="number" name="quantity_filter" placeholder="Search by Quantity..." value="<?php echo isset($_POST['quantity_filter']) ? $_POST['quantity_filter'] : ''; ?>" onchange="this.form.submit()">
    
                    <select name="category_filter" onchange="this.form.submit()">
                        <option value="">Select Category</option>
                        <?php
                        while ($row = $category_result->fetch_assoc()) {
                            $category_id = $row['category_id'];
                            $category_name = $row['name'];
                            $selected = (isset($_POST['category_filter']) && $_POST['category_filter'] == $category_id) ? 'selected' : '';
                            echo "<option value='$category_id' $selected>" . htmlspecialchars($category_name) . "</option>";
                        }
                        ?>
                    </select>

                <?php if ($search_filter || $quantity_filter || $category_filter): ?>
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
                            <th>Item ID</th>
                            <th>Item Name</th>
                            <th>Brand</th>
                            <th>Size</th>
                            <th class="td-color">Color</th>
                            <th>Price</th>
                            <th>Investment Price</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = $deleted_items_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['item_id']); ?></td>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($item['brand']); ?></td>
                                <td><?php echo htmlspecialchars($item['size']); ?></td>
                                <td class="td-color">
                                    <?php
                                        if (!empty($item['colors'])) {
                                            $colors = explode(',', $item['colors']); 

                                            foreach ($colors as $color) {
                                                $color = trim($color); 
                                                echo "<p class='color-circle' style='background-color: $color;'></p>";
                                            }
                                        }
                                    ?>
                                </td>
                                <td>₱<?php echo number_format($item['price'], 2); ?></td>
                                <td>₱<?php echo number_format($item['investment_price'], 2); ?></td>  
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td>
                                    <button class="restore-button" onclick="openRestoreModal('<?php echo $item['item_id']; ?>')">Restore</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>


    <!-- Restore Modal -->
<div class="modal" id="restoreModal">
    <div class="modal-content">
        <h3>Confirm Restore</h3>
        <p>Are you sure you want to restore this item?</p>
        <form method="POST" action="">
            <input type="hidden" name="item_id" id="item_id_to_restore">
            <button type="submit" name="restore" class="button-confirm">Restore</button>
            <button type="button" class="button-cancel" onclick="closeRestoreModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
    function openRestoreModal(item_id) {
        document.getElementById('restoreModal').style.display = 'block';
        document.getElementById('item_id_to_restore').value = item_id;
    }

    function closeRestoreModal() {
        document.getElementById('restoreModal').style.display = 'none';
    }
</script>

<script>
    const resetButton = document.getElementById('resetButton');
    const form = document.getElementById('filterForm');

    form.addEventListener('submit', function(e) {
        if (e.submitter === resetButton) {
            return;
        }

        e.preventDefault();
    });

    resetButton.addEventListener('click', function() {
        form.reset();
        form.submit();
    });
</script>