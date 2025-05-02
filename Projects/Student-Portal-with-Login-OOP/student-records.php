<?php
include('session_config.php');
session_start();
include('config.php');

class StudentRecords {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function fetchStudentRecord($student_id) {
        $query = "SELECT students.student_id, students.name, students.age, students.email, students.gpa 
                  FROM students
                  INNER JOIN login ON students.student_id = login.student_id
                  WHERE students.student_id='$student_id'";
        $result = $this->conn->query($query);

        return $result->fetch_assoc();
    }
}

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$student_id = $_SESSION['user'];

$studentRecords = new StudentRecords($conn);
$row = $studentRecords->fetchStudentRecord($student_id);

$student_name = $row['name'] ?? ""; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include 'src/table.css'; ?>
    </style>
    <link rel="stylesheet" href="table.css">

</head>
<body class="table-body">
    <header class="body-header">
        <h2 class="header">&nbsp;Student Records</h2> 
        <nav id="menu">
        <input type="checkbox" id="responsive-menu" onclick="toggleMenu()">
        <label for="responsive-menu" class="menu-icon">&#9776;</label>
        <ul>
        <div class="student-icon">
                <img src="src/User.png" alt="Student Icon">
                <p>Welcome, <?php echo $student_name; ?></p>
            </div>
            <li><a class="dropdown-arrow" href="change-password.php">Change Password</a></li>
            <li><a class="dropdown-arrow" href="user-logout.php" onclick="return confirmLogout()">Logout</a></li>
        </ul><script>
                function confirmLogout() {
                    return confirm("Are you sure you want to logout?");
                }
        </script>
    </nav>
    </header>

    <div class="sidebar">
        <div class="sidebar-glass">
            <div class="student-icon">
                <img src="src/User.png" alt="Student Icon">
                <p>Welcome, <?php echo $student_name; ?></p>
            </div>
            
            <ul>
                <li><a class="password" href="change-password.php">Change Password </a></li>
                <a class="logout" href="user-logout.php" onclick="return confirmLogout()">Logout</a>
                <script>
                function confirmLogout() {
                    return confirm("Are you sure you want to logout?");
                }
        </script>
            </ul>
        </div>
    </div>

    <div class="horizontal-container">
        <p><strong>Course Information:</strong> The CLSU IT course offered in the building of CLIRDEC provides comprehensive details on syllabi, schedules, required textbooks, and assignments, facilitating streamlined access to essential course information for enrolled students. This centralized resource ensures efficient navigation and organization of academic materials, enhancing the learning experience within the CLIRDEC facility.</p>
    </div>

    <div class="table-container">
        <table>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Email</th>
                <th>GPA</th>
            </tr>
            <?php
            if ($row) {
                echo "<tr>";
                echo "<td>".$row["student_id"]."</td>";
                echo "<td>".$row["name"]."</td>";
                echo "<td>".$row["age"]."</td>";
                echo "<td>".$row["email"]."</td>";
                echo "<td>".$row["gpa"]."</td>";
                echo "</tr>";
            } else {
                echo "<tr><td colspan='5'>No records found</td></tr>";
            }
            ?>
        </table>
    </div>    
</body>
</html>
