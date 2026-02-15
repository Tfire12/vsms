<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: view.php");
    exit;
}

$service = $conn->query("SELECT * FROM services WHERE service_id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['service_name'];
    $cost = $_POST['cost'];

    $stmt = $conn->prepare(
        "UPDATE services SET service_name=?, cost=? WHERE service_id=?"
    );
    $stmt->bind_param("sdi", $name, $cost, $id);
    $stmt->execute();

    header("Location: view.php");
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../includes/sidebar_admin.php"; ?>

        <div class="col-md-10 p-4">
            <h4>Edit Service</h4>

            <div class="card shadow-sm col-md-6">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Service Name</label>
                            <input type="text" name="service_name" class="form-control"
                                   value="<?= htmlspecialchars($service['service_name']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Cost</label>
                            <input type="number" step="0.01" name="cost" class="form-control"
                                   value="<?= $service['cost']; ?>" required>
                        </div>

                        <button class="btn btn-primary">Update</button>
                        <a href="view.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
