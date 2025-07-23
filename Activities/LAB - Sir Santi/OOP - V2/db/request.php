<?php 

require "db.php";
$mydb = new myDB();     

if (isset($_POST['add_student'])) {
    unset($_POST['add_student']);
    $mydb->insert('tbl_students', [...$_POST]);
    header("Location: ../");
}

if (isset($_POST['update_student'])) {
    unset($_POST['update_student']);
    $id = $_POST['id'];
    $data = $_POST;
    unset($data['id']); 
    $mydb->update('tbl_students', $data, "id = $id");
    header("Location: ../");
    exit;
}

if (isset($_POST['delete_student'])) {
    unset($_POST['delete_student']);
    $id = $_POST['id'];
    $data = $_POST;
    $mydb->delete('tbl_students', $data, "id = $id");
    header("Location: ../");
    exit;
}

?>