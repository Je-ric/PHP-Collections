<?php
include('session_config.php');
session_start();
include('config.php');

if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $gpa = isset($_POST['gpa']) ? $_POST['gpa'] : NULL; 
    
    $student_id = $_POST['student_id'];

    $password_query = "SELECT password FROM login WHERE student_id='$student_id'";
    $password_result = $conn->query($password_query);
    
    $password = $password_result->num_rows > 0 ? "Registered" : "Not registered yet";

    // Update 
    $update_query = "UPDATE students SET name='$name', age='$age', email='$email', gpa='$gpa' WHERE id='$id'";
    if ($conn->query($update_query) === TRUE) {
        // header("Location: admin-R.php");
        echo '<script>alert("Student record updated successfully!"); window.location.href = "admin-R.php";</script>';
        exit;
    } else {
        $update_error = "Update failed";
    }
    
} else {
    $id = $_GET['id'];
    
    //get info
    $query = "SELECT students.*, login.password FROM students LEFT JOIN login ON students.student_id = login.student_id WHERE students.id='$id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $password = $row['password'] ? "Registered" : "Not registered yet";
    } else {
        $password = "Not registered yet";
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
    <title>Admin Dashboard - Update Student</title>
</head>
<body class="table-body">
    <header class="body-header">
        <h2 class="header">&nbsp;Admin Dashboard - Update Student </h2> 
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

    <div class="update-container">
        <h2>Update Student</h2>
        <p>Please review and confirm the changes to the following student record:</p>
        <form action="" method="post" id="updateForm">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <input type="hidden" name="student_id" value="<?php echo $row['student_id']; ?>">
            <p class="input id"><strong>ID:</strong> <?php echo $row['id']; ?></p>
            <p class="input id"><strong>Student ID:</strong> <?php echo $row['student_id']; ?></p>
            <p class="input"><strong>Name:</strong> <input type="text" name="name" value="<?php echo $row['name']; ?>" pattern="[A-Za-z ]+" title="Please enter only letters" required></p>
            <p class="input"><strong>Age:</strong> <input type="text" name="age" value="<?php echo $row['age']; ?>" pattern="\d{1,2}" title="Please enter a valid age" required></p>
            <p class="input"><strong>Email:</strong> <input type="email" name="email" value="<?php echo $row['email']; ?>" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Please enter a valid email address" required></p>
            <p class="input"><strong>GPA:</strong> <input type="text" name="gpa" value="<?php echo isset($row['gpa']) ? ($row['gpa'] >= 1 ? $row['gpa'] : "No Grade Yet") : "No Grade Yet"; ?>" pattern="^$|^(?:5\.00|4\.\d\d|3\.[0-9]\d|3\.00|2\.\d\d|1\.\d\d|1\.00|0\.\d\d?)$" title="Please enter a GPA between 0.00 and 5.00"></p>
            <p class="input id"><strong>Status:</strong> <?php echo htmlspecialchars($password); ?></p>
            <h2></h2>
            <div class="buttons-below">
                <input class="update-button" type="button" value="Update" onclick="showModal()">
                <a class="back" href="admin-R.php">Cancel and Back to Student List</a>
            </div>
        </form>
        <?php if(isset($update_error)) echo "<p>$update_error</p>"; ?>
    </div>

    <div id="updateConfirmationModal" class="update-modal">
        <div class="update-modal-content">
            <span class="close" onclick="hideModal()">&times;</span>
            <div class="update-inside-content" style="text-align: center;">
                <h1>Confirm Student Record Update</h1>
                <p>Are you sure you want to update this student's information?</p>
                <p>This action will <span>save the changes</span> made to the student's record.</p>
                <div class="below-button">
                    <input type="submit" form="updateForm" value="Yes, Update">
                    <button class="button" onclick="hideModal()">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showModal() {
            document.getElementById('updateConfirmationModal').style.display = 'block';
        }

        function hideModal() {
            document.getElementById('updateConfirmationModal').style.display = 'none';
        }
    </script>

    <script>
        function cancel() {
            window.location.href = "admin-R.php";
        }
    </script>
    <script src="src/toggleMenu.js"></script>
</body>
</html>


<?php
// include('session_config.php');
// session_start();
// include('config.php');

// if (!isset($_SESSION['admin'])) {
//     header("Location: admin-login.php");
//     exit;
// }

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $id = $_POST['id'];
//     $name = $_POST['name'];
//     $age = $_POST['age'];
//     $email = $_POST['email'];
//     $gpa = isset($_POST['gpa']) ? $_POST['gpa'] : NULL; 
    
//     $student_id = $_POST['student_id'];
//     $password_query = "SELECT student_id, password FROM login WHERE student_id='$student_id'";
//     $password_result = $conn->query($password_query);

//     // check kung nasa db
//     if ($password_result->num_rows > 0) {
//         $password_row = $password_result->fetch_assoc();
//         $password = "Registered";
//     } else {
//         $password = "Not registered yet";
//     }

//     $update_query = "UPDATE students SET name='$name', age='$age', email='$email', gpa='$gpa' WHERE id='$id'";
//     if ($conn->query($update_query) === TRUE) {
//         header("Location: admin-R.php");
//         exit;
//     } else {
//         $update_error = "Update failed";
//     }
    
// } else {
//     $id = $_GET['id'];
//     $query = "SELECT * FROM students WHERE id='$id'";
//     $result = $conn->query($query);
//     $row = $result->fetch_assoc();

//     $student_id_query = "SELECT student_id FROM students WHERE id='$id'";
//     $student_id_result = $conn->query($student_id_query);
//     if ($student_id_result->num_rows > 0) {
//         $student_id_row = $student_id_result->fetch_assoc();
//         $student_id = $student_id_row['student_id'];
//     } else {
//         $student_id = "Not registered yet";
//     }
    
//     $password_query = "SELECT password FROM login WHERE student_id='$student_id'";
//     $password_result = $conn->query($password_query);

//     if ($password_result->num_rows > 0) {
//         $password = "Registered";
//     } else {
//         $password = "Not registered yet";
//     }
// }?>