<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";

// TCPDF
require_once "../../libs/tcpdf/tcpdf.php";

if (!isset($_GET['payment_id'])) {
    die("Invalid request");
}

$payment_id = (int)$_GET['payment_id'];
$staff_id   = $_SESSION['user_id']; // 🔐 security

/* =========================
   FETCH PAYMENT (STAFF ONLY)
========================= */
$sql = "
SELECT 
    p.payment_id,
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
AND sr.staff_id = ?
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $payment_id, $staff_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Unauthorized or invoice not found");
}

$row = $result->fetch_assoc();

/* =========================
   TCPDF SETUP
========================= */
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('VSMS');
$pdf->SetAuthor('VSMS');
$pdf->SetTitle('Payment Invoice');
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->AddPage();

/* =========================
   COMPANY LOGO & HEADER
========================= */
$pdf->Image('../../assets/images/logo.png', 15, 10, 30); // 🔴 adjust path if needed

$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 8, 'Vehicle Service Management System', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 6, 'P.O Box 123, Dar es Salaam, Tanzania', 0, 1, 'C');
$pdf->Cell(0, 6, 'Phone: +255 700 000 000 | Email: info@vsms.co.tz', 0, 1, 'C');

$pdf->Ln(10);

/* =========================
   INVOICE TITLE
========================= */
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'PAYMENT INVOICE', 0, 1, 'C');

$pdf->Ln(4);

/* =========================
   INVOICE META
========================= */
$pdf->SetFont('helvetica', '', 11);

$html = '
<table cellpadding="6">
<tr>
    <td width="50%">
        <strong>Invoice No:</strong> VSMS-'.str_pad($row['payment_id'], 5, '0', STR_PAD_LEFT).'<br>
        <strong>Status:</strong> <span style="color:green;"><b>PAID</b></span><br>
        <strong>Payment Date:</strong> '.$row['payment_date'].'
    </td>
    <td width="50%">
        <strong>Customer:</strong> '.$row['customer_name'].'<br>
        <strong>Phone:</strong> '.$row['phone'].'<br>
        <strong>Vehicle:</strong> '.$row['plate_number'].'
    </td>
</tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln(6);

/* =========================
   SERVICE & PAYMENT TABLE
========================= */
$html = '
<table border="1" cellpadding="8">
<tr style="background-color:#f2f2f2;">
    <th width="60%">Service</th>
    <th width="20%">Service Date</th>
    <th width="20%" align="right">Amount (TZS)</th>
</tr>
<tr>
    <td>'.$row['service_name'].'</td>
    <td>'.$row['service_date'].'</td>
    <td align="right">'.number_format($row['amount'], 2).'</td>
</tr>
<tr>
    <td colspan="2"><strong>Total</strong></td>
    <td align="right"><strong>'.number_format($row['amount'], 2).'</strong></td>
</tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln(10);

/* =========================
   FOOTER
========================= */
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 6, 'Received By: '.$row['staff_name'], 0, 1);
$pdf->Ln(6);
$pdf->Cell(0, 6, 'This is a system generated receipt.', 0, 1, 'C');
$pdf->Cell(0, 6, 'Thank you for choosing VSMS.', 0, 1, 'C');

/* =========================
   OUTPUT
========================= */
$pdf->Output('invoice_'.$row['payment_id'].'.pdf', 'I');
