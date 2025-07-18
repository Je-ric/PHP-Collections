<?php
require_once('config.php');

session_start();

if (!isset($_SESSION['person'])) {
    header("Location: index.php");
    exit;
}

$sql = "SELECT * FROM job_applications WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$id = $_GET['id'];

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
} else {
    echo "Error: " . $stmt->error;
}
if (isset($_POST['cancel'])) {
    header('Location: read.php');
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application</title>
    <style>
        <?php include 'src/form.css'; ?>
    </style>
</head>
<body>

<div class="view-container">
    <h2 class="job">Job Application Details</h2>
        
        <div class="view-outline">
        <p><strong>Name</strong><span><?php echo $row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']; ?></span></p>
        <h6></h6>
        <p><strong>Date of Birth</strong><span><?php echo $row['dateofbirth']; ?></span></p>
        <h6></h6>
        <p><strong>Address</strong><span><?php echo $row['street'] . ', ' . $row['barangay'] . ', ' . $row['city'] . ', ' . $row['province'] . ', ' . $row['zip']; ?></span></p>
        <h6></h6>
        <p><strong>Email</strong><span><?php echo $row['email']; ?></span></p>
        <h6></h6>
        <p><strong>Phone Number</strong><span><?php echo $row['phone']; ?></span></p>
        <h6></h6>
        <p><strong>LinkedIn</strong><span><?php echo $row['linkedin']; ?></span></p>
        <h6></h6>
        <p><strong>Position Applied for</strong><span><?php echo $row['position']; ?></span></p>
        <h6></h6>
        <p><strong>How did you hear about us</strong><span><?php echo $row['how']; ?></span></p>
        <h6></h6>
        <p><strong>Start Date</strong><span><?php echo $row['startdate']; ?></span></p>
        <h6></h6>
        <p><strong>Resume Link</strong><span><?php echo $row['resumelink']; ?></span></p>
        <h6></h6>
        <p><strong>Cover Letter</strong><span><?php echo $row['letter']; ?></span></p>
        <h6></h6>
        <p><strong>Comments</strong><span><?php echo $row['comments']; ?></span></p>
        <h6></h6>
        <p><strong>Signature</strong><span>
        <img src="upload/<?php echo $row['signatures']; ?>" alt="Signature" style="max-width: 100%;"></span></p>
        <form action="" method="post">
            
        <div class="buttons">
                <input class="cancel" type="submit" name="cancel" value="Back">
            </div>
    </div>
</div>

</body>
</html>
