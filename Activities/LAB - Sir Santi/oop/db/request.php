<?php 

require "db.php";
$mydb = new myDB(); 

if (isset($_POST['add_student'])) {
    unset($_POST['add_student']);
    $mydb->insert('tbl_students', [...$_POST]);
    header("Location: ../");

}

?>