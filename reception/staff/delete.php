<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";

// Get staff ID from URL
if(isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prevent deleting currently logged-in admin/staff
    if($id == $_SESSION['user_id']) {
        $_SESSION['error'] = "You cannot delete your own account!";
        header("Location: view.php");
        exit;
    }

    // Delete staff from DB
    $sql = "DELETE FROM staff WHERE staff_id = $id";
    if(mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Staff member deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting staff: " . mysqli_error($conn);
    }
}

header("Location: view.php");
exit;
?>
