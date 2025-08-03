<?php

require "db/db.php";
$mydb = new myDB();

$mydb->select('tbl_students', '*');
$student_data = $mydb->res;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OOP - V2</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Main Page</h1>
    
    <form action="db/request.php" method="post" class="add-student-form">
        <input type="text" name="full_name" placeholder="Enter your name">
        <input type="text" name="email" placeholder="Enter your email">
        <input type="text" name="course_year_section" placeholder="Enter your course, year and section">
        <input type="submit" name="add_student" value="ADD STUDENT">
    </form>

    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Course Year & Section</th>
                <th>Buttonesss</th>
            </tr>
        </thead>
        <tbody>
                <?php while ($row = mysqli_fetch_assoc($student_data)): ?>
                <tr>                
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['full_name']; ?></td>   
                    <td><?= $row['email']; ?></td>
                    <td><?= $row['course_year_section']; ?></td>
                    <td>
                        <a href="update.php?id=<?= $row['id']; ?>">Edit</a>&nbsp;
                        <a href="delete.php?id=<?= $row['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>

</html>