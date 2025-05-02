        <?php
        require_once('session.php');
        require_once('config.php');
        session_start();

        if (!isset($_SESSION['admin_log'])) {
            header("Location: index.php");
            exit;
        }

        // Add new category
        if (isset($_POST['add_category'])) {
            $newCategoryName = $_POST['new_category_name'];
            $newCategoryImage = $_FILES['new_category_image']['name'];

            // Assuming the target directory is category_uploads/
            $target_dir_category = "../category_uploads/";
            $target_file_category = $target_dir_category . basename($_FILES["new_category_image"]["name"]);

            // Move uploaded file to the target directory
            if (move_uploaded_file($_FILES["new_category_image"]["tmp_name"], $target_file_category)) {
                $sqlNewCategory = "INSERT INTO categories (name, category_image) VALUES ('$newCategoryName', '$newCategoryImage')";
                if ($conn->query($sqlNewCategory) === TRUE) {
                    echo "New category added successfully";
                    header("Location: manage_category.php");
                    exit;
                } else {
                    echo "Error adding new category: " . $conn->error;
                    exit;
                }
            } else {
                echo "Sorry, there was an error uploading your category image.";
                exit;
            }
        }

        // Update category
        if (isset($_POST['update_category'])) {
            $category_id = $_POST['category_id'];
            $category_name = $_POST['category_name'];
            $newCategoryImage = $_FILES['update_category_image']['name'];

            // If a new image is uploaded
            if (!empty($newCategoryImage)) {
                $target_dir_category = "../category_uploads/";
                $target_file_category = $target_dir_category . basename($_FILES["update_category_image"]["name"]);

                // Move uploaded file to the target directory
                if (move_uploaded_file($_FILES["update_category_image"]["tmp_name"], $target_file_category)) {
                    // Update category with new image
                    $sqlUpdateCategory = "UPDATE categories SET name='$category_name', category_image='$newCategoryImage' WHERE id='$category_id'";
                    if ($conn->query($sqlUpdateCategory) === TRUE) {
                        echo "Category updated successfully";
                        header("Location: manage_category.php");
                        exit;
                    } else {
                        echo "Error updating category: " . $conn->error;
                        exit;
                    }
                } else {
                    echo "Sorry, there was an error uploading your category image.";
                    exit;
                }
            } else {
                // Update category without changing the image
                $sqlUpdateCategory = "UPDATE categories SET name='$category_name' WHERE id='$category_id'";
                if ($conn->query($sqlUpdateCategory) === TRUE) {
                    echo "Category updated successfully";
                    header("Location: manage_category.php");
                    exit;
                } else {
                    echo "Error updating category: " . $conn->error;
                    exit;
                }
            }
        }

        // Delete category
        if (isset($_GET['delete_category'])) {
            $category_id = $_GET['delete_category'];

            // Delete category image file if exists
            $sqlGetCategoryImage = "SELECT category_image FROM categories WHERE id='$category_id'";
            $resultGetCategoryImage = $conn->query($sqlGetCategoryImage);
            if ($resultGetCategoryImage->num_rows > 0) {
                $category_image_row = $resultGetCategoryImage->fetch_assoc();
                $category_image = $category_image_row['category_image'];
                $category_image_path = "../category_uploads/" . $category_image;
                if (file_exists($category_image_path)) {
                    unlink($category_image_path); // Delete category image file
                }
            }

            // Delete category from database
            $sqlDeleteCategory = "DELETE FROM categories WHERE id='$category_id'";
            if ($conn->query($sqlDeleteCategory) === TRUE) {
                echo "Category deleted successfully";
                header("Location: manage_category.php");
                exit;
            } else {
                echo "Error deleting category: " . $conn->error;
                exit;
            }
        }

        // Fetch all categories
        $sql_categories = "SELECT * FROM categories";
        $result_categories = $conn->query($sql_categories);
        $categories = array();
        if ($result_categories->num_rows > 0) {
            while ($row = $result_categories->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        ?>




        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
            <title>Manage Categories</title>
            <style>
                <?php include('C:\xampp\htdocs\PHP-Projects\E-Commerce-BeatStrum\css\manage_category.css'); ?>
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

                                    <a class="delete-button" href="manage_category.php?delete_category=<?php echo $category['id']; ?>" onclick="return confirm('Are you sure you want to delete this category?')">
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
                                <form action="manage_category.php" method="POST" enctype="multipart/form-data">
                                    
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
                                <form action="manage_category.php" method="POST" enctype="multipart/form-data">
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
        </body>
        </html>
