<?php
session_start();
require_once "config/config.php";
require_once "config/db.php";

if(!isset($_SESSION['reset_user'])) {
    header("Location: login.php");
    exit;
}

$msg = "";

if(isset($_POST['reset'])) {
    $new = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user = $_SESSION['reset_user'];

    mysqli_query($conn,
        "UPDATE staff SET password='$new' WHERE username='$user'"
    );
    mysqli_query($conn,
        "UPDATE customers SET password='$new' WHERE email='$user'"
    );

    unset($_SESSION['reset_user']);
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 col-md-4">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="text-center">Reset Password</h4>

            <form method="POST">
                <label>New Password</label>
                <input type="password" name="password" class="form-control" required>

                <button name="reset" class="btn btn-success w-100 mt-3">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
