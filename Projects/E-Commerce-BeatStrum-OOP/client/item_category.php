<?php
session_start();

$host = "localhost:3307";
$username = "root";
$password = "";
$database = "ecommerce";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

class ItemManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getItems($category_id = null, $search_query = '', $sort = 'name') {
        // Default SQL query
        $sql = "SELECT items.*, IFNULL(SUM(order_items.quantity), 0) AS total_sold, IFNULL(average_rating, 0) AS rating_avg 
                FROM items 
                LEFT JOIN order_items ON items.id = order_items.item_id 
                LEFT JOIN orders ON order_items.order_id = orders.id";

        // Category filter
        if (!is_null($category_id)) {
            $sql .= " INNER JOIN item_categories ON items.id = item_categories.item_id
                      WHERE item_categories.category_id = $category_id";
        }

        // Search functionality
        if (!empty($search_query)) {
            $sql .= " AND (items.name LIKE '%$search_query%' OR items.description LIKE '%$search_query%')";
        }
            
        // Group by item ID
        $sql .= " GROUP BY items.id";

        // Sorting functionality
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
                $sql .= " ORDER BY items.name ASC";
                break;
        }

        $result = $this->conn->query($sql);
        return $result;
    }

}

$itemManager = new ItemManager($conn);

$category_id = isset($_GET['category']) ? $_GET['category'] : null;
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

$result = $itemManager->getItems($category_id, $search_query, $sort);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.0/css/boxicons.min.css">
    <title>Item Category</title>
    <style>
        <?php include('C:\xampp\htdocs\PHP-Projects\E-Commerce-BeatStrum-OOP\css\item_category.css'); ?>
 
    </style>
</head>
<body>
    <div class="head">
    <div class="head-group1">
        <a href="item_category.php">Browse by Category</a>
        <div class="search-box">
            <form action="item_category.php" method="GET">
                <input type="text" name="search" placeholder="Search...">
                <i class='bx bx-search icon'></i>
                <input type="hidden" name="category" value="<?php echo $category_id; ?>">
            </form>
        </div>
    </div>
    <div>
        <a href="index.php"><img src="/PHP-Projects/E-Commerce-BeatStrum-OOP/images/svg/logo-no-background.svg" alt=""></a>
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
</div>
</div>

</body>
</html>
