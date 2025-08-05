<?php
// PayClone Configuration File - File-based system
// No database configuration needed!

return [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'paypal_clone',
    'charset' => 'utf8mb4'
];

// Application Settings
define('APP_NAME', 'PayClone');
define('APP_VERSION', '2.0.0');
define('APP_URL', 'http://localhost/paypal_clone/');

// Security Settings
define('SESSION_TIMEOUT', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('PASSWORD_MIN_LENGTH', 8);
?>
