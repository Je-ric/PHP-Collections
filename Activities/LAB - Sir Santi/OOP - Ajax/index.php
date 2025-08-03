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
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="js/jquery.mins.js"></script>
</head>
<body>
    <h1>Main Page</h1>

    <form action="db/request.php" method="post" id="addStudentForm" class="add-student-form">
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
        <tbody id="tbodyStudent">

        </tbody>
    </table>

</body>
<script type="text/javascript">
    $(document).ready(function() {
        loadStudents();
    })

    function loadStudents(){
        $.ajax({
            url: "db/request.php",
            method: "POST",
            data: { 
                "get_students": true,
            },
            success: function(result) {
                var tBody = ``;
                var cnt = 1;
                var datas = JSON.parse(result);
                datas.forEach(function(data) {
                    tBody += `<tr>`
                        tBody +=`<td>${cnt++}</td>`;
                        tBody +=`<td>${data['full_name']}</td>`;
                        tBody +=`<td>${data['email']}</td>`;
                        tBody +=`<td>${data['course_year_section']}</td>`;
                        // tBody +=`<td><!-- Buttonesss --></td>`;
                    tBody += `</tr>`;
                });
                $('#tbodyStudent').html(tBody);
            },
            error: function(error){
                alert("Something went wrong!.");
            }
        })
    }

    $("#addStudentForm").on("submit", function(e) {
        e.preventDefault();
        var datas = $(this).serializeArray();
        var data_array = {};
        $.map(datas, function(data) {
            data_array[data['name']] = data['value'];
        });
        console.log(datas);
        console.log(data_array);
        $.ajax({
            url: "db/request.php",
            method: "POST",
            data: {
                "add_student": true,
                ...data_array
            },
            success: function(result) {
                loadStudents();
            },
            error: function(error) {
                alert("Something went wrong!");
            }
        })
    })
</script>

</html>