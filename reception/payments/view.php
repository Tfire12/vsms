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
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Payments</h4>
                <a href="add.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Payment
                </a>
            </div>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Invoice</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "
                    SELECT p.payment_id, p.amount, p.payment_date,
                           c.full_name,
                           v.plate_number,
                           s.service_name
                    FROM payments p
                    JOIN service_records sr ON p.service_record_id = sr.service_record_id
                    JOIN vehicles v ON sr.vehicle_id = v.vehicle_id
                    JOIN customers c ON v.customer_id = c.customer_id
                    JOIN services s ON sr.service_id = s.service_id
                    ORDER BY p.payment_date DESC
                ";

                $result = mysqli_query($conn, $sql);
                $i = 1;

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$i}</td>
                        <td>{$row['full_name']}</td>
                        <td>{$row['plate_number']}</td>
                        <td>{$row['service_name']}</td>
                        <td>TZS " . number_format($row['amount'], 2) . "</td>
                        <td>{$row['payment_date']}</td>
                        <td>
                            <a href='invoice.php?payment_id={$row['payment_id']}' class='btn btn-sm btn-info'>
                                <i class='bi bi-receipt'></i> View Invoice
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
