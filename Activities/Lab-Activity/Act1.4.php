<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
    body{
        overflow-y: hidden;
    }

    div{
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    table {
            border-collapse: collapse;
            margin: 20px;
            font-size: 20px;
            border: 2px solid black;
    }

    td{
        padding: 15px;
    }
    </style>

    <title></title>
</head>
<body>
    <?php
       $mr = array(
        "Mr A" => 1000,
        "Mr B" => 1200,
        "Mr C" => 1400,
       );
    ?>

    <div>
        <table table border="1">
            <?php
                foreach ($mr as $salaryOf => $salary) {
                    echo "<tr>";
                    echo    "<td>Salary of $salaryOf is</td>";
                    echo    "<td>$salary$</td>";
                    echo "</tr>";
                }
            ?>
        </table>
    </div>
    
</body>
</html>
