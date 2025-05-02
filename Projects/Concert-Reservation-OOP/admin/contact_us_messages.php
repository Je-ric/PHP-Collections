<?php
session_start();
if (empty($_SESSION['log-admin'])) {
    header('location: ../login.php');
    exit();
}
if (isset($_SESSION['log-customer'])) {
    header('location: ../login.php');
    exit();
}
$email = $_SESSION['email'];

include 'config.php';

class ContactMessageManager
{
    private $conn;
    private $categories;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->categories = [
            "All",
            "Technical Support",
            "General Feedback",
            "Website Feedback",
            "Account Support",
            "Report a Bug",
            "Feature Request",
            "Other"
        ];
    }

    public function getCategoryOptions($selectedCategory)
    {
        $categoryOptions = '';

        foreach ($this->categories as $category) {
            $selected = ($selectedCategory == $category) ? 'selected' : '';
            $categoryOptions .= "<option value=\"$category\" $selected>$category</option>";
        }

        return $categoryOptions;
    }

    public function getMessages($selectedCategory)
    {
        $whereClause = ($selectedCategory != 'All') ? "WHERE category = ?" : '';
        $query = "SELECT * FROM ContactMessages $whereClause ORDER BY created_at ASC";

        $stmt = $this->conn->prepare($query);

        if ($selectedCategory != 'All') {
            $stmt->bind_param("s", $selectedCategory);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }

        return $messages;
    }

    public function deleteMessage($messageID)
    {
        $deleteQuery = "DELETE FROM ContactMessages WHERE messageID = ?";
        $stmt = $this->conn->prepare($deleteQuery);
        $stmt->bind_param("i", $messageID);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

// Usage example:
$contactMessageManager = new ContactMessageManager($conn);

$selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'All';
$categoryOptions = $contactMessageManager->getCategoryOptions($selectedCategory);
$messages = $contactMessageManager->getMessages($selectedCategory);

if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $messageID = $_GET['delete'];
    if ($contactMessageManager->deleteMessage($messageID)) {
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "<script>alert('An error occurred while deleting the message.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contact Us</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
    <link href="https://api.fontshare.com/v2/css?f[]=poppins@500,400,300,700&display=swap" rel="stylesheet">
    <link rel="icon" href="../images/S LOGO.png" type="image/icon type">
    <style>
        <?php
        include '..\css\blog.css';
        ?>
    </style>
</head>

<body>
    <!-- nav-bar -->
    <?php include('navbar.php'); ?>

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
                if (!empty($messages)) {
                    foreach ($messages as $row) {
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
