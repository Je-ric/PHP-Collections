<?php
session_start();
unset($_SESSION['admin_log']); 
// session_destroy();
header("Location: index.php");
exit;
?>
