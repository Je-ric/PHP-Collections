<?php
session_start();
session_unset(); 
session_destroy(); 
unset($_SESSION['admin_log']); 
header("Location: ../index.php"); 
exit();
?>
