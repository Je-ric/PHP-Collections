<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application</title>
    <link rel="stylesheet" href="src/form.css">
    
    <?php
    require_once('session_config.php');
    require_once('config.php');
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['continue'])) {
        // Gather form data
        $firstname = $_POST['firstname'] ?? '';
        $middlename = $_POST['middlename'] ?? '';
        $lastname = $_POST['lastname'] ?? '';
        $dateofbirth = $_POST['dateofbirth'] ?? '';
        $street = $_POST['street'] ?? '';
        $barangay = $_POST['barangay'] ?? '';
        $city = $_POST['city'] ?? '';
        $province = $_POST['province'] ?? '';
        $zip = $_POST['zip'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $linkedin = $_POST['linkedin'] ?? '';
        $position = $_POST['position'] ?? '';
        $how = $_POST['how'] ?? '';
        $startdate = $_POST['startdate'] ?? '';
        $resumelink = $_POST['resumelink'] ?? '';
        $letter = $_POST['letter'] ?? '';
        $comments = $_POST['comments'] ?? '';

        // File upload handling
        if (isset($_FILES['signatures'])) {
            $fileName = $_FILES['signatures']['name'];
            $fileTmpName = $_FILES['signatures']['tmp_name'];
            $fileSize = $_FILES['signatures']['size'];
            $fileError = $_FILES['signatures']['error'];

            if ($fileError === UPLOAD_ERR_OK) {
                $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                $fileNameNew = uniqid('', true) . '.' . strtolower($fileExt);
                $upload_dir = 'upload/';

                // Move uploaded file
                if (move_uploaded_file($fileTmpName, $upload_dir . $fileNameNew)) {
                    // Prepare SQL insert statement
                    $sql = "INSERT INTO job_applications (
                        firstname, middlename, lastname, dateofbirth, street, barangay, 
                        city, province, zip, email, phone, linkedin, position, 
                        how, startdate, resumelink, letter, signatures, comments
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param(
                            "sssssssssssssssssss", 
                            $firstname, $middlename, $lastname, $dateofbirth, $street, 
                            $barangay, $city, $province, $zip, $email, $phone, 
                            $linkedin, $position, $how, $startdate, $resumelink, 
                            $letter, $fileNameNew, $comments
                        );

                        // Execute and check for success
                        if ($stmt->execute()) {
                            echo "<script>alert('Application submitted successfully!');</script>";
                            header("Location: read.php");
                            exit();
                        } else {
                            echo "Error executing query: " . $stmt->error;
                        }
                    } else {
                        echo "Error preparing statement: " . $conn->error;
                    }
                } else {
                    echo "Error moving uploaded file.";
                }
            } else {
                echo "Error uploading file: " . $fileError;
            }
        }
    }

    $conn->close();
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
        <div class="container">
            <div class="outline"> 
                <h1>Job Application Form</h1>
                <p>Please fill out the fields below to help us determine if you are a good fit for any 
                    of our open positions. Please try to answer as thoroughly and honestly as possible.</p>
                
                <h3>Full Name</h3>
                <div class="side-by-side">
                    <div class="text-box">
                        <input type="text" name="firstname" placeholder="First Name" pattern="[A-Za-z ]+" title="Please enter letters only" required>
                    </div>
                    <div class="text-box">
                        <input type="text" name="middlename" placeholder="Middle Name" pattern="[A-Za-z ]+" title="Please enter letters only" required>
                    </div>
                    <div class="text-box">
                        <input type="text" name="lastname" placeholder="Last Name" pattern="[A-Za-z ]+" title="Please enter letters only" required>
                    </div>
                </div>

                <h3>Date of Birth</h3>
                <div class="text-box">
                    <input type="date" name="dateofbirth" required>
                </div>

                <h3>Current Address</h3>
                <div class="side-by-side">
                    <div class="text-box">
                        <select name="province" onchange="updateCities(this.value)" required>
                            <option value="">Select Province/State</option>
                            <option value="Bulacan">Bulacan</option>
                            <option value="Nueva Ecija">Nueva Ecija</option>
                            <option value="Pampanga">Pampanga</option>
                        </select>
                    </div>
                    <div class="text-box">
                        <select name="city" id="citySelect" onchange="updateBrgy(this.value)" required>
                            <option value="">Select City</option>
                        </select>
                    </div>
                    <div class="text-box">
                        <select name="barangay" id="brgySelect" required>
                            <option value="">Select Barangay</option>
                        </select>
                    </div>
                </div>
                <div class="side-by-side">
                    <div class="text-box">
                        <input type="text" name="street" placeholder="Street Address" required>
                    </div>
                    <div class="text-box">
                        <input type="text" name="zip" placeholder="Postal/Zip Code" pattern="\d+" title="Please enter only numbers" required>
                    </div>
                </div>

                <div class="side-by-side">
                    <div class="single-side">
                        <h3>Email</h3>
                        <div class="text-box">
                            <input type="email" name="email" placeholder="ex. myname@example.com" required>
                        </div>
                    </div>
                    <div class="single-side">
                        <h3>Phone</h3>
                        <div class="text-box">
                            <input type="tel" name="phone" placeholder="Please include area code" pattern="[0-9]{11}" title="Please enter 11-digit numbers only" required>
                        </div>
                    </div>
                </div>

                <h3>LinkedIn</h3>
                <div class="text-box">
                    <input type="url" name="linkedin" placeholder="LinkedIn URL" pattern="^(https?:\/\/)?([a-z0-9-]+\.)?[a-z0-9-]+\.[a-z]{2,}(\/.*)?$" title="Please enter a valid LinkedIn URL" required>
                </div>

                <div class="side-by-side">
                    <div class="single-side">
                        <h3>Positions You Wish to Apply to</h3>
                        <div class="text-box">
                            <select name="position" required>
                                <option disabled selected>Please select</option>
                                <option value="Job 1">Job 1</option>
                                <option value="Job 2">Job 2</option>
                            </select>
                        </div>
                    </div>
                    <div class="single-side">
                        <h3>How Did You Hear About Us</h3>
                        <div class="text-box">
                            <select name="how" required>
                                <option disabled selected>Please select</option>
                                <option value="Social Media">Social Media</option>
                                <option value="Newspaper">Newspaper</option>
                            </select>
                        </div>
                    </div>
                </div>

                <h3>Available Start Date</h3>
                <div class="text-box">
                    <input type="date" name="startdate" required>
                </div>

                <h3>Resume Link</h3>
                <div class="text-box">
                    <input type="url" name="resumelink" placeholder="Link to your CV" pattern="^(https?:\/\/)?([a-z0-9-]+\.)?[a-z0-9-]+\.[a-z]{2,}(\/.*)?$" title="Please enter a valid URL" required>
                </div>

                <h3>Cover Letter</h3>
                <div class="text-box">
                    <textarea name="letter" placeholder="Copy and paste your entire cover letter here" required></textarea>
                </div>

                <h3>Signature</h3>
                <div class="text-box">
                    <input type="file" name="signatures" accept=".jpg, .jpeg, .png, .gif" required>
                </div>

                <h3>Additional Comments</h3>
                <div class="text-box">
                    <textarea name="comments" placeholder="Write any additional comments here" cols="30" rows="10" required></textarea>
                </div>

                <div class="buttons">
                    <input class="submit" type="submit" name="continue" value="Submit">
                    <input class="cancel" type="button" value="Cancel" onclick="window.location.href='read.php';">
                </div>
            </div>
        </div>
    </form>

    <script src="/PHP-Projects/Job-Application-Form/src/address.js"></script>

</body>
</html>
