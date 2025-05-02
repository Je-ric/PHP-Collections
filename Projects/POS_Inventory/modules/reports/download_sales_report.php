<?php
include('../../includes/config.php'); 

session_start();
if (!isset($_SESSION['admin_log'])) {
    header("Location: ../../index.php");
    exit();
}

$reportType = $_GET['report_type'];
$filter = '';
$filterValue = '';
$filenameSuffix = '';

if ($reportType == 'daily' && isset($_GET['date'])) {
    $filter = 'DATE(sale_date) = ?';
    $filterValue = $_GET['date'];
    $filenameSuffix = 'Daily_' . $filterValue; // Example: "Daily_2024-11-01"
} elseif ($reportType == 'weekly' && isset($_GET['week'])) {
    $filter = 'DATE_FORMAT(sale_date, "%Y-%u") = ?';
    $filterValue = $_GET['week'];
    $filenameSuffix = 'Week_' . $filterValue; // Example: "Week_2024-44"
} elseif ($reportType == 'monthly' && isset($_GET['month'])) {
    $filter = 'DATE_FORMAT(sale_date, "%Y-%m") = ?';
    $filterValue = $_GET['month'];
    $filenameSuffix = 'Month_' . $filterValue; // Example: "Month_2024-11"
}

$sql = "
    SELECT 
        s.sale_id, 
        s.sale_date, 
        s.total_amount, 
        si.quantity, 
        si.price, 
        i.name AS item_name, 
        u.name AS salesperson
    FROM Sales s
    JOIN Sale_Items si ON s.sale_id = si.sale_id
    JOIN Item i ON si.item_id = i.item_id
    JOIN Users u ON s.user_id = u.user_id
    WHERE $filter
    ORDER BY s.sale_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $filterValue); 
$stmt->execute();
$result = $stmt->get_result();

// Generate CSV file
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="sales_report_' . $filenameSuffix . '.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Sale ID', 'Sale Date', 'Item Name', 'Quantity', 'Price', 'Total Amount', 'Salesperson']); 

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row); 
}

fclose($output);
exit();
?>
