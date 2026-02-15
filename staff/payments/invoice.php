<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";

if (!isset($_GET['payment_id'])) {
    echo "<div class='alert alert-danger'>Invalid request</div>";
    exit;
}

$payment_id = (int)$_GET['payment_id'];

$sql = "
SELECT 
    p.amount,
    p.payment_date,
    c.full_name AS customer_name,
    c.phone,
    v.plate_number,
    s.service_name,
    sr.service_date,
    st.full_name AS staff_name
FROM payments p
JOIN service_records sr ON p.service_record_id = sr.service_record_id
JOIN vehicles v ON sr.vehicle_id = v.vehicle_id
JOIN customers c ON v.customer_id = c.customer_id
JOIN services s ON sr.service_id = s.service_id
JOIN staff st ON sr.staff_id = st.staff_id
WHERE p.payment_id = ?
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<div class='alert alert-danger'>Payment not found</div>";
    exit;
}

$row = $result->fetch_assoc();
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-body">

            <div class="text-center mb-4">
                <!-- optional logo -->
                <!-- <img src="../../assets/images/logo.png" height="80"> -->
                <h4 class="mt-2">Vehicle Service Payment Invoice</h4>
                <small class="text-muted">VSMS</small>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Customer:</strong> <?= htmlspecialchars($row['customer_name']) ?><br>
                    <strong>Phone:</strong> <?= htmlspecialchars($row['phone']) ?><br>
                    <strong>Vehicle:</strong> <?= htmlspecialchars($row['plate_number']) ?>
                </div>

                <div class="col-md-6 text-end">
                    <strong>Service:</strong> <?= htmlspecialchars($row['service_name']) ?><br>
                    <strong>Service Date:</strong> <?= $row['service_date'] ?><br>
                    <strong>Payment Date:</strong> <?= $row['payment_date'] ?>
                </div>
            </div>

            <table class="table table-bordered">
                <tr>
                    <th>Description</th>
                    <th class="text-end">Amount (TZS)</th>
                </tr>
                <tr>
                    <td><?= htmlspecialchars($row['service_name']) ?></td>
                    <td class="text-end"><?= number_format($row['amount'], 2) ?></td>
                </tr>
                <tr class="table-dark">
                    <th>Total</th>
                    <th class="text-end"><?= number_format($row['amount'], 2) ?></th>
                </tr>
            </table>

            <p class="mt-3">
                <strong>Received By:</strong> <?= htmlspecialchars($row['staff_name']) ?>
            </p>

            <div class="text-end">
                <a href="view.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <!-- PDF step next -->
                <a href="invoice_pdf.php?payment_id=<?= $payment_id ?>" class="btn btn-primary">
                    <i class="bi bi-download"></i> Download PDF
                </a>
            </div>

        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
