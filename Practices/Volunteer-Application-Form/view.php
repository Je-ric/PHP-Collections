<?php
    include("config.php");
    $sql = "SELECT * FROM volunteer_applications";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Volunteer Applications</title>
    <style>
        @font-face {
        font-family: 'oswald';
        src:url(src/Font/Oswald-VariableFont_wght.ttf);
        }

        * {
            font-family: 'oswald';
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }   
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Volunteer Applications</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Date of Birth</th>
                <th>Contact Info</th>
                <th>Gender</th>
                <th>Occupation</th>
                <th>Occupation Details</th>
                <th>Emergency Contacts</th>
                <th>Reason to Volunteer</th>
                <th>Hobbies</th>
                <th>Interests</th>
                <th>Volunteered Before</th>
                <th>How Found</th>
                <th>Work Day</th>
                <th>Actions to be done (EME)</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo $row["firstname"] . " " . $row["lastname"]; ?></td>
                <td><?php echo $row["dateofbirth"]; ?></td>
                <td>
                    <p>Email: <?php echo $row["email"]; ?></p>
                    <p>Address: <?php echo $row["street"] . ", " . $row["addressline"] . ", " . $row["city"] . ", " . $row["province"] . " " . $row["zip"]; ?></p>
                </td>
                <td><?php echo $row["gender"]; ?></td>
                <td><?php echo $row["occupation"]; ?></td>
                <td><?php echo $row["details"]; ?></td>
                <td>
                    <p><strong>Contact 1:</strong><br>
                    Name: <?php echo $row["emergencycontactname1"]; ?><br>
                    Phone: <?php echo $row["emergencycontactphone1"]; ?><br>
                    Relationship: <?php echo $row["emergencycontactrelationship1"]; ?></p>
                    <p><strong>Contact 2:</strong><br>
                    Name: <?php echo $row["emergencycontactname2"]; ?><br>
                    Phone: <?php echo $row["emergencycontactphone2"]; ?><br>
                    Relationship: <?php echo $row["emergencycontactrelationship2"]; ?></p>
                </td>
                <td><?php echo $row["reasontovolunteer"]; ?></td>
                <td><?php echo $row["hobbies"]; ?></td>
                <td><?php echo $row["interests"]; ?></td>
                <td><?php echo $row["volunteeredbefore"]; ?></td>
                <td><?php echo $row["how"]; ?></td>
                <td><?php echo $row["workday"]; ?></td>
                <td>
                    <a href="update.php?id=<?php echo $row['id']; ?>">Update</a><br>
                    <a href="delete.php?id=<?php echo $row['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php 
            }
        } else {
            echo "<tr><td colspan='15'>0 results</td></tr>";
        }
        $conn->close();
        ?>
        </tbody>
    </table>
</body>
</html>
