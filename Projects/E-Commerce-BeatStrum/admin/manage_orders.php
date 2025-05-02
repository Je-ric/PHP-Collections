<?php
require_once('session.php');
require_once('config.php');
session_start();

// Redirect to login page if admin is not logged in
if (!isset($_SESSION['admin_log'])) {
    header("Location: admin.php");
    exit;
}

// Handle order approval
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $sql_approve_order = "UPDATE orders SET status = 'Approved' WHERE id = $order_id";
    if ($conn->query($sql_approve_order)) {
        header("Location: manage_orders.php");
        exit();
    } else {
        echo "Error approving order: " . $conn->error;
    }
}

// Fetch pending orders with quantity, total price, and item name
$sql_pending_orders = "SELECT orders.*, client_accounts.name AS buyer_name, client_accounts.address AS buyer_address, 
                            SUM(order_items.quantity) AS total_quantity, SUM(items.price * order_items.quantity) AS total_price,
                            GROUP_CONCAT(items.name SEPARATOR ', ') AS item_names
                        FROM orders
                        INNER JOIN client_accounts ON orders.client_id = client_accounts.id
                        INNER JOIN order_items ON orders.id = order_items.order_id
                        INNER JOIN items ON order_items.item_id = items.id
                        WHERE orders.status = 'Pending'
                        GROUP BY orders.id
                        ORDER BY orders.order_date DESC";  // Order by order date in descending order
$result_pending_orders = $conn->query($sql_pending_orders);


// Fetch approved orders with quantity, total price, and item name
$sql_approved_orders = "SELECT orders.*, client_accounts.name AS buyer_name, client_accounts.address AS buyer_address, 
                            SUM(order_items.quantity) AS total_quantity, SUM(items.price * order_items.quantity) AS total_price,
                            GROUP_CONCAT(items.name SEPARATOR ', ') AS item_names
                        FROM orders
                        INNER JOIN client_accounts ON orders.client_id = client_accounts.id
                        INNER JOIN order_items ON orders.id = order_items.order_id
                        INNER JOIN items ON order_items.item_id = items.id
                        WHERE orders.status IN ('Approved', 'Received')
                        GROUP BY orders.id
                        ORDER BY orders.order_date DESC"; // Order by order date in descending order
$result_approved_orders = $conn->query($sql_approved_orders);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Approval</title>
    <!-- Add any CSS styling here -->
    <style>
        <?php 
        include('C:\xampp\htdocs\PHP-Projects\E-Commerce-BeatStrum\css\manage_orders.css');
        ?>
</style>

</head>
<body>

<?php include 'header.php'; ?>
<?php #include '1.header.php'; ?>
   <div class="container">
        <h1>Manage Orders</h1>
        <!-- Tab navigation -->
        <div class="tab-navigation">
        <button onclick="openTab('pending')">Pending Orders</button>
        <button onclick="openTab('approved')">Approved Orders</button>
        <button onclick="openTab('cancelled')">Cancelled Orders</button> <!-- Add this line -->
    </div>

        <!-- Pending Orders Tab -->
        <div id="pendingTab" class="tab active">
            <table>
                <thead>
                    <tr>
                        <!-- <th>Order ID</th> -->
                        <th>Buyer Name</th>
                        <th>Buyer Address</th>
                        <th>Item Name(s)</th> <!-- Add this line -->
                        <th>Total Quantity</th>
                        <th>Total Price (including Shipping)</th>
                        <th>Status</th>
                        <th>Order Date & Time</th> <!-- New column for order date and time -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_pending_orders->num_rows > 0): ?>
                        <?php while ($row = $result_pending_orders->fetch_assoc()): ?>
                            <tr>
                                <!-- <td><?php #echo $row['id']; ?></td> -->
                                <td><?php echo $row['buyer_name']; ?></td>
                                <td><?php echo $row['buyer_address']; ?></td>
                                <td><?php echo $row['item_names']; ?></td> <!-- Display item names -->
                                <td><?php echo $row['total_quantity']; ?></td>
                                <td>$<?php echo $row['total_price'] + $row['shipping_fee']; ?></td> <!-- Include shipping fee -->
                                <td><?php echo $row['status']; ?></td>
                                <td><?php echo $row['order_date']; ?></td> <!-- Display order date and time -->
                                <td>
                                    <?php if ($row['status'] == 'Pending'): ?>
                                        <a href="<?php echo $_SERVER['PHP_SELF'] . "?order_id=" . $row['id']; ?>">Approve</a>
                                    <?php else: ?>
                                        Approved
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center;">No pending orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    
        <!-- Approved Orders Tab -->
        <div id="approvedTab" class="tab">
            <?php if ($result_approved_orders->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <!-- <th>Order ID</th> -->
                            <th>Buyer Name</th>
                            <th>Buyer Address</th>
                            <th>Item Name(s)</th> <!-- Add this line -->
                            <th>Total Quantity</th>
                            <th>Total Price (including Shipping)</th>
                            <th>Status</th>
                            <th>Order Date & Time</th> <!-- New column for order date and time -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_approved_orders->fetch_assoc()): ?>
                            <tr>
                                <!-- <td><?php #echo $row['id']; ?></td> -->
                                <td><?php echo $row['buyer_name']; ?></td>
                                <td><?php echo $row['buyer_address']; ?></td>
                                <td><?php echo $row['item_names']; ?></td> <!-- Display item names -->
                                <td><?php echo $row['total_quantity']; ?></td>
                                <td>$<?php echo $row['total_price'] + $row['shipping_fee']; ?></td> <!-- Include shipping fee -->
                                <td><?php echo $row['status']; ?></td>
                                <td><?php echo $row['order_date']; ?></td> <!-- Display order date and time -->
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No approved orders found.</p>
            <?php endif; ?>
        </div>
    
        <!-- Cancelled Orders Tab -->
    <div id="cancelledTab" class="tab">
        <?php
        // Fetch cancelled orders with quantity and total price
       // Fetch cancelled orders with quantity, total price, and item name
$sql_cancelled_orders = "SELECT orders.*, client_accounts.name AS buyer_name, client_accounts.address AS buyer_address, 
SUM(order_items.quantity) AS total_quantity, SUM(items.price * order_items.quantity) AS total_price,
GROUP_CONCAT(items.name SEPARATOR ', ') AS item_names
FROM orders
INNER JOIN client_accounts ON orders.client_id = client_accounts.id
INNER JOIN order_items ON orders.id = order_items.order_id
INNER JOIN items ON order_items.item_id = items.id
WHERE orders.status = 'Cancelled'
GROUP BY orders.id
ORDER BY orders.order_date DESC"; // Order by order date in descending order
$result_cancelled_orders = $conn->query($sql_cancelled_orders);

    
        if ($result_cancelled_orders->num_rows > 0) {
            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Buyer Name</th>";
            echo "<th>Buyer Address</th>";
            echo "<th>Item Name</th>";
            echo "<th>Total Quantity</th>";
            echo "<th>Total Price (including Shipping)</th>";
            echo "<th>Status</th>";
            echo "<th>Order Date & Time</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while ($row = $result_cancelled_orders->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['buyer_name'] . "</td>";
                echo "<td>" . $row['buyer_address'] . "</td>";
                echo "<td>" . $row['item_names'] . "</td>";
                echo "<td>" . $row['total_quantity'] . "</td>";
                echo "<td>$" . ($row['total_price'] + $row['shipping_fee']) . "</td>"; // Include shipping fee
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>" . $row['order_date'] . "</td>"; // Display order date and time
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No cancelled orders found.</p>";
        }
        ?>
    </div>
   </div>


    <!-- JavaScript for tab functionality -->
    <script>
    function openTab(tabName) {
        var i, tabContent, tabButtons;
        tabContent = document.getElementsByClassName("tab");
        tabButtons = document.getElementsByClassName("tab-navigation")[0].getElementsByTagName("button");

        // Hide all tab content and remove active class from tab buttons
        for (i = 0; i < tabContent.length; i++) {
            tabContent[i].classList.remove("active");
            tabButtons[i].classList.remove("active");
        }

        // Show the selected tab content and set the corresponding tab button as active
        document.getElementById(tabName + "Tab").classList.add("active");
        for (i = 0; i < tabButtons.length; i++) {
            if (tabButtons[i].getAttribute("onclick").includes(tabName)) {
                tabButtons[i].classList.add("active");
                break;
            }
        }
    }

    // By default, open the 'pending' tab
    openTab('pending');
    </script>

</body>
</html>
