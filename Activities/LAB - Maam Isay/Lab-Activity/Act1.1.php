<?php
    $phpVersion = phpversion();
    echo "PHP Version: $phpVersion\n";

    echo "\nPHP Configuration:\n";
    phpinfo();
?> 

<?php
    echo phpversion() . "<br>";

    echo "\nPHP Configuration:\n";
    phpinfo(INFO_CONFIGURATION);
?>
