<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";

$staff_id = $_SESSION['user_id'];
$error = "";
$success = "";

/* =========================
   HANDLE ADD PAYMENT
========================= */
if (isset($_POST['add_payment'])) {
    $service_record_id = (int) $_POST['service_record_id'];
    $amount = (float) $_POST['amount'];
    $payment_date = $_POST['payment_date'];

    $check = mysqli_query($conn,
        "SELECT payment_id FROM payments WHERE service_record_id = $service_record_id"
    );

    if (mysqli_num_rows($check) > 0) {
        $error = "Payment already exists for this service record.";
    } else {
        mysqli_query($conn, "
            INSERT INTO payments (amount, payment_date, service_record_id)
            VALUES ('$amount', '$payment_date', '$service_record_id')
        ");
        $success = "Payment recorded successfully.";
    }
}

/* =========================
   SERVICE RECORDS (NO PAYMENT)
========================= */
$records = mysqli_query($conn, "
    SELECT sr.service_record_id,
           c.full_name,
           v.plate_number,
           s.service_name
    FROM service_records sr
    JOIN vehicles v ON sr.vehicle_id = v.vehicle_id
    JOIN customers c ON v.customer_id = c.customer_id
    JOIN services s ON sr.service_id = s.service_id
    LEFT JOIN payments p ON sr.service_record_id = p.service_record_id
    WHERE sr.staff_id = $staff_id
      AND sr.status = 'Completed'
      AND p.payment_id IS NULL
");
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../includes/sidebar_staff.php"; ?>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Payments Received</h4>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                    <i class="bi bi-plus-circle"></i> Add Payment
                </button>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success; ?></div>
            <?php endif; ?>

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

<!-- ======================
     ADD PAYMENT MODAL
====================== -->
<div class="modal fade" id="addPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Service Record</label>
                    <select name="service_record_id" class="form-select" required>
                        <option value="">Select Service</option>
                        <?php while ($r = mysqli_fetch_assoc($records)): ?>
                            <option value="<?= $r['service_record_id']; ?>">
                                <?= $r['full_name']; ?> |
                                <?= $r['plate_number']; ?> |
                                <?= $r['service_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Amount (TZS)</label>
                    <input type="number" step="0.01" name="amount" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Payment Date</label>
                    <input type="date" name="payment_date" class="form-control"
                           value="<?= date('Y-m-d'); ?>" required>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" name="add_payment" class="btn btn-success">
                    <i class="bi bi-cash"></i> Save Payment
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
