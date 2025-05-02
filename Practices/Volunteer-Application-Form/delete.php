<?php 
include "config.php"; 

if (isset($_GET['id'])) {
    $volunteer_id = $_GET['id']; 
    
    if(isset($_POST['confirm_delete'])) {
        $sql = "DELETE FROM `volunteer_applications` WHERE `id`='$volunteer_id'"; 
        $result = $conn->query($sql);
        
        if ($result == TRUE) {
            header('Location: view.php');
            exit;
        } else {
            echo "Error:" . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['cancel'])) {
        header('Location: view.php');
        exit;
    }
}   
?> 

<!DOCTYPE html>
<html>
<head>
    <title>Delete</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Delete Confirmation</h1>
        <form action="" method="post"> 
            <p>Are you sure you want to delete this record?</p>
            <div class="delete-buttons">
                <input type="submit" name="confirm_delete" value="Yes, Delete">
                <input type="submit" name="cancel" value="Cancel">
            </div>
        </form>
    </div>
</body>
</html>
