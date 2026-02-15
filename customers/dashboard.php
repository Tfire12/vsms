<?php
require_once "../config/config.php";
require_once "../config/db.php";
require_once "../includes/auth_check.php";
include BASE_PATH . "includes/header.php";

if ($_SESSION['role'] !== 'customer') {
    die("Unauthorized access");
}

$customer_id = $_SESSION['user_id'];

/* Vehicles count */
$v = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM vehicles WHERE customer_id = $customer_id"
));

/* Services count */
$s = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total 
     FROM service_records sr
     JOIN vehicles v ON sr.vehicle_id = v.vehicle_id
     WHERE v.customer_id = $customer_id"
));

/* Total payments */
$p = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT IFNULL(SUM(p.amount),0) AS total
     FROM payments p
     JOIN service_records sr ON p.service_record_id = sr.service_record_id
     JOIN vehicles v ON sr.vehicle_id = v.vehicle_id
     WHERE v.customer_id = $customer_id"
));
?>

<div class="container-fluid p-0">
    <div class="row">
        <!-- Sidebar -->
        <?php include BASE_PATH . "includes/sidebar_customer.php"; ?>

        <!-- Main content -->
        <div class="col-md-10 p-4">
            <h3>Welcome, <?= $_SESSION['name']; ?></h3>

            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <div class="card shadow text-center">
                        <div class="card-body">
                            <h5>Total Vehicles</h5>
                            <h2><?= $v['total']; ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card shadow text-center">
                        <div class="card-body">
                            <h5>Service Records</h5>
                            <h2><?= $s['total']; ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card shadow text-center">
                        <div class="card-body">
                            <h5>Total Paid</h5>
                            <h2>TZS <?= number_format($p['total'], 2); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . "includes/footer.php"; ?>
