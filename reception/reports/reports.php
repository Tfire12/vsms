<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../includes/sidebar_admin.php"; ?>

        <div class="col-md-10 p-4">
            <h4>Reports</h4>
            <p>Summary of Vehicles, Services, Payments, and Customers</p>

            <div class="row g-3 mt-3">

                <?php
                // Total Customers
                $res = mysqli_query($conn, "SELECT COUNT(*) AS total_customers FROM customers");
                $total_customers = mysqli_fetch_assoc($res)['total_customers'];

                // Total Vehicles
                $res = mysqli_query($conn, "SELECT COUNT(*) AS total_vehicles FROM vehicles");
                $total_vehicles = mysqli_fetch_assoc($res)['total_vehicles'];

                // Total Services
                $res = mysqli_query($conn, "SELECT COUNT(*) AS total_services FROM services");
                $total_services = mysqli_fetch_assoc($res)['total_services'];

                // Total Staff
                $res = mysqli_query($conn, "SELECT COUNT(*) AS total_staff FROM staff");
                $total_staff = mysqli_fetch_assoc($res)['total_staff'];

                // Total Payments (Revenue)
                $res = mysqli_query($conn, "SELECT SUM(amount) AS total_revenue FROM payments");
                $total_revenue = mysqli_fetch_assoc($res)['total_revenue'];
                ?>

                <div class="col-md-3">
                    <div class="card shadow text-center p-3">
                        <h6>Total Customers</h6>
                        <h4><?= $total_customers ?></h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow text-center p-3">
                        <h6>Total Vehicles</h6>
                        <h4><?= $total_vehicles ?></h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow text-center p-3">
                        <h6>Total Services</h6>
                        <h4><?= $total_services ?></h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow text-center p-3">
                        <h6>Total Staff</h6>
                        <h4><?= $total_staff ?></h4>
                    </div>
                </div>

                <div class="col-md-3 mt-3">
                    <div class="card shadow text-center p-3 bg-success text-white">
                        <h6>Total Revenue</h6>
                        <h4>TZS <?= number_format($total_revenue,2) ?></h4>
                    </div>
                </div>

            </div>

            <!-- Optional: Table of recent payments -->
            <div class="mt-5">
                <h5>Recent Payments</h5>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Vehicle</th>
                            <th>Service</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "
                            SELECT p.amount, p.payment_date, c.full_name, v.plate_number, s.service_name
                            FROM payments p
                            JOIN service_records sr ON p.service_record_id = sr.service_record_id
                            JOIN vehicles v ON sr.vehicle_id = v.vehicle_id
                            JOIN customers c ON v.customer_id = c.customer_id
                            JOIN services s ON sr.service_id = s.service_id
                            ORDER BY p.payment_date DESC
                            LIMIT 10
                        ";
                        $res = mysqli_query($conn, $sql);
                        $i = 1;
                        while($row = mysqli_fetch_assoc($res)) {
                            echo "<tr>
                                <td>{$i}</td>
                                <td>{$row['full_name']}</td>
                                <td>{$row['plate_number']}</td>
                                <td>{$row['service_name']}</td>
                                <td>TZS ".number_format($row['amount'],2)."</td>
                                <td>{$row['payment_date']}</td>
                            </tr>";
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
