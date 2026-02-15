<?php
require_once "config/config.php";
require_once "config/db.php";

$msg = "";

if(isset($_POST['submit'])) {
    $email = trim($_POST['email']);

    // check staff
    $q1 = mysqli_query($conn, "SELECT * FROM staff WHERE username='$email'");
    $q2 = mysqli_query($conn, "SELECT * FROM customers WHERE email='$email'");

    if(mysqli_num_rows($q1) || mysqli_num_rows($q2)) {
        $_SESSION['reset_user'] = $email;
        header("Location: reset_password.php");
        exit;
    } else {
        $msg = "Account not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 col-md-4">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="text-center">Forgot Password</h4>

            <?php if($msg): ?>
                <div class="alert alert-danger"><?= $msg ?></div>
            <?php endif; ?>

            <form method="POST">
                <label>Email / Username</label>
                <input type="text" name="email" class="form-control" required>

                <button name="submit" class="btn btn-primary w-100 mt-3">
                    Continue
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
