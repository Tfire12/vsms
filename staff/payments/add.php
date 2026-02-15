<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";

$staff_id = $_SESSION['user_id'];
$error = "";

// Handle form submission
if (isset($_POST['submit'])) {
    $service_record_id = (int) $_POST['service_record_id'];
    $amount = (float) $_POST['amount'];
    $payment_date = $_POST['payment_date'];

    // Check if payment already exists for this service record
    $check = mysqli_query($conn,
        "SELECT payment_id FROM payments WHERE service_record_id = $service_record_id"
    );

    if (mysqli_num_rows($check) > 0) {
        $error = "Payment already exists for this service record.";
    } else {
        $sql = "
            INSERT INTO payments (amount, payment_date, service_record_id)
            VALUES ('$amount', '$payment_date', '$service_record_id')
        ";
        mysqli_query($conn, $sql);

        header("Location: view.php");
        exit;
    }
}

// Fetch completed service records without payment
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
    ORDER BY sr.service_date DESC
");
?>

<div class="container-fluid">
    <div class="row">

        <?php include "../../includes/sidebar_staff.php"; ?>

        <div class="col-md-10 p-4">
            <h4>Add Payment</h4>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST" class="card shadow p-4 col-md-6">

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

                <button type="submit" name="submit" class="btn btn-success">
                    <i class="bi bi-cash"></i> Save Payment
                </button>

                <a href="view.php" class="btn btn-secondary ms-2">Back</a>
            </form>
        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
