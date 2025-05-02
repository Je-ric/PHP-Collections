<?php
session_start();
include('config.php');

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$items_per_page = 12;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Search functionality
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$search_condition = '';
if (!empty($search_query)) {
    $search_condition = "WHERE items.name LIKE '%$search_query%'";
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name'; 
$validSorts = array('name', 'name_desc', 'total_sold', 'price', 'price_desc');
if (!in_array($sort, $validSorts)) {
    $sort = 'name';
}

$sql = "SELECT items.*, IFNULL(SUM(order_items.quantity), 0) AS total_sold, IFNULL(average_rating, 0) AS rating_avg 
        FROM items 
        LEFT JOIN order_items ON items.id = order_items.item_id 
        LEFT JOIN orders ON order_items.order_id = orders.id ";

$sql .= $search_condition;

$sql .= " GROUP BY items.id";

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

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.0/css/boxicons.min.css">
    <title>Category Slider</title>
    <style>
        <?php include('C:\xampp\htdocs\PHP-Projects\E-Commerce-BeatStrum\css\index_client.css'); ?>
    </style>
   <script src="/PHP-Projects/E-Commerce-BeatStrum/script/search_index.js"></script>
</head>
<body>


<div class="head">
<div class="head-group1">
    <a href="item_category.php">Browse by Category</a>


    <li class="search-box">
        <form action="index.php" method="GET">
        <input type="text" id="search" name="search" placeholder="Search...">
            <i class='bx bx-search icon'></i>
            <ul id="search-results"></ul>
        </form>
    </li>
</div>

<div>
    <a href="index.php"><img src="/PHP-Projects/E-Commerce-BeatStrum/images/svg/logo-no-background.svg" alt=""></a></li>
</div>

<div>
<ul class="head-group">

       <a href="#"><i class='bx bx-heart'></i></a>
       <a href="shopping_cart.php"><i class='bx bx-cart'></i></a>
       <a href="user_details.php"><i class='bx bx-user-circle'></i></a>
    </ul>

    <div class="sorting-options">
    <label for="sort">Sort by:</label>
    <select id="sort" name="sort" onchange="location = 'index.php?page=1&search=<?php echo urlencode($search_query); ?>&sort=' + this.value;">
        <option value="name" <?php echo ($sort == 'name') ? 'selected' : ''; ?>>Name (A-Z)</option>
        <option value="name_desc" <?php echo ($sort == 'name_desc') ? 'selected' : ''; ?>>Name (Z-A)</option>
        <option value="total_sold" <?php echo ($sort == 'total_sold') ? 'selected' : ''; ?>>Most Sold</option>
        <option value="price" <?php echo ($sort == 'price') ? 'selected' : ''; ?>>Price (Low to High)</option>
        <option value="price_desc" <?php echo ($sort == 'price_desc') ? 'selected' : ''; ?>>Price (High to Low)</option>
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


<div class="discover">

        <h1>Discover Items</h1>
        <div class="items-container">
           <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    
                    echo "<div class='item'>";
                    echo "<a href='view_item.php?item_id=" . $row['id'] . "'>";
                    $imagePath = '../uploads/' . $row['item_image'];
                    if (file_exists($imagePath)) {
                        echo "<img src='" . $imagePath . "' alt='" . $row['name'] . "' style='width: 350px; height: 250px;'>"; 
                    } else {
                        echo "<img src='path_to_default_image.jpg' alt='Default Image' style='width: 350px; height: 250px;'>"; 
                    }
                    echo "<p class='name'>" . $row['name'] . "</p>";
                    echo "<div class='side'><p class='price'>&#8369;" . $row['price'] . "</p>";
                    // echo "<p>Rating: ";
                    // if ($row['rating_avg'] > 0) {
                    //     echo number_format($row['rating_avg'], 2);  
                    // } else {
                    //     echo "No ratings yet";
                    // }
                    echo "</p class='sold'>";
                    echo "<p>Sold: ". $row['total_sold'] . "</p></div>"; 
                    echo "</a>";
                    echo "</div>";
                }
            } else {
                echo "No items available.";
            }
            ?>
        </div>
    
        <!-- Pagination -->
        <?php
        $sql_count = "SELECT COUNT(*) AS total_items FROM items $search_condition";
        $result_count = $conn->query($sql_count);
        $row_count = $result_count->fetch_assoc();
        $total_items = $row_count['total_items'];
        $total_pages = ceil($total_items / $items_per_page);
        
    
        echo "<div class='pagination'>";
for ($i = 1; $i <= $total_pages; $i++) {
    echo "<a href='index.php?page=$i&search=" . urlencode($search_query) . "&sort=$sort'>$i</a>";
}
echo "</div>";

        ?>
    
</div>



<!-- 
<div class="category-slider">
        
    <h1>Browse by Category</h1>
        <div class="category-images">
            <?php
            
            // $sql_categories = "SELECT * FROM categories";
            // $result_categories = $conn->query($sql_categories);
            // if ($result_categories->num_rows > 0) {
            //     while ($row_category = $result_categories->fetch_assoc()) {
            //         $category_id = $row_category['id'];
            //         $category_name = $row_category['name'];
            //         $category_image = $row_category['category_image']; 
                    
            //         echo "<div class='category'>";
            //         echo "<a href='item_category.php?category=$category_id'>";
            //         echo "<p>$category_name</p>";
            //         echo "<img src='../category_uploads/$category_image' alt='$category_name'>";
            //         echo "</a>";
            //         echo "</div>";
            //     }
            // }
            ?>
        </div>
        <button class="prev" onclick="slide(-1)">❮</button>
        <button class="next" onclick="slide(1)">❯</button>
    </div>
    

    <script>
    let slideIndex = 0;
    const slides = document.querySelectorAll('.category');
    const totalSlides = slides.length;

    function slide(direction) {
        const nextSlideIndex = slideIndex + direction;

        if (nextSlideIndex >= 0 && nextSlideIndex < totalSlides) {
            slideIndex = nextSlideIndex;
        } else {    
            
            slideIndex = 0;
        }

        const offset = -slideIndex * slides[0].offsetWidth;
        document.querySelector('.category-images').style.transform = `translateX(${offset}px)`;
    }
</script> -->

</body>
</html>

