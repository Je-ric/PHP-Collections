<?php

class Database {
    private $host = "localhost:3307";
    private $username = "root";
    private $password = "";
    private $database = "ecommerce";
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

class ItemManager {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getItemById($id) {
        $conn = $this->db->getConnection();

        $sql_fetch_item = "SELECT name, item_image FROM items WHERE id = $id";
        $result_fetch_item = $conn->query($sql_fetch_item);

        if ($result_fetch_item->num_rows == 1) {
            return $result_fetch_item->fetch_assoc();
        } else {
            return false;
        }
    }

    public function deleteItem($id) {
        $conn = $this->db->getConnection();

        $sql_delete_item = "DELETE FROM items WHERE id = $id";

        if ($conn->query($sql_delete_item) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
}

session_start();

if (!isset($_SESSION['admin_log'])) {
    header("Location: admin.php");
    exit;
}

if(isset($_GET['id'])) {
    $item_id = $_GET['id'];

    $db = new Database();
    $itemManager = new ItemManager($db);

    $item = $itemManager->getItemById($item_id);

    if ($item) {
        $item_name = $item['name'];
        $item_image = $item['item_image'];
    } else {
        echo "Item not found";
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
        if ($itemManager->deleteItem($item_id)) {
            echo "Item deleted successfully";
            header("Location: index.php");
            exit;
        } else {
            echo "Error deleting item";
        }
    }
} else {
    echo "Item ID not provided";
    exit;
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Item</title>
    <style>
        <?php include('C:\xampp\htdocs\PHP-Projects\E-Commerce-BeatStrum-OOP\css\admin_form.css'); ?>
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("confirmDeleteForm").addEventListener("submit", function(event) {
                if (!confirm("Are you sure you want to delete the item?")) {
                    event.preventDefault(); 
                }
            });

            document.getElementById("cancelButton").addEventListener("click", function(event) {
                if (!confirm("Are you sure you want to cancel deleting the item?")) {
                    event.preventDefault(); 
                } else {
                    
                    window.location.href = "index.php";
                }
            });
        });
    </script>
</head>
<body>

    
<?php include ('header.php'); ?>  

    <div class="container">
        <h1>Delete Item</h1>
        <div class="delete-container">
            <div class="container-group">
                <img src="../uploads/<?php echo $item_image; ?>" alt="<?php echo $item_name; ?>">
            </div>
            <div class="delete-container-group">
                <p><?php echo $item_name; ?></p>
                <h4>Reminder: </h4>
                <p>This action cannot be undone. The item will be <span>permanently removed</span> from the system.</p>
            </div>
        </div>
        <form action="delete_item.php?id=<?php echo $item_id; ?>" method="post" id="confirmDeleteForm">
                <input type="submit" name="confirm_delete" value="Confirm Delete">
                <input type="button" id="cancelButton" value="Cancel">
        </form>
    </div>
</body>
</html>