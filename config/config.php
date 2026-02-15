<?php
// Start session globally
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base paths
define("BASE_URL", "http://localhost/vsms/");
define("BASE_PATH", $_SERVER['DOCUMENT_ROOT'] . "/vsms/");
?>
