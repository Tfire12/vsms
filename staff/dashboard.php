<?php
require_once "../config/config.php";
require_once "../config/db.php";
require_once "../includes/auth_check.php";
require_once "../includes/header.php";
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../includes/sidebar_staff.php"; ?>

        <div class="col-md-10 p-4">
            <h3>Staff Dashboard</h3>
            <p>Welcome, <strong><?= $_SESSION['name'] ?></strong></p>

            <?php
            $staff_id = $_SESSION['user_id'];

            // Assigned Service Records
            $res = mysqli_query($conn, "
                SELECT COUNT(*) AS total_services
                FROM service_records
                WHERE staff_id = $staff_id
            ");
            $total_services = mysqli_fetch_assoc($res)['total_services'];

            // Completed Services
            $res = mysqli_query($conn, "
                SELECT COUNT(*) AS completed_services
                FROM service_records
                WHERE staff_id = $staff_id AND status='Completed'
            ");
            $completed_services = mysqli_fetch_assoc($res)['completed_services'];

            // Pending Services
            $pending_services = $total_services - $completed_services;
            ?>

            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <div class="card shadow text-center p-3">
                        <h6>Total Assigned Services</h6>
                        <h4><?= $total_services ?></h4>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow text-center p-3 bg-warning text-white">
                        <h6>Pending Services</h6>
                        <h4><?= $pending_services ?></h4>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow text-center p-3 bg-success text-white">
                        <h6>Completed Services</h6>
                        <h4><?= $completed_services ?></h4>
                    </div>
                </div>
            </div>

            <!-- Recent Service Records -->
            <div class="mt-5">
                <h5>Recent Service Records</h5>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Vehicle</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "
                            SELECT sr.*, v.plate_number, c.full_name, s.service_name
                            FROM service_records sr
                            JOIN vehicles v ON sr.vehicle_id = v.vehicle_id
                            JOIN customers c ON v.customer_id = c.customer_id
                            JOIN services s ON sr.service_id = s.service_id
                            WHERE sr.staff_id = $staff_id
                            ORDER BY sr.service_date DESC
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
                                <td>{$row['status']}</td>
                                <td>{$row['service_date']}</td>
                                <td>
                                    <a href='service_record/update_status.php?id={$row['service_record_id']}' class='btn btn-sm btn-primary'>
                                        Update Status
                                    </a>
                                </td>
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

<?php require_once "../includes/footer.php"; ?>
