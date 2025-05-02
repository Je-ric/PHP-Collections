<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Selected Items</title>
    <link href="https:
    <style>
       
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
        }
        .items-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
        .item {
            margin: 10px;
            text-align: center;
        }
        img {
            max-width: 150px;
            max-height: 150px;
            margin-bottom: 10px;
        }
        .confirm-buttons {
            text-align: center;
            margin-top: 20px;
        }
        button {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .confirm {
            background-color: #dc3545;
            color: #fff;
        }
        .cancel {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <?php
    require_once('session.php');
    require_once('config.php');

    
    function deleteSelectedItems($conn, $selected_items) {
        
        $placeholders = implode(',', array_fill(0, count($selected_items), '?'));
        $sql = "DELETE FROM items WHERE id IN ($placeholders)";
        
        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param(str_repeat('i', count($selected_items)), ...$selected_items);
        
        if ($stmt->execute()) {
            return true; 
        } else {
            return false; 
        }
    }

    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        if(isset($_POST['selected_items']) && !empty($_POST['selected_items'])) {
            
            $selected_items = $_POST['selected_items'];
            
            if(isset($_POST['confirm'])) {
                
                if(deleteSelectedItems($conn, $selected_items)) {
                    
                    echo "<script>alert('Selected items deleted successfully.'); window.location.href = 'index.php';</script>";
                    exit;
                } else {
                    
                    echo "Error: Unable to delete selected items.";
                }
            } elseif (isset($_POST['cancel'])) {
                
                header("Location: index.php");
                exit;
            }
        } else {
            
            echo "No items selected for deletion.";
        }
    } else {
        
        echo "Invalid request.";
    }
    ?>
    <h1>Confirmation to Delete Selected Items</h1>
    <div class="items-container">
        <?php
        
        if(isset($_POST['selected_items']) && !empty($_POST['selected_items'])) {
            
            $selected_items = $_POST['selected_items'];
            
            $placeholders = implode(',', array_fill(0, count($selected_items), '?'));
            $sql = "SELECT * FROM items WHERE id IN ($placeholders)";
            
            $stmt = $conn->prepare($sql);
            
            $stmt->bind_param(str_repeat('i', count($selected_items)), ...$selected_items);
            
            $stmt->execute();
            
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                echo "<div class='item'>";
                echo "<img src='../uploads/{$row['item_image']}' alt='{$row['name']}'>";
                echo "<p>{$row['name']}</p>";
                echo "<p>Price: ₱{$row['price']}</p>";
                echo "</div>";
            }
            
            $stmt->close();
        } else {
            echo "No items selected.";
        }
        ?>
    </div>
    <div class="confirm-buttons">
        <form action="" method="post">
            <?php
            
            if(isset($selected_items) && !empty($selected_items)) {
                foreach ($selected_items as $item_id) {
                    echo "<input type='hidden' name='selected_items[]' value='$item_id'>";
                }
            }
            ?>
            <button type="submit" name="confirm" class="confirm">Confirm Delete</button>
            <button type="submit" name="cancel" class="cancel">Cancel</button>
        </form>
    </div>
</body>
</html>
