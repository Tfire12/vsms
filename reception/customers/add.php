<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);
    $status    = $_POST['status'];
    $password  = $_POST['password'];

    // Hash password if provided
    $hash = $password ? password_hash($password, PASSWORD_DEFAULT) : null;

    $stmt = $conn->prepare("INSERT INTO customers (full_name, email, password, phone, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $full_name, $email, $hash, $phone, $status);
    $stmt->execute();

    header("Location: view.php");
    exit;
}
