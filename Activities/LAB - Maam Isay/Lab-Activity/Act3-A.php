<?php
 if ($_POST) {
        $input = $_POST['input']; 

        if($input % 2 == 0){
            echo "You entered $input, which is an even number";
		}
		elseif($input % 2 == 1){
            echo "You entered $input, which is an odd number";
		}
		else{ echo "Invalid Input"; }
    }
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type"content="text/html;charset=iso-8859-1">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Testing Odd and Even Numbers</h2>

    <form action="" method="post">
        Enter Value: <br>
        <input type="text" name="input" required/>
        <input type="submit" name="submit" value="Go">
    </form>
</body>
</html>