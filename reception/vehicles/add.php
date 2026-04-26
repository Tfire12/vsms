<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";

$customers = $conn->query("SELECT * FROM customers ORDER BY full_name ASC");

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plate = strtoupper(trim($_POST['plate_number']));
    $type  = trim($_POST['vehicle_type']);
    $cust  = $_POST['customer_id'];

    // check unique plate
    $check = $conn->prepare("SELECT vehicle_id FROM vehicles WHERE plate_number=?");
    $check->bind_param("s", $plate);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Plate number already exists.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO vehicles (plate_number, vehicle_type, customer_id)
             VALUES (?, ?, ?)"
        );
        $stmt->bind_param("ssi", $plate, $type, $cust);
        $stmt->execute();

        header("Location: view.php");
        exit;
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../includes/sidebar_admin.php"; ?>

        <div class="col-md-10 p-4">
            <h4>Add Vehicle</h4>

            <div class="card shadow-sm col-md-6">
                <div class="card-body">

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Plate Number</label>
                            <input type="text" name="plate_number" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Vehicle Type</label>
                            <input type="text" name="vehicle_type" class="form-control" placeholder="e.g. Sedan, Truck">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Customer</label>
                            <select name="customer_id" class="form-select" required>
                                <option value="">-- Select Customer --</option>
                                <?php while($c = $customers->fetch_assoc()): ?>
                                    <option value="<?= $c['customer_id']; ?>">
                                        <?= htmlspecialchars($c['full_name']); ?>
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
