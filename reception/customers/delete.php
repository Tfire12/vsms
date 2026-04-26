<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";

$id = $_GET['id'] ?? null;
if ($id) {
    $conn->query("DELETE FROM customers WHERE customer_id=$id");
}
header("Location: view.php");
exit;
