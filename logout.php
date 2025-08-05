<?php
session_start();

// Destroy all session data
session_destroy();

// Remove remember me cookie if it exists
if (isset($_COOKIE['remember_user'])) {
    setcookie('remember_user', '', time() - 3600, '/');
}

// Redirect to home page
header('Location: index.php');
exit();
?>
