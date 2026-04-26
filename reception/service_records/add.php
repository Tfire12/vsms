<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";

$vehicles = $conn->query("SELECT vehicle_id, plate_number FROM vehicles");
$services = $conn->query("SELECT service_id, service_name FROM services");
$staff    = $conn->query("SELECT staff_id, full_name FROM staff WHERE role='Technician'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date    = $_POST['service_date'];
    $vehicle = $_POST['vehicle_id'];
    $service = $_POST['service_id'];
    $tech    = $_POST['staff_id'];

    $stmt = $conn->prepare(
        "INSERT INTO service_records (service_date, vehicle_id, service_id, staff_id)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("siii", $date, $vehicle, $service, $tech);
    $stmt->execute();

    header("Location: view.php");
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../includes/sidebar_admin.php"; ?>

        <div class="col-md-10 p-4">
            <h4>New Service Record</h4>

            <div class="card shadow-sm col-md-6">
                <div class="card-body">
                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Service Date</label>
                            <input type="date" name="service_date" class="form-control"
                                   value="<?= date('Y-m-d'); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Vehicle</label>
                            <select name="vehicle_id" class="form-select" required>
                                <option value="">-- Select Vehicle --</option>
                                <?php while($v = $vehicles->fetch_assoc()): ?>
                                    <option value="<?= $v['vehicle_id']; ?>">
                                        <?= $v['plate_number']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Service</label>
                            <select name="service_id" class="form-select" required>
                                <option value="">-- Select Service --</option>
                                <?php while($s = $services->fetch_assoc()): ?>
                                    <option value="<?= $s['service_id']; ?>">
                                        <?= htmlspecialchars($s['service_name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Technician</label>
                            <select name="staff_id" class="form-select">
                                <option value="">-- Assign Technician --</option>
                                <?php while($t = $staff->fetch_assoc()): ?>
                                    <option value="<?= $t['staff_id']; ?>">
                                        <?= htmlspecialchars($t['full_name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <button class="btn btn-success">Save</button>
                        <a href="view.php" class="btn btn-secondary">Back</a>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
