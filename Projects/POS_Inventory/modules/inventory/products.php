<?php
include('../../includes/config.php'); 
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['admin_log'])) {
    header("Location: ../index.php");
    exit();
}

include('products_functions.php'); 
include('category_functions.php'); 

$category_id = isset($_GET['category_id']) && is_numeric($_GET['category_id']) ? (int)$_GET['category_id'] : null;
$categorySelected = !is_null($category_id);


$categoryName = '';
if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
    $category_id = (int)$_GET['category_id'];
    $category_query = "SELECT name FROM Categories WHERE category_id = $category_id";
    $category_result = mysqli_query($conn, $category_query);

    if ($category_result && $category_row = mysqli_fetch_assoc($category_result)) {
        $categoryName = $category_row['name'];
    } else {
        $categoryName = ''; 
    }
} else {
    $category_id = null;
    $categoryName = ''; 
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="../../assets/css/body-container.css">
    <link rel="stylesheet" href="../../assets/css/modal.css">
    <link rel="stylesheet" href="../../assets/css/products.css">
    <link rel="stylesheet" href="../../assets/css/buttons.css">
</head>
<body>

<?php include('../../includes/sidebar.php');  ?>

<div class="container">

    <div class="category">
        <div class="category-header">
            <div>
                <h5>CATEGORIES</h5>
                <!-- <p>Select From Below Categories</p> -->
            </div>

            <div class="bx-con" >
            <button class="bx-bt" onclick="openAddCategoryModal()" ><i class='bx bx-plus'></i></button> 
            </div>
           
        </div>



        <div class="carousel">
    <span class="carousel-btn" id="carousel-prev"><i class='bx bx-chevron-left'></i></span>

    <div class="category-container" id="category-container">
    <div class="carousel-content">
    <!-- "All" category -->
    <div class="category-card">
        <div class="card-header">
            <img src="../../assets/src/images/All.png" alt="All Items" class="category-image">
            <div class="card-info">
                <h3>All</h3>
                <p>
                    <?php 
                    $allItemsCountQuery = "SELECT COUNT(*) AS total FROM Item WHERE isActive > 0";
                    $allItemsCountResult = $conn->query($allItemsCountQuery);
                    $allItemsCount = $allItemsCountResult->fetch_assoc()['total'];
                    echo $allItemsCount;
                    ?> Items
                </p>
            </div>
        </div>
        <form method="GET" action="" class="card-select">
            <input type="hidden" name="category_id" value="">
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button class="category-card-btn" type="submit">
                <span>View All</span>
            </button>
        </form>
    </div>
    
    <!-- Categories -->
    <?php 
    $categories_result = $conn->query("SELECT * FROM Categories");
    while ($category = mysqli_fetch_assoc($categories_result)):
        $currentCategoryId = $category['category_id'];

        $item_count_query = "SELECT COUNT(*) AS item_count FROM Item WHERE category_id = $currentCategoryId AND isActive = 1";
        if (!empty($searchTerm)) {
            $escapedSearchTerm = $conn->real_escape_string($searchTerm);
            $item_count_query .= " AND (name LIKE '%$escapedSearchTerm%' OR brand LIKE '%$escapedSearchTerm%')";
        }

        $item_count_result = mysqli_query($conn, $item_count_query);
        $item_count = mysqli_fetch_assoc($item_count_result)['item_count'];
    ?>
        <div class="category-card">
            <div class="card-header">
                <img src="<?php echo $category['category_image']; ?>" alt="Category Image" class="category-image">
                <div class="card-info">
                    <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                    <p><?php echo $item_count; ?> Items</p>
                </div>
            </div>
            <div class="card-actions">
                        <button class="button-update" onclick="openUpdateModal(<?php echo $currentCategoryId; ?>, '<?php echo $category['name']; ?>', '<?php echo $category['category_image']; ?>')">
                            <i class='bx bx-edit'></i>
                        </button>
                        <button class="button-delete" onclick="deleteCategoryModal(<?php echo $currentCategoryId; ?>)">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>

            <form method="GET" action="" class="card-select">
                <input type="hidden" name="category_id" value="<?php echo $currentCategoryId; ?>">
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>"> 
                <button class="category-card-btn" type="submit">
                    <span>View</span>
                </button>
            </form>
        </div>
    <?php endwhile; ?>
</div>
    </div>

    <span class="carousel-btn" id="carousel-next"><i class='bx bx-chevron-right'></i></span>
</div>

    </div>

        <div class="category-header" >
            <h5>PRODUCTS</h5>

            <div class="bx-con" >
            <button class="bx-bt" id="addProductBtn" onclick="openAddProductModal()"<?php if (!$categorySelected): ?> disabled <?php endif; ?>><i class='bx bx-plus'></i></button>
                </div>
        </div>




        <div class="prod-page-con">

        <div class="search-div">
    <h5 class="category-name">
        <?php if ($categoryName): ?>
            <?php echo htmlspecialchars($categoryName); ?>
        <?php else: ?>
            All
        <?php endif; ?>
    </h5>

    <form class="search-group" method="GET">
        <input class="search" type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Search by Name or Brand">
        <input type="hidden" name="category_id" value="<?php echo $category_id; ?>" />
        <button class="search-bot" type="submit"><i class='bx bx-search-alt'></i></button>
        <?php if ($searchTerm): ?>
            <button type="submit" class="clear-search" name="search" value="">Clear Search</button>
        <?php endif; ?>
    </form>
</div>


        <table id="itemTable" border="1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item Name</th>
                    <th>Brand</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Color</th>
                    <th>Cost</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php
    if ($totalCount == 0) {
        echo "<tr><td colspan='9' style='text-align: center;'>No items found.</td></tr>";
    } else {
        foreach ($items as $index => $item) {
            $stockStatus = '';
            $stockIcon = '';
            $stockColor = '';

            switch (true) {
                case ($item['quantity'] <= 0):
                    $stockStatus = 'Out of Stock';
                    $stockIcon = "<i class='bx bxs-error-circle' style='color: red;' title='$stockStatus'></i>";
                    $stockColor = 'out-of-stock';
                    break;
                case ($item['quantity'] <= 7):
                    $stockStatus = 'Low Stock';
                    $stockIcon = "<i class='bx bx-no-entry' style='color: orange;' title='$stockStatus'></i>";
                    $stockColor = 'low-stock';
                    break;
                default:
                    $stockStatus = 'In Stock';
                    $stockIcon = "<i class='bx bxs-check-circle' style='color: green;' title='$stockStatus'></i>";
                    $stockColor = 'in-stock';
            }

            echo "<tr>";
            echo "<td>" . ($index + 1 + $offset) . "</td>";
            echo "<td>{$item['name']}</td>";
            echo "<td>{$item['brand']}</td>";
            echo "<td>{$item['size']}</td>";
            echo "<td class='$stockColor'>{$item['quantity']} $stockIcon</td>";

            echo "<td>";
            if (!empty($item['colors'])) {
                foreach ($item['colors'] as $color) {
                    echo "<div class='color-circle' style='background-color: $color;'></div>";
                }
            }
            echo "</td>";
            echo "<td>{$item['investment_price']}</td>";
            echo "<td>{$item['price']}</td>";
            echo "<td>
                    <button onclick='openRestockModal({$item['item_id']})'><i class='bx bx-list-plus'></i></button>
                    <button onclick='confirmDelete({$item['item_id']})'><i class='bx bx-trash'></i></button>
                </td>";
            echo "</tr>";
        }
    }
    ?>
</tbody>

        </table>
        </div>

        <div class="itemTable-bottom">
            <div id="itemCountDisplay">
                <?php 
                if ($totalCount > 0) {
                    echo "$currentStart - $currentEnd items of $totalCount";
                } else {
                    echo "No items found.";
                }
                ?>
            </div>

            <div id="pagination" class="pagination-container">
                <!-- Previous -->
                <a href="?page=<?php echo max($page - 1, 1); ?>&category_id=<?php echo $category_id ?? ''; ?>&search=<?php echo urlencode($searchTerm); ?>" class="pagination-btn" 
                <?php echo $page <= 1 ? 'style="pointer-events: none; opacity: 0.5;"' : ''; ?>>
                    <i class="bx bx-chevron-left"></i>
                </a>

                <?php
                for ($i = 1; $i <= $totalPages; $i++) {
                    $activeClass = ($i == $page) ? 'class="active"' : '';
                    echo "<a href='?page=$i&category_id=" . ($category_id ?? '') . "&search=" . urlencode($searchTerm) . "' $activeClass class='pagination-link'>$i</a>";
                }
                ?>

                <!-- Next -->
                <a href="?page=<?php echo min($page + 1, $totalPages); ?>&category_id=<?php echo $category_id ?? ''; ?>&search=<?php echo urlencode($searchTerm); ?>" class="pagination-btn" 
                <?php echo $page >= $totalPages ? 'style="pointer-events: none; opacity: 0.5;"' : ''; ?>>
                    <i class="bx bx-chevron-right"></i>
                </a>
            </div>

        </div>
    </div>


<?php include('modals.php'); ?>

</body>

<script src="../../assets/js/carousel_slider.js"></script>
<!-- <script src="../../assets/js/input_number.js"></script> -->
 <script src="../../assets/js/input_number.js"></script>

</html>
