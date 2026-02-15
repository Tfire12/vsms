<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = $_POST['customer_id'];
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);
    $status    = $_POST['status'];
    $password  = trim($_POST['password']); // password mpya kutoka modal, inaweza kuwa empty

    if (!empty($password)) {
        // Hash new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE customers SET full_name=?, email=?, phone=?, status=?, password=? WHERE customer_id=?");
        $stmt->bind_param("sssssi", $full_name, $email, $phone, $status, $hashed_password, $id);
    } else {
        // Haibadilishi password ikiwa password field ni empty
        $stmt = $conn->prepare("UPDATE customers SET full_name=?, email=?, phone=?, status=? WHERE customer_id=?");
        $stmt->bind_param("ssssi", $full_name, $email, $phone, $status, $id);
    }

    $stmt->execute();
    header("Location: view.php");
    exit;
}
