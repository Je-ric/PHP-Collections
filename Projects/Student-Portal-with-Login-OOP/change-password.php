<?php
include('session_config.php');
session_start();
include('config.php');

class PasswordChanger {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function changePassword($oldPassword, $newPassword, $confirmPassword, $studentId) {
        $changeError = "";

        $query = "SELECT password FROM login WHERE student_id='$studentId'";
        $result = $this->conn->query($query);

        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $currentPassword = $row['password'];

            // Verify old password
            if (password_verify($oldPassword, $currentPassword)) {
                if ($newPassword === $confirmPassword) {
                    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updateQuery = "UPDATE login SET password='$newPasswordHash' WHERE student_id='$studentId'";
                    if ($this->conn->query($updateQuery) === TRUE) {
                        $changeError = "Password changed successfully";
                    } else {
                        $changeError = "Failed to update password";
                    }
                } else {
                    $changeError = "New password and confirm password do not match";
                }
            } else {
                $changeError = "Incorrect old password";
            }
        } else {
            $changeError = "User not found";
        }

        return $changeError;
    }
}

$passwordChanger = new PasswordChanger($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    $studentId = $_SESSION['user'];

    $changeError = $passwordChanger->changePassword($oldPassword, $newPassword, $confirmPassword, $studentId);
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
    <?php if(isset($changeError)) echo "<p>$changeError</p>"; ?>
</div>


</body>
</html>
