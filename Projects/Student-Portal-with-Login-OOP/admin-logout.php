<?php
class AdminLogout {
    public function __construct() {
        session_start();
    }

    public function logout() {
        unset($_SESSION['admin']);
        session_destroy();
        $this->redirectToLoginPage();
    }

    private function redirectToLoginPage() {
        header("Location: admin-login.php");
        exit;
    }
}

// Usage
$adminLogout = new AdminLogout();
$adminLogout->logout();
?>
