<?php
session_start();
include('config.php');


if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}


if (isset($_GET['item_id'])) {
    $itemId = $_GET['item_id'];
    $sql = "SELECT * FROM items WHERE id = $itemId";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        echo "Item not found.";
        exit();
    }
    $row = $result->fetch_assoc();
} else {
    echo "Invalid item ID.";
    exit();
}


function addToCart($itemId, $quantity, $conn, $clientId) {
    $itemId = mysqli_real_escape_string($conn, $itemId);
    $quantity = mysqli_real_escape_string($conn, $quantity);

    
    $check_query = "SELECT * FROM shopping_cart WHERE client_id = $clientId AND item_id = $itemId";
    $check_result = $conn->query($check_query);

    if($check_result->num_rows > 0) {
        
        $row = $check_result->fetch_assoc();
        $newQuantity = $row['quantity'] + $quantity;
        $update_query = "UPDATE shopping_cart SET quantity = $newQuantity WHERE client_id = $clientId AND item_id = $itemId";
        if ($conn->query($update_query) === TRUE) {
            echo "<script>alert('Item quantity updated in the cart.')</script>";
        } else {
            echo "<script>alert('Error updating item quantity in the cart: " . $conn->error . "')</script>";
        }
    } else {
        
        $insert_query = "INSERT INTO shopping_cart (client_id, item_id, quantity) VALUES ($clientId, $itemId, $quantity)";
        if ($conn->query($insert_query) === TRUE) {
            echo "<script>alert('Item successfully added to the cart.')</script>";
        } else {
            echo "<script>alert('Error adding item to the cart: " . $conn->error . "')</script>";
        }
    }
}

if(isset($_POST['add_to_cart'])) {
    $itemId = $_POST['item_id'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; 
    addToCart($itemId, $quantity, $conn, $_SESSION['client_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.0/css/boxicons.min.css">

    <title>View Item</title>
    <style>
    <?php include('C:\xampp\htdocs\PHP-Projects\E-Commerce-BeatStrum\css\view_item.css'); ?>
    </style>
</head>
<body>    
<?php 
    include ('1.header.php');
?>
    <input class="cancel" type="button" value="Back" onclick="window.location.href='index.php';">
    
    <div class="container">
        <!-- Item Container -->
        <div class="item-container">
            <?php 
            $imagePath = '../uploads/' . $row['item_image'];
            if (file_exists($imagePath)) {
                echo "<img src='" . $imagePath . "' alt='" . $row['name'] . "' class='item-image' style='width: 65%; height: 60%; display: block; margin: 0 auto; padding: 10px'>"; 
            } else {
                echo "<img src='path_to_default_image.jpg' alt='Default Image' class='item-image'>"; 
            }
            ?>
            
        </div>

        <!-- Details Container -->
        <div class="details-container">
            <div class="details-container-group1">
                <h1><?php echo $row['name']; ?></h1>
                <p>&#8369;<?php echo $row['price']; ?></p>
            </div>

            <!-- Description Toggle -->
            <div class="description-toggle">
                <div class="description-container">
                    <p>Description: </p>
                    <span class="toggle-trigger" onclick="toggleDetails(this)">Click for more details</span>
                    <div class="toggle-details">
                    <p>&nbsp;<?php echo $row['description']; ?></p>
                    </div>
                </div>
            </div>

            <!-- Add to Cart Form -->
            <form method='post' action=''>
                <input type='hidden' name='item_id' value='<?php echo $row['id']; ?>'>

                <!-- Quantity Input Field with Minus and Plus Symbols -->
                <div class="quantity-input">
                    <p>Quantity:</p>
                    <button type="button" onclick="decrementQuantity()">-</button>
                    <input type="number" id="quantity" name="quantity" min="1" max="5" value="1">
                    <button type="button" onclick="incrementQuantity()">+</button>
                </div>

                <input type='submit' name='add_to_cart' value='Add to Cart'>
            </form>
        </div>     
    </div>
   


<div class="related-items">
    <h2>You might also be interested in</h2>
    <div class="items-container">
        <?php
        // Fetching category ID along with other item details
        $sql = "SELECT items.*, item_categories.category_id 
                FROM items 
                LEFT JOIN item_categories ON items.id = item_categories.item_id 
                WHERE items.id = $itemId";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $categoryId = $row['category_id'];
            
            // Fetching 4 random items based on the category of the current item
            $relatedItemsQuery = "SELECT * 
                                  FROM items 
                                  WHERE id != $itemId 
                                  AND id IN (SELECT item_id 
                                             FROM item_categories 
                                             WHERE category_id = $categoryId) 
                                  ORDER BY RAND() 
                                  LIMIT 4";

            $relatedItemsResult = $conn->query($relatedItemsQuery);

            if ($relatedItemsResult->num_rows > 0) {
                while ($relatedItem = $relatedItemsResult->fetch_assoc()) {
                    echo "<div class='item'>";
                    echo "<a href='view_item.php?item_id=" . $relatedItem['id'] . "'>";
                    $relatedImagePath = '../uploads/' . $relatedItem['item_image'];
                    if (file_exists($relatedImagePath)) {
                        echo "<img src='" . $relatedImagePath . "' alt='" . $relatedItem['name'] . "' style='width: 350px; height: 250px;'>"; 
                    } else {
                        echo "<img src='path_to_default_image.jpg' alt='Default Image' style='width: 350px; height: 250px;'>"; 
                    }
                    echo "<p class='name'>" . $relatedItem['name'] . "</p>";
                    echo "<div class='side'>";
                    echo "<p class='price'>&#8369;" . $relatedItem['price'] . "</p>";
                    echo "<p>Sold: " . $relatedItem['sold'] . "</p>";
                    echo "</div>";
                    echo "</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>No related items found.</p>";
            }
        } else {
            echo "<p>Item not found.</p>";
        }
        ?>
    </div>
</div>



    <!-- Script for Toggle Details and Quantity Control -->
    <script>
        // Function to toggle additional details
        function toggleDetails(element) {
            var details = element.nextElementSibling;
            if (details.style.display === 'none' || details.style.display === '') {
                details.style.display = 'block';
            } else {
                details.style.display = 'none';
            }
        }


    </script>

<script src="/PHP-Projects/E-Commerce-BeatStrum/script/quantityButton.js"></script>
</body>
</html>