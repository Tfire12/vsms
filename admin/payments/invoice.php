<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";

$payment_id = $_GET['payment_id'] ?? 0;

$sql = "
    SELECT p.payment_id, p.amount, p.payment_date,
           c.full_name, c.phone,
           v.plate_number, v.vehicle_type,
           s.service_name, s.cost
    FROM payments p
    JOIN service_records sr ON p.service_record_id = sr.service_record_id
    JOIN vehicles v ON sr.vehicle_id = v.vehicle_id
    JOIN customers c ON v.customer_id = c.customer_id
    JOIN services s ON sr.service_id = s.service_id
    WHERE p.payment_id = '$payment_id'
";

$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Invoice not found");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?= $data['payment_id'] ?></title>
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-4">
    <div class="card shadow p-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h4>Vehicle Service Management System</h4>
                <p>
                    Phone: +255 XXX XXX XXX<br>
                    Location: Tanzania
                </p>
            </div>
            <div class="col-md-6 text-end">
                <h5>INVOICE</h5>
                <p>
                    Invoice #: <?= $data['payment_id'] ?><br>
                    Date: <?= $data['payment_date'] ?>
                </p>
            </div>
        </div>

        <hr>

        <h6>Customer Details</h6>
        <p>
            Name: <strong><?= $data['full_name'] ?></strong><br>
            Phone: <?= $data['phone'] ?>
        </p>

        <h6>Vehicle Details</h6>
        <p>
            Plate Number: <?= $data['plate_number'] ?><br>
            Type: <?= $data['vehicle_type'] ?>
        </p>

        <table class="table table-bordered mt-3">
            <thead class="table-secondary">
                <tr>
                    <th>Service</th>
                    <th>Cost (TZS)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $data['service_name'] ?></td>
                    <td><?= number_format($data['amount'], 2) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="text-end">
            <h5>Total: TZS <?= number_format($data['amount'], 2) ?></h5>
        </div>

        <div class="mt-4 text-center">
            <button onclick="window.print()" class="btn btn-primary">
                Print Invoice
            </button>

            <a href="invoice_pdf.php?payment_id=<?= $data['payment_id'] ?>"
               class="btn btn-danger">
                Download PDF
            </a>
        </div>
    </div>
</div>
</body>
</html>
