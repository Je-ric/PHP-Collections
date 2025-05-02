<?php

require_once('session_config.php');
require_once('config.php');
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit; 
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT * FROM person WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    
    $row = mysqli_fetch_assoc($result);
} else {
    
    echo "Invalid user ID";
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $barangay = isset($_POST['barangay']) ? $_POST['barangay'] : '';
    $city = $_POST['city'];
    $province = $_POST['province'];
    $signature = $_FILES['signature']['name'];
    $resumelink = $_POST['resumelink'];
    $comment = $_POST['comment'];
    $favorite_color = $_POST['favorite_color'];

    
    $target_dir = "C:\\xampp\\htdocs\\PHP-Practice\\CRUD-with-Login\\upload\\";
    $target_file = $target_dir . basename($_FILES["signature"]["name"]);

    
    if (move_uploaded_file($_FILES["signature"]["tmp_name"], $target_file)) {
        
        
        $sql = "UPDATE person SET firstname='$firstname', middlename='$middlename', lastname='$lastname', age='$age', gender='$gender', birthdate='$birthdate', phone='$phone', email='$email', barangay='$barangay', city='$city', province='$province', signature='$signature', resumelink='$resumelink', comment='$comment', favorite_color='$favorite_color' WHERE id='$id'";

        
        if (mysqli_query($conn, $sql)) {
            
            echo "Record updated successfully";
            header('Location: read.php'); 
            exit;
        } else {
            
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        
        echo "Error uploading file";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Information</title>
    <!-- Include CSS styles -->
    <style>
        <?php include 'src/form.css'; ?>
    </style>
</head>
<body>
    <!-- Form for updating user information -->
    <div class="full-form">
        <div class="form">
            <form action="" method="POST" enctype="multipart/form-data">
                <h1 class="title">Update Information Form</h1>

                <!-- Hidden input field for ID -->
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                <!-- Full Name -->
                <h3>Full Name</h3>
                <div class="side-by-side">
                    <div class="text-box">
                        <input type="text" placeholder="First Name" name="firstname" value="<?php echo $row['firstname']; ?>" required>
                    </div>
                    <div class="text-box">
                        <input type="text" placeholder="Middle Name" name="middlename" value="<?php echo $row['middlename']; ?>" required>
                    </div>
                    <div class="text-box">
                        <input type="text" placeholder="Last Name" name="lastname" value="<?php echo $row['lastname']; ?>" required>
                    </div>
                </div>

                <!-- Age, Birthdate, Gender -->
                <div class="side-by-side">
                    <div class="text-box">
                        <h3>Age</h3>
                        <input type="number" placeholder="Age" name="age" value="<?php echo $row['age']; ?>">
                    </div>
                    <div class="text-box">
                        <h3>Birthdate</h3>
                        <input type="date" placeholder="Birthdate" name="birthdate" value="<?php echo $row['birthdate']; ?>">
                    </div>
                    <div class="text-box">
                        <h3>Gender</h3>
                        <input type="radio" name="gender" value="Male" <?php if ($row['gender'] == 'Male') echo 'checked'; ?>> Male
                        <input type="radio" name="gender" value="Female" <?php if ($row['gender'] == 'Female') echo 'checked'; ?>> Female
                    </div>
                </div>

                <!-- Phone, Email -->
                <div class="side-by-side">
                    <div class="text-box">
                        <h3>Phone</h3>
                        <input type="tel" placeholder="Phone" name="phone" value="<?php echo $row['phone']; ?>">
                    </div>
                    <div class="text-box">
                        <h3>Email</h3>
                        <input type="email" placeholder="Email" name="email" value="<?php echo $row['email']; ?>" required>
                    </div>
                </div>

                <!-- Province, City -->
                <div class="side-by-side">
                    <div class="text-box">
                        <h3>Province</h3>
                        <input type="text" placeholder="Province" name="province" value="<?php echo $row['province']; ?>">
                    </div>
                    <div class="text-box">
                        <h3>City</h3>
                        <input type="text" placeholder="City" name="city" value="<?php echo $row['city']; ?>">
                    </div>
                </div>

                <!-- Barangay, Street -->
                <div class="side-by-side">
                    <div class="text-box">
                        <h3>Barangay</h3>
                        <input type="text" placeholder="Barangay" name="barangay" value="<?php echo $row['barangay']; ?>">
                    </div>
                    <div class="text-box">
                        <h3>Street</h3>
                        <input type="text" placeholder="Street" name="street" value="<?php echo $row['street']; ?>">
                    </div>
                </div>

                <!-- Signature, Resume Link, Comment -->
                <div class="side-by-side">
                    <div class="text-box">
                        <h3>Signature</h3>
                        <input type="file" name="signature" accept="image/*">
                    </div>
                    <div class="text-box">
                        <h3>Resume Link</h3>
                        <input type="url" name="resumelink" value="<?php echo isset($row['resumelink']) ? $row['resumelink'] : ''; ?>">
                    </div>
                    <div class="text-box">
                        <h3>Comment</h3>
                        <textarea name="comment" placeholder="Comment"><?php echo $row['comment']; ?></textarea>
                    </div>
                </div>

                <!-- Favorite Color -->
                <div class="side-by-side">
                    <div class="text-box">
                        <h3>Favorite Color</h3>
                        <select name="favorite_color">
                            <option value="Red" <?php if ($row['favorite_color'] == 'Red') echo 'selected'; ?>>Red</option>
                            <option value="Blue" <?php if ($row['favorite_color'] == 'Blue') echo 'selected'; ?>>Blue</option>
                            <option value="Green" <?php if ($row['favorite_color'] == 'Green') echo 'selected'; ?>>Green</option>
                            <option value="Yellow" <?php if ($row['favorite_color'] == 'Yellow') echo 'selected'; ?>>Yellow</option>
                            <option value="Other" <?php if ($row['favorite_color'] == 'Other') echo 'selected'; ?>>Other</option>
                        </select>
                    </div>
                </div>

                <!-- Update button -->
                <input type="submit" value="Update">
                <a href="read.php" class="cancel-button">Cancel</a> <!-- Link to go back to 2-Read.php -->
            </form>
        </div>
    </div>
</body>
</html>
