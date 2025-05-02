<?php

include ("config.php");

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$street = $_POST['street'];
$addressline = $_POST['addressline'];
$city = $_POST['city'];
$province = $_POST['province'];
$zip = $_POST['zip'];
$email = $_POST['email'];
$dateofbirth = $_POST['dateofbirth'];
$gender = $_POST['gender'];
$occupation = $_POST['occupation'];
$details = $_POST['details'];
$emergencycontactname1 = $_POST['emergencycontactname1'];
$emergencycontactphone1 = $_POST['emergencycontactphone1'];
$emergencycontactrelationship1 = $_POST['emergencycontactrelationship1'];
$emergencycontactname2 = $_POST['emergencycontactname2'];
$emergencycontactphone2 = $_POST['emergencycontactphone2'];
$emergencycontactrelationship2 = $_POST['emergencycontactrelationship2'];
$reasontovolunteer = $_POST['reasontovolunteer'];
$hobbies = $_POST['hobbies'];
$interests = $_POST['interests'];
$volunteeredbefore = $_POST['volunteeredbefore'];
$how = $_POST['how'];
$workday = $_POST['workday'];

$stmt = $conn->prepare("INSERT INTO volunteer_applications (firstname, lastname, street, addressline, city, province, zip, email, dateofbirth, gender, occupation, details, emergencycontactname1, emergencycontactphone1, emergencycontactrelationship1, emergencycontactname2, emergencycontactphone2, emergencycontactrelationship2, reasontovolunteer, hobbies, interests, volunteeredbefore, how, workday) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssssssssssssssssss", $firstname, $lastname, $street, $addressline, $city, $province, $zip, $email, $dateofbirth, $gender, $occupation, $details, $emergencycontactname1, $emergencycontactphone1, $emergencycontactrelationship1, $emergencycontactname2, $emergencycontactphone2, $emergencycontactrelationship2, $reasontovolunteer, $hobbies, $interests, $volunteeredbefore, $how, $workday);

if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
