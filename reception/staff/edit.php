<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";

$id = $_GET['id'];
$res = mysqli_query($conn, "SELECT * FROM staff WHERE staff_id = $id");
$staff = mysqli_fetch_assoc($res);

if(isset($_POST['submit'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);

    // Update password only if provided
    if(!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE staff SET full_name='$full_name', role='$role', phone='$phone', username='$username', password='$password' WHERE staff_id=$id";
    } else {
        $sql = "UPDATE staff SET full_name='$full_name', role='$role', phone='$phone', username='$username' WHERE staff_id=$id";
    }

    mysqli_query($conn, $sql);
    header("Location: view.php");
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../includes/sidebar_admin.php"; ?>

        <div class="col-md-10 p-4">
            <h4>Edit Staff</h4>

            <form method="POST">
                <div class="mb-3">
                    <label>Full Name</label>
                    <input type="text" name="full_name" class="form-control" value="<?= $staff['full_name'] ?>" required>
                </div>

                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" class="form-select" required>
                        <option value="Technician" <?= $staff['role']=='Technician'?'selected':'' ?>>Technician</option>
                        <option value="Manager" <?= $staff['role']=='Manager'?'selected':'' ?>>Manager</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= $staff['phone'] ?>">
                </div>

                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?= $staff['username'] ?>" required>
                </div>

                <div class="mb-3">
                    <label>Password <small>(leave blank to keep current)</small></label>
                    <input type="password" name="password" class="form-control">
                </div>

                <button type="submit" name="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Update Staff
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
