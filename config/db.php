<?php
// Database configuration
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "vsms";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
