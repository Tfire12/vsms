<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";

// Handle form submission
if(isset($_POST['submit'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO staff (full_name, role, phone, username, password)
            VALUES ('$full_name', '$role', '$phone', '$username', '$password')";
    mysqli_query($conn, $sql);
    header("Location: view.php");
}

?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../includes/sidebar_admin.php"; ?>

        <div class="col-md-10 p-4">
            <h4>Add Staff</h4>

            <form method="POST">
                <div class="mb-3">
                    <label>Full Name</label>
                    <input type="text" name="full_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" class="form-select" required>
                        <option value="">Select Role</option>
                        <option value="Technician">Technician</option>
                        <option value="Manager">Manager</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" name="submit" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Add Staff
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
