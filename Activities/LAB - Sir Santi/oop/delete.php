<?php 

require "db/db.php";
$mydb = new myDB();

$id = $_GET['id'];
$full_name = '';
$email = '';
$course_year_section = '';

if ($id){
    $mydb->select('tbl_students', '*', "id = $id");
    $student = $mydb->res->fetch_assoc();
    $full_name = $student['full_name'] ?? '';
    $email = $student['email'] ?? '';
    $course_year_section = $student['course_year_section'] ?? '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        input[type="text"] {
            width: 200px;
            padding: 5px;
            margin: 5px 0;
        }

        input[type="submit"] {
            width: 200px;
            padding: 5px;
            margin: 5px 0;
            background-color: black;
            color: white;
        }

    </style>
</head>
<body>
    <h1>
        <?php
        echo "Delete Page (index.php)";
        ?>
    </h1>

    <form action="db/request.php" method="post">
        <h1><?php echo $full_name?></h1>
        <h1><?php echo $email?></h1>
        <h1><?php echo $course_year_section?></h1>
        <h1><?php echo $id?></h1>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="submit" name="delete_student" value="Delete">
    </form>
        
</body>
</html>