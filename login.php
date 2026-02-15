<?php
session_start();
require_once "config/config.php";
require_once "config/db.php";

$error = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']); // email or username
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please enter username/email and password";
    } else {

        /* =========================
           1. CHECK STAFF TABLE
        ========================== */
        $sql = "SELECT staff_id AS id, full_name, role, password 
                FROM staff 
                WHERE username = ? 
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role']    = strtolower($user['role']); // manager | technician
                $_SESSION['name']    = $user['full_name'];

                if ($user['role'] === 'Manager') {
                    header("Location: " . BASE_URL . "admin/dashboard.php");
                } else {
                    header("Location: " . BASE_URL . "staff/dashboard.php");
                }
                exit;
            }
        }

        /* =========================
           2. CHECK CUSTOMERS TABLE
        ========================== */
        $sql = "SELECT customer_id AS id, full_name, password 
                FROM customers 
                WHERE email = ? AND status = 'active'
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $customer = $result->fetch_assoc();

            if (password_verify($password, $customer['password'])) {

                $_SESSION['user_id'] = $customer['id'];
                $_SESSION['role']    = 'customer';
                $_SESSION['name']    = $customer['full_name'];

                header("Location: " . BASE_URL . "customers/dashboard.php");
                exit;
            }
        }

        $error = "Invalid username/email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VSMS Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">

                    <div class="text-center mb-4">
                        <i class="bi bi-car-front-fill fs-1 text-primary"></i>
                        <h4 class="mt-2">VSMS Login</h4>
                        <p class="text-muted">Vehicle Service Management System</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger text-center">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email / Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="login" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <p class="text-center text-muted mt-3">
                &copy; <?= date('Y'); ?> VSMS
            </p>
        </div>
    </div>
</div>

</body>
</html>
