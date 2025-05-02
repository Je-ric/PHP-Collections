<?php
include('session_config.php');
require_once('config.php');

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

class StudentApplication {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function applyForStudent($name, $age, $email) {
        $application_error = "";

        $student_id = $this->generateStudentID();

        $gpa = 0; 

        $insert_query = "INSERT INTO students (name, age, email, student_id, gpa) VALUES ('$name', '$age', '$email', '$student_id', '$gpa')";
        if ($this->conn->query($insert_query) === TRUE) {
            return $student_id;
        } else {
            $application_error = "Application failed";
            return $application_error;
        }
    }

    

    private function generateStudentID() {
        $query = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'students'";
        $result = $this->conn->query($query);
        
        if ($result && $row = $result->fetch_assoc()) {
            return "20240" . $row['AUTO_INCREMENT'];
        } else {
            return "Failed to retrieve student ID";
        }
    }
}

$studentApplication = new StudentApplication($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $email = $_POST['email'];

    $application_result = $studentApplication->applyForStudent($name, $age, $email);
    if ($application_result){
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('studentId').innerText = '$application_result';
                    document.getElementById('myModal').style.display = 'block';
                });
            </script>";
    } else {
        $application_error = $application_result;
    }
}
?>
<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Application</title>
    <style>
 <?php include 'src/style.css'; ?>
 <?php include 'src/modal.css'; ?>
        
    </style>
</head>
<body>
    <div class="container">
    <div class="image-container">
    <img src="/PHP-Projects/Student-Portal-with-Login/src/LoginSide.jpeg" alt="">
    <div class="overlay-text-1"><br><br></div>
        <div class="overlay-text-2"><br><br></div>
    </div>
    <div class="form-container">
        <h2>Student Application</h2>
        <p class="prompt-blank"><span>Join Us:</span> Apply for Student Portal Access.</p>
        <form action="" method="post">
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="age" placeholder="Age" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="submit" value="Submit Application">
            <p class="prompt-center">Don't have an account? <a href="user-registration.php">Register here</a>.</p>
            <p class="prompt-center">Already have an Account? <a href="user-login.php">Login here</a>.</p> 
            <h3></h3>
        </form>
        <?php 
        if(isset($application_error)) {
            echo "<p>$application_error</p>";
        } 
        ?>
        </div>
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('myModal').style.display = 'none';">&times;</span>
                <div class="inside-content">
                <h1>Congratulations! Thank you for Applying!</h1>
                <p>We appreciate your application. Please <span>take note</span> and <span>remember</span> your <span>Student ID</span> for future purposes.</p>
                <p>To complete your account registration, click <b>"Continue"</b> below and enter the provided Student ID.</p>
                <p class="sid">Your Student ID: <span id="studentId"></span></p>
                <form action="user-registration.php" method="post">
                    <input type="hidden" name="student_id" value="<?php echo isset($student_id) ? $student_id : ''; ?>">
                    <input type="submit" value="Continue">
                </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html> -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Application</title>
    <style>
 <?php include 'src/style.css'; ?>
 <?php include 'src/modal.css'; ?>
        
    </style>
</head>
<body>
    <div class="container">
    <div class="image-container">
    <img src="/PHP-Projects/Student-Portal-with-Login/src/LoginSide.jpeg" alt="">
        <div class="overlay-text-1"><br><br></div>
        <div class="overlay-text-2"><br><br></div>
    </div>
    <div class="form-container">
        <h2>Student Application</h2>
        <p class="prompt-blank"><span>Join Us:</span> Apply for Student Portal Access.</p>
        <form action="" method="post">
            <input type="text" name="name" placeholder="Name" pattern="[A-Za-z ]+" title="Please enter only letters" required>
            <input type="text" name="age" placeholder="Age" pattern="\d{2}" title="Please enter two-digit numbers" required>
            <input type="email" name="email" placeholder="Email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Please enter a valid email address" required>
            <input type="submit" value="Submit Application">
            <p class="prompt-center">Don't have an account? <a href="user-registration.php">Register here</a>.</p>
            <p class="prompt-center">Already have an Account? <a href="index.php">Login here</a>.</p> 
            <h3></h3>
        </form>
        <?php 
        if(isset($application_error)) {
            echo "<p>$application_error</p>";
        } 
        ?>
        </div>
        
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('myModal').style.display = 'none';">&times;</span>
                <div class="inside-content">
                <h1>Congratulations! Thank you for Applying!</h1>
                <p>We appreciate your application. Please <span>take note</span> and <span>remember</span> your <span>Student ID</span> for future purposes.</p>
                <p>To complete your account registration, click <b>"Continue"</b> below and enter the provided Student ID.</p>
                <p class="sid">Your Student ID: <span id="studentId"></span></p>
                <form action="user-registration.php" method="post">
                    <input type="hidden" name="student_id" value="<?php echo isset($student_id) ? $student_id : ''; ?>">
                    <input type="submit" value="Continue">
                </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
