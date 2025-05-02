<?php
class SessionManager
{
    public static function destroySession()
    {
        session_start();
        session_destroy();
        header('location: index.php');
        exit();
    }
}

SessionManager::destroySession();
?>
