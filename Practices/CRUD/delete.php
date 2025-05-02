<?php

require_once('session_config.php');
require_once('config.php');
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['confirm_delete'])) {
        
        $id = $_POST['id'];

        
        $sql = "DELETE FROM person WHERE id='$id'";

        
        if (mysqli_query($conn, $sql)) {
            
            header('Location: read.php');
            exit;
        } else {
            
            echo "Error deleting record: " . mysqli_error($conn);
        }
    } elseif (isset($_POST['cancel_delete'])) {
        
        header('Location: read.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Confirmation</title>
    <!-- Include CSS styles -->
    <style>
        <?php include 'src/style.css'; ?>
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete Confirmation</h1>
        <form action="" method="post">
            <p>Are you sure you want to delete this record?</p>
            <div class="buttons">
                <!-- Hidden input field to store the ID -->
                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                <!-- Submit button to confirm deletion -->
                <input type="submit" name="confirm_delete" value="Yes, Delete">
                <!-- Submit button to cancel deletion -->
                <input type="submit" name="cancel_delete" value="Cancel" class="cancel-button">
            </div>
        </form>
    </div>
</body>
</html>
