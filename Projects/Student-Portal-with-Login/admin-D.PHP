<?php
require_once('session_config.php');
session_start();
require_once('config.php');

if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cancel'])) {
        header("Location: admin-R.php");
        exit;
    } elseif (isset($_POST['confirm'])) {
        $id = $_POST['id'];
        $conn->begin_transaction();

        $delete_login_query = "DELETE FROM login WHERE student_id=(SELECT student_id FROM students WHERE id='$id')";
        if ($conn->query($delete_login_query) === TRUE) {
            $delete_students_query = "DELETE FROM students WHERE id='$id'";
            if ($conn->query($delete_students_query) === TRUE) {
                $conn->commit();
                echo '<script>alert("Student record deleted successfully!"); window.location.href = "admin-R.php";</script>';
                exit;
            } else {
                $conn->rollback();
                $delete_error = "Deletion from 'students' table failed";
            }
        } else {
            $conn->rollback();
            $delete_error = "Deletion from 'login' table failed";
        }
    }
} else {
    $id = $_GET['id'];
    $query = "SELECT * FROM students WHERE id='$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include 'src/table.css'; ?>
        <?php include 'src/container.css'; ?>
        <?php include 'src/modal.css'; ?>
    </style>
    <title>Admin Dashboard - Delete Student</title>
</head>
<body class="table-body">
<header class="body-header">
    <h2 class="header">&nbsp;Admin Dashboard - Delete Student </h2> 
    <a class="logout-link" href="admin-logout.php">Logout</a>
        <a class="create-link" href="admin-C.php">Add New Student</a>
    <nav id="menu">
        <input type="checkbox" id="responsive-menu" onclick="toggleMenu()">
        <label for="responsive-menu" class="menu-icon">&#9776;</label>
        <ul>
            <li><a class="dropdown-arrow" href="admin-C.php">Add New Student</a></li>
            <li><a class="dropdown-arrow" href="admin-logout.php">Logout</a></li>
        </ul>
    </nav>
</header>


    <div class="delete-container">
        <h2>Delete Student</h2>
        <p>Are you sure you want to delete the following student record?</p>
        <p><strong>ID: </strong><?php echo $row['id']; ?></p>
        <p><strong>Student ID: </strong><?php echo $row['student_id']; ?></p>
        <p><strong>Name: </strong><?php echo $row['name']; ?></p>
        <p><strong>Age: </strong><?php echo $row['age']; ?></p>
        <p><strong>Email: </strong><?php echo $row['email']; ?></p>
        <p><strong>GPA: </strong><?php echo $row['gpa']; ?></p>
        <form action="" method="post" id="deleteForm">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <h2></h2>
            <div class="button-below">
                <!-- Update button to trigger modal display -->
                <input class="delete-button" type="button" value="Delete" onclick="showModal()">
                <a class="back" href="admin-R.php">Cancel and Back to Student List</a>
            </div>
        </form>
        <?php if(isset($delete_error)) echo "<p>$delete_error</p>"; ?>
    
    <!-- Modal -->
    </div>

    <div id="myModal" class="delete-modal">
        <div class="delete-modal-content">
            <span class="close" onclick="hideModal()">&times;</span>
            <div class="delete-inside-content" style="text-align: center;">
                <h1>Confirm Student Account Deletion</h1>
                <p>Are you sure you want to delete this student's account?</p>
                <p>This action will <span>permanently remove</span> the student's account and all associated records.</p>
                <form action="" method="post">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <div class="below-button">
                    <input type="submit" name="confirm" value="Yes, Delete">
                    <button class="button" type="button" onclick="hideModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showModal() {
            document.getElementById('myModal').style.display = 'block';
        }

        function hideModal() {
            document.getElementById('myModal').style.display = 'none';
        }
    </script>
    <script src="src/toggleMenu.js"></script>
</body>
</html>

