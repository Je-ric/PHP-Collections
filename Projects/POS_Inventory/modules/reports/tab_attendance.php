<?php 
// Users
$sql = "SELECT * FROM Users";
$result = mysqli_query($conn, $sql);

// Employee Tab
$user_filter = $_POST['user_filter'] ?? '';
$roleFilter = $_POST['role_filter'] ?? '';
$statusFilter = $_POST['status_filter'] ?? '';

$sql = "SELECT * FROM Users";
$conditions = [];
if ($user_filter) {
    $conditions[] = "name LIKE '%$user_filter%'";
}
if ($roleFilter) {
    $conditions[] = "role = '$roleFilter'";
}
if ($statusFilter) {
    $conditions[] = "status = '$statusFilter'";
}

if ($conditions) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$result = mysqli_query($conn, $sql);

// -------------------------------------------------------------------------------
// Reset all filters
if (isset($_POST['reset_all'])) {
    unset($_POST['user_filter']);
    unset($_POST['role_filter']);
    unset($_POST['status_filter']);
    unset($_POST['date_filter']);
    unset($_POST['action_filter']);
    unset($_POST['search_filter']);
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>  
<div class="filter-section">
    <form method="POST" class="filter-form">
        <input type="text" name="user_filter" placeholder="Filter by User..." value="<?= htmlspecialchars($_POST['user_filter'] ?? '') ?>" onchange="this.form.submit()">
        
    <select name="role_filter" id="role_filter" onchange="this.form.submit()">
    <option value="">Select Roles to filter</option>
    <option value="admin" <?php echo (isset($_POST['role_filter']) && $_POST['role_filter'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
    <option value="employee" <?php echo (isset($_POST['role_filter']) && $_POST['role_filter'] == 'employee') ? 'selected' : ''; ?>>Employee</option>
</select>

<select name="status_filter" id="status_filter" onchange="this.form.submit()">
    <option value="">Select Status to filter</option>
    <option value="active" <?php echo (isset($_POST['status_filter']) && $_POST['status_filter'] == 'active') ? 'selected' : ''; ?>>Active</option>
    <option value="inactive" <?php echo (isset($_POST['status_filter']) && $_POST['status_filter'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
</select>

        <!-- Reset Button -->
        <?php if ($user_filter || $statusFilter || $roleFilter): ?>
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
                <th>ID</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row["user_id"]; ?></td>  
                    <td><?php echo $row["name"]; ?></td>     
                    <td>
                        <button class="view-history-button" onclick="location.href='../accounts/attendance_history.php?user_id=<?= htmlspecialchars($row['user_id']) ?>'">
                            <i class='bx bx-history'></i>
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
