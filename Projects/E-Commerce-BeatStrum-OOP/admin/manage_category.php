    <?php
    require_once('session.php');
    require_once('config.php');

    session_start();

    class CategoryManager {
        private $conn;

        public function __construct($host, $username, $password, $database) {
            $this->conn = new mysqli($host, $username, $password, $database);
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        }

        // Method to add a new category
        public function addCategory($newCategoryName, $newCategoryImage) {
            $target_dir_category = "../category_uploads/";
            $target_file_category = $target_dir_category . basename($newCategoryImage["name"]);
            if (move_uploaded_file($newCategoryImage["tmp_name"], $target_file_category)) {
                $sqlNewCategory = "INSERT INTO categories (name, category_image) VALUES (?, ?)";
                $stmt = $this->conn->prepare($sqlNewCategory);
                $stmt->bind_param("ss", $newCategoryName, $newCategoryImage["name"]);
                if ($stmt->execute()) {
                    return true;
                } else {
                    return "Error adding new category: " . $stmt->error;
                }
            } else {
                return "Sorry, there was an error uploading your category image.";
            }
        }

        // Method to update category
    public function updateCategory($category_id, $category_name, $newCategoryImage) {
        if ($category_id === $this->getOthersCategoryId()) {
            return "Cannot update 'Others' category";
        }
        if (!empty($newCategoryImage['name'])) { 
            $target_dir_category = "../category_uploads/";
            $target_file_category = $target_dir_category . basename($newCategoryImage["name"]);
            if (move_uploaded_file($newCategoryImage["tmp_name"], $target_file_category)) {
                $sqlUpdateCategory = "UPDATE categories SET name=?, category_image=? WHERE id=?";
                $stmt = $this->conn->prepare($sqlUpdateCategory);
                $stmt->bind_param("ssi", $category_name, $newCategoryImage["name"], $category_id);
            } else {
                return "Sorry, there was an error uploading your category image.";
            }
        } else { 
            $sqlUpdateCategory = "UPDATE categories SET name=? WHERE id=?";
            $stmt = $this->conn->prepare($sqlUpdateCategory);
            $stmt->bind_param("si", $category_name, $category_id);
        }

        if ($stmt->execute()) {
            return true;
        } else {
            return "Error updating category: " . $stmt->error;
        }
    }

    // Method to delete category
    public function deleteCategory($category_id) {
        // Check if the category is "Others"
        $sqlCheckCategory = "SELECT name FROM categories WHERE id=?";
        $stmt = $this->conn->prepare($sqlCheckCategory);
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $resultCheckCategory = $stmt->get_result();
        if ($resultCheckCategory->num_rows > 0) {
            $category = $resultCheckCategory->fetch_assoc();
            if ($category['name'] === 'Others') {
                return "Cannot delete 'Others' category";
            }
        }
    
        // Check if the category is "Others" using a separate method if available
        if ($category_id === $this->getOthersCategoryId()) {
            return "Cannot delete 'Others' category";
        }
    
        // Delete category image
        $sqlGetCategoryImage = "SELECT category_image FROM categories WHERE id=?";
        $stmt = $this->conn->prepare($sqlGetCategoryImage);
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $resultGetCategoryImage = $stmt->get_result();
        if ($resultGetCategoryImage->num_rows > 0) {
            $category_image_row = $resultGetCategoryImage->fetch_assoc();
            $category_image = $category_image_row['category_image'];
            $category_image_path = "../category_uploads/" . $category_image;
            if (file_exists($category_image_path)) {
                unlink($category_image_path);
            }
        }
    
        // Update items associated with the deleted category to "Others" category
        $sqlUpdateItemsCategory = "UPDATE item_categories SET category_id = (SELECT id FROM categories WHERE name = 'Others') WHERE category_id = ?";
        $stmt = $this->conn->prepare($sqlUpdateItemsCategory);
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
    
        // Delete category from the database
        $sqlDeleteCategory = "DELETE FROM categories WHERE id=?";
        $stmt = $this->conn->prepare($sqlDeleteCategory);
        $stmt->bind_param("i", $category_id);
        if ($stmt->execute()) {
            return true;
        } else {
            return "Error deleting category: " . $stmt->error;
        }
    }
    
        // Method to fetch all categories
        public function getAllCategories() {
            $sql_categories = "SELECT * FROM categories";
            $result_categories = $this->conn->query($sql_categories);
            $categories = array();
            if ($result_categories->num_rows > 0) {
                while ($row = $result_categories->fetch_assoc()) {
                    $categories[] = $row;
                }
            }
            return $categories;
        }

        private function getOthersCategoryId() {
            $sql = "SELECT id FROM categories WHERE name = 'Others'";
            $result = $this->conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['id'];
            } else {
                // If "Others" category doesn't exist, return a default ID or handle it as needed
                return -1;
            }
        }
        public function __destruct() {
            $this->conn->close();
        }
    }

    $categoryManager = new CategoryManager("localhost", "root", "", "ecommerce");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Add new category
        if (isset($_POST['add_category'])) {
            $newCategoryName = $_POST['new_category_name'];
            $newCategoryImage = $_FILES['new_category_image'];

            $addResult = $categoryManager->addCategory($newCategoryName, $newCategoryImage);
            if ($addResult === true) {
                echo "New category added successfully";
                header("Location: manage_category.php");
                exit;
            } else {
                echo $addResult;
                exit;
            }
        }

        // Update category
        if (isset($_POST['update_category'])) {
            $category_id = $_POST['category_id'];
            $category_name = $_POST['category_name'];
            $newCategoryImage = $_FILES['update_category_image'];

            $updateResult = $categoryManager->updateCategory($category_id, $category_name, $newCategoryImage);
            if ($updateResult === true) {
                echo "Category updated successfully";
                header("Location: manage_category.php");
                exit;
            } else {
                echo $updateResult;
                exit;
            }
        }

        
    }


    // Handle category deletion
    if (isset($_GET['delete_category'])) {
        $category_id = $_GET['delete_category'];

        $deleteResult = $categoryManager->deleteCategory($category_id);
        if ($deleteResult === true) {
            echo "Category deleted successfully";
            header("Location: manage_category.php");
            exit;
        } else {
            echo $deleteResult;
            exit;
        }
    }

    // Fetch all categories
    $categories = $categoryManager->getAllCategories();


    ?>



            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
                <title>Manage Categories</title>
                <style>
                    <?php include('C:\xampp\htdocs\PHP-Projects\E-Commerce-BeatStrum-OOP\css\manage_category.css'); ?>
                        </style>
            </head>
            <body>
            <?php include 'header.php'; ?>
                <div class="container">
                    <div class="left">
                        <h1>Manage Categories</h1>
                        <table>
                            <thead>
                                <tr>
                                    <th>CID</th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 1;  ?> 
                                <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?php echo $counter++; #echo $category['id']; ?></td>
                                    <td><?php echo $category['name']; ?></td>
                                    <td><img src="../category_uploads/<?php echo $category['category_image']; ?>" alt="<?php echo $category['name']; ?>" style="max-width: 100px;"></td>
                                    <td>
                                <a class="edit-button" href="#" data-category-id="<?php echo $category['id']; ?>">
                                            <i class='bx bx-edit'></i>
                                        </a>

                                        <a class="delete-button" href="manage_category.php?delete_category=<?php echo $category['id']; ?>" onclick="return confirmDelete();">
                                            <i class='bx bx-trash'></i>
                                        </a>

                                </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="right">

                            <div class="forms">
                                <div class="add-form">
                                    <h2>Add New Category</h2>
                                    <!-- <form action="manage_category.php" method="POST" enctype="multipart/form-data"> -->
                                    <form action="manage_category.php" method="POST" enctype="multipart/form-data" onsubmit="return confirmAdd();">
                                    <div class="input-group">
                <label for="new_category_name">Category name:</label>
                <input type="text" id="new_category_name" name="new_category_name" required>
            </div>
            
            <div class="input-group">
                <label for="new_category_image">Category Image:</label>
                <input type="file" id="new_category_image" name="new_category_image" accept="image/*" required>
            </div>
            
            
                                        <input type="submit" name="add_category" value="Add Category">
                                    </form>
                                </div>
                                <div class="update-form">
                                    <h2>Update Category</h2>
                                    <form action="manage_category.php" method="POST" enctype="multipart/form-data" onsubmit="return confirmUpdate();">
                                    <!-- <form action="manage_category.php" method="POST" enctype="multipart/form-data"> -->
                                        <input type="hidden" id="update_category_id" name="category_id">
                                        <div class="input-group">
                <label for="update_category_name">Category Name:</label>
                <input type="text" id="update_category_name" name="category_name" required>
            </div>
            
            <div class="input-group">
                <label for="update_category_image">New Image:</label>
                <input type="file" id="update_category_image" name="update_category_image" accept="image/*">
            </div>
            
                                        <input type="submit" name="update_category" value="Update Category">
                                    </form>
                                </div>
                            </div>
                            <h3></h3>
                    </div>
                    
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const editButtons = document.querySelectorAll('.edit-button');
                        const updateCategoryIdInput = document.getElementById("update_category_id");
                        const updateCategoryNameInput = document.getElementById("update_category_name");

                        editButtons.forEach(button => {
                            button.addEventListener('click', function () {
                                const categoryId = this.getAttribute('data-category-id');
                                const categoryName = this.parentElement.parentElement.children[1].textContent.trim();
                                updateCategoryIdInput.value = categoryId;
                                updateCategoryNameInput.value = categoryName;
                            });
                        });
                    });
                </script>

<script>
    // Function to prompt before category deletion
    function confirmDelete() {
        return confirm('Are you sure you want to delete this category?');
    }

    // Function to prompt before submitting the add category form
    function confirmAdd() {
        return confirm('Are you sure you want to add this category?');
    }

    // Function to prompt before submitting the update category form
    function confirmUpdate() {
        return confirm('Are you sure you want to update this category?');
    }

    document.addEventListener("DOMContentLoaded", function () {
        const editButtons = document.querySelectorAll('.edit-button');
        const updateCategoryIdInput = document.getElementById("update_category_id");
        const updateCategoryNameInput = document.getElementById("update_category_name");

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const categoryId = this.getAttribute('data-category-id');
                const categoryName = this.parentElement.parentElement.children[1].textContent.trim();
                updateCategoryIdInput.value = categoryId;
                updateCategoryNameInput.value = categoryName;
            });
        });
    });
</script>
            </body>
            </html>
