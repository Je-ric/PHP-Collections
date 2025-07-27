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

// // Access values
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
</head>
<body>
    <h1>
        <?php
        echo "Delete Page";
        ?>
    </h1>

    <form action="db/request.php" method="post">
        <h1>Full Name: <?php echo $full_name?></h1>
        <h1>Email: <?php echo $email?></h1>
        <h1>Course, Year and Section: <?php echo $course_year_section?></h1>
        <h1>ID: <?php echo $id?></h1>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="submit" name="delete_student" value="Delete">
    </form>
    
</body>
</html>