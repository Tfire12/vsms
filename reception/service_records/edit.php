<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: view.php");
    exit;
}

$record = $conn->query("SELECT * FROM service_records WHERE service_record_id=$id")->fetch_assoc();
$staff  = $conn->query("SELECT staff_id, full_name FROM staff WHERE role='Technician'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tech   = $_POST['staff_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare(
        "UPDATE service_records SET staff_id=?, status=? WHERE service_record_id=?"
    );
    $stmt->bind_param("isi", $tech, $status, $id);
    $stmt->execute();

    header("Location: view.php");
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../includes/sidebar_admin.php"; ?>

        <div class="col-md-10 p-4">
            <h4>Edit Service Record</h4>

            <div class="card shadow-sm col-md-6">
                <div class="card-body">
                    <form method="POST">

                        <div class="mb-3">
                            <label>Technician</label>
                            <select name="staff_id" class="form-select">
                                <option value="">-- Select Technician --</option>
                                <?php while($t = $staff->fetch_assoc()): ?>
                                    <option value="<?= $t['staff_id']; ?>"
                                        <?= $t['staff_id']==$record['staff_id']?'selected':''; ?>>
                                        <?= htmlspecialchars($t['full_name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option <?= $record['status']=='Pending'?'selected':''; ?>>Pending</option>
                                <option <?= $record['status']=='Completed'?'selected':''; ?>>Completed</option>
                            </select>
                        </div>

                        <button class="btn btn-primary">Update</button>
                        <a href="view.php" class="btn btn-secondary">Back</a>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
