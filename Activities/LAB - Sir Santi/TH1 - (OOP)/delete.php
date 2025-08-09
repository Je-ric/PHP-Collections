<?php 

require "db/db.php";
$mydb = new myDB();

$id = $_GET['id']; 
$full_name = '';
$email = '';
$course_year_section = '';

if ($id){
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
    <style>
    </style>
</head>
<body>
    <h1>Delete Page</h1>

    <div class="container">
        <form action="db/request.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            
            <div class="student-info">
                <p><strong>ID:</strong> <?php echo $id ?></p>
                <p><strong>Full Name:</strong> <?php echo $full_name ?></p>
                <p><strong>Email:</strong> <?php echo $email ?></p>
                <p><strong>Course, Year and Section:</strong> <?php echo $course_year_section ?></p>
            </div>

            <div class="buttoness">
                <input type="submit" name="delete_student" value="Delete">
                <a href="index.php" class="cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>