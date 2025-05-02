<?php
class UserLogout {
    public function __construct() {
        session_start();
    }

    public function logout() {
        unset($_SESSION['user']);
        // session_destroy();
        $this->redirectToLoginPage();
    }

    private function redirectToLoginPage() {
        header("Location: index.php");
        exit;
    }
}

// Usage
$userLogout = new UserLogout();
$userLogout->logout();
?>
