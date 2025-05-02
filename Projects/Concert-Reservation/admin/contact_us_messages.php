<?php
session_start();
if(empty($_SESSION['log-admin'])) {
    header('location: ../login.php');
    exit();
}
if(isset($_SESSION['log-customer']) ) {
    header('location: ../login.php');
    exit();
}   
$email = $_SESSION['email'];

include 'config.php';


$categories = array(
    "All",
    "Technical Support",
    "General Feedback",
    "Website Feedback",
    "Account Support",
    "Report a Bug",
    "Feature Request",
    "Other"
);


$selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'All';
$categoryOptions = '';


foreach ($categories as $category) {
    $selected = ($selectedCategory == $category) ? 'selected' : '';
    $categoryOptions .= "<option value=\"$category\" $selected>$category</option>";
}


$whereClause = ($selectedCategory != 'All') ? "WHERE category = '$selectedCategory'" : '';


$query = "SELECT * FROM ContactMessages $whereClause ORDER BY created_at ASC";
$result = $conn->query($query);


if(isset($_GET['delete']) && !empty($_GET['delete'])) {
    $messageID = $_GET['delete'];
    $deleteQuery = "DELETE FROM ContactMessages WHERE messageID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $messageID);
    if ($stmt->execute()) {
        
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "<script>alert('An error occurred while deleting the message.');</script>";
        error_log("Error: Unable to delete message with ID: $messageID");
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Contact Us</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
    <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/blog.css">

</head>
<body>
     <!-- nav-bar -->
     <div class="header">
             <div class="left-side">
                <a href="index.php"> <img class="logo" src="../images/LOGO.png" alt=""> </a>
                <a class="navs" href="index.php">Concerts</a>
                <a class="navs" href="add_concert.php">Add Concert</a>
                <div class="dropdown">
                    <a href="index.php" class="navs">Pages</a>
                    <div class="pages">
                    <a class="navs2" href="blog_form.php">blog form</a> 
                    <a class="navs2" href="view_blogs.php">view blogs</a>
                    <a class="navs2" href="contact_us_messages.php">coontact us messages</a>
                    </div>
                </div>
             </div>
             <div class="right-side">
                <div class="dropdown">
                    <a href="index.php" class="acc-btn"><img src="../images/acc-icon.png"></a>
                    <div class="acc">
                        <p>welcome, Admin</p>
                        <p>E-mail: <?php echo $email ?></p> 
                        <a href="../logout.php" class="log-in-btn" href="">Log out</a>
                    </div>
                </div>
             </div>
        </div>

    <!-- DISPLAY CONTENT -->

    <div class="label-pos">
      <h2>Contact Messages</h2><br>
    </div>
    <div class="label-pos">     
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            <label for="category">Filter by Category:</label>
            <select id="category" name="category" onchange="this.form.submit()">
                <?php echo $categoryOptions; ?>
            </select>
        </form>
    </div>
    <div class="tab-pos">
    <table>
        <thead>
            <tr>
                <th style="display: none;">ID</th>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Concern/Issue</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $counter = 1;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td style='display: none;'>" . $row['messageID'] . "</td>";
                    echo "<td>" . $counter++ . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['message'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td><a class='delete-btn' href=\"{$_SERVER['PHP_SELF']}?delete={$row['messageID']}\">Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No contact messages found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php

$conn->close();
?>
