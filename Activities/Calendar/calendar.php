<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birth Month</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="calendar">
        <table>
            <tr class="month">
                <td colspan="7" class="month-header">SEPTEMBER</td>
            </tr>
            <td></td>
            <tr class="weekdays">
                <th class="weekdays2"><span class="gradient-text">SUN</span></th>
                <th class="weekdays2"><span class="gradient-text">MON</span></th>
                <th class="weekdays2"><span class="gradient-text">TUE</span></th>
                <th class="weekdays2"><span class="gradient-text">WED</span></th>
                <th class="weekdays2"><span class="gradient-text">THU</span></th>
                <th class="weekdays2"><span class="gradient-text">FRI</span></th>
                <th class="weekdays2"><span class="gradient-text">SAT</span></th>
            </tr>
          
            <?php
            $september = array(
                1 => " ",
                2 => " ",
                3 => " ",
                4 => " ",
                5 => " ",
                6 => " ",
                7 => " ",
                8 => " ",
                9 => " ",
                10 => " ",
                11 => " ",
                12 => " ",
                13 => " ",
                14 => " ",
                15 => " ",
                16 => " ",
                17 => " ",
                18 => " ",
                19 => " ",
                20 => " ",
                21 => " ",
                22 => " ",
                23 => " ",
                24 => " ",
                25 => " ",
                26 => " ",
                27 => " ",
                28 => " ",
                29 => " ",
                30 => " ",
            );

            $currentDay = 1;
            $totalDays = count($september);
            $firstDayOfWeek = 1; 

            echo "<tr>";
            for ($i = 1; $i < $firstDayOfWeek; $i++) {
                echo "<td></td>";
            }
            while ($currentDay <= $totalDays) {
                
                for ($i = $firstDayOfWeek; $i <= 7 && $currentDay <= $totalDays; $i++) {
                    if ($currentDay == 22) {
                        echo "<td>
                        <div class='highlight'>$currentDay<br>{$september[$currentDay]}</div></td>";
                    } else {
                        echo "<td>$currentDay<br>{$september[$currentDay]}</td>";
                    }
                    $currentDay++;
                }
                if ($currentDay <= $totalDays) {
                    echo "</tr><tr>";
                }
                $firstDayOfWeek = 1;
            }
            ?>
        </table>
    </div>

    <?php require 'footer.php'; ?>
</body>
</html>
