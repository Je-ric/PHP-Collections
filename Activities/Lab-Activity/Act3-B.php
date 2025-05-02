<?php 
    $n = 20;
    for($i = 1; $i<=$n; $i++){
        for($j=1; $j<=$n; $j++){
            if($j== $i || $j==$n - $i + 1){
                echo "*";
            }
            else{
                echo "&nbsp";
            }
        }
        echo "<br>";
    }
?>
