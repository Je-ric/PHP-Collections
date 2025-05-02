 <?php
include('session_config.php');
session_start();
include('config.php');

$results_per_page = 10; 
$current_page = isset($_GET['page']) ? $_GET['page'] : 1; 
$start_index = ($current_page - 1) * $results_per_page; 

if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit;
}

$total_records_query = "SELECT COUNT(*) AS total FROM students";
$total_records_result = $conn->query($total_records_query);
$total_records_row = $total_records_result->fetch_assoc();
$total_records = $total_records_row['total'];

$total_pages = ceil($total_records / $results_per_page);

$query = "SELECT * FROM students LIMIT $start_index, $results_per_page";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include 'src/table.css'; ?>
        <?php include 'src/container.css'; ?>
    </style>
    <title>Admin Dashboard - Student List</title>
</head>
<body class="table-body">
    <header class="body-header">
        <h2 class="header">&nbsp;Admin Dashboard - Student List </h2> 
        <a class="logout-link" href="admin-logout.php">Logout</a>
        <a class="create-link" href="admin-C.php">Add New Student</a>
        <nav id="menu">
        <input type="checkbox" id="responsive-menu" onclick="toggleMenu()">
        <label for="responsive-menu" class="menu-icon">&#9776;</label>
        <ul>
        <div class="student-icon">
                <img src="src/Admin.png" alt="Student Icon">
                <p>Welcome, Admin</p>
            </div>
            <li><a class="dropdown-arrow" href="admin-C.php">Add New Student</a></li>
            <li><a class="dropdown-arrow" href="admin-logout.php">Logout</a></li>
        </ul>
    </nav>
    </header>


    
<div class="sidebar">
    <div class="sidebar-glass">
            <div class="student-icon">
                <img src="src/Admin.png" alt="Student Icon">
                <p>Welcome, Admin</p>
            </div>
            
            <ul>
                <!-- <li>Registration Form</li>
                <li>View Grades</li>
                <li>Enrollment</li>
                <li>Pre-Registration</li> -->
            </ul>
        </div>
        </div>
    <div class="table-container">
        <table>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Email</th>
                <th>GPA</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td> </td>";
                    echo "<td>".$row["id"]."</td>";
                    echo "<td>".$row["name"]."</td>";
                    echo "<td>".$row["age"]."</td>";
                    echo "<td>".$row["email"]."</td>";
                    echo "<td>".(($row["gpa"] >= 1 || $row["gpa"] === null) ? $row["gpa"] : "No Grade Yet")."</td>";
                    echo "<td class='responsive-dropdown'>";
                    echo "<div class='dropdown'>";
                    echo "<button class='dropdown-btn'>Actions</button>";
                    echo "<div class='dropdown-content'>";
                    echo "<a class='view-link' href='admin-view-student.php?id=".$row["id"]."'>View</a>"; 
                    echo "<a class='update-link' href='admin-U.php?id=".$row["id"]."'>Update</a>"; 
                    echo "<a class='delete-link' href='admin-D.php?id=".$row["id"]."'>Delete</a>";
                    echo "</div>"; // dropdown-content
                    echo "</div>"; // dropdown
                    echo "</td>"; // responsive-dropdown
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No records found</td></tr>";
            }
            ?>
        </table>
    </div>

    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php if ($current_page > 1): ?>
            <a href="?page=<?php echo $current_page - 1; ?>">Previous</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a <?php echo ($i == $current_page) ? 'class="active"' : ''; ?> href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($current_page < $total_pages): ?>
            <a href="?page=<?php echo $current_page + 1; ?>">Next</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <script src="src/toggleMenu.js"></script>
    <script src="src/auto-refresh.js"></script>
    <script src="src/responsive-dropdown.js"></script>
</body>
</html>

