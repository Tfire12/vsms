<?php
require_once "../../config/config.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";
?>

<div class="container-fluid">
    <div class="row">

        <?php include "../../includes/sidebar_admin.php"; ?>

        <div class="col-md-10 p-4">
            <h4>Add Payment</h4>

            <form method="POST">
                <div class="mb-3">
                    <label>Service Record</label>
                    <select name="service_record_id" class="form-select" required>
                        <option value="">-- Select Service --</option>
                        <?php
                        $sr = mysqli_query($conn, "
                            SELECT sr.service_record_id,
                                   c.full_name,
                                   v.plate_number,
                                   s.service_name
                            FROM service_records sr
                            JOIN vehicles v ON sr.vehicle_id = v.vehicle_id
                            JOIN customers c ON v.customer_id = c.customer_id
                            JOIN services s ON sr.service_id = s.service_id
                            WHERE sr.status = 'Completed'
                              AND sr.service_record_id NOT IN (
                                  SELECT service_record_id FROM payments
                              )
                        ");

                        while ($row = mysqli_fetch_assoc($sr)) {
                            echo "<option value='{$row['service_record_id']}'>
                                {$row['full_name']} - {$row['plate_number']} ({$row['service_name']})
                            </option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Amount (TZS)</label>
                    <input type="number" name="amount" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Payment Date</label>
                    <input type="date" name="payment_date" class="form-control" required>
                </div>

                <button name="save" class="btn btn-success">
                    Save Payment
                </button>
                <a href="view.php" class="btn btn-secondary">Back</a>
            </form>

            <?php
            if (isset($_POST['save'])) {
                $service_record_id = $_POST['service_record_id'];
                $amount = $_POST['amount'];
                $date = $_POST['payment_date'];

                mysqli_query($conn, "
                    INSERT INTO payments (service_record_id, amount, payment_date)
                    VALUES ('$service_record_id', '$amount', '$date')
                ");

                echo "<script>
                    alert('Payment recorded successfully');
                    window.location='view.php';
                </script>";
            }
            ?>
        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
