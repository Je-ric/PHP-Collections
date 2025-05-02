<?php
require_once('session_config.php');
require_once('config.php');

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit; 
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {   
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $street = $_POST['street'];
    $barangay = isset($_POST['barangay']) ? $_POST['barangay'] : ''; 
    $city = $_POST['city'];
    $province = $_POST['province'];
    $signature = $_FILES['signature']['name'];
    $resumelink = $_POST['resumelink'];
    $comment = $_POST['comment'];
    $favorite_color = $_POST['favorite_color'];

    
    $target_dir = "C:\\xampp\\htdocs\\PHP-Practice\\CRUD\\upload\\";
    $target_file = $target_dir . basename($_FILES["signature"]["name"]);

    
    if (move_uploaded_file($_FILES["signature"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO person (firstname, middlename, lastname, 
        age, gender, birthdate, 
        phone, email, 
        street ,barangay, city, province, 
        signature, resumelink, comment, favorite_color) 
                VALUES ('$firstname', '$middlename', '$lastname', 
                '$age', '$gender', '$birthdate', 
                '$phone', '$email', 
                '$street','$barangay', '$city', '$province', 
                '$signature', '$resumelink', '$comment', '$favorite_color')";

        
        if (mysqli_query($conn, $sql)) {
            
            echo "Record created successfully";
            header('Location: read.php'); 
            exit;
        } else {
            
            echo "Error: " . mysqli_error($conn);
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
    <title>User Form</title>
    <style>
        <?php include 'src/form.css'; ?>

        body{
            margin: 0;
            padding: 0;
            background-image: url(/PHP-Practice/CRUD/src/Body.jpg);
            background-size: cover;
            /* background-position: center; */
         }

         .full-form{
            display: flex;
    justify-content: center;
    align-items: center;
    padding-top: 50px;
    padding-bottom: 50px;
         }

.form {
    background: rgba( 62, 62, 62, 0.25 );
    box-shadow: 0 8px 32px 0 rgba( 31, 38, 135, 0.37 );
    backdrop-filter: blur( 2.5px );
    -webkit-backdrop-filter: blur( 2.5px );
    border: 1px solid rgba( 255, 255, 255, 0.18 );
    height: 100%;
    width: 50%;
    /* border: 2px solid black; */
    border-radius: 20px;
    padding: 20px;
    color: white;
}

.title {
    text-align: center;
    padding-bottom: 20px;
    border-bottom: 2px solid red;
}

.side-by-side {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.side-by-side .text-box {
    flex: 1;
}

input::placeholder {
    color: white;
  }

input[type="text"],
    input[type="number"],
    input[type="date"],
    input[type="tel"],
    input[type="email"],
    input[type="url"],
    textarea,
    select {
        background-color: transparent; /* Set background color to transparent */
        border: none; /* Remove default border */
        border-bottom: 1px solid white; /* Add border-bottom */
        color: white; /* Set text color */
        padding: 8px; /* Add padding */
        margin-bottom: 10px; /* Add margin-bottom for spacing between inputs */
        width: 90%; /* Set width to fill container */
    }
    

    input.error,
    textarea.error,
    select.error {
        border-bottom-color: red; /* Change border color for error state */
    }

    /* Add this to style the submit button */
    input[type="submit"],
    input[type="button"] {
        background-color: white; /* Set background color for buttons */
        color: black; /* Set text color for buttons */
        border: none; /* Remove default button border */
        padding: 10px 20px; /* Add padding */
        cursor: pointer; /* Change cursor to pointer on hover */
        border-radius: 5px; /* Add border radius */
        margin-top: 10px; /* Add margin-top for spacing between inputs */
    }

    /* Add this to style the select dropdown */
    select {
        /* background-image: url('data:image/svg+xml;utf8,<svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>'); Add custom dropdown arrow */
        background-repeat: no-repeat; /* Prevent the arrow from repeating */
        background-position: right 8px center; /* Position the arrow */
        appearance: none; /* Remove default appearance */
        -webkit-appearance: none; /* Remove default appearance for Safari */
        -moz-appearance: none; /* Remove default appearance for Firefox */
        padding-right: 25px; /* Add padding for the arrow */
    }
    </style>
</head>
<body>
<div class="full-form">
        <div class="form">
        <form action=" " method="POST" enctype="multipart/form-data" name="myForm" onsubmit="return validateForm()">
                <h1 class="title">User Information Form</h1>
                <!-- Full Name -->
                <h3>Full Name</h3>
                <div class="side-by-side">
                    <div class="text-box">
                        <input type="text" placeholder="First Name" name="firstname" required>
                    </div>
                    <div class="text-box">
                        <input type="text" placeholder="Middle Name" name="middlename" required>
                    </div>
                    <div class="text-box">
                        <input type="text" placeholder="Last Name" name="lastname" required>
                    </div>
                </div>

                <!-- Age, Birthdate, Gender -->
                <div class="side-by-side">
                    <div class="text-box">
                        <h3>Age</h3>
                        <input type="number" placeholder="Age" name="age" required>
                    </div>
                    <div class="text-box">
                        <h3>Birthdate</h3>
                        <input type="date" placeholder="Birthdate" name="birthdate" required>
                    </div>
                </div>

                <div class="side-by-side">
                <div class="text-box">
                        <h3>Gender</h3>
                        <input type="radio" name="gender" value="Male"> Male
                        <input type="radio" name="gender" value="Female"> Female
                    </div>
                    <div class="text-box">
                        <h3>Favorite Color</h3>
                        <select name="favorite_color">
                            <option value="Red">Red</option>
                            <option value="Blue">Blue</option>
                            <option value="Green">Green</option>
                            <option value="Yellow">Yellow</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Phone, Email -->
                <div class="side-by-side">
                    <div class="text-box">
                        <h3>Phone</h3>
                        <input type="tel" placeholder="Phone" name="phone" required>
                    </div>
                    <div class="text-box">
                        <h3>Email</h3>
                        <input type="email" placeholder="Email" name="email" required>
                    </div>
                </div>

                <!-- Province, City -->
                <div class="side-by-side">
                    <div class="text-box">
                        <h3>Province</h3>
                        <select name="province" onchange="updateCities(this.value)">
    <option value="">Select Province/State</option>
    <option value="Metro Manila" <?php if(isset($_POST['state']) && $_POST['state'] == "Metro Manila") echo "selected"; ?>>Metro Manila</option>
    <option value="Abra" <?php if(isset($_POST['state']) && $_POST['state'] == "Abra") echo "selected"; ?>>Abra</option>
    <option value="Apayao" <?php if(isset($_POST['state']) && $_POST['state'] == "Apayao") echo "selected"; ?>>Apayao</option>
    <option value="Benguet" <?php if(isset($_POST['state']) && $_POST['state'] == "Benguet") echo "selected"; ?>>Benguet</option>
    <option value="Ifugao" <?php if(isset($_POST['state']) && $_POST['state'] == "Ifugao") echo "selected"; ?>>Ifugao</option>
    <option value="Kalinga" <?php if(isset($_POST['state']) && $_POST['state'] == "Kalinga") echo "selected"; ?>>Kalinga</option>
    <option value="Mountain Province" <?php if(isset($_POST['state']) && $_POST['state'] == "Mountain Province") echo "selected"; ?>>Mountain Province</option>
    <option value="Ilocos Norte" <?php if(isset($_POST['state']) && $_POST['state'] == "Ilocos Norte") echo "selected"; ?>>Ilocos Norte</option>
    <option value="Ilocos Sur" <?php if(isset($_POST['state']) && $_POST['state'] == "Ilocos Sur") echo "selected"; ?>>Ilocos Sur</option>
    <option value="La Union" <?php if(isset($_POST['state']) && $_POST['state'] == "La Union") echo "selected"; ?>>La Union</option>
    <option value="Pangasinan" <?php if(isset($_POST['state']) && $_POST['state'] == "Pangasinan") echo "selected"; ?>>Pangasinan</option>
    <option value="Batanes" <?php if(isset($_POST['state']) && $_POST['state'] == "Batanes") echo "selected"; ?>>Batanes</option>
    <option value="Cagayan" <?php if(isset($_POST['state']) && $_POST['state'] == "Cagayan") echo "selected"; ?>>Cagayan</option>
    <option value="Isabela" <?php if(isset($_POST['state']) && $_POST['state'] == "Isabela") echo "selected"; ?>>Isabela</option>
    <option value="Nueva Vizcaya" <?php if(isset($_POST['state']) && $_POST['state'] == "Nueva Vizcaya") echo "selected"; ?>>Nueva Vizcaya</option>
    <option value="Quirino" <?php if(isset($_POST['state']) && $_POST['state'] == "Quirino") echo "selected"; ?>>Quirino</option>
    <option value="Aurora" <?php if(isset($_POST['state']) && $_POST['state'] == "Aurora") echo "selected"; ?>>Aurora</option>
    <option value="Bataan" <?php if(isset($_POST['state']) && $_POST['state'] == "Bataan") echo "selected"; ?>>Bataan</option>
    <option value="Bulacan" <?php if(isset($_POST['state']) && $_POST['state'] == "Bulacan") echo "selected"; ?>>Bulacan</option>
    <option value="Nueva Ecija" <?php if(isset($_POST['state']) && $_POST['state'] == "Nueva Ecija") echo "selected"; ?>>Nueva Ecija</option>
    <option value="Pampanga" <?php if(isset($_POST['state']) && $_POST['state'] == "Pampanga") echo "selected"; ?>>Pampanga</option>
    <option value="Tarlac" <?php if(isset($_POST['state']) && $_POST['state'] == "Tarlac") echo "selected"; ?>>Tarlac</option>
    <option value="Zambales" <?php if(isset($_POST['state']) && $_POST['state'] == "Zambales") echo "selected"; ?>>Zambales</option>
    <option value="Batangas" <?php if(isset($_POST['state']) && $_POST['state'] == "Batangas") echo "selected"; ?>>Batangas</option>
    <option value="Cavite" <?php if(isset($_POST['state']) && $_POST['state'] == "Cavite") echo "selected"; ?>>Cavite</option>
    <option value="Laguna" <?php if(isset($_POST['state']) && $_POST['state'] == "Laguna") echo "selected"; ?>>Laguna</option>
    <option value="Quezon" <?php if(isset($_POST['state']) && $_POST['state'] == "Quezon") echo "selected"; ?>>Quezon</option>
    <option value="Rizal" <?php if(isset($_POST['state']) && $_POST['state'] == "Rizal") echo "selected"; ?>>Rizal</option>
    <option value="Marinduque" <?php if(isset($_POST['state']) && $_POST['state'] == "Marinduque") echo "selected"; ?>>Marinduque</option>
    <option value="Occidental Mindoro" <?php if(isset($_POST['state']) && $_POST['state'] == "Occidental Mindoro") echo "selected"; ?>>Occidental Mindoro</option>
    <option value="Oriental Mindoro" <?php if(isset($_POST['state']) && $_POST['state'] == "Oriental Mindoro") echo "selected"; ?>>Oriental Mindoro</option>
    <option value="Palawan" <?php if(isset($_POST['state']) && $_POST['state'] == "Palawan") echo "selected"; ?>>Palawan</option>
    <option value="Romblon" <?php if(isset($_POST['state']) && $_POST['state'] == "Romblon") echo "selected"; ?>>Romblon</option>
    <option value="Albay" <?php if(isset($_POST['state']) && $_POST['state'] == "Albay") echo "selected"; ?>>Albay</option>
    <option value="Camarines Norte" <?php if(isset($_POST['state']) && $_POST['state'] == "Camarines Norte") echo "selected"; ?>>Camarines Norte</option>
    <option value="Camarines Sur" <?php if(isset($_POST['state']) && $_POST['state'] == "Camarines Sur") echo "selected"; ?>>Camarines Sur</option>
    <option value="Catanduanes" <?php if(isset($_POST['state']) && $_POST['state'] == "Catanduanes") echo "selected"; ?>>Catanduanes</option>
    <option value="Masbate" <?php if(isset($_POST['state']) && $_POST['state'] == "Masbate") echo "selected"; ?>>Masbate</option>
    <option value="Sorsogon" <?php if(isset($_POST['state']) && $_POST['state'] == "Sorsogon") echo "selected"; ?>>Sorsogon</option>
    <option value="Aklan" <?php if(isset($_POST['state']) && $_POST['state'] == "Aklan") echo "selected"; ?>>Aklan</option>
    <option value="Antique" <?php if(isset($_POST['state']) && $_POST['state'] == "Antique") echo "selected"; ?>>Antique</option>
    <option value="Capiz" <?php if(isset($_POST['state']) && $_POST['state'] == "Capiz") echo "selected"; ?>>Capiz</option>
    <option value="Guimaras" <?php if(isset($_POST['state']) && $_POST['state'] == "Guimaras") echo "selected"; ?>>Guimaras</option>
    <option value="Iloilo" <?php if(isset($_POST['state']) && $_POST['state'] == "Iloilo") echo "selected"; ?>>Iloilo</option>
    <option value="Negros Occidental" <?php if(isset($_POST['state']) && $_POST['state'] == "Negros Occidental") echo "selected"; ?>>Negros Occidental</option>
    <option value="Bohol" <?php if(isset($_POST['state']) && $_POST['state'] == "Bohol") echo "selected"; ?>>Bohol</option>
    <option value="Cebu" <?php if(isset($_POST['state']) && $_POST['state'] == "Cebu") echo "selected"; ?>>Cebu</option>
    <option value="Negros Oriental" <?php if(isset($_POST['state']) && $_POST['state'] == "Negros Oriental") echo "selected"; ?>>Negros Oriental</option>
    <option value="Siquijor" <?php if(isset($_POST['state']) && $_POST['state'] == "Siquijor") echo "selected"; ?>>Siquijor</option>
    <option value="Biliran" <?php if(isset($_POST['state']) && $_POST['state'] == "Biliran") echo "selected"; ?>>Biliran</option>
    <option value="Eastern Samar" <?php if(isset($_POST['state']) && $_POST['state'] == "Eastern Samar") echo "selected"; ?>>Eastern Samar</option>
    <option value="Leyte" <?php if(isset($_POST['state']) && $_POST['state'] == "Leyte") echo "selected"; ?>>Leyte</option>
    <option value="Northern Samar" <?php if(isset($_POST['state']) && $_POST['state'] == "Northern Samar") echo "selected"; ?>>Northern Samar</option>
    <option value="Samar (Western Samar)" <?php if(isset($_POST['state']) && $_POST['state'] == "Samar (Western Samar)") echo "selected"; ?>>Samar (Western Samar)</option>
    <option value="Southern Leyte" <?php if(isset($_POST['state']) && $_POST['state'] == "Southern Leyte") echo "selected"; ?>>Southern Leyte</option>
    <option value="Zamboanga del Norte" <?php if(isset($_POST['state']) && $_POST['state'] == "Zamboanga del Norte") echo "selected"; ?>>Zamboanga del Norte</option>
    <option value="Zamboanga del Sur" <?php if(isset($_POST['state']) && $_POST['state'] == "Zamboanga del Sur") echo "selected"; ?>>Zamboanga del Sur</option>
    <option value="Zamboanga Sibugay" <?php if(isset($_POST['state']) && $_POST['state'] == "Zamboanga Sibugay") echo "selected"; ?>>Zamboanga Sibugay</option>
    <option value="Bukidnon" <?php if(isset($_POST['state']) && $_POST['state'] == "Bukidnon") echo "selected"; ?>>Bukidnon</option>
    <option value="Camiguin" <?php if(isset($_POST['state']) && $_POST['state'] == "Camiguin") echo "selected"; ?>>Camiguin</option>
    <option value="Lanao del Norte" <?php if(isset($_POST['state']) && $_POST['state'] == "Lanao del Norte") echo "selected"; ?>>Lanao del Norte</option>
    <option value="Misamis Occidental" <?php if(isset($_POST['state']) && $_POST['state'] == "Misamis Occidental") echo "selected"; ?>>Misamis Occidental</option>
    <option value="Misamis Oriental" <?php if(isset($_POST['state']) && $_POST['state'] == "Misamis Oriental") echo "selected"; ?>>Misamis Oriental</option>
    <option value="Compostela Valley (Davao de Oro)" <?php if(isset($_POST['state']) && $_POST['state'] == "Compostela Valley (Davao de Oro)") echo "selected"; ?>>Compostela Valley (Davao de Oro)</option>
    <option value="Davao del Norte" <?php if(isset($_POST['state']) && $_POST['state'] == "Davao del Norte") echo "selected"; ?>>Davao del Norte</option>
    <option value="Davao del Sur" <?php if(isset($_POST['state']) && $_POST['state'] == "Davao del Sur") echo "selected"; ?>>Davao del Sur</option>
    <option value="Davao Occidental" <?php if(isset($_POST['state']) && $_POST['state'] == "Davao Occidental") echo "selected"; ?>>Davao Occidental</option>
    <option value="Davao Oriental" <?php if(isset($_POST['state']) && $_POST['state'] == "Davao Oriental") echo "selected"; ?>>Davao Oriental</option>
    <option value="Cotabato (North Cotabato)" <?php if(isset($_POST['state']) && $_POST['state'] == "Cotabato (North Cotabato)") echo "selected"; ?>>Cotabato (North Cotabato)</option>
    <option value="Cotabato City" <?php if(isset($_POST['state']) && $_POST['state'] == "Cotabato City") echo "selected"; ?>>Cotabato City</option>
    <option value="Sarangani" <?php if(isset($_POST['state']) && $_POST['state'] == "Sarangani") echo "selected"; ?>>Sarangani</option>
    <option value="South Cotabato" <?php if(isset($_POST['state']) && $_POST['state'] == "South Cotabato") echo "selected"; ?>>South Cotabato</option>
    <option value="Sultan Kudarat" <?php if(isset($_POST['state']) && $_POST['state'] == "Sultan Kudarat") echo "selected"; ?>>Sultan Kudarat</option>
    <option value="Agusan del Norte" <?php if(isset($_POST['state']) && $_POST['state'] == "Agusan del Norte") echo "selected"; ?>>Agusan del Norte</option>
    <option value="Agusan del Sur" <?php if(isset($_POST['state']) && $_POST['state'] == "Agusan del Sur") echo "selected"; ?>>Agusan del Sur</option>
    <option value="Dinagat Islands" <?php if(isset($_POST['state']) && $_POST['state'] == "Dinagat Islands") echo "selected"; ?>>Dinagat Islands</option>
    <option value="Surigao del Norte" <?php if(isset($_POST['state']) && $_POST['state'] == "Surigao del Norte") echo "selected"; ?>>Surigao del Norte</option>
    <option value="Surigao del Sur" <?php if(isset($_POST['state']) && $_POST['state'] == "Surigao del Sur") echo "selected"; ?>>Surigao del Sur</option>
    <option value="Basilan" <?php if(isset($_POST['state']) && $_POST['state'] == "Basilan") echo "selected"; ?>>Basilan</option>
    <option value="Lanao del Sur" <?php if(isset($_POST['state']) && $_POST['state'] == "Lanao del Sur") echo "selected"; ?>>Lanao del Sur</option>
    <option value="Maguindanao" <?php if(isset($_POST['state']) && $_POST['state'] == "Maguindanao") echo "selected"; ?>>Maguindanao</option>
    <option value="Sulu" <?php if(isset($_POST['state']) && $_POST['state'] == "Sulu") echo "selected"; ?>>Sulu</option>
    <option value="Tawi-Tawi" <?php if(isset($_POST['state']) && $_POST['state'] == "Tawi-Tawi") echo "selected"; ?>>Tawi-Tawi</option>

</select>
                    </div>
                    <div class="text-box">
                        <h3>City</h3>
                        <select name="city" id="citySelect">
                        <option value="">Select City</option>
                    </select>
                    </div>
                </div>

                <!-- Barangay, Street -->
                <div class="side-by-side">
                    <div class="text-box">
                        <h3>Barangay</h3>
                        <!-- <select name="barangay" id="barangaySelect">
                            <option value="">Select Barangay</option>
                        </select> -->
                        <input type="text" placeholder="Barangay" name="barangay">
                    </div>
                    <div class="text-box">
                        <h3>Street</h3>
                        <input type="text" placeholder="Street" name="street">
                    </div>
                </div>

                <!-- Resume Link -->
                <div class="side-by-side">
                    <div class="text-box">
                        <h3>Resume Link</h3>
                        <input type="url" placeholder="Link to your CV" name="resumelink" required>
                    </div>
                </div>

                <!-- Signature, Comment, Favorite Color -->
                <div class="side-by-side">
                    <div class="text-box">
                        <h3>Signature</h3>
                        <input type="file" name="signature" accept="image/*" required>
                    </div>
                    <div class="text-box">
                        <h3>Comment</h3>
                        <textarea name="comment" placeholder="Comment"></textarea>
                    </div>
                </div>

                <!-- Submit button -->
                <input type="submit" value="Submit">
                <input type="button" value="Back" onclick="history.back()">
            </form>
        </div>
    </div>

    <script>
    function validateForm() {
        
        var emailInput = document.forms["myForm"]["email"];
        if (!emailInput.value.includes("@") || !emailInput.value.includes(".com")) {
            alert("Invalid email format");
            emailInput.classList.add("error"); 
            return false;
        } else {
            emailInput.classList.remove("error"); 
        }

        
        var phoneInput = document.forms["myForm"]["phone"];
        if (!/^\d{11}$/.test(phoneInput.value)) {
            alert("Invalid phone number. Please enter 11 digits.");
            phoneInput.classList.add("error"); 
            return false;
        } else {
            phoneInput.classList.remove("error"); 
        }

        
        var ageInput = document.forms["myForm"]["age"];
        if (ageInput.value < 1 || ageInput.value > 99 || isNaN(ageInput.value)) {
            alert("Invalid age. Please enter a valid age between 1 and 99.");
            ageInput.classList.add("error"); 
            return false;
        } else {
            ageInput.classList.remove("error"); 
        }

        
        var nameInputs = ["firstname", "middlename", "lastname"];
        for (var i = 0; i < nameInputs.length; i++) {
            var nameInput = document.forms["myForm"][nameInputs[i]];
            if (!/^[a-zA-Z]+$/.test(nameInput.value)) {
                alert("Invalid name format. Please use only letters.");
                nameInput.classList.add("error"); 
                return false;
            } else {
                nameInput.classList.remove("error"); 
            }
        }

        
        var resumeLinkInput = document.forms["myForm"]["resumelink"];
        if (resumeLinkInput.value && !/^((https?:\/\/)?(www\.)?[a-zA-Z0-9-]+(\.[a-zA-Z]{2,})(\/[a-zA-Z0-9-._~:/?#[\]@!$&'()*+,;=]*)?)$/.test(resumeLinkInput.value)) {
            alert("Invalid resume link format. Please enter a valid URL.");
            resumeLinkInput.classList.add("error"); 
            return false;
        } else {
            resumeLinkInput.classList.remove("error"); 
        }

        return true;
    }
</script>
<script src="/PHP-Practice/CRUD/src/address.js"></script>

</body>
</html>
