<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['service_name']);
    $cost = $_POST['cost'];

    if ($name && is_numeric($cost)) {
        $stmt = $conn->prepare(
            "INSERT INTO services (service_name, cost) VALUES (?, ?)"
        );
        $stmt->bind_param("sd", $name, $cost);
        $stmt->execute();

        header("Location: view.php");
        exit;
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../includes/sidebar_admin.php"; ?>

        <div class="col-md-10 p-4">
            <h4>Add Service</h4>

            <div class="card shadow-sm col-md-6">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Service Name</label>
                            <input type="text" name="service_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cost</label>
                            <input type="number" step="0.01" name="cost" class="form-control" required>
                        </div>

                        <button class="btn btn-success">Save</button>
                        <a href="view.php" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
