<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../includes/sidebar_staff.php"; ?>

        <div class="col-md-10 p-4">
            <h4>My Service Records</h4>
            <a href="add.php" class="btn btn-primary mb-3">
                <i class="bi bi-plus-circle"></i> Add Service Record
            </a>

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
                    $staff_id = $_SESSION['user_id'];
                    $res = mysqli_query($conn, "
                        SELECT sr.*, v.plate_number, c.full_name, s.service_name
                        FROM service_records sr
                        JOIN vehicles v ON sr.vehicle_id = v.vehicle_id
                        JOIN customers c ON v.customer_id = c.customer_id
                        JOIN services s ON sr.service_id = s.service_id
                        WHERE sr.staff_id = $staff_id
                        ORDER BY sr.service_date DESC
                    ");
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
                                <a href='update_status.php?id={$row['service_record_id']}' class='btn btn-sm btn-primary'>
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

<?php require_once "../../includes/footer.php"; ?>
