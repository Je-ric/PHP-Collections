<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'C:\xampp\htdocs\PHP-Projects\Concert-Reservation-OOP\sendemail\phpmailer\src\Exception.php';
require 'C:\xampp\htdocs\PHP-Projects\Concert-Reservation-OOP\sendemail\phpmailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\PHP-Projects\Concert-Reservation-OOP\sendemail\phpmailer\src\SMTP.php';

class ContactForm
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function processForm()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = mysqli_real_escape_string($this->conn, $_POST['name']);
            $email = mysqli_real_escape_string($this->conn, $_POST['email']);
            $message = mysqli_real_escape_string($this->conn, $_POST['message']);
            $selectedCategory = mysqli_real_escape_string($this->conn, $_POST['category']);

            $insertQuery = "INSERT INTO ContactMessages (name, email, message, category) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($insertQuery);

            $stmt->bind_param("ssss", $name, $email, $message, $selectedCategory);

            if ($stmt->execute()) {
                echo "<script>alert('Thank you for contacting us! We appreciate your concern.');</script>";

                // Send email to user
                $this->sendEmailToUser($email, $name, $message);

                // Send email to admin (jericjdelacruz@gmail.com)
                $this->sendEmailToAdmin($email, $name, $message);
            } else {
                echo "<script>alert('An error occurred while processing your request. Please try again later.');</script>";
                error_log("Error: " . $stmt->error);
            }

            $stmt->close();
        }
    }

    private function sendEmailToUser($email, $name, $message)
    {
        $subject = 'Query Details';
        $message = "
        Your Concern/Issue Details:<br>
        $message <br>
        Thank you for your concern!
        ";

        $this->sendEmail($email, $name, $subject, $message);
    }

    private function sendEmailToAdmin($email, $name, $message)
    {
        $subject = 'New Query Received';
        $message = "
        New Query Details:<br>
        From: $name ($email)<br>
        Message: $message
        ";

        $adminEmail = 'jericjdelacruz@gmail.com';
        $this->sendEmail($adminEmail, 'Admin', $subject, $message);
    }

    private function sendEmail($to, $name, $subject, $message)
    {
        $mail = new PHPMailer(true);
        // $mail->SMTPDebug = 2; 0 for no output
        $mail->SMTPDebug = 0;
        $mail->SMTPAutoTLS = false;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'jericjdelacruz@gmail.com'; 
            $mail->Password   = 'ivxhidyrloajrple'; 
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            $mail->setFrom('jericjdelacruz@gmail.com', 'Your Name');
            $mail->addAddress($to, $name);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function generateCategoryOptions()
    {
        $categories = array(
            "Technical Support",
            "General Feedback",
            "Website Feedback",
            "Account Support",
            "Report a Bug",
            "Feature Request",
            "Other"
        );

        $categoryOptions = '';

        foreach ($categories as $category) {
            $categoryOptions .= "<option value=\"$category\">$category</option>";
        }

        return $categoryOptions;
    }
}

include 'config.php';

$contactForm = new ContactForm($conn);
$contactForm->processForm(); // Process form submission

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/about-us.css">
    <link rel="icon" href="../images/S LOGO.png" type="image/icon type">
</head>
<body>
    <!-- nav-bar -->
    <?php include('navbar.php'); ?>

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
                    <?php echo $contactForm->generateCategoryOptions(); ?>
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

<?php
$conn->close();
?>
