<?php
session_start();
class Database {
    private $conn;
    
    public function __construct($host, $username, $password, $dbname) {
        $this->conn = new mysqli($host, $username, $password, $dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function closeConnection() {
        $this->conn->close();
    }
}

class SessionManager {
    public static function isAdminLoggedIn() {
        return isset($_SESSION['admin_log']);
    }
}
class FormHandler {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addNewItem() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $image = $_FILES['item_image']['name'];
            $shipping_fee = $_POST['shipping_fee'];
            $quantity = $_POST['quantity'];
    
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES["item_image"]["name"]);
    
            if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
                $category_id = $_POST['category'];
                if ($category_id == 'add_new_category') {
                    $newCategoryName = $_POST['new_category_name'];
                    $newCategoryImage = $_FILES['new_category_image']['name'];
                    
                    $target_dir_category = "../category_uploads/";
                    $target_file_category = $target_dir_category . basename($_FILES["new_category_image"]["name"]);
    
                    if (move_uploaded_file($_FILES["new_category_image"]["tmp_name"], $target_file_category)) {
                        $sqlNewCategory = "INSERT INTO categories (name, category_image) VALUES ('$newCategoryName', '$newCategoryImage')";
                        if ($this->db->getConnection()->query($sqlNewCategory) === TRUE) {
                            $category_id = $this->db->getConnection()->insert_id;
                        } else {
                            echo "Error adding new category: " . $this->db->getConnection()->error;
                            exit;
                        }
                    } else {
                        echo "Sorry, there was an error uploading your category image.";
                        exit;
                    }
                }
    
                $sql = "INSERT INTO items (name, description, price, item_image, shipping_fee, quantity) 
                        VALUES ('$name', '$description', '$price', '$image', '$shipping_fee', '$quantity')";
    
                if ($this->db->getConnection()->query($sql) === TRUE) {
                    $item_id = $this->db->getConnection()->insert_id;
                    $sql_item_category = "INSERT INTO item_categories (item_id, category_id) VALUES ('$item_id', '$category_id')";
                    $this->db->getConnection()->query($sql_item_category);
                    echo "New record created successfully";
                    header("Location: index.php");
                    exit;
                } else {
                    echo "Error: " . $sql . "<br>" . $this->db->getConnection()->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
    
}

$host = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$db = new Database($host, $username, $password, $dbname);
$formHandler = new FormHandler($db);

if (!SessionManager::isAdminLoggedIn()) {
    header("Location: index.php");
    exit;
}

$sqlCategories = "SELECT id, name FROM categories";
$result = $db->getConnection()->query($sqlCategories);
$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

$formHandler->addNewItem();

$db->closeConnection();
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <style>
        <?php include('C:\xampp\htdocs\PHP-Projects\E-Commerce-BeatStrum-OOP\css\admin_form.css');?>
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var categoryDropdown = document.getElementById("category");
            var newCategoryForm = document.getElementById("new_category_form");
            categoryDropdown.addEventListener("change", function () {
            if (categoryDropdown.value === "add_new_category") {
                newCategoryForm.style.display = "block";
            } else {
                newCategoryForm.style.display = "none";
            }
        });

        document.getElementById("backButton").addEventListener("click", function(event) {
            event.preventDefault(); 
            if (confirm("Are you sure you want to add the item?")) {
                window.location.href = "index.php"; 
            }
        });

        document.getElementById("updateForm").addEventListener("submit", function(event) {
            if (!confirm("Are you sure you want to update the item?")) {
                event.preventDefault(); 
            }
        });
    });
</script>
</head>
<body>
<?php include ('header.php');?>

<div class="container">
    <h1>Add New Item</h1>
    <form action="add_item.php" method="POST" enctype="multipart/form-data" id="updateForm">
        <div class="container-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea>
        
        <label for="price">Price:</label>
        <input type="text" id="price" name="price" required>
        
    </div>
    <div class="container-group">
        <label for="category">Category:</label>
        <select id="category" name="category">
            <option value="">Select Category</option>
            <?php
            foreach ($categories as $category) {
                echo "<option value='{$category['id']}'>{$category['name']}</option>";
            }
           ?>
            <option value="add_new_category">Add New Category</option>
        </select>
        <div id="new_category_form" style="display: none;">
            <label for="new_category_name">New Category Name:</label>
            <input type="text" id="new_category_name" name="new_category_name">
            
            <label for="new_category_image">Category Image:</label>
            <input type="file" id="new_category_image" name="new_category_image" accept="image/*">
        </div>

        <label for="quantity">Cart Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="0" required>

        <label for="shipping_fee">Shipping Fee:</label>
        <input type="text" id="shipping_fee" name="shipping_fee" required>

        <label for="item_image">Item Image:</label>
        <input type="file" id="item_image" name="item_image" accept="image/*" required>
        
    </div>
    <h2></h2>
    <input type="submit" value="Add New Item">
    <input type="button" id="backButton" value="Back">
</form></div>
</body>
</html>