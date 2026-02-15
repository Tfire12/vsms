<?php
require_once "../config/config.php";
require_once "../config/db.php";
require_once "../includes/auth_check.php";

// Only customer can access
if ($_SESSION['role'] !== 'customer') {
    die("Unauthorized access");
}

$customer_id = $_SESSION['user_id'];

// Fetch vehicles
$res = mysqli_query($conn,
    "SELECT * FROM vehicles WHERE customer_id = $customer_id"
);
?>

<?php include "../includes/header.php"; ?>

<div class="container-fluid p-0">
    <div class="row">
        <!-- Sidebar -->
        <?php include BASE_PATH . "includes/sidebar_customer.php"; ?>

        <!-- Main content -->
        <div class="col-md-10 p-4">
            <h4>My Vehicles</h4>

            <table class="table table-bordered table-striped mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Plate Number</th>
                        <th>Model</th>
                        <th>Color</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; while($row = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($row['plate_number']); ?></td>
                        <td><?= htmlspecialchars($row['vehicle_type']); ?></td>
                        <td><?= htmlspecialchars($row['color']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if(mysqli_num_rows($res) === 0): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">No vehicles found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include BASE_PATH . "includes/footer.php"; ?>
