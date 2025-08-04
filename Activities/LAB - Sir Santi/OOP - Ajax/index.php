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
    <title>OOP - AJAX</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="js/jquery.mins.js"></script>
</head>
<body>
    <h1>Main Page</h1>

    <form action="db/request.php" method="post" id="addStudentForm" class="add-student-form">
        <input type="text" name="full_name" placeholder="Enter your name" required>
        <input type="text" name="email" placeholder="Enter your email" required>
        <input type="text" name="course_year_section" placeholder="Enter your course, year and section" required>
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
                        // tBody +=`<td><button class="delete-btn" data-id="${data['id']}">Delete</button></td>`;
                        tBody += `<td>
                                    <button 
                                        class="delete-btn" 
                                        data-id="${data['id']}" 
                                        data-full_name="${data['full_name']}" 
                                        data-email="${data['email']}" 
                                        data-course_year_section="${data['course_year_section']}">
                                        Delete
                                    </button>
                                </td>`;
                        // tBody +=`<td><button class="update-btn" data-id="${data['id']}">Update</button></td>`;
                        tBody += `<td>
                                    <button 
                                        class="update-btn" 
                                        data-id="${data['id']}" 
                                        data-full_name="${data['full_name']}" 
                                        data-email="${data['email']}" 
                                        data-course_year_section="${data['course_year_section']}">
                                        Update
                                    </button>
                                </td>`;
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
                $("#addStudentForm")[0].reset(); 
                // [0] - access the 1st and usually raw DOM element
            },
            error: function(error) {
                alert("Something went wrong!");
            }
        })
    })

    $(document).on("click", ".delete-btn", function() {
        var id = $(this).data("id");
        var full_name = $(this).data("full_name");
        var email = $(this).data("email");
        var course_year_section = $(this).data("course_year_section");
        if (confirm(`Are you sure you want to delete this student?\nName: ${full_name}\nEmail: ${email}\nCourse Year & Section: ${course_year_section}`)) {
            $.ajax({
                url: "db/request.php",
                method: "POST",
                data: {
                    "delete_student": true,
                    "id": id
                },
                success: function(result) {
                    loadStudents();
                },
                error: function(error) {
                    alert("Something went wrong!");
                }
            });
        }
    })

    $(document).on("click", ".update-btn", function() {
        var id = $(this).data("id");
        var full_name = $(this).data("full_name");
        var email = $(this).data("email");
        var course_year_section = $(this).data("course_year_section");
        if (confirm(`Are you sure you want to update this student?\nName: ${full_name}\nEmail: ${email}\nCourse Year & Section: ${course_year_section}`)) {
            $.ajax({
                url: "db/request.php",
                method: "POST",
                data: {
                    "delete_student": true,
                    "id": id
                },
                success: function(result) {
                    loadStudents();
                },
                error: function(error) {
                    alert("Something went wrong!");
                }
            });
        }
    })
</script>

</html>