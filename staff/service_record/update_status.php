<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";

$id = $_GET['id'];

$res = mysqli_query($conn, "SELECT * FROM service_records WHERE service_record_id = $id");
$record = mysqli_fetch_assoc($res);

if(isset($_POST['submit'])) {
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE service_records SET status='$status' WHERE service_record_id=$id");
    header("Location: view.php");
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../includes/sidebar_staff.php"; ?>

        <div class="col-md-10 p-4">
            <h4>Update Service Status</h4>
            <form method="POST">
                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="Pending" <?= $record['status']=='Pending'?'selected':'' ?>>Pending</option>
                        <option value="Ongoing" <?= $record['status']=='Ongoing'?'selected':'' ?>>Ongoing</option>
                        <option value="Completed" <?= $record['status']=='Completed'?'selected':'' ?>>Completed</option>
                    </select>
                </div>
                <button type="submit" name="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Update
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
