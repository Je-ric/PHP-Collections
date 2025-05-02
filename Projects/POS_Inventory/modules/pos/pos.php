<?php
session_start();
include('../../includes/config.php'); 

if (!isset($_SESSION['admin_log']) && !isset($_SESSION['log_emp'])) {
    header("Location: ../index.php");
    exit();
}

function clean_cart(&$cart) {
    foreach ($cart as $item_id => $item) {
        if (empty($item['name']) || !isset($item['price'], $item['quantity'])) {
            unset($cart[$item_id]);
        }
    }
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$price = 0;
$total_price = 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

$items_per_page = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page; 

switch ($action) {
    case 'add_to_cart':
        $item_id = $_POST['item_id'];
        $quantity = $_POST['quantity'];

        $item_query = "SELECT * FROM Item WHERE item_id = ?";
        $stmt = $conn->prepare($item_query);
        $stmt->bind_param("i", $item_id);
        $stmt->execute();
        $item_result = $stmt->get_result()->fetch_assoc();

        if ($item_result['quantity'] < $quantity) {
            $_SESSION['message'] = "Adjusted to maximum available stock for " . $item_result['name'] . ".";
            $_SESSION['cart'][$item_id]['quantity'] = $item_result['quantity'];
        } else {
            if (!isset($_SESSION['cart'][$item_id])) {
                $_SESSION['cart'][$item_id] = [
                    'name' => $item_result['name'],
                    'price' => $item_result['price'],
                    'quantity' => $quantity
                ];
            } else {
                $_SESSION['cart'][$item_id]['quantity'] += $quantity;
                if ($_SESSION['cart'][$item_id]['quantity'] > $item_result['quantity']) {
                    $_SESSION['cart'][$item_id]['quantity'] = $item_result['quantity'];
                    $_SESSION['message'] = "Adjusted to maximum available stock for " . $item_result['name'] . ".";
                }
            }
        }
        break;

    case 'remove':
        $item_id = $_POST['item_id'];
        if (isset($_SESSION['cart'][$item_id])) {
            unset($_SESSION['cart'][$item_id]);
        }
        header("Location: pos.php");
        exit();

    case 'update_quantity':
        $item_id = $_POST['item_id'];
        $new_quantity = $_POST['quantity'];

        $item_query = "SELECT quantity, name, price FROM Item WHERE item_id = ?";
        $stmt = $conn->prepare($item_query);
        $stmt->bind_param("i", $item_id);
        $stmt->execute();
        $item_data = $stmt->get_result()->fetch_assoc();
        $available_quantity = $item_data['quantity'];

        if ($new_quantity > $available_quantity) {
            // $_SESSION['cart'][$item_id]['quantity'] = $available_quantity;
            // $_SESSION['message'] = "Adjusted to maximum available stock.";
            $_SESSION['cart'][$item_id] = [
                'name' => $item_data['name'],
                'price' => $item_data['price'],
                'quantity' => $available_quantity
            ];
            $_SESSION['message'] = "Adjusted to maximum available stock.";
        } elseif ($new_quantity == 0) {
            unset($_SESSION['cart'][$item_id]);
        } else {
            // $_SESSION['cart'][$item_id]['quantity'] = $new_quantity;
            $_SESSION['cart'][$item_id] = [
                'name' => $item_data['name'],
                'price' => $item_data['price'],
                'quantity' => $new_quantity
            ];
        }
        clean_cart($_SESSION['cart']);
        break;

    case 'cancel_transaction':
        unset($_SESSION['cart']);
        $_SESSION['message'] = "Transaction cancelled.";
        header("Location: pos.php"); 
        break;
}

if (isset($_GET['remove'])) {
    $item_id = $_GET['remove'];
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
    header("Location: pos.php");
    exit();
}


foreach ($_SESSION['cart'] as $item) {
//     if (isset($item['price']) && isset($item['quantity'])) {
//         $total_price += $item['price'] * $item['quantity'];
//     }
    $price = isset($item['price']) ? $item['price'] : 0;
    $quantity = isset($item['quantity']) ? $item['quantity'] : 0;
    $total_price += $price * $quantity;
}


$categories_query = "SELECT * FROM Categories";
$categories_result = mysqli_query($conn, $categories_query);

$selected_category = isset($_GET['category']) ? $_GET['category'] : null;
$search_query = '';
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

if ($selected_category) {
    $search_query = " WHERE category_id = ?";
    if ($search_term) {
        $search_query .= " AND (name LIKE ? OR brand LIKE ?)";
        $items_query = "SELECT * FROM Item" . $search_query . " AND quantity > 0 LIMIT ?, ?";
        $stmt = $conn->prepare($items_query);
        $search_term_for_sql = "%$search_term%";
        $stmt->bind_param("issii", $selected_category, $search_term_for_sql, $search_term_for_sql, $offset, $items_per_page);
    } else {
        $items_query = "SELECT * FROM Item" . $search_query . " AND quantity > 0 LIMIT ?, ?";
        $stmt = $conn->prepare($items_query);
        $stmt->bind_param("iii", $selected_category, $offset, $items_per_page);
    }
} else {
    $items_query = "SELECT * FROM Item WHERE quantity > 0 LIMIT ?, ?";
    $stmt = $conn->prepare($items_query);
    $stmt->bind_param("ii", $offset, $items_per_page);
}

$stmt->execute();
$items_result = $stmt->get_result();

$total_items_query = "SELECT COUNT(*) as total_items FROM Item WHERE quantity > 0";
if ($selected_category) {
    $total_items_query .= " AND category_id = $selected_category";
    if ($search_term) {
        $total_items_query .= " AND (name LIKE ? OR brand LIKE ?)";
    }
}
$total_items_stmt = $conn->prepare($total_items_query);
if ($search_term) {
    $total_items_stmt->bind_param("ss", $search_term_for_sql, $search_term_for_sql);
}
$total_items_stmt->execute();
$total_items_result = $total_items_stmt->get_result()->fetch_assoc();
$total_items = $total_items_result['total_items'];

$total_pages = ceil($total_items / $items_per_page);


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/body-container.css">
    <link rel="stylesheet" href="../../assets/css/pos.css">
    <title>POS System - Cart</title>

    
</head>

<?php include('../../includes/sidebar.php');  ?>

<div class="container">

        <?php if (isset($_SESSION['message'])): ?>
            <div style="color: red;"><?php echo $_SESSION['message']; ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        
<div class="pos-container">
    <div class="left-side">
        <div>
            <div class="category-header">
                <div>
                     <h5>Categories</h5> 
                     <!-- <p>Select From Below Categories</p> -->
                </div>
                
                <div>
                <?php 
                    $categoryName = '';
                    if (isset($_GET['category'])) {
                        $category_id = $_GET['category'];
                        $category_query = "SELECT name FROM Categories WHERE category_id = ?";
                        $stmt = $conn->prepare($category_query);
                        $stmt->bind_param("i", $category_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($row = $result->fetch_assoc()) {
                            $categoryName = $row['name'];
                        }
                    }
                    if (!empty($categoryName)): ?>
                        <p><?php echo htmlspecialchars($categoryName); ?></p>
                    <?php else: ?>
                        <p>No category selected.</p>
                    <?php endif; ?>
                </div>
                <div>
                    <span class="carousel-btn" id="carousel-prev"><i class='bx bx-chevron-left'></i></span>
                    <span class="carousel-btn" id="carousel-next"><i class='bx bx-chevron-right'></i></span>
                </div>
            </div>
            <!-- <p>Select From Below Categories</p> -->
            <div class="category-container" id="category-container">
               <div class="carousel-content">
               
                    <?php 
                    while ($category = mysqli_fetch_assoc($categories_result)):
                        $category_id = $category['category_id'];
                        $item_count_query = "SELECT COUNT(*) AS item_count FROM Item WHERE category_id = $category_id AND quantity > 0";
                        $item_count_result = mysqli_query($conn, $item_count_query);
                        $item_count = mysqli_fetch_assoc($item_count_result)['item_count'];
                    ?>
                    <form method="GET" action="pos.php" style="display:inline;">
                        <input type="hidden" name="category" value="<?php echo $category['category_id']; ?>">
                        <button class="category-card" type="submit">
                            <img src="<?php echo $category['category_image']; ?>" alt="Category Image" class="category-image">
                            <div class="card-info">
                                <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                                <p><?php echo $item_count; ?> Items</p>
                            </div>
                            <!-- <h3><?php #echo htmlspecialchars($category['name']); ?></h3>
                            <p><?php #echo $item_count; ?> Items</p>  -->
                        </button>
                    </form>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <!-- Available Products -->
        <div class="pos-section">
        <div class="available-header">
            <?php if ($selected_category): ?>
                <form method="GET" action="pos.php" style="display: flex; align-items: center; margin-bottom: 7px;">
                    <input type="text" name="search" placeholder="Search by name or brand..." value="<?php echo htmlspecialchars($search_term); ?>" 
                        style="padding: 5px; margin-right: 10px; background-color: white; color: black; border: 1px solid gray;"/>
                    <?php if ($selected_category): ?>
                        <input type="hidden" name="category" value="<?php echo $selected_category; ?>" />
                    <?php endif; ?>
                    <button type="submit" style="padding: 5px 10px; cursor: pointer; background-color: black; color: white; border: 1px solid gray;">
                        Search
                    </button>
                </form>
            <?php endif; ?>
                <!-- <h3>Available Products</h3> -->
        </div>
            <!-- <div class="scrollable"> -->
                <?php if ($selected_category): ?>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Name/Brand</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        while ($item = mysqli_fetch_assoc($items_result)): 
                            $item_id = $item['item_id'];
                            $cart_quantity = isset($_SESSION['cart'][$item_id]) ? $_SESSION['cart'][$item_id]['quantity'] : 0;
                            $available_quantity = $item['quantity'] - $cart_quantity;
                        ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($item['name']); ?><br>
                                <span style="font-size: 0.8em; color: #888;"><?php echo htmlspecialchars($item['brand']); ?></span>
                            </td>
                            <td>₱<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <?php echo $available_quantity > 0 ? $available_quantity : '<span style="color: red;">Out of stock</span>'; ?>
                            </td>
                            <td>
                                <?php if ($available_quantity > 0): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" name="action" class="add-to-cart" value="add_to_cart"><i class='bx bx-cart-add'></i> Add to Cart</button>
                                </form>
                                <?php else: ?>
                                <button disabled>Unavailable</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p>Please select a category to view products.</p>
                <?php endif; ?>
            <!-- </div> -->

            <!-- Pagination -->
            <?php if ($total_pages > 1 && $selected_category !== null): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?category=<?php echo $selected_category; ?>&page=<?php echo $i; ?>" class="page-link <?php echo $page == $i ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    

    <div class="right-side">
        <div class="cart-section">
           <div class="scrollable">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($_SESSION['cart'] as $item_id => $item): ?>
                        <?php 
                        if (isset($item['name']) && isset($item['price'])): 
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>₱<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                                    <input type="number" name="quantity" class="cart-item-quantity" value="<?php echo $item['quantity']; ?>" min="1" onchange="this.form.submit();">
                                    <input type="hidden" name="action" value="update_quantity">
                                </form>
                                <a href="#" class="remove-item" data-item-id="<?php echo $item_id; ?>"><i class='bx bx-trash'></i></a>
                            </td>
                            <td >₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                
                            </td>
                        </tr>
                        <?php endif; ?>
                <?php endforeach; ?>
                    </tbody>
                </table>
           </div>

            <div class="cart-actions">
                <div>
                    <h3>Total: ₱<?php echo number_format($total_price, 2); ?></h3>
                </div>
                <div class="cart-button-group">
                    <form method="POST" action="pos.php" onsubmit="return confirmCancel();">
                        <button class="cancel-btn" type="submit" name="action" value="cancel_transaction">Cancel</button>
                    </form>
                    <form method="POST" action="payment.php">
                        <button class="payment" type="submit" <?php echo empty($_SESSION['cart']) ? 'disabled' : ''; ?>>Proceed to Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
 
    </div>

    <script>
    function confirmCancel() {
        const message = "Are you sure you want to cancel? This will remove all items in your cart.";
        return confirm(message); 
    }
</script>
<script src="../../assets/js/carousel_slider.js"></script>
<script src="../../assets/js/input_number.js"></script>
<script src="../../assets/js/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        
        $('.add-to-cart').click(function() {
            var itemId = $(this).data('item-id');
            var quantity = $(this).data('quantity');
            $.post('pos.php', { action: '', item_id: itemId, quantity: quantity }, function(response) {
                location.reload(); 
            });
        });

        
        $('.cart-item-quantity').on('change', function() {
            var itemId = $(this).data('item-id');
            var newQuantity = $(this).val();

            $.post('pos.php', { action: 'update_quantity', item_id: itemId, quantity: newQuantity }, function(response) {
                location.reload(); 
            });
        });

        
        $('.remove-item').on('click', function(e) {
            e.preventDefault();
            var itemId = $(this).data('item-id');

            $.post('pos.php', { 
                action: 'remove', item_id: itemId }, function(response) {
                location.reload(); 
            });
        });
    });

</script>

</body>
</html>
