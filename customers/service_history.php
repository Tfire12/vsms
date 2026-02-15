<?php
require_once "../config/config.php";
require_once "../config/db.php";
require_once "../includes/auth_check.php";

// Only customer can access
if ($_SESSION['role'] !== 'customer') {
    die("Unauthorized access");
}

$customer_id = $_SESSION['user_id'];

// Fetch service history
$res = mysqli_query($conn, "
    SELECT sr.service_record_id, sr.service_date, sr.status, 
           v.plate_number, s.service_name,
           IFNULL(p.amount, 0) AS amount
    FROM service_records sr
    JOIN vehicles v ON sr.vehicle_id = v.vehicle_id
    JOIN services s ON sr.service_id = s.service_id
    LEFT JOIN payments p ON sr.service_record_id = p.service_record_id
    WHERE v.customer_id = $customer_id
    ORDER BY sr.service_date DESC
");
?>

<?php include "../includes/header.php"; ?>

<div class="container-fluid p-0">
    <div class="row">
        <!-- Sidebar -->
        <?php include BASE_PATH . "includes/sidebar_customer.php"; ?>

        <!-- Main content -->
        <div class="col-md-10 p-4">
            <h4>Service History</h4>

            <table class="table table-bordered table-striped mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Vehicle</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Amount (TZS)</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php while($row = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($row['plate_number']); ?></td>
                        <td><?= htmlspecialchars($row['service_name']); ?></td>
                        <td>
                            <span class="badge bg-<?= $row['status']=='Completed' ? 'success' : 'warning'; ?>">
                                <?= htmlspecialchars($row['status']); ?>
                            </span>
                        </td>
                        <td><?= number_format($row['amount'], 2); ?></td>
                        <td><?= date("d M Y", strtotime($row['service_date'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if(mysqli_num_rows($res) === 0): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No service history found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include BASE_PATH . "includes/footer.php"; ?>
