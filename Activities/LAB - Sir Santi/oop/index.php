<?php 

require "db/db.php";
$mydb = new myDB();

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
        echo "Main Page (index.php)";
        ?>
    </h1>

    <form action="db/request.php" method="post">
        <input type="text" name="full_name" placeholder="Enter your name">
        <input type="text" name="email" placeholder="Enter your email">
        <input type="text" name="course_year_section" placeholder="Enter your course, year and section">
        <input type="submit"name="add_student" value="ADD">
    </form>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Course Year & Section</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

</body>
</html>