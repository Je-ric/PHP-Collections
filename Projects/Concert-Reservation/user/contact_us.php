<?php
  session_start();
  if(empty($_SESSION['log-customer'])) {
      header('location: ../login.php');
      exit();
  }
  if(isset($_SESSION['log-admin']) ) {
      header('location: ../login.php');
      exit();
  }   
  $usern = $_SESSION['username'];
  $email = $_SESSION['email'];

   
  include 'config.php';   



$categories = array(
    "Technical Support",
    "General Feedback",
    "Website Feedback",
    "Account Support",
    "Report a Bug",
    "Feature Request",
    "Other"
);


$selectedCategory = '';
$categoryOptions = '';


foreach ($categories as $category) {
    $categoryOptions .= "<option value=\"$category\">$category</option>";
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $selectedCategory = mysqli_real_escape_string($conn, $_POST['category']); 

    
    $insertQuery = "INSERT INTO ContactMessages (name, email, message, category) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);

    
    $stmt->bind_param("ssss", $name, $email, $message, $selectedCategory);

    
    if ($stmt->execute()) {
        echo "<script>alert('Thank you for contacting us! We appreciate your concern.');</script>";
    } else {
        echo "<script>alert('An error occurred while processing your request. Please try again later.');</script>";
        error_log("Error: " . $stmt->error);
    }

    
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
            <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contact Us</title>
        <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/about-us.css">
        <link rel="stylesheet" href="../css/home.css">
    </head>
    <body>
      <!-- nav-bar -->
     <div class="header">
             <div class="left-side">
                <a href="index.php"> <img class="logo" src="../images/LOGO.png" alt=""> </a>
                <a class="navs" href="index.php">Concerts</a>
                <a class="navs" href="reservations.php">View Reservation</a>
                <div class="dropdown">
                    <a href="index.php" class="navs">Pages</a>
                    <div class="pages">
                    <a class="navs2" href="view_blogs.php">blogs</a>
                    <a class="navs2" href="contact_us.php">contact us</a>
                    <a class="navs2" href="about_us.php">about us</a>

                    </div>
                </div>
             </div>
             <div class="right-side">
                <div class="dropdown">
                    <a href="index.php" class="acc-btn"><img src="../images/acc-icon.png"></a>
                    <div class="acc">
                        <p>User name: <?php echo $usern ?></p>
                        <p>E-mail: <?php echo $email ?></p> 
                        <a href="../logout.php" class="log-in-btn" href="">Log out</a>
                    </div>
                </div>
             </div>
        </div>
    
     <div class="label-pos">
         <h2>Contact Us</h2>
     </div>

     <div class="con-us-pos">
        <form id="contactForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="con-grid">
                <label for="name">Name:</label>
                <input style="flex: 1;" type="text" id="name" name="name" required>
            </div>

            <div class="con-grid">
                <label for="email">Your Email:</label>
                <input style="flex: 1;" type="email" id="email" name="email" required>
            </div>

            <div class="con-grid">
                <label for="category">Category:</label>
                <select style="flex: 1;" id="category" name="category" required>
                    <option value="" disabled selected>Select a category</option>
                    <?php echo $categoryOptions; ?>
                </select>
            </div>

            <div class="con-grid">
                <label for="message">Concern/Issue:</label>
                <textarea style="flex: 1;" id="message" name="message" rows="5" maxlength="200" required></textarea>
                <p id="charCount"></p>
            </div>

            <div class="pad-pos">
                <div class="snd" style="text-align: end;">
                <button type="submit">send message</button>
                </div>
            </div>
        </form>
     </div>
            
    </body>
</html>

<script>
        document.getElementById("message").addEventListener("input", function() {
            var maxLength = 200; // Maximum characters allowed
            var currentLength = this.value.length;
            document.getElementById("charCount").innerText = currentLength + "/" + maxLength;
            if (currentLength >= maxLength) {
                this.value = this.value.substring(0, maxLength);
            }
        });
    </script>
    <!-- <div class="form-group">
                <label for="message">Concern/Issue:</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div> -->
<?php

$conn->close();
?>