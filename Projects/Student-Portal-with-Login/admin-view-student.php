<?php
include('session_config.php');
session_start();
include('config.php');

if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: admin-R.php");
    exit;
}

$id = $_GET['id'];
$query = "SELECT students.*, login.password FROM students LEFT JOIN login ON students.student_id = login.student_id WHERE students.id='$id'";
$result = $conn->query($query);
if ($result->num_rows == 0) {
    header("Location: admin-R.php");
    exit;
}

$row = $result->fetch_assoc();
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
    <title>Admin Dashboard - View Student</title>
    <style>

    </style>
</head>
<body class="table-body">
    <header class="body-header">
        <h2 class="header">&nbsp;Admin Dashboard - View Student </h2> 
        <a class="logout-link" href="admin-logout.php">Logout</a>
        <a class="create-link" href="admin-C.php">Add New Student</a>
        <nav id="menu">
        <input type="checkbox" id="responsive-menu" onclick="toggleMenu()">
        <label for="responsive-menu" class="menu-icon">&#9776;</label>
        <ul>
            <li><a class="dropdown-arrow" href="admin-logout.php">Logout</a></li>
            <li><a class="dropdown-arrow" href="admin-C.php">Add New Student</a></li>
        </ul>
    </nav>
    </header>

    <div class="admin-view-container">
        <h2>Student Information</h2>
        <p  ><strong>ID:</strong> <?php echo $row['id']; ?></p>
        <p><strong>Student ID:</strong> <?php echo isset($row['student_id']) ? $row['student_id'] : "Not registered yet"; ?></p>
        <p><strong>Name:</strong> <?php echo $row['name']; ?></p>
        <p><strong>Age:</strong> <?php echo $row['age']; ?></p>
        <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
        <p><strong>GPA:</strong> <?php echo $row['gpa'] ? $row['gpa'] : "No Grade Yet"; ?></p>
        <p><strong>Password:</strong> <?php echo $row['password'] ? $row['password'] : "Not registered yet"; ?></p>
        <h2></h2>
        <a class="back" href="admin-R.php">Back to Student List</a>
    </div>

    <script src="src/toggleMenu.js"></script>
</body>
</html>
