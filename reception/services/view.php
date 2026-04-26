<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";

$result = $conn->query("SELECT * FROM services ORDER BY service_id DESC");
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../includes/sidebar_admin.php"; ?>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Services</h4>
                <a href="add.php" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Add Service
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Service Name</th>
                                <th>Cost</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1; while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= htmlspecialchars($row['service_name']); ?></td>
                                <td><?= number_format($row['cost'], 2); ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $row['service_id']; ?>" class="btn btn-warning btn-sm">
                                        Edit
                                    </a>
                                    <a href="delete.php?id=<?= $row['service_id']; ?>"
                                       onclick="return confirm('Delete this service?')"
                                       class="btn btn-danger btn-sm">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>

                            <?php if ($result->num_rows == 0): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No services found</td>
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
