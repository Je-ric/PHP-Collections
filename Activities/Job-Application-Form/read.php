<?php
require_once('config.php');
require_once('session_config.php');
session_start(); 

if(!isset($_SESSION['person'])) { 
    header("Location: index.php"); 
    exit;
}


$records_per_page = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read</title>
    <style>
        <?php include 'src/table.css'; ?>
    </style>
</head>
<body >
    
<div class="container">
<div class="sidebar">
        <h3>Welcome, User!</h3>
        <p>User Information</p>
        <ul>
            <li>Jeric J. Dela Cruz</li>
            <li>BSIT 2-2</li>
            <li>INTECH 2200 - LAB</li>
        </ul>
        <p>Dashboard</p>
        <ul>
            <li>Profile</li>
            <li>Notification</li>
            <li>Setups</li>
            <li>Settings</li>
        </ul>
    </div>

    <div class="main-content">
    <header>
        <!-- <h2 class="header"><img src="/PHP-Projects/Job-Application-Form/src/DJ.png" alt="21"></h2>  -->
        <h2 class="header">&nbsp;&nbsp;&nbsp;Dashboard - Job Applications</h2>
        <a class="logout-link" href="logout.php">Logout</a> 
        <a class="create-link" href="form.php">Application Form</a> 
    </header>

    <div class="table-outline">
    <div class="table">
    <table>
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Actions to be Done</th>
            </tr>
        </thead>

        <tbody>
        <?php 
            include("config.php");
            $sql = "SELECT id, firstname, middlename, lastname, 
            email, phone, street, city, barangay, province, zip FROM job_applications LIMIT $offset, $records_per_page";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
        ?>
        
        <tr>
            <td><input type="checkbox"></td>
            <td><?php echo $row["id"]; ?></td>
            <td><?php echo $row["firstname"] . " " . $row["middlename"] . " " . $row["lastname"]; ?></td>
            <td><?php echo $row["email"]; ?>
            <td><?php echo $row["phone"]; ?></td>
            <td><?php echo $row["street"] . ", " . $row["barangay"] . ", ". $row["city"] . ", " . $row["province"] . ", " . $row["zip"]; ?>
            <td><?php echo "<a class='view-link' href='view.php?id=" . $row["id"] . "'>View</a> <a class='update-link' href='update.php?id=" . $row["id"] . "'>Update</a> <a class='delete-link' href='delete.php?id=" . $row["id"] . "'>Delete</a>"; ?>
        </td>
           
        </tr>
                
        <?php 
                }
            } else {
                echo "<tr><td colspan='8'>0 results</td></tr>";
            }
        ?>
        </tbody>
    </table>
    </div>
    </div>

    <div class="pages">
    <div class="pagination">
        <?php
        $total_pages_sql = "SELECT COUNT(*) AS total FROM job_applications";
        $result = $conn->query($total_pages_sql);
        $total_rows = $result->fetch_assoc()['total'];
        $total_pages = ceil($total_rows / $records_per_page);

        $prev_page = $page - 1;
        $next_page = $page + 1;
 
        if ($prev_page > 0) {
            echo "<a href='?page=$prev_page'>Previous</a>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                echo "<a class='active' href='?page=$i'>$i</a>";
            } else {
                echo "<a href='?page=$i'>$i</a>";
            }
        }
        
        if ($next_page <= $total_pages) {
            echo "<a href='?page=$next_page'>Next</a>";
        }

        $conn->close(); 
        ?>
    </div>
    </div>
    </div>
    </div>
</body>
</html>
