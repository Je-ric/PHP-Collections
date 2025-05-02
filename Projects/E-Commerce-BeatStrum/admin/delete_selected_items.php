<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Selected Items</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
    <style>
        /* Add your CSS styles here */
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

    // Function to delete selected items
    function deleteSelectedItems($conn, $selected_items) {
        // Prepare SQL statement for deletion
        $placeholders = implode(',', array_fill(0, count($selected_items), '?'));
        $sql = "DELETE FROM items WHERE id IN ($placeholders)";
        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);
        // Bind parameters
        $stmt->bind_param(str_repeat('i', count($selected_items)), ...$selected_items);
        // Execute the statement
        if ($stmt->execute()) {
            return true; // Deletion successful
        } else {
            return false; // Deletion failed
        }
    }

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if selected items are submitted
        if(isset($_POST['selected_items']) && !empty($_POST['selected_items'])) {
            // Retrieve selected item IDs
            $selected_items = $_POST['selected_items'];
            // Check if deletion is confirmed
            if(isset($_POST['confirm'])) {
                // Attempt to delete selected items
                if(deleteSelectedItems($conn, $selected_items)) {
                    // Deletion successful, redirect to index page
                    echo "<script>alert('Selected items deleted successfully.'); window.location.href = 'index.php';</script>";
                    exit;
                } else {
                    // Deletion failed
                    echo "Error: Unable to delete selected items.";
                }
            } elseif (isset($_POST['cancel'])) {
                // If cancellation is confirmed, redirect to index page
                header("Location: index.php");
                exit;
            }
        } else {
            // If no items are selected
            echo "No items selected for deletion.";
        }
    } else {
        // If not submitted via POST method
        echo "Invalid request.";
    }
    ?>
    <h1>Confirmation to Delete Selected Items</h1>
    <div class="items-container">
        <?php
        // Fetch and display selected items
        if(isset($_POST['selected_items']) && !empty($_POST['selected_items'])) {
            // Retrieve selected item IDs
            $selected_items = $_POST['selected_items'];
            // Prepare SQL statement to fetch selected items' details
            $placeholders = implode(',', array_fill(0, count($selected_items), '?'));
            $sql = "SELECT * FROM items WHERE id IN ($placeholders)";
            // Prepare and execute the statement
            $stmt = $conn->prepare($sql);
            // Bind parameters
            $stmt->bind_param(str_repeat('i', count($selected_items)), ...$selected_items);
            // Execute the statement
            $stmt->execute();
            // Get result
            $result = $stmt->get_result();
            // Display selected items
            while ($row = $result->fetch_assoc()) {
                echo "<div class='item'>";
                echo "<img src='../uploads/{$row['item_image']}' alt='{$row['name']}'>";
                echo "<p>{$row['name']}</p>";
                echo "<p>Price: ₱{$row['price']}</p>";
                echo "</div>";
            }
            // Close statement
            $stmt->close();
        } else {
            echo "No items selected.";
        }
        ?>
    </div>
    <div class="confirm-buttons">
        <form action="" method="post">
            <?php
            // Hidden fields to pass selected items
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
