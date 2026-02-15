<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";

$sql = "
SELECT sr.service_record_id, sr.service_date, sr.status,
       v.plate_number,
       s.service_name,
       st.full_name AS technician
FROM service_records sr
JOIN vehicles v ON sr.vehicle_id = v.vehicle_id
JOIN services s ON sr.service_id = s.service_id
LEFT JOIN staff st ON sr.staff_id = st.staff_id
ORDER BY sr.service_record_id DESC
";

$result = $conn->query($sql);
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../includes/sidebar_admin.php"; ?>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Service Records</h4>
                <a href="add.php" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> New Service Record
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Plate</th>
                                <th>Service</th>
                                <th>Technician</th>
                                <th>Status</th>
                                <th width="160">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1; while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $row['service_date']; ?></td>
                                <td><?= htmlspecialchars($row['plate_number']); ?></td>
                                <td><?= htmlspecialchars($row['service_name']); ?></td>
                                <td><?= $row['technician'] ?? 'Not Assigned'; ?></td>
                                <td>
                                    <span class="badge bg-<?= $row['status']=='Completed'?'success':'warning'; ?>">
                                        <?= $row['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?= $row['service_record_id']; ?>"
                                       class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete.php?id=<?= $row['service_record_id']; ?>"
                                       onclick="return confirm('Delete this record?')"
                                       class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>

                            <?php if ($result->num_rows == 0): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">No service records found</td>
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
