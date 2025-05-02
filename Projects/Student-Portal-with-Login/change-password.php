<?php
session_start();
// include('session_config.php');
include('config.php');

if (!isset($_SESSION['user'])) {
    header("Location: user-login.php");
    exit;
}

$change_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $student_id = $_SESSION['user'];

    $query = "SELECT password FROM login WHERE student_id='$student_id'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $current_password = $row['password'];

        // Verify old password
        if (password_verify($old_password, $current_password)) {
            if ($new_password === $confirm_password) {
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $update_query = "UPDATE login SET password='$new_password_hash' WHERE student_id='$student_id'";
                if ($conn->query($update_query) === TRUE) {
                    $change_error = "Password changed successfully";
                } else {
                    $change_error = "Failed to update password";
                }
            } else {
                $change_error = "New password and confirm password do not match";
            }
        } else {
            $change_error = "Incorrect old password";
        }
    } else {
        $change_error = "User not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include 'src/table.css'; ?>
        <?php include 'src/container.css'; ?>
    </style>
    <title>Change Password</title>
</head>

<body class="table-body">
    <header class="body-header">
        <h2 class="header">&nbsp;Student Records - Change Password </h2> 
    </header>

    <div class="change-password-container">
    <h2>Change Password</h2>
    <p>Please confirm that you want to change your password:</p>
    <form action="" method="post">
        <p class="input"><strong>Old Password: </strong><input type="password" name="old_password" placeholder="Old Password" required></p>
        <p class="input"><strong>New Password: </strong><input type="password" name="new_password" placeholder="New Password" required></p>
        <p class="input"><strong>Confirm New Password: </strong><input type="password" name="confirm_password" placeholder="Confirm New Password" required></p>
        <h2></h2>
        <div class="buttons-below">
            <input class="update-button" type="submit" value="Change Password">
            <a class="back" href="student-records.php">Cancel</a>
        </div>
    </form>
    <?php if(isset($change_error)) echo "<p>$change_error</p>"; ?>
</div>


</body>
</html>

