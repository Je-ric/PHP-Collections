<?php 

include "config.php";

    if (isset($_POST['update'])) {
        $firstname = $_POST['firstname'];
        $user_id = $_POST['user_id'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $gender = $_POST['gender']; 

        $sql = "UPDATE `users` SET `firstname`='$firstname',`lastname`='$lastname',`email`='$email',`password`='$password',`gender`='$gender' WHERE `id`='$user_id'"; 
        $result = $conn->query($sql); 

        if ($result == TRUE) {
            echo "Record updated successfully.";
        }else{
            echo "Error:" . $sql . "<br>" . $conn->error;
        }
    } 

if (isset($_GET['id'])) {
    $user_id = $_GET['id']; 
    $sql = "SELECT * FROM `users` WHERE `id`='$user_id'";

    $result = $conn->query($sql); 

    if ($result->num_rows > 0) {        
        while ($row = $result->fetch_assoc()) {
            $first_name = $row['firstname'];
            $lastname = $row['lastname'];
            $email = $row['email'];
            $password  = $row['password'];
            $gender = $row['gender'];
            $id = $row['id'];
        } 
    ?>

        <h2>User Update Form</h2>
        <form action="" method="post">
          <fieldset>
            <legend>Personal information:</legend>
            First name:<br/>
                <input type="text" name="firstname" value="<?php echo $first_name; ?>">
                <input type="hidden" name="user_id" value="<?php echo $id; ?>">
                <br/>

            Last name:<br/>
                <input type="text" name="lastname" value="<?php echo $lastname; ?>">
                <br/>

            Email:<br/>
            <input type="email" name="email" value="<?php echo $email; ?>">
            <br/>

            Password:<br/>
            <input type="password" name="password" value="<?php echo $password; ?>">
            <br/>

            Gender:<br/>
            <input type="radio" name="gender" value="Male" <?php if($gender == 'Male'){ echo "checked";} ?> >Male
            <input type="radio" name="gender" value="Female" <?php if($gender == 'Female'){ echo "checked";} ?>>Female
            <br/><br/>

            <input type="submit" value="Update" name="update">
          </fieldset>
        </form> 

        </body>

        </html> 

    <?php

    }else{ 
        header('Location: view.php');
    } 
}

?> 