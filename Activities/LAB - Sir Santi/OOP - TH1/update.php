<?php 

require "db/db.php";
$mydb = new myDB();

$id = $_GET['id'] ?? '';
$full_name = '';
$email = '';
$course_year_section = '';

if ($id) {
    $mydb->select('tbl_students', '*', ['id' => $id]);
    if ($mydb->res && $mydb->res->num_rows > 0) { 
        $student_data = $mydb->res->fetch_assoc();
        $full_name = $student_data['full_name'] ?? '';
        $email = $student_data['email'] ?? '';
        $course_year_section = $student_data['course_year_section'] ?? '';
    }
}

// $id = $_GET['id'] ?? '';
// $student = getStudentById($mydb, $id);

// $full_name = $student['full_name'];
// $email = $student['email'];
// $course_year_section = $student['course_year_section'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="test.css">
</head>
<body>
    <h1>Update Page</h1>

    <div class="container">
        <form action="db/request.php" method="post">
            <div class="student-info">
                <div class="input-group">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                </div>

                <div class="input-group">
                    <strong>Full Name:</strong>
                    <input type="text" 
                        name="full_name" 
                        value="<?php echo $full_name; ?>" 
                        placeholder="Enter your name">
                </div>

                <div class="input-group">
                    <strong>Email:</strong>
                    <input type="text" 
                        name="email" 
                        value="<?php echo $email; ?>" 
                        placeholder="Enter your email">
                </div>

                <div class="input-group">
                    <strong>Course, Year and Section:</strong>
                    <input type="text" 
                        name="course_year_section" 
                        value="<?php echo $course_year_section; ?>" 
                        placeholder="Enter your course, year and section">
                </div>
            </div>

            <div class="buttoness">
                <input type="submit" name="update_student" value="UPDATE">
                <a href="index.php" class="cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>