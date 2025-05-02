<?php
include('../../includes/config.php'); 
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['admin_log']) && !isset($_SESSION['log_emp'])) {
    header("Location: ../../index.php");
    exit();
}

// $userId = $_SESSION['user_id']; 

// $attendanceCheck = $conn->query("SELECT * FROM Attendance WHERE user_id = $userId AND attendance_date = CURDATE()");

// $attendanceExists = $attendanceCheck->num_rows > 0; 
// $categoriesCount = $conn->query("SELECT COUNT(*) FROM Categories")->fetch_row()[0];
// $itemsCount = $conn->query("SELECT COUNT(*) FROM Item")->fetch_row()[0];
// $adminsCount = $conn->query("SELECT COUNT(*) FROM Users WHERE role = 'admin'")->fetch_row()[0];
// $employeesCount = $conn->query("SELECT COUNT(*) FROM Users WHERE role = 'employee'")->fetch_row()[0]; 

$quantity_count = 5;

$shortProducts = $conn->query("SELECT name, quantity FROM Item WHERE quantity = 0 LIMIT 5");

$lowStockByCategory = $conn->query("
    SELECT c.name AS category_name, COUNT(i.item_id) AS low_stock_count 
    FROM Item i
    JOIN Categories c ON i.category_id = c.category_id
    WHERE i.quantity < $quantity_count
    GROUP BY c.name
");

$topSellingProducts = $conn->query("
    SELECT i.name AS product_name, SUM(si.quantity) AS total_sold
    FROM Sale_Items si
    JOIN Item i ON si.item_id = i.item_id
    GROUP BY si.item_id
    ORDER BY total_sold DESC
    LIMIT 5
");


$recentSales = $conn->query("
    SELECT sale_id, sale_date, total_amount, u.name AS salesperson
    FROM Sales s
    JOIN Users u ON s.user_id = u.user_id
    ORDER BY sale_date DESC
    LIMIT 3
");

$dailySales = $conn->query("
    SELECT DATE(sale_date) AS day, SUM(total_amount) AS total_sales
    FROM Sales
    GROUP BY day
    ORDER BY day DESC
    LIMIT 5
");

$monthlySales = $conn->query("
    SELECT DATE_FORMAT(sale_date, '%Y-%m') AS month, SUM(total_amount) AS total_sales
    FROM Sales
    GROUP BY month
    ORDER BY month DESC
    LIMIT 5
");

$weeklySales = $conn->query("
    SELECT DATE_FORMAT(sale_date, '%Y-%u') AS week, SUM(total_amount) AS total_sales
    FROM Sales
    GROUP BY week
    ORDER BY week DESC
    LIMIT 5
");


$todaysDate = date('Y-m-d');
$weeklyStartDate = date('Y-m-d', strtotime('-7 days'));

$sqlTodays = "SELECT 
                COUNT(sale_id) AS ordersCount, 
                SUM(total_amount) AS incomeTotal 
              FROM Sales 
              WHERE DATE(sale_date) = '$todaysDate'";
$resultTodays = $conn->query($sqlTodays);
$todays = $resultTodays->fetch_assoc();

$sqlWeekly = "SELECT 
                COUNT(sale_id) AS ordersCount, 
                SUM(total_amount) AS incomeTotal 
              FROM Sales 
              WHERE DATE(sale_date) BETWEEN '$weeklyStartDate' AND '$todaysDate'";
$resultWeekly = $conn->query($sqlWeekly);
$weekly = $resultWeekly->fetch_assoc();

$sqlBestSelling = "
    SELECT I.name, SUM(SI.quantity) AS total_sold
    FROM Sale_Items SI
    JOIN Item I ON SI.item_id = I.item_id
    JOIN Sales S ON SI.sale_id = S.sale_id
    WHERE DATE(S.sale_date) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()
    GROUP BY SI.item_id
    ORDER BY total_sold DESC
    LIMIT 5
";
$resultBestSelling = $conn->query($sqlBestSelling);


// ----------------------------------------------------------------
// Total Revenue = Selling Price * Quantity Sold
$query = "
    SELECT 
        SUM(s.total_amount) AS total_revenue,
        SUM(si.quantity * i.investment_price) AS total_cost
    FROM Sales s
    JOIN Sale_Items si ON s.sale_id = si.sale_id
    JOIN Item i ON si.item_id = i.item_id
    WHERE s.sale_status = 'Completed';
";

$result = $conn->query($query);
$row = $result->fetch_assoc();

$totalRevenue = $row['total_revenue'] ?? 0;  
$totalCost = $row['total_cost'] ?? 0;    

// Total Profit = Total Revenue - Total Cost
$totalProfit = $totalRevenue - $totalCost;

// Total Cost of Inventory = Quantity * Investment Price of all items
$query_inventory = "
    SELECT 
        SUM(i.quantity * i.investment_price) AS total_cost_inventory
    FROM Item i;
";

$result_inventory = $conn->query($query_inventory);
$row_inventory = $result_inventory->fetch_assoc();

$totalCostInventory = $row_inventory['total_cost_inventory'] ?? 0;  
?>

<!DOCTYPE html>
<html lang="en">-
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/body-container.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>

<?php include('../../includes/sidebar.php');  ?>

<div class="container">

<div class="dashboard-page">
<div class="stat-group">
        <?php
                $userId = $_SESSION['user_id'];  
                $todaysDate = date('Y-m-d'); 

                $sqlCheckAttendance = "
                    SELECT attendance_status
                    FROM Attendance 
                    WHERE user_id = ? AND attendance_date = ?
                ";

                $stmt = $conn->prepare($sqlCheckAttendance);
                $stmt->bind_param("is", $userId, $todaysDate);  
                $stmt->execute();
                $stmt->bind_result($attendanceStatus);
                $stmt->fetch();
                $stmt->close();

                if ($attendanceStatus === 'Absent'):  
                ?>
                    <div class="attendance-reminder" style="padding-top: 10px;">
                        <h3>Attendance Reminder</h3>
                        <p>Your attendance for today is marked as absent. Please make sure to mark your attendance as present if this was an error.</p>
                    </div>
                <?php endif; ?>
        </div>  
<div class="group_1">
    <div class="last-7">
        <div class="stat-group">
            <div class="stat-card">
                <div class="stat-content">
                    <i class='bx bx-wallet' title="Today's Income"></i>
                    <div class="stat-text">
                        <p>Today's Income</p>
                        <h1>₱<?= number_format($todays['incomeTotal'] ?? 0, 2) ?></h1>
                        <p>Today's Orders: <?= $todays['ordersCount'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-content">
                    <i class='bx bx-calendar' title="Last 7 Days Income"></i>
                    <div class="stat-text">
                        <p>Last 7 Days Income</p>
                        <h1>₱<?= number_format($weekly['incomeTotal'] ?? 0, 2) ?></h1>
                        <p>Orders: <?= $weekly['ordersCount'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="best-selling">
            <h3><i class='bx bx-trending-up' style='color: green;' title='Trending'></i>Best-Selling Products (Last 7 Days)</h3>
        
            <?php if ($resultBestSelling->num_rows > 0): ?>
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Total Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultBestSelling->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= $row['total_sold'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No sales data found for the past 7 days.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="stats">
    <div class="total-card">
        <div class="stat-content">
            <i class='bx bx-dollar-circle' title="Total Revenue"></i>
            <div class="stat-text">
                <p>Total Revenue</p>
                <h1><?php echo "₱" . number_format($totalRevenue, 2); ?></h1>
            </div>
        </div>
    </div>

    <div class="total-card">
        <div class="stat-content">
            <i class='bx bx-cart' title="Total Cost"></i>
            <div class="stat-text">
                <p>Total Cost</p>
                <h1><?php echo "₱" . number_format($totalCost, 2); ?></h1>
            </div>
        </div>
    </div>

    <div class="total-card">
        <div class="stat-content">
            <i class='bx bx-line-chart' title="Total Profit"></i>
            <div class="stat-text">
                <p>Total Profit</p>
                <h1><?php echo "₱" . number_format($totalProfit, 2); ?></h1>
            </div>
        </div>
    </div>
</div>

</div>

<div class="reminder-group">
    <div class="quantity-group">
        <div class="inventory-status-card">
            <h3><i class='bx bx-no-entry' style='color: orange;' title='Low Stock'></i> Low Stock</h3>
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th style="text-align: center;">Name</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($category = $lowStockByCategory->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($category['category_name']) ?></td>
                            <td><?= htmlspecialchars($category['low_stock_count']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="inventory-status-card">
            <h3><i class='bx bx-error-circle' style='color: red;' title='Out of Stock'></i> Short Products</h3>
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th style="text-align: center;">Name</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($short = $shortProducts->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($short['name']) ?></td>
                            <td><?= htmlspecialchars($short['quantity']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="inventory-status-card">
        <h3><i class='bx bx-trending-up' style='color: green;' title='Trending'></i> Top-Selling Products</h3>
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th style="text-align: center;">Rank</th>
                        <th>Name</th>
                        <th>Total Sold</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    <?php while ($product = $topSellingProducts->fetch_assoc()): ?>
                        <tr>
                            <td style="text-align: center;"><?= $count ?></td>
                            <td><?= htmlspecialchars($product['product_name']) ?></td>
                            <td><?= htmlspecialchars($product['total_sold']) ?></td>
                        </tr>
                        <?php $count++; ?>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>


<div class="sales-trend-card">
    <h3>Recent Sales</h3>
    <table class="sales-table">
        <thead>
            <tr>
                <th>Sale ID</th>
                <th>Amount</th>
                <th>Salesperson</th>
                <th>Sale Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($sale = $recentSales->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($sale['sale_id']) ?></td>
                    <td class="amount">₱<?= number_format($sale['total_amount'], 2) ?></td>
                    <td class="salesperson"><?= htmlspecialchars($sale['salesperson']) ?></td>
                    <td class="sale-date"><?= date('F j, Y (g:i A)', strtotime($sale['sale_date'])) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>



<div class="sales-overview">
    <div class="overview-header">
        <h2>Sales Overview</h2>
        <p>Daily, weekly, and monthly sales totals</p>
    </div>

    <div class="sales-group-row">
        <!-- Daily Sales Card -->
        <div class="sales-count-card">
            <h3>Daily Sales Trends</h3>
            <ul>
                <?php while ($day = $dailySales->fetch_assoc()): ?>
                    <li>
                        <?= date('F j, Y', strtotime($day['day'])) ?> - ₱<?= number_format($day['total_sales'], 2) ?>
                        <a href="download_sales_report.php?report_type=daily&date=<?= htmlspecialchars($day['day']) ?>" 
                           class="btn btn-download" 
                           onclick="return confirmDownload('daily', '<?= date('F j, Y', strtotime($day['day'])) ?>')">Download</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>

        <!-- Weekly Sales Card -->
        <div class="sales-count-card">
            <h3>Weekly Sales Trends</h3>
            <ul>
                <?php while ($week = $weeklySales->fetch_assoc()): ?>
                    <?php
                    $year = substr($week['week'], 0, 4);
                    $weekNumber = substr($week['week'], 5);
                    $firstDayOfYear = strtotime($year . "-01-01"); 
                    $startDate = strtotime("+" . ($weekNumber - 1) . " weeks", $firstDayOfYear);
                    $startDateFormatted = date('F j, Y', $startDate);
                    $endDate = strtotime("+6 days", $startDate);
                    $endDateFormatted = date('F j, Y', $endDate);
                    ?>

                    <li>
                        <?= $startDateFormatted ?> - <?= $endDateFormatted ?> - ₱<?= number_format($week['total_sales'], 2) ?>
                        <a href="download_sales_report.php?report_type=weekly&week=<?= htmlspecialchars($week['week']) ?>" 
                           class="btn btn-download" 
                           onclick="return confirmDownload('weekly', 'Week of <?= $startDateFormatted ?> - <?= $endDateFormatted ?>')">Download</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>

        <!-- Monthly Sales Card -->
        <div class="sales-count-card">
            <h3>Monthly Sales Trends</h3>
            <ul>
                <?php while ($month = $monthlySales->fetch_assoc()): ?>
                    <li>
                        <?= date('F Y', strtotime($month['month'] . '-01')) ?> - ₱<?= number_format($month['total_sales'], 2) ?>
                        <a href="download_sales_report.php?report_type=monthly&month=<?= htmlspecialchars($month['month']) ?>" 
                           class="btn btn-download" 
                           onclick="return confirmDownload('monthly', '<?= date('F Y', strtotime($month['month'] . '-01')) ?>')">Download</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</div>

        </div>
  </div>
  <script>
function confirmDownload(reportType, reportIdentifier) {
    var reportTypeLabel = '';
    if (reportType === 'daily') {
        reportTypeLabel = 'Daily';
    } else if (reportType === 'weekly') {
        reportTypeLabel = 'Weekly';
    } else if (reportType === 'monthly') {
        reportTypeLabel = 'Monthly';
    }

    
    var confirmation = confirm(`Are you sure you want to download the ${reportTypeLabel} sales report for ${reportIdentifier}?`);

    
    if (confirmation) {
        return true;
    } else {
        return false;  
    }
}
</script>


</body>
</html>
