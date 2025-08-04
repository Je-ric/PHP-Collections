<?php 

require "db.php";
$mydb = new myDB();     

if (isset($_POST['add_student'])) {
    unset($_POST['add_student']);
    $mydb->insert('tbl_students', $_POST);
}

if (isset($_POST['update_student'])) {
    unset($_POST['update_student']);
    $id = $_POST['id'];
    $data = $_POST; // get all data sa form
    unset($data['id']); // remove id para hindi ma-update
    $mydb->update('tbl_students', $data, "id = $id");
    // header("Location: ../");
    // exit;
}

if (isset($_POST['get_students'])) {
    $mydb->select('tbl_students');
    $datas = [];
    while($row = $mydb->res->fetch_assoc()){
        array_push($datas, $row);
    }
    echo json_encode($datas);
    exit;
}

if (isset($_POST['delete_student'])) {
    unset($_POST['delete_student']);
    $id = $_POST['id'];
    $data = $_POST;
    $mydb->delete('tbl_students', $data, "id = $id");
    // header("Location: ../");
    // exit;
}

?>