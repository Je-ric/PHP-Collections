<?php

require_once('session_config.php');
require_once('config.php');
session_start();


if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit; 
}


$sql = "SELECT * FROM person";
$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <!-- Include CSS styles -->
    <style>
        <?php include 'src/table.css'; ?>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body class="table-body">
    <!-- Header section -->
    <header class="body-header">
        <h2 class="header">&nbsp;User Information List</h2> 
        <a class="logout-link" href="logout.php">Logout</a> <!-- Logout link -->
        <a class="create-link" href="create.php">Add New Student</a> <!-- Link to create new user -->
    </header>

    <!-- Table to display user information -->
    <table>
        <tr>
            <th>ID</th>
            <th>User Information</th>
            <th>Address & Contact Info</th>
            <th>Signature</th>
            <th>Resume Link</th>
            <th>Comment</th>
            <th>Favorite Color</th>
            <th>Actions</th>
        </tr>
        <?php
        
        if (mysqli_num_rows($result) > 0) {
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["firstname"] . " " . $row["middlename"] . " " . $row["lastname"] . "<br>" . $row["age"] . "<br>" . $row["gender"] . "<br>" . $row["birthdate"] . "</td>";
                echo "<td>" . $row["street"] . ", " . $row["barangay"] . ", " . $row["city"] . ", " . $row["province"] . "<br>" . $row["phone"] . "<br>" . $row["email"] . "</td>";
                echo "<td><img src='upload/" . $row["signature"] . "' width='100' height='100'></td>"; 
                echo "<td>" . $row["resumelink"] . "</td>";
                echo "<td>" . $row["comment"] . "</td>";
                echo "<td>" . $row["favorite_color"] . "</td>";
                
                echo "<td><a class='view-link' href='view.php?id=" . $row["id"] . "'>View</a> <a class='update-link' href='update.php?id=" . $row["id"] . "'>Update</a> <a class='delete-link' href='delete.php?id=" . $row["id"] . "'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            
            echo "<tr><td colspan='9'>0 results</td></tr>";
        }
        
        mysqli_close($conn);
        ?>
    </table>
</body>
</html>
