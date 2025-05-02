<?php
session_start();
include('config.php');

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

// Default SQL query
$sql = "SELECT items.*, IFNULL(SUM(order_items.quantity), 0) AS total_sold, IFNULL(average_rating, 0) AS rating_avg 
        FROM items 
        LEFT JOIN order_items ON items.id = order_items.item_id 
        LEFT JOIN orders ON order_items.order_id = orders.id";

// Category filter
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category_id = $_GET['category'];
    $sql .= " INNER JOIN item_categories ON items.id = item_categories.item_id
              WHERE item_categories.category_id = $category_id";
}

// Search functionality
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $category_id = isset($_GET['category']) ? $_GET['category'] : ''; // Get the category ID from the URL
    // Append search condition with category filter
    $sql .= " AND (items.name LIKE '%$search%' OR items.description LIKE '%$search%')";
    if (!empty($category_id)) {
        $sql .= " AND item_categories.category_id = $category_id"; // Add category filter to the SQL query
    }
}
    

// Group by item ID
$sql .= " GROUP BY items.id";

// Sorting functionality
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
switch ($sort) {
    case 'name':
        $sql .= " ORDER BY items.name ASC";
        break;
    case 'name_desc':
        $sql .= " ORDER BY items.name DESC";
        break;
    case 'total_sold':
        $sql .= " ORDER BY total_sold DESC";
        break;
    case 'price':
        $sql .= " ORDER BY items.price ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY items.price DESC";
        break;
    default:
        // Default sorting if $sort is not recognized
        $sql .= " ORDER BY items.name ASC";
        break;
}

// Execute the SQL query
$result = $conn->query($sql);

// Get the total number of items
$total_items_query = $conn->query("SELECT COUNT(*) AS total_items FROM ($sql) AS count_result");
$total_items_row = $total_items_query->fetch_assoc();
$total_items = $total_items_row['total_items'];

// Pagination variables
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$items_per_page = 10; // Change this value to the desired number of items per page
$start_range = ($page - 1) * $items_per_page + 1;
$end_range = min($page * $items_per_page, $total_items);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.0/css/boxicons.min.css">
    <title>Item Category</title>
    <style><?php include('C:\xampp\htdocs\PHP-Projects\E-Commerce-BeatStrum\css\item_category.css'); ?></style>
    <script src="/PHP-Projects/E-Commerce-BeatStrum/script/search_index.js"></script>
</head>
<body>
    <div class="head">
    <div class="head-group1">
        <a href="item_category.php">Browse by Category</a>
        <li class="search-box">
        <form action="item_category.php" method="GET">
    <input type="text" id="search" name="search" placeholder="Search...">
    <i class='bx bx-search icon'></i>
    <ul id="search-results"></ul>
    <!-- Include hidden input for category -->
    <input type="hidden" name="category" value="<?php echo $category_id; ?>">
</form>

    </li>
    </div>
    <div>
        <a href="index.php"><img src="/PHP-Projects/E-Commerce-BeatStrum/images/svg/logo-no-background.svg" alt=""></a>
    </div>
    <div>
        <ul class="head-group">
            <a href="#"><i class='bx bx-heart'></i></a>
           <a href="shopping_cart.php"><i class='bx bx-cart'></i></a>
           <a href="user_details.php"><i class='bx bx-user-circle'></i></a>
        </ul>
        <div class="sorting-options">
            <label for="sort">Sort by:</label>
            <select id="sort" name="sort" onchange="location = this.value;">
                <option value="item_category.php?category=<?php echo $category_id; ?>&sort=name" <?php echo ($sort == 'name') ? 'selected' : ''; ?>>Name (A-Z)</option>
                <option value="item_category.php?category=<?php echo $category_id; ?>&sort=name_desc" <?php echo ($sort == 'name_desc') ? 'selected' : ''; ?>>Name (Z-A)</option>
                <option value="item_category.php?category=<?php echo $category_id; ?>&sort=total_sold" <?php echo ($sort == 'total_sold') ? 'selected' : ''; ?>>Most Sold</option>
                <option value="item_category.php?category=<?php echo $category_id; ?>&sort=price" <?php echo ($sort == 'price') ? 'selected' : ''; ?>>Price (Low to High)</option>
                <option value="item_category.php?category=<?php echo $category_id; ?>&sort=price_desc" <?php echo ($sort == 'price_desc') ? 'selected' : ''; ?>>Price (High to Low)</option>
            </select>
        </div>
    </div>
</div>

    <div class="categories">
        <div class="category-links">
            <?php
            echo "<a class='title'>Category</a>";
            $sql_categories = "SELECT * FROM categories";
            $result_categories = $conn->query($sql_categories);
            if ($result_categories->num_rows > 0) {
                while ($row_category = $result_categories->fetch_assoc()) {
                    $category_id = $row_category['id'];
                    $category_name = $row_category['name'];
                    // Modify the following line to include the anchor tags
                    echo "<a href='item_category.php?category=$category_id'>$category_name</a>";
                }
            }
            ?>
        </div>
    </div>

<div class="outside-content">
    <div class="content">
        <h1>Browse by Category</h1>
    <?php
    if (!isset($_GET['category']) || empty($_GET['category'])) {
        echo "<p style='text-align: center; background-color: white; border: 1px solid #ccc'>Select a category</p>";
    } else {
    ?>
    <div class="items-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='item'>";
                echo "<a href='view_item.php?item_id=" . $row['id'] . "'>";
                $imagePath = '../uploads/' . $row['item_image'];
                if (file_exists($imagePath)) {
                    echo "<img src='" . $imagePath . "' alt='" . $row['name'] . "' style='width: 200px; height: 200px;'>"; 
                } else {
                    echo "<img src='path_to_default_image.jpg' alt='Default Image' style='width: 200px; height: 200px;'>"; 
                }
                echo "<p class='name'>" . $row['name'] . "</p>";
                echo "<div class='side'><p class='price'>&#8369;" . $row['price'] . "</p>";
                echo "<p class='sold'>Sold: " . $row['total_sold'] . "</p></div>"; 
                echo "</a>";
                echo "</div>";
            }
        } else {
            echo "No items available.";
        }
        ?>
    </div>
    <?php } ?>
</div></div>

</body>
</html>
