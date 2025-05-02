<?php 

$action = $_POST['action'] ?? null; 

switch ($action) {
    case 'delete':
        if (isset($_POST['category_id'])) {
            $category_id = $_POST['category_id'];
    
            $check_stmt = $conn->prepare("SELECT COUNT(*) as item_count FROM Item WHERE category_id = ?");
            $check_stmt->bind_param("i", $category_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $count = $check_result->fetch_assoc()['item_count'];
    
            if ($count == 0) {
                $delete_stmt = $conn->prepare("DELETE FROM Categories WHERE category_id = ?");
                $delete_stmt->bind_param("i", $category_id);
                $delete_stmt->execute();
                header("Location: products.php");
                exit();
            } else {
                $delete_error = "Cannot delete category as it has associated items.";
            }
        }
        break;    

    case 'add':
        if (isset($_POST['name'])) {
            $category_name = $_POST['name'];

            $category_image = '';
            if (isset($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
                $image_name = $_FILES['image']['name'];
                $image_tmp = $_FILES['image']['tmp_name'];
                $category_image = '../../upload_images/category_images/' . time() . '_' . $image_name;
                move_uploaded_file($image_tmp, $category_image);
            }
            
            $add_stmt = $conn->prepare("INSERT INTO Categories (name, category_image) VALUES (?, ?)");
            $add_stmt->bind_param("ss", $category_name, $category_image);
            $add_stmt->execute();
            header("Location: products.php"); 
            exit();
        }
        break;

    case 'update':
        if (isset($_POST['category_id']) && isset($_POST['name'])) {
            $category_id = $_POST['category_id'];
            $category_name = $_POST['name'];

            $category_image = '';
            if (isset($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
                $image_name = $_FILES['image']['name'];
                $image_tmp = $_FILES['image']['tmp_name'];
                $category_image = '../../upload_images/category_images/' . time() . '_' . $image_name;
                move_uploaded_file($image_tmp, $category_image);

                $update_stmt = $conn->prepare("UPDATE Categories SET name = ?, category_image = ? WHERE category_id = ?");
                $update_stmt->bind_param("ssi", $category_name, $category_image, $category_id);
            } else {
                $update_stmt = $conn->prepare("UPDATE Categories SET name = ? WHERE category_id = ?");
                $update_stmt->bind_param("si", $category_name, $category_id);
            }

            $update_stmt->execute();
            header("Location: products.php"); 
            exit();
        }
        break;

    default:
    
        break;
}
?>