<?php 
include "config.php";

if (isset($_POST['update'])) {
    
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $dateofbirth = $_POST['dateofbirth'];
    $street = $_POST['street'];
    $addressline = $_POST['addressline'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $zip = $_POST['zip'];
    $email = $_POST['email'];
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
    $id = $_POST['id']; 

    
    $sql = "UPDATE `volunteer_applications` SET `firstname`='$firstname', `lastname`='$lastname', `dateofbirth`='$dateofbirth', `street`='$street', `addressline`='$addressline', `city`='$city', `province`='$province', `zip`='$zip', `email`='$email', `gender`='$gender', `occupation`='$occupation', `details`='$details', `emergencycontactname1`='$emergencycontactname1', `emergencycontactphone1`='$emergencycontactphone1', `emergencycontactrelationship1`='$emergencycontactrelationship1', `emergencycontactname2`='$emergencycontactname2', `emergencycontactphone2`='$emergencycontactphone2', `emergencycontactrelationship2`='$emergencycontactrelationship2', `reasontovolunteer`='$reasontovolunteer', `hobbies`='$hobbies', `interests`='$interests', `volunteeredbefore`='$volunteeredbefore', `how`='$how', `workday`='$workday' WHERE `id`='$id'"; 
    $result = $conn->query($sql); 

    if ($result == TRUE) {
        header('Location: view.php');
        exit;
    } else {
        echo "Error:" . $sql . "<br>" . $conn->error;
    }
} 

if (isset($_GET['id'])) {
    $id = $_GET['id']; 
    $sql = "SELECT * FROM `volunteer_applications` WHERE `id`='$id'";
    $result = $conn->query($sql); 

    if ($result->num_rows > 0) {        
        while ($row = $result->fetch_assoc()) {
            
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $dateofbirth = $row['dateofbirth'];
            $street = $row['street'];
            $addressline = $row['addressline'];
            $city = $row['city'];
            $province = $row['province'];
            $zip = $row['zip'];
            $email = $row['email'];
            $gender = $row['gender'];
            $occupation = $row['occupation'];
            $details = $row['details'];
            $emergencycontactname1 = $row['emergencycontactname1'];
            $emergencycontactphone1 = $row['emergencycontactphone1'];
            $emergencycontactrelationship1 = $row['emergencycontactrelationship1'];
            $emergencycontactname2 = $row['emergencycontactname2'];
            $emergencycontactphone2 = $row['emergencycontactphone2'];
            $emergencycontactrelationship2 = $row['emergencycontactrelationship2'];
            $reasontovolunteer = $row['reasontovolunteer'];
            $hobbies = $row['hobbies'];
            $interests = $row['interests'];
            $volunteeredbefore = $row['volunteeredbefore'];
            $how = $row['how'];
            $workday = $row['workday'];
        } 
?>
    <link rel="stylesheet" href="style.css">
    <form action="" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="container">
        <h1>Update Volunteer Application</h1>
        
        <h3>Full Name</h3>
        <div class="fullname">
        <div class="text-box">
            <input type="text" name="firstname" placeholder="First Name" value="<?php echo $firstname; ?>" required>
        </div>
        <div class="text-box">
            <input type="text" name="lastname" placeholder="Last Name" value="<?php echo $lastname; ?>" required>
        </div>
        </div>

        <h3>Current Address</h3>

        <div class="text-box">
            <input type="text" name="street" placeholder="Street Address" value="<?php echo $street; ?>" required>
        </div>
        <div class="address">
        <div class="text-box">
            <input type="text" name="addressline" placeholder="Street Address Line Two" value="<?php echo $addressline; ?>">
        </div>
        <div class="text-box">
            <input type="text" name="city" placeholder="City" value="<?php echo $city; ?>" required>
        </div>
        </div>
        <div class="address">
        <div class="text-box">
            <input type="text" name="province" placeholder="State/Province" value="<?php echo $province; ?>" required>
        </div>
        <div class="text-box">
            <input type="text" name="zip" placeholder="Postal/Zip Code" value="<?php echo $zip; ?>" required>
        </div>
        </div>
        <h3>Email Address</h3>
        <div class="text-box">
            <input type="email" name="email" placeholder="ex. myname@example.com" value="<?php echo $email; ?>" required>
        </div>

        <h3>Date of Birth:</h3>
        <div class="text-box">
            <input type="date" name="dateofbirth" value="<?php echo $dateofbirth; ?>" required>
        </div>

        <h3>Gender</h3>
        <div class="radio-buttons">
        <div class="text-box">
            <input type="radio" name="gender" value="Male" id="male" <?php if($gender == "Male") echo "checked"; ?>>
            <label for="male">Male</label>
        </div>
        <div class="text-box">
            <input type="radio" name="gender" value="Female" id="female" <?php if($gender == "Female") echo "checked"; ?>>
            <label for="female">Female</label>
        </div>
        <div class="text-box">
            <input type="radio" name="gender" value="N/A" id="na" <?php if($gender == "N/A") echo "checked"; ?>>
            <label for="na">N/A</label>
        </div>
        </div>

        <h3>Current Occupation</h3>
        <div class="radio-buttons">
        <div class="text-box">
            <input type="radio" id="student" name="occupation" value="Student" <?php if($occupation == "Student") echo "checked"; ?>>
            <label for="student">Student</label>
        </div>
        <div class="text-box">
            <input type="radio" id="fulltime" name="occupation" value="Full Time" <?php if($occupation == "Full Time") echo "checked"; ?>>
            <label for="fulltime">Full Time</label>
        </div>
        <div class="text-box">
            <input type="radio" id="parttime" name="occupation" value="Part Time" <?php if($occupation == "Part Time") echo "checked"; ?>>
            <label for="parttime">Part Time</label>
        </div>
        <div class="text-box">
            <input type="radio" id="other" name="occupation" value="Other" <?php if($occupation == "Other") echo "checked"; ?>>
            <label for="other">Other</label>
        </div>
        </div>

        <h3>Details of Occupation</h3>
        <div class="text-box">
            <input type="text" name="details" placeholder="Please provide relevant details about your current occupation" value="<?php echo $details; ?>">
        </div>

        <h3>Emergency Contacts</h3>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Relationship</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Contact</td>
                    <td><div class="text-box"><input type="text" name="emergencycontactname1" placeholder="Enter Name" value="<?php echo $emergencycontactname1; ?>" required></div></td>
                    <td><div class="text-box"><input type="text" name="emergencycontactphone1" placeholder="Enter Phone Number" value="<?php echo $emergencycontactphone1; ?>" required></div></td>
                    <td><div class="text-box"><input type="text" name="emergencycontactrelationship1" placeholder="Enter Relationship" value="<?php echo $emergencycontactrelationship1; ?>" required></div></td>
                </tr>
                <tr>
                    <td>Contact</td>
                    <td><div class="text-box"><input type="text" name="emergencycontactname2" placeholder="Enter Name" value="<?php echo $emergencycontactname2; ?>"></div></td>
                    <td><div class="text-box"><input type="text" name="emergencycontactphone2" placeholder="Enter Phone Number" value="<?php echo $emergencycontactphone2; ?>"></div></td>
                    <td><div class="text-box"><input type="text" name="emergencycontactrelationship2" placeholder="Enter Relationship" value="<?php echo $emergencycontactrelationship2; ?>"></div></td>
                </tr>
            </tbody>
        </table>

        <h3>Why do you want to become a volunteer?</h3>
        <div class="text-box">
            <input type="text" name="reasontovolunteer" placeholder="Please tell us why you want to become a volunteer" value="<?php echo $reasontovolunteer; ?>" required>
        </div>

        <h3>Please tell us your hobbies and interests briefly</h3>
        <div class="text-box">
            <input type="text" name="hobbies" placeholder="Enter Hobbies and Interests" value="<?php echo $hobbies; ?>">
        </div>

        <h3>What are your hobbies and interests?</h3>
        <div class="text-box">
            <input type="text" name="interests" placeholder="Enter Hobbies and Interests" value="<?php echo $interests; ?>">
        </div>

        <h3>Have you ever volunteered before?</h3>
        <div class="text-box">
            <input type="text"  name="volunteeredbefore" placeholder="Please tell us about any of your past volunteering experiences" value="<?php echo $volunteeredbefore; ?>">
        </div>

        <h3>Where did you hear about us?</h3>
        <div class="text-box">
            <select name="how" required>
                <option value="Social Media" <?php if($how == "Social Media") echo "selected"; ?>>Social Media</option>
                <option value="Website" <?php if($how == "Website") echo "selected"; ?>>Website</option>
                <option value="Friend/Family" <?php if($how == "Friend/Family") echo "selected"; ?>>Friend/Family</option>
                <option value="Event" <?php if($how == "Event") echo "selected"; ?>>Event</option>
                <option value="Other" <?php if($how == "Other") echo "selected"; ?>>Other</option>
            </select>
        </div>

        <h3>Which day can you work?</h3>
        <div class="text-box">
            <select name="workday" required> <!--multiple -->
                <option value="Monday" <?php if($workday == "Monday") echo "selected"; ?>>Monday</option>
                <option value="Tuesday" <?php if($workday == "Tuesday") echo "selected"; ?>>Tuesday</option>
                <option value="Wednesday" <?php if($workday == "Wednesday") echo "selected"; ?>>Wednesday</option>
                <option value="Thursday" <?php if($workday == "Thursday") echo "selected"; ?>>Thursday</option>
                <option value="Friday" <?php if($workday == "Friday") echo "selected"; ?>>Friday</option>
                <option value="Saturday" <?php if($workday == "Saturday") echo "selected"; ?>>Saturday</option>
                <option value="Sunday" <?php if($workday == "Sunday") echo "selected"; ?>>Sunday</option>
            </select>
        </div>

        <h3>Candidate Signature:</h3>
        <div class="text-box">
            <input type="file" name="signature" accept="image/*">
        </div>

        <div class="update-buttons">
        <input type="submit" name="update" value="Update">
        </div>
        </div>
    </form>



<?php
    } else { 
        header('Location: view.php'); 
    } 
}
?>
