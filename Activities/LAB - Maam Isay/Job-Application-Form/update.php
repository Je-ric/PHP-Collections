<?php
require_once('session_config.php');
session_start();

include("config.php");

if (!isset($_SESSION['person'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $existingSignature = $_POST['existing_signature'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $dateofbirth = $_POST['dateofbirth'];
    $street = $_POST['street'];
    $barangay = $_POST['barangay'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $zip = $_POST['zip'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $linkedin = $_POST['linkedin'];
    $position = $_POST['position'];
    $how = $_POST['how'];
    $startdate = $_POST['startdate'];
    $resumelink = $_POST['resumelink'];
    $letter = $_POST['letter'];
    $comments = $_POST['comments'];

    if (isset($_FILES['signatures']) && $_FILES['signatures']['error'] === UPLOAD_ERR_OK) {
        
        $fileName = $_FILES['signatures']['name'];
        $fileTmpName = $_FILES['signatures']['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $fileNameNew = uniqid('', true) . '.' . $fileExt;
        $upload_dir = 'upload/';
        
        if (move_uploaded_file($fileTmpName, $upload_dir . $fileNameNew)) {
            $signature = $fileNameNew;
        } else {
            $_SESSION['error_message'] = "Error uploading file";
            header('Location: read.php');
            exit;
        }
    } else {
        $signature = $existingSignature;
    }
    
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $dateofbirth = $_POST['dateofbirth'];
    $street = $_POST['street'];
    $barangay = $_POST['barangay'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $zip = $_POST['zip'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $linkedin = $_POST['linkedin'];
    $position = $_POST['position'];
    $how = $_POST['how'];
    $startdate = $_POST['startdate'];
    $resumelink = $_POST['resumelink'];
    $letter = $_POST['letter'];
    $comments = $_POST['comments'];

    $sql = "UPDATE `job_applications` SET 
            `signatures`='$signature', 
            `firstname`='$firstname', 
            `middlename`='$middlename', 
            `lastname`='$lastname', 
            `dateofbirth`='$dateofbirth', 
            `street`='$street', 
            `barangay`='$barangay', 
            `city`='$city', 
            `province`='$province', 
            `zip`='$zip', 
            `email`='$email', 
            `phone`='$phone', 
            `linkedin`='$linkedin', 
            `position`='$position', 
            `how`='$how', 
            `startdate`='$startdate', 
            `resumelink`='$resumelink', 
            `letter`='$letter', 
            `comments`='$comments' 
            WHERE `id`='$id'";
    $result = $conn->query($sql);

    if ($result === TRUE) {
        $_SESSION['success_message'] = "Record updated successfully";
        header('Location: read.php');
        exit;
    } else {
        $_SESSION['error_message'] = "Error updating record: " . $conn->error;
        header('Location: read.php');
        exit;
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM `job_applications` WHERE `id`='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $firstname = $row['firstname'];
                $middlename = $row['middlename'];
                $lastname = $row['lastname'];
                $dateofbirth = $row['dateofbirth'];
                $street = $row['street'];
                $barangay = $row['barangay'];
                $city = $row['city'];
                $province = $row['province'];
                $zip = $row['zip'];
                $email = $row['email'];
                $phone = $row['phone'];
                $linkedin = $row['linkedin'];
                $position = $row['position'];
                $how = $row['how'];
                $startdate = $row['startdate'];
                $resumelink = $row['resumelink'];
                $letter = $row['letter'];
                $comments = $row['comments'];
        $existingSignature = $row['signatures'];
    } else {
        $_SESSION['error_message'] = "Record not found";
        header('Location: read.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
    <style>
        <?php include 'src/form.css'; ?>
        </style>
</head>
<body>
            <form action="" method="post" enctype="multipart/form-data">
                
<div class="container">
    <div class="outline">
            <h1>Update Job Application Form</h1>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                
                <h3>Full Name</h3>
                <div class="side-by-side">
                    <div class="text-box">
                        <input type="text" name="firstname" value="<?php echo $firstname; ?>"  pattern="[A-Za-z ]+" title="Please enter letters only" required>
                    </div>
                    <div class="text-box">
                        <input type="text" name="middlename" value="<?php echo $middlename; ?>"  pattern="[A-Za-z ]+" title="Please enter letters only" required>
                    </div>
                    <div class="text-box">
                        <input type="text" name="lastname" value="<?php echo $lastname; ?>"  pattern="[A-Za-z ]+" title="Please enter letters only" required>
                    </div>
                </div>

                <h3>Date of Birth</h3>
                <div class="text-box">
                    <input type="date" name="dateofbirth" value="<?php echo $dateofbirth; ?>" required>
                </div>

<h3>Current Address</h3>
<div class="side-by-side">
    <div class="text-box">
    <select name="province" id="provinceSelect" onchange="updateCities(this.value)" required>

        <option value="">Select Province/State</option>
                <option value="Ilocos Norte" <?php if(isset($province) && $province == "Ilocos Norte") echo "selected"; ?>>Ilocos Norte</option>
                <option value="Ilocos Sur" <?php if(isset($province) && $province == "Ilocos Sur") echo "selected"; ?>>Ilocos Sur</option>
                <option value="La Union" <?php if(isset($province) && $province == "La Union") echo "selected"; ?>>La Union</option>
                <option value="Pangasinan" <?php if(isset($province) && $province == "Pangasinan") echo "selected"; ?>>Pangasinan</option>
                <option value="Metro Manila" <?php if(isset($province) && $province == "Metro Manila") echo "selected"; ?>>Metro Manila</option>
                <option value="Cebu" <?php if(isset($province) && $province == "Cebu") echo "selected"; ?>>Cebu</option>
                <option value="Tawi-Tawi" <?php if(isset($province) && $province == "Tawi-Tawi") echo "selected"; ?>>Tawi-Tawi</option>
                <option value="Batanes" <?php if(isset($province) && $province == "Batanes") echo "selected"; ?>>Batanes</option>
                <option value="Cagayan" <?php if(isset($province) && $province == "Cagayan") echo "selected"; ?>>Cagayan</option>
                <option value="Isabela" <?php if(isset($province) && $province == "Isabela") echo "selected"; ?>>Isabela</option>
                <option value="Nueva Vizcaya" <?php if(isset($province) && $province == "Nueva Vizcaya") echo "selected"; ?>>Nueva Vizcaya</option>
                <option value="Quirino" <?php if(isset($province) && $province == "Quirino") echo "selected"; ?>>Quirino</option>
                <option value="Aurora" <?php if(isset($province) && $province == "Aurora") echo "selected"; ?>>Aurora</option>
                <option value="Bataan" <?php if(isset($province) && $province == "Bataan") echo "selected"; ?>>Bataan</option>
                <option value="Bulacan" <?php if(isset($province) && $province == "Bulacan") echo "selected"; ?>>Bulacan</option>
                <option value="Nueva Ecija" <?php if(isset($province) && $province == "Nueva Ecija") echo "selected"; ?>>Nueva Ecija</option>
                <option value="Pampanga" <?php if(isset($province) && $province == "Pampanga") echo "selected"; ?>>Pampanga</option>
                <option value="Tarlac" <?php if(isset($province) && $province == "Tarlac") echo "selected"; ?>>Tarlac</option>
                <option value="Zambales" <?php if(isset($province) && $province == "Zambales") echo "selected"; ?>>Zambales</option>
                <option value="Batangas" <?php if(isset($province) && $province == "Batangas") echo "selected"; ?>>Batangas</option>
                <option value="Cavite" <?php if(isset($province) && $province == "Cavite") echo "selected"; ?>>Cavite</option>
                <option value="Laguna" <?php if(isset($province) && $province == "Laguna") echo "selected"; ?>>Laguna</option>
                <option value="Quezon" <?php if(isset($province) && $province == "Quezon") echo "selected"; ?>>Quezon</option>
                <option value="Rizal" <?php if(isset($province) && $province == "Rizal") echo "selected"; ?>>Rizal</option>
                <option value="Marinduque" <?php if(isset($province) && $province == "Marinduque") echo "selected"; ?>>Marinduque</option>
                <option value="Occidental Mindoro" <?php if(isset($province) && $province == "Occidental Mindoro") echo "selected"; ?>>Occidental Mindoro</option>
                <option value="Oriental Mindoro" <?php if(isset($province) && $province == "Oriental Mindoro") echo "selected"; ?>>Oriental Mindoro</option>
                <option value="Palawan" <?php if(isset($province) && $province == "Palawan") echo "selected"; ?>>Palawan</option>
                <option value="Romblon" <?php if(isset($province) && $province == "Romblon") echo "selected"; ?>>Romblon</option>
                <option value="Albay" <?php if(isset($province) && $province == "Albay") echo "selected"; ?>>Albay</option>
                <option value="Camarines Norte" <?php if(isset($province) && $province == "Camarines Norte") echo "selected"; ?>>Camarines Norte</option>
                <option value="Camarines Sur" <?php if(isset($province) && $province == "Camarines Sur") echo "selected"; ?>>Camarines Sur</option>
                <option value="Catanduanes" <?php if(isset($province) && $province == "Catanduanes") echo "selected"; ?>>Catanduanes</option>
                <option value="Masbate" <?php if(isset($province) && $province == "Masbate") echo "selected"; ?>>Masbate</option>
                <option value="Sorsogon" <?php if(isset($province) && $province == "Sorsogon") echo "selected"; ?>>Sorsogon</option>
                <option value="Aklan" <?php if(isset($province) && $province == "Aklan") echo "selected"; ?>>Aklan</option>
                <option value="Antique" <?php if(isset($province) && $province == "Antique") echo "selected"; ?>>Antique</option>
                <option value="Capiz" <?php if(isset($province) && $province == "Capiz") echo "selected"; ?>>Capiz</option>
                <option value="Guimaras" <?php if(isset($province) && $province == "Guimaras") echo "selected"; ?>>Guimaras</option>
                <option value="Iloilo" <?php if(isset($province) && $province == "Iloilo") echo "selected"; ?>>Iloilo</option>
                <option value="Negros Occidental" <?php if(isset($province) && $province == "Negros Occidental") echo "selected"; ?>>Negros Occidental</option>
                <option value="Bohol" <?php if(isset($province) && $province == "Bohol") echo "selected"; ?>>Bohol</option>
                <option value="Cebu" <?php if(isset($province) && $province == "Cebu") echo "selected"; ?>>Cebu</option>
                <option value="Negros Oriental" <?php if(isset($province) && $province == "Negros Oriental") echo "selected"; ?>>Negros Oriental</option>
                <option value="Siquijor" <?php if(isset($province) && $province == "Siquijor") echo "selected"; ?>>Siquijor</option>
                <option value="Biliran" <?php if(isset($province) && $province == "Biliran") echo "selected"; ?>>Biliran</option>
                <option value="Eastern Samar" <?php if(isset($province) && $province == "Eastern Samar") echo "selected"; ?>>Eastern Samar</option>
                <option value="Leyte" <?php if(isset($province) && $province == "Leyte") echo "selected"; ?>>Leyte</option>
                <option value="Northern Samar" <?php if(isset($province) && $province == "Northern Samar") echo "selected"; ?>>Northern Samar</option>
                <option value="Samar (Western Samar)" <?php if(isset($province) && $province == "Samar (Western Samar)") echo "selected"; ?>>Samar (Western Samar)</option>
                <option value="Southern Leyte" <?php if(isset($province) && $province == "Southern Leyte") echo "selected"; ?>>Southern Leyte</option>
                <option value="Zamboanga del Norte" <?php if(isset($province) && $province == "Zamboanga del Norte") echo "selected"; ?>>Zamboanga del Norte</option>
                <option value="Zamboanga del Sur" <?php if(isset($province) && $province == "Zamboanga del Sur") echo "selected"; ?>>Zamboanga del Sur</option>
                <option value="Zamboanga Sibugay" <?php if(isset($province) && $province == "Zamboanga Sibugay") echo "selected"; ?>>Zamboanga Sibugay</option>
                <option value="Bukidnon" <?php if(isset($province) && $province == "Bukidnon") echo "selected"; ?>>Bukidnon</option>
                <option value="Camiguin" <?php if(isset($province) && $province == "Camiguin") echo "selected"; ?>>Camiguin</option>
                <option value="Lanao del Norte" <?php if(isset($province) && $province == "Lanao del Norte") echo "selected"; ?>>Lanao del Norte</option>
                <option value="Misamis Occidental" <?php if(isset($province) && $province == "Misamis Occidental") echo "selected"; ?>>Misamis Occidental</option>
                <option value="Misamis Oriental" <?php if(isset($province) && $province == "Misamis Oriental") echo "selected"; ?>>Misamis Oriental</option>
                <option value="Compostela Valley (Davao de Oro)" <?php if(isset($province) && $province == "Compostela Valley (Davao de Oro)") echo "selected"; ?>>Compostela Valley (Davao de Oro)</option>
                <option value="Davao del Norte" <?php if(isset($province) && $province == "Davao del Norte") echo "selected"; ?>>Davao del Norte</option>
                <option value="Davao del Sur" <?php if(isset($province) && $province == "Davao del Sur") echo "selected"; ?>>Davao del Sur</option>
                <option value="Davao Occidental" <?php if(isset($province) && $province == "Davao Occidental") echo "selected"; ?>>Davao Occidental</option>
                <option value="Davao Oriental" <?php if(isset($province) && $province == "Davao Oriental") echo "selected"; ?>>Davao Oriental</option>
                <option value="Cotabato (North Cotabato)" <?php if(isset($province) && $province == "Cotabato (North Cotabato)") echo "selected"; ?>>Cotabato (North Cotabato)</option>
                <option value="Cotabato City" <?php if(isset($province) && $province == "Cotabato City") echo "selected"; ?>>Cotabato City</option>
                <option value="Sarangani" <?php if(isset($province) && $province == "Sarangani") echo "selected"; ?>>Sarangani</option>
                <option value="South Cotabato" <?php if(isset($province) && $province == "South Cotabato") echo "selected"; ?>>South Cotabato</option>
                <option value="Sultan Kudarat" <?php if(isset($province) && $province == "Sultan Kudarat") echo "selected"; ?>>Sultan Kudarat</option>
                <option value="Agusan del Norte" <?php if(isset($province) && $province == "Agusan del Norte") echo "selected"; ?>>Agusan del Norte</option>
                <option value="Agusan del Sur" <?php if(isset($province) && $province == "Agusan del Sur") echo "selected"; ?>>Agusan del Sur</option>
                <option value="Dinagat Islands" <?php if(isset($province) && $province == "Dinagat Islands") echo "selected"; ?>>Dinagat Islands</option>
                <option value="Surigao del Norte" <?php if(isset($province) && $province == "Surigao del Norte") echo "selected"; ?>>Surigao del Norte</option>
                <option value="Surigao del Sur" <?php if(isset($province) && $province == "Surigao del Sur") echo "selected"; ?>>Surigao del Sur</option>
                <option value="Basilan" <?php if(isset($province) && $province == "Basilan") echo "selected"; ?>>Basilan</option>
                <option value="Lanao del Sur" <?php if(isset($province) && $province == "Lanao del Sur") echo "selected"; ?>>Lanao del Sur</option>
                <option value="Maguindanao" <?php if(isset($province) && $province == "Maguindanao") echo "selected"; ?>>Maguindanao</option>
                <option value="Sulu" <?php if(isset($province) && $province == "Sulu") echo "selected"; ?>>Sulu</option>
                <option value="Tawi-Tawi" <?php if(isset($province) && $province == "Tawi-Tawi") echo "selected"; ?>>Tawi-Tawi</option>
                <option value="Abra" <?php if(isset($province) && $province == "Abra") echo "selected"; ?>>Abra</option>
                <option value="Apayao" <?php if(isset($province) && $province == "Apayao") echo "selected"; ?>>Apayao</option>
                <option value="Benguet" <?php if(isset($province) && $province == "Benguet") echo "selected"; ?>>Benguet</option>
                <option value="Ifugao" <?php if(isset($province) && $province == "Ifugao") echo "selected"; ?>>Ifugao</option>
                <option value="Kalinga" <?php if(isset($province) && $province == "Kalinga") echo "selected"; ?>>Kalinga</option>
                <option value="Mountain Province" <?php if(isset($province) && $province == "Mountain Province") echo "selected"; ?>>Mountain Province</option>

        </select>
    </div>
    <div class="text-box">
        <select name="city" id="citySelect" onchange="updateBrgy(this.value)" required>
            <option value="">Select City</option>
            <?php if(isset($city)): ?>
                <option value="<?php echo htmlspecialchars($city); ?>" selected><?php echo htmlspecialchars($city); ?></option>
            <?php endif; ?>
        </select>
    </div>
    <div class="text-box">
        <select name="barangay" id="brgySelect" required>
            <option value="">Select Barangay</option>
            <?php if(isset($barangay)): ?>
                <option value="<?php echo htmlspecialchars($barangay); ?>" selected><?php echo htmlspecialchars($barangay); ?></option>
            <?php endif; ?>
        </select>
    </div>
</div>
<div class="side-by-side">
<div class="text-box">
        <input type="text" placeholder="Street Address" name="street" value="<?php echo $street; ?>" required>
    </div>
    <div class="text-box">
        <input type="text" placeholder="Postal/Zip Code" name="zip" value="<?php echo $zip; ?>" pattern="\d+" title="Please enter only numbers" required>
    </div>
</div>

<script src="/PHP-Projects/Job-Application-Form/src/update-address.js"></script>
    

                <div class="side-by-side">
                    <div class="single-side">
                        <h3>Email</h3>
                        <div class="text-box">
                            <input type="email" name="email" value="<?php echo $email; ?>" required>
                        </div>
                    </div> 
                    <div class="single-side">
                        <h3>Phone</h3>
                        <div class="text-box">
                            <input type="tel" name="phone" value="<?php echo $phone; ?>" pattern="[0-9]{11}" title="Please enter 11-digit numbers only"  required>
                        </div>
                    </div>
                </div> 

                <h3>LinkedIn</h3>
                <div class="text-box">
                    <input type="url" name="linkedin" value="<?php echo $linkedin; ?>" pattern="^(https?:\/\/)?([a-z0-9-]+\.)?[a-z0-9-]+\.[a-z]{2,}(\/.*)?$" title="Please enter a valid LinkedIn URL" required>
                </div>

                <div class="side-by-side">
                    
                <div class="single-side">
                <h3>Positions You Wish to Apply to</h3>
                <div class="text-box">
                    <select name="position" required>
                        <option disabled>Please select</option>
                        <option value="Job 1" <?php if($position == "Job 1") echo "selected"; ?>>Job 1</option>
                        <option value="Job 2" <?php if($position == "Job 2") echo "selected"; ?>>Job 2</option>
                    </select>
                </div>
                </div>

                <div class="single-side">
                <h3>How Did You Hear About Us</h3>
                <div class="text-box">
                    <select name="how" required>
                        <option disabled>Please select</option>
                        <option value="Social Media" <?php if($how == "Social Media") echo "selected"; ?>>Social Media</option>
                        <option value="Newspaper" <?php if($how == "Newspaper") echo "selected"; ?>>Newspaper</option>
                    </select>
                </div>
                </div>
                </div>

                <h3>Available Start Date</h3>
                <div class="text-box">
                    <input type="date" name="startdate" value="<?php echo $startdate; ?>" required>
                </div>

                <h3>Resume Link</h3>
                <div class="text-box">
                    <input type="url" name="resumelink" value="<?php echo $resumelink; ?>" pattern="^(https?:\/\/)?([a-z0-9-]+\.)?[a-z0-9-]+\.[a-z]{2,}(\/.*)?$" title="Please enter a valid LinkedIn URL" required>
                </div>

                <h3>Cover Letter</h3>
                <div class="text-box">
                    <input type="text" name="letter" value="<?php echo $letter; ?>" required>
                </div>
                

                <div class="side-by-side">
                    <div class="single-side">
                <h3>Existing Signature</h3>
    <div class="text-box">
        <img src="upload/<?php echo $existingSignature; ?>" width="100" height="100" alt="Existing Signature">
    </div>
    </div>

    <div class="single-side">
    <h3>New Signature</h3>
    <div class="text-box">
        <input type="file" name="signatures" accept="image/*">
        <!-- Include a hidden input field to hold the existing signature value -->
        <input type="hidden" name="existing_signature" value="<?php echo $existingSignature; ?>">
    </div>
    </div>
    </div>

                <h3>Additional Comments</h3>
                <div class="text-box">
                    <input type="text" name="comments" value="<?php echo $comments; ?>">
                </div>
                <div class="buttons">
                <input class="submit" type="submit" name="update" value="Update">
                <input class="cancel" type="button" value="Cancel" onclick="window.location.href='read.php';">
                </div>
                
        </div> 
        </div>
    </form>
</body>
</html>
   
     