<?php
include "config.php";
require_once('session_config.php');

session_start(); 

if (!isset($_SESSION['person'])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    if (isset($_POST['confirm_delete'])) {
        $sql = "DELETE FROM `job_applications` WHERE `id`='$user_id'";
        echo "SQL Query: $sql";
        $result = $conn->query($sql);

        if ($result === TRUE) {
            echo "Record deleted successfully.";
            header('Location: read.php');
            exit;
        } else {
            echo "Error:" . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['cancel'])) {
        header('Location: read.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete</title>
    <style>
        <?php include 'src/form.css'; ?>
    </style>
</head>
<body>
        <form action="" method="post">
            
    <div class="container">
        
        <div class="outline">
            <h1>Delete Confirmation</h1>
            <p>Are you sure you want to delete this record?</p> 
            <div class="buttons">
                <input class="submit" type="submit" name="confirm_delete" value="Yes, Delete">
                <input class="cancel" type="submit" name="cancel" value="Cancel">
            </div>
        
    </div>
    </div>
    </form>
</body>
</html>
