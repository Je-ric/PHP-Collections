<?php
    require_once('config.php');
    session_start();

    // Redirect to login page if not logged in
    if (!isset($_SESSION['admin_log'])) {
        header("Location: admin.php");
        exit;
    }

    // Fetch items or search results based on query
    $items_per_page = 10;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $items_per_page;

    // Check if a search query is provided
    $search_condition = ""; // Initialize search condition
    if(isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $_GET['search'];
        $search_condition = " WHERE name LIKE '%$search%'";
    }

    // Initial SQL query to fetch items
    $sql = "SELECT items.*, 
            IFNULL(SUM(order_items.quantity), 0) AS total_sold, 
            IFNULL(average_rating, 0) AS rating_avg 
            FROM items 
            LEFT JOIN order_items ON items.id = order_items.item_id 
            LEFT JOIN orders ON order_items.order_id = orders.id ";

    $sql .= $search_condition;

    $sql .= " GROUP BY items.id";

    // Define the default sorting option
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'name';

    // Adjust the SQL query based on the selected sorting option
    switch ($sort) {
        case 'name':
            $sql .= " ORDER BY name ASC";
            break;
        case 'name_desc':
            $sql .= " ORDER BY name DESC";
            break;
        case 'total_sold':
            $sql .= " ORDER BY total_sold DESC";
            break;
        case 'price':
            $sql .= " ORDER BY price ASC";
            break;
        case 'price_desc':
            $sql .= " ORDER BY price DESC";
            break;
    }

    $sql .= " LIMIT $offset, $items_per_page";

    // Execute the SQL query
    $result = $conn->query($sql);

    // Count total items
    $sql_count = "SELECT COUNT(*) AS total_items FROM items";
    if(isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $_GET['search'];
        $sql_count .= " WHERE name LIKE '%$search%'";
    }

    $result_count = $conn->query($sql_count);
    $row_count = $result_count->fetch_assoc();
    $total_items = $row_count['total_items'];
    ?>
    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
    <style>
        <?php include('C:\xampp\htdocs\PHP-Projects\E-Commerce-BeatStrum\css\index.css'); ?>
       
    </style>
</head>
<body>
   
    <?php include 'header.php'; ?>

    <h1 class="dashboard">Dashboard</h1>
    <div class="head">
    <li class="search-box">
    <form action="index.php" method="GET">
        <input type="text" name="search" placeholder="Search...">
        <i class='bx bx-search icon'></i>
    </form>
</li>

        <?php
        $start_range = ($page - 1) * $items_per_page + 1;
        $end_range = min($page * $items_per_page, $total_items);
        ?>

        <p><?php echo "($start_range-$end_range products of $total_items)"; ?></p>

        <div class="sorting-options">
            <label for="sort">Sort by:</label>
            <select id="sort" name="sort" onchange="location = this.value;">
                <option value="index.php?sort=name" <?php echo ($sort == 'name') ? 'selected' : ''; ?>>Name (A-Z)</option>
                <option value="index.php?sort=name_desc" <?php echo ($sort == 'name_desc') ? 'selected' : ''; ?>>Name (Z-A)</option>
                <option value="index.php?sort=total_sold" <?php echo ($sort == 'total_sold') ? 'selected' : ''; ?>>Most Sold</option>
                <option value="index.php?sort=price" <?php echo ($sort == 'price') ? 'selected' : ''; ?>>Price (Low to High)</option>
                <option value="index.php?sort=price_desc" <?php echo ($sort == 'price_desc') ? 'selected' : ''; ?>>Price (High to Low)</option>
            </select>
        </div>
    </div>

    <div class="items-container">
        <form action="delete_selected_items.php" method="post">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Sold</th>
                        <th>Shipping Fee</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='checkbox'><input type='checkbox' name='selected_items[]' value='" . $row['id'] . "'></td>";
                            echo "<td><img src='../uploads/" . $row['item_image'] . "' alt='" . $row['name'] . "' style='width: 100px; height: 100px;'></td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>₱" . $row['price'] . "</td>";
                            echo "<td>" . $row['total_sold'] . "</td>";
                            echo "<td>₱" . $row['shipping_fee'] . "</td>";
                            echo "<td>" . $row['quantity'] . "</td>";
                            echo "<td class='status-column'>";
                            if ($row['quantity'] == 0) {    
                                echo "<div class='no-stock'>Out of Stock</div>";
                            } else {
                                echo "<div class='available'>Available</div>";
                            }
                            echo "</td>";
                            echo "<td><a href='update_item.php?id=".$row['id']."' class='edit-link'><i class='bx bx-edit edit-icon'></i></a> <a href='delete_item.php?id=".$row['id']."' class='delete-link'><i class='bx bx-trash delete-icon'></i></a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='11'>0 results</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <button type="submit">Delete Selected Items</button>
        </form>
    </div>

    <!-- Pagination -->
    <?php
    // Pagination links
    $total_pages = ceil($total_items / $items_per_page);

    echo "<div class='pagination'>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='?page=$i&sort=$sort" . (isset($_GET['search']) ? '&search=' . $_GET['search'] : '') . "'>$i</a>";
    }
    echo "</div>";
    
    ?>  
</body>
</html>
