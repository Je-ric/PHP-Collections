<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }

        form {
            width: 30%;
            padding: 10px;
        }
    </style>
    <title>Document</title>
</head>
<body>

<!-- 1 -->
<!-- -------------------------------------------------------------- -->

<?php 
    $color = array('white', 'green', 'red', 'blue', 'black');

    echo "The memory of that scene for me is like a frame of film forever frozen at that moment: the <br> $color[2]
    the $color[1] lawn, the $color[0] house, the leaden sky. The new president and his first lady. -Richard M. Nixon";

?>

<br><br>
<!-- 2 -->
<!-- -------------------------------------------------------------- -->

<?php 
    $ceu = array( 
        "Italy"=>"Rome", 
        "Luxembourg"=>"Luxembourg", 
        "Belgium" => "Brussels", 
        "Denmark"=>"Copenhagen", 
        "Finland"=>"Helsinki", 
        "France" => "Paris", 
        "Slovakia"=>"Bratislava", 
        "Slovenia"=>"Ljubljana", 
        "Germany" => "Berlin", 
        "Greece" => "Athens", 
        "Ireland"=>"Dublin", 
        "Netherlands"=>"Amsterdam", 
        "Portugal"=>"Lisbon", 
        "Spain"=>"Madrid",
        "Sweden"=>"Stockholm", 
        "United Kingdom"=>"London", 
        "Cyprus"=>"Nicosia", 
        "Lithuania"=>"Vilnius", 
        "Czech Republic"=>"Prague", 
        "Estonia"=>"Tallin", 
        "Hungary"=>"Budapest", 
        "Latvia"=>"Riga", 
        "Malta"=>"Valetta", 
        "Austria" => "Vienna",
        "Poland"=>"Warsaw");

        asort($ceu);
        foreach($ceu as $country => $capital){
            echo "The capital of $country is $capital. <br>";
        }   
    
?>

<br><br>
<!-- 3 -->
<!-- -------------------------------------------------------------- -->

<?php
    $table = array(1, 2, 3, 4, 5, 6);
    $end = 6;

    echo "<table border='1'>";

    for ($i = 0; $i < count($table); $i++) {
        echo "<tr>";
        for ($j = 1; $j <= $end; $j++) {
            $result = $table[$i] * $j;
            echo "<td>{$table[$i]}*$j = $result</td>"; 
        }
        echo "</tr>"; 
    }  

    echo "</table>";
?>    

<br><br>
<!-- 4 -->
<!-- -------------------------------------------------------------- -->

<div style="display: flex; justify-content: space-between;">

    <form action="" method="post" style="border: 1px solid black; flex: 1; padding: 10px; margin-right: 10px;">
        <h1>Rectangle Area Function</h1>
        <p>Please enter the values of the length and width of your rectangle</p>

        Length: <input type="number" name="length" required>
        Width:  <input type="number" name="width" required>
        <br>
        <input type="submit" value="Go">
    </form>

    <?php
    if ($_POST) {
        $length = $_POST["length"];
        $width = $_POST["width"];

        if (isset($length) && isset($width)) {
            echo '<div style="border: 1px solid black; padding: 10px; flex: 1;">';
            echo "<h1>Rectangle Area Function</h1>";
            echo "<p>Area of rectangle is $length * $width = " . ($length * $width) . "</p>";
            echo '</div>';
        }
    }
    ?>
</div>


</body>
</html>

