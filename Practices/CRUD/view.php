<?php
require_once('session_config.php');
require_once('config.php');
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit; 
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM person WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>User Information</title>
            <style>
                /* Include CSS styles */
                <?php include 'src/table.css'; ?>
            </style>
        </head>
        <body class="table-body">
            <!-- Display user information -->
            <div class="user-info">
                <h2>User Information</h2>
                <p><strong>Name:</strong> <?php echo $row['firstname'] . " " . $row['middlename'] . " " . $row['lastname']; ?></p>
                <p><strong>Age:</strong> <?php echo $row['age']; ?></p>
                <p><strong>Gender:</strong> <?php echo $row['gender']; ?></p>
                <p><strong>Birthdate:</strong> <?php echo $row['birthdate']; ?></p>
            </div>
            <div class="address-info">
                <h2>Address & Contact Info</h2>
                <p><strong>Street:</strong> <?php echo $row['street']; ?></p>
                <p><strong>Barangay:</strong> <?php echo $row['barangay']; ?></p>
                <p><strong>City:</strong> <?php echo $row['city']; ?></p>
                <p><strong>Province:</strong> <?php echo $row['province']; ?></p>
                <p><strong>Phone:</strong> <?php echo $row['phone']; ?></p>
                <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
            </div>
            <div class="signature">
                <h2>Signature</h2>
                <img src="upload/<?php echo $row['signature']; ?>" width="100" height="100" alt="Signature">
            </div>
            <div class="resume-info">
                <h2>Resume Link</h2>
                <p><?php echo $row['resumelink']; ?></p>
            </div>
            <div class="comment">
                <h2>Comment</h2>
                <p><?php echo $row['comment']; ?></p>
            </div>
            <div class="favorite-color">
                <h2>Favorite Color</h2>
                <p><?php echo $row['favorite_color']; ?></p>
            </div>
            <div class="buttons">
                <a href="read.php"><input type="submit" value="Back"></a>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "No application found with ID: " . $id;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request";
}
?>
