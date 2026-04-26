<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";

$sql = "
SELECT v.vehicle_id, v.plate_number, v.vehicle_type, c.full_name
FROM vehicles v
JOIN customers c ON v.customer_id = c.customer_id
ORDER BY v.vehicle_id DESC
";
$result = $conn->query($sql);
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../includes/sidebar_admin.php"; ?>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Vehicles</h4>
                <a href="add.php" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Add Vehicle
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Plate Number</th>
                                <th>Type</th>
                                <th>Customer</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1; while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= htmlspecialchars($row['plate_number']); ?></td>
                                <td><?= htmlspecialchars($row['vehicle_type']); ?></td>
                                <td><?= htmlspecialchars($row['full_name']); ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $row['vehicle_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete.php?id=<?= $row['vehicle_id']; ?>"
                                       onclick="return confirm('Delete this vehicle?')"
                                       class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>

                            <?php if ($result->num_rows == 0): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No vehicles found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
