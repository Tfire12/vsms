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

$vehicle = $conn->query("SELECT * FROM vehicles WHERE vehicle_id=$id")->fetch_assoc();
$customers = $conn->query("SELECT * FROM customers ORDER BY full_name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plate = strtoupper(trim($_POST['plate_number']));
    $type  = $_POST['vehicle_type'];
    $cust  = $_POST['customer_id'];

    $stmt = $conn->prepare(
        "UPDATE vehicles SET plate_number=?, vehicle_type=?, customer_id=? WHERE vehicle_id=?"
    );
    $stmt->bind_param("ssii", $plate, $type, $cust, $id);
    $stmt->execute();

    header("Location: view.php");
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../includes/sidebar_admin.php"; ?>

        <div class="col-md-10 p-4">
            <h4>Edit Vehicle</h4>

            <div class="card shadow-sm col-md-6">
                <div class="card-body">
                    <form method="POST">

                        <div class="mb-3">
                            <label>Plate Number</label>
                            <input type="text" name="plate_number" class="form-control"
                                   value="<?= htmlspecialchars($vehicle['plate_number']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Vehicle Type</label>
                            <input type="text" name="vehicle_type" class="form-control"
                                   value="<?= htmlspecialchars($vehicle['vehicle_type']); ?>">
                        </div>

                        <div class="mb-3">
                            <label>Customer</label>
                            <select name="customer_id" class="form-select" required>
                                <?php while($c = $customers->fetch_assoc()): ?>
                                    <option value="<?= $c['customer_id']; ?>"
                                        <?= $c['customer_id']==$vehicle['customer_id'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($c['full_name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <button class="btn btn-primary">Update</button>
                        <a href="view.php" class="btn btn-secondary">Cancel</a>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
