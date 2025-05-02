<?php 

$user_id = $_SESSION['user_id'];

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$category_id = isset($_GET['category_id']) && $_GET['category_id'] !== '' ? (int)$_GET['category_id'] : null;
$searchTerm = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$query = "SELECT COUNT(*) AS total FROM Item WHERE isActive > 0";

if ($category_id) {
    $query .= " AND category_id = $category_id";
}

if ($searchTerm) {
    $query .= " AND (name LIKE '%$searchTerm%' OR brand LIKE '%$searchTerm%')";
}

$totalResult = $conn->query($query);
$totalCount = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalCount / $limit);

$currentStart = ($offset + 1);
$currentEnd = min($offset + $limit, $totalCount);

$itemQuery = "
    SELECT i.*, GROUP_CONCAT(DISTINCT c.color_name SEPARATOR ',') AS colors
    FROM Item i
    LEFT JOIN ItemColors ic ON i.item_id = ic.item_id
    LEFT JOIN Colors c ON ic.color_id = c.color_id
    WHERE i.isActive > 0
";

if ($category_id) {
    $itemQuery .= " AND i.category_id = $category_id";
}

if ($searchTerm) {
    $itemQuery .= " AND (i.name LIKE '%$searchTerm%' OR i.brand LIKE '%$searchTerm%')";
}

$itemQuery .= " GROUP BY i.item_id LIMIT $limit OFFSET $offset";

$result = $conn->query($itemQuery);
$items = [];
while ($row = $result->fetch_assoc()) {
    $row['colors'] = explode(',', $row['colors']);
    $items[] = $row;
}


if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'addItem':
            $name = $_POST['name'];
            $brand = $_POST['brand'];
            $size = $_POST['size'];
            $colors = $_POST['color']; 
            $quantity = $_POST['quantity'];
            $investment_price = $_POST['investment_price']; 
            $price = $_POST['price']; 
            $category_id = $_POST['category_id'];
            $isActive = 1;
        
            $categoryResult = $conn->query("SELECT category_id FROM Categories WHERE category_id = $category_id");
            if ($categoryResult->num_rows == 0) {
                echo "Error: Selected category does not exist.";
                exit;
            }
        
            
            $stmt = $conn->prepare("INSERT INTO Item (name, brand, size, price, investment_price, quantity, category_id, isActive) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssdisi", $name, $brand, $size, $price, $investment_price, $quantity, $category_id, $isActive);
            $stmt->execute();
        
            $item_id = $conn->insert_id;
        
            
            $colorArray = explode(',', $colors);
            $stmtColor = $conn->prepare("INSERT INTO ItemColors (item_id, color_id) VALUES (?, ?)");
            foreach ($colorArray as $colorName) {
                $colorName = trim($colorName);
                $colorResult = $conn->query("SELECT color_id FROM Colors WHERE color_name = '$colorName'");
                if ($colorResult->num_rows > 0) {
                    $colorRow = $colorResult->fetch_assoc();
                    $color_id = $colorRow['color_id'];
                    $stmtColor->bind_param("ii", $item_id, $color_id);
                    $stmtColor->execute();
                } else {
                    $conn->query("INSERT INTO Colors (color_name) VALUES ('$colorName')");
                    $color_id = $conn->insert_id;
                    $stmtColor->bind_param("ii", $item_id, $color_id);
                    $stmtColor->execute();
                }
            }
        
            
            $conn->query("INSERT INTO ItemHistory (item_id, action, user_id, details) 
                          VALUES ($item_id, 'add', $user_id, 'Added new item: $name')");
            header("Location: " . $_SERVER['REQUEST_URI']); 
            break;
        
        
        case 'restockItem':
            $item_id = $_POST['item_id'];
            $quantity = $_POST['quantity'];
            
            $conn->query("UPDATE Item SET quantity = quantity + $quantity WHERE item_id = $item_id");
    
            $conn->query("INSERT INTO ItemHistory (item_id, action, user_id, details) 
                VALUES ($item_id, 'restock', $user_id, 'Restocked item with $quantity units')");
            
            header("Location: " . $_SERVER['REQUEST_URI']); 
            break;
            
        case 'deleteItem':
            $item_id = $_POST['item_id'];
    
            $conn->query("UPDATE Item SET isactive = 0 WHERE item_id = $item_id");
            
            $conn->query("INSERT INTO ItemHistory (item_id, action, user_id, details)
                VALUES ($item_id, 'delete', $user_id, 'Item Deleted')");
            header("Location: " . $_SERVER['REQUEST_URI']); 
        break;

    }
}
    $add_history_stmt = $conn->prepare("SELECT ih.timestamp, u.username, ih.details, i.name , ih.action
                            FROM ItemHistory ih
                            JOIN Users u ON ih.user_id = u.user_id
                            JOIN Item i ON ih.item_id = i.item_id
                            ORDER BY ih.timestamp DESC");
    $add_history_stmt->execute();
    $add_history_result = $add_history_stmt->get_result();

    $stmt = $conn->prepare("SELECT * FROM Categories");
$stmt->execute();
$result = $stmt->get_result();

?>