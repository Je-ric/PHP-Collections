<?php
session_start();
unset($_SESSION['admin']); 
// session_destroy();
header("Location: admin-login.php");
exit;
?>
