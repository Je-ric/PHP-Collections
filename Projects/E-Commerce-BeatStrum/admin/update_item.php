<?php
require_once('session.php');
require_once('config.php');
session_start();

if (!isset($_SESSION['admin_log'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category'];
    $shipping_fee = $_POST['shipping_fee'];
    $quantity = $_POST['quantity'];

    // Handle item image upload if a new image is selected
    if ($_FILES['item_image']['size'] > 0) {
        $image = $_FILES['item_image']['name'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["item_image"]["name"]);

        if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
            $sql = "UPDATE items 
                    SET name='$name', description='$description', price='$price', 
                        item_image='$image', shipping_fee='$shipping_fee', quantity='$quantity'
                    WHERE id='$item_id'";
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    } else {
        // If no new image is selected, keep the existing image
        $sql = "UPDATE items 
                SET name='$name', description='$description', price='$price', 
                    shipping_fee='$shipping_fee', quantity='$quantity'
                WHERE id='$item_id'";
    }

    // Handle category image upload if a new image is selected
    if ($_FILES['new_category_image']['size'] > 0) {
        $newCategoryImage = $_FILES['new_category_image']['name'];
        $target_dir = "../category_uploads/";
        $target_file = $target_dir . basename($_FILES["new_category_image"]["name"]);

        if (move_uploaded_file($_FILES["new_category_image"]["tmp_name"], $target_file)) {
            // Update category details including image
            $sqlUpdateCategory = "UPDATE categories SET category_image='$newCategoryImage' WHERE id='$category_id'";
            if ($conn->query($sqlUpdateCategory) === FALSE) {
                echo "Error updating category image: " . $conn->error;
                exit;
            }
        } else {
            echo "Sorry, there was an error uploading the category image.";
            exit;
        }
    }

    if ($conn->query($sql) === TRUE) {
        $sql_item_category = "UPDATE item_categories SET category_id='$category_id' WHERE item_id='$item_id'";
        $conn->query($sql_item_category);
        echo "Record updated successfully";
        header("Location: index.php");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$item_id = $_GET['id'] ?? null;

if (!$item_id) {
    echo "Item ID not provided";
    exit;
}

$sql_item = "SELECT * FROM items WHERE id='$item_id'";
$result_item = $conn->query($sql_item);

if ($result_item->num_rows == 0) {
    echo "Item not found";
    exit;
}

$item_data = $result_item->fetch_assoc();

// Fetch the category ID associated with the item
$sql_item_category = "SELECT category_id FROM item_categories WHERE item_id='$item_id'";
$result_item_category = $conn->query($sql_item_category);

if ($result_item_category->num_rows == 0) {
    echo "Category ID not found for the item";
    exit;
}

$item_category_data = $result_item_category->fetch_assoc();
$item_category_id = $item_category_data['category_id'];

$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);
$categories = array();
if ($result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories[$row['id']] = $row['name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Item</title>
    <style>
        <?php include('C:\xampp\htdocs\PHP-Projects\E-Commerce-BeatStrum\css\admin_form.css'); ?>
    </style>
</head>
<body>
    
<?php include 'header.php'; ?>

    <div class="container">
        <h1>Update Item</h1>
        <form id="updateForm" action="update_item.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="item_id" value="<?php echo $item_data['id']; ?>">
            <div class="container-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $item_data['name']; ?>" required>
                
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required><?php echo $item_data['description']; ?></textarea>
                
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" value="<?php echo $item_data['price']; ?>" required>
                
            </div>
            <div class="container-group">
                <label for="category">Category:</label>
                <select id="category" name="category">
                    <?php
                    foreach ($categories as $id => $name) {
                        $selected = ($id == $item_category_id) ? 'selected' : '';
                        echo "<option value='$id' $selected>$name ($id)</option>";
                    }
                    ?>
                </select>

                <label for="new_category_image">New Category Image:</label>
                <input type="file" id="new_category_image" name="new_category_image" accept="image/*">
                
                <br><label for="quantity">Cart Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="<?php echo $item_data['quantity']; ?>" required>

                <label for="shipping_fee">Shipping Fee:</label>
                <input type="text" id="shipping_fee" name="shipping_fee" value="<?php echo $item_data['shipping_fee']; ?>" required>

                <label for="item_image">Item Image:</label>
                <input type="file" id="item_image" name="item_image" accept="image/*">
                
            </div>
                <input type="submit" value="Update Item">
                <input type="button" id="backButton" value="Back">
        </form>
    </div>

    <script>
        document.getElementById("backButton").addEventListener("click", function(event) {
            event.preventDefault(); // Prevent the form submission
            if (confirm("Are you sure you want to cancel updating the item?")) {
                window.location.href = "index.php"; // Redirect to index.php if confirmed
            }
        });

        document.getElementById("updateForm").addEventListener("submit", function(event) {
            if (!confirm("Are you sure you want to update the item?")) {
                event.preventDefault(); // Prevent form submission if not confirmed
            }
        });
    </script>
</body>
</html>
