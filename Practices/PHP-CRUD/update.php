<?php
require_once 'config/database.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    
    if($stmt->execute()){
        $result = $stmt->get_result();
        // if a record was found
        if($result->num_rows == 1){
            // get the record
            $row = $result->fetch_assoc();
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $email = $row['email'];
            $phone = $row['phone'];
            $age = $row['age'];
            $gender = $row['gender'];
            $address = $row['address'];
            $date_of_birth = $row['date_of_birth'];
        } else {
            echo "No record found.";
            exit;
        }
    } else {
        echo "Error executing query.";
        exit;
    }
} else {
    echo "ID not set.";
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $date_of_birth = $_POST['date_of_birth'];

    $query = "UPDATE users SET first_name=?, last_name=?, email=?, phone=?, age=?, gender=?, address=?, date_of_birth=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssiisss", $first_name, $last_name, $email, $phone, $age, $gender, $address, $date_of_birth, $id);
    
    if($stmt->execute()){
        header("Location: read.php");
        exit;
    } else {
        echo "Error updating record.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Update User</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id); ?>" method="post">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" value="<?php echo $first_name; ?>" required>
            
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" value="<?php echo $last_name; ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $email; ?>" required>
            
            <label for="phone">Phone:</label>
            <input type="text" name="phone" value="<?php echo $phone; ?>">
            
            <label for="age">Age:</label>
            <input type="number" name="age" value="<?php echo $age; ?>">
            
            <label for="gender">Gender:</label>
            <select name="gender" required>
                <option value="Male" <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo ($gender == 'Other') ? 'selected' : ''; ?>>Other</option>
            </select>
            
            <label for="address">Address:</label>
            <textarea name="address"><?php echo $address; ?></textarea>
            
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" name="date_of_birth" value="<?php echo $date_of_birth; ?>">
            
            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>