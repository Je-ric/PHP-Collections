<?php
include('session_config.php');
session_start();
include('config.php');

if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    
    $student_id = generateStudentID();

    $gpa = 0; 

    $insert_query = "INSERT INTO students (name, age, email, student_id, gpa) VALUES ('$name', '$age', '$email', '$student_id', '$gpa')";
    if ($conn->query($insert_query) === TRUE) {    
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('studentId').innerText = '$student_id';
                    document.getElementById('myModal').style.display = 'block';
                });
              </script>";
    } else {
        $insert_error = "Insertion failed";
    }
}

function generateStudentID() {
    global $conn;
    
    $query = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'students'";
    $result = $conn->query($query);
    
    if ($result && $row = $result->fetch_assoc()) {
        
        return "20240" . $row['AUTO_INCREMENT'];
    } else {
        return "Failed to retrieve student ID";
    }
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
    <title>Admin Dashboard - Add New Student</title>
</head>
<body class="table-body">
    <header class="body-header">
        <h2 class="header">&nbsp;Admin Dashboard - Add New Student </h2> 
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

    <div class="create-container">
    <h2>Add New Student</h2>
    <p>Please fill out the following information to create a new student record:</p>
    <form action="" method="post" id="studentForm">
        <!-- <p class="input"><strong>Student ID: </strong><input type="text" name="student_id" pattern="[0-9]+" title="Please enter only numbers" required></p> -->
        <p class="input"><strong>Name: </strong><input type="text" name="name" pattern="[A-Za-z ]+" title="Please enter only letters" required></p>
        <p class="input"><strong>Age: </strong><input type="text" name="age" pattern="\d{2}" title="Please enter two-digit numbers" required></p>
        <p class="input"><strong>Email: </strong><input type="email" name="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Please enter a valid email address" required></p>
        <!-- GPA: <input type="text" name="gpa" required><br> -->
        <h2></h2>
        <div class="button-below">
            <input class="create-button" type="submit" value="Create">
            <a class="back" href="admin-R.php">Cancel and Back to Student List</a>
            </div>
    </form>
    <?php if(isset($insert_error)) echo "<p>$insert_error</p>"; ?>
    <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('myModal').style.display = 'none';">&times;</span>
                <div class="inside-content">
                <h1>Congratulations! Record Added Successfully</h1>
                <p>Thank you for adding the student record. Click <b>"Continue"</b> below to proceed.</p>
                <p class="sid">Student ID: <span id="studentId"></span></p>
                <form action="admin-R.php" method="post">
                    <input type="hidden" name="student_id" value="<?php echo isset($student_id) ? $student_id : ''; ?>">
                    <input type="submit" value="Continue">
                </form>
                </div>
            </div>
        </div>
</div>

    <script>
        function cancel() {
            window.location.href = "admin-R.php";
        }
    </script>
    <script src="src/toggleMenu.js"></script>
</body>
</html>
