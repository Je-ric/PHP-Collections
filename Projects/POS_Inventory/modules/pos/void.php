<?php
session_start();
include('../../includes/config.php'); 

if (!isset($_SESSION['admin_log']) && !isset($_SESSION['log_emp'])) {
    header("Location: ../index.php");
}

$user_id = isset($_SESSION['admin_log']) ? $_SESSION['admin_log'] : (isset($_SESSION['log_emp']) ? $_SESSION['log_emp'] : null);

if ($user_id === null) {
    echo "Error: User not logged in.";
    exit();
}

$sale_id = isset($_POST['sale_id']) ? intval($_POST['sale_id']) : 0;

if ($sale_id <= 0) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];  

$user_check_query = "SELECT COUNT(*) FROM Users WHERE user_id = ?";
$stmt = $conn->prepare($user_check_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_exists);
$stmt->fetch();
$stmt->close();

if ($user_exists == 0) {
    echo "Error: The user performing this action does not exist.";
    exit();
}

$conn->begin_transaction();

try {
    $sale_items_query = "SELECT item_id, quantity, price FROM Sale_Items WHERE sale_id = ?";
    $stmt = $conn->prepare($sale_items_query);
    $stmt->bind_param("i", $sale_id);
    $stmt->execute();
    $sale_items_result = $stmt->get_result();
    
    while ($item = $sale_items_result->fetch_assoc()) {
        $item_id = $item['item_id'];
        $quantity_sold = $item['quantity'];
        
        $update_inventory_query = "UPDATE Item SET quantity = quantity + ? WHERE item_id = ?";
        $stmt = $conn->prepare($update_inventory_query);
        $stmt->bind_param("ii", $quantity_sold, $item_id);
        $stmt->execute();
    }
    
    $update_sale_query = "UPDATE Sales SET sale_status = 'Voided', total_amount = 0.00, payment = 0.00, changed = 0.00 WHERE sale_id = ?";
    $stmt = $conn->prepare($update_sale_query);
    $stmt->bind_param("i", $sale_id);
    $stmt->execute();

    $insert_void_action_query = "INSERT INTO Void_Actions (sale_id, user_id, voided_by) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_void_action_query);
    $stmt->bind_param("iii", $sale_id, $user_id, $user_id);
    $stmt->execute();
    
    $conn->commit();
    
    header("Location: receipt.php?sale_id=" . $sale_id);
    exit();

} catch (Exception $e) {
    
    $conn->rollback();
    echo "Error: " . $e->getMessage();
    exit();
}
?>
