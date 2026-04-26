<?php
require_once "../../config/config.php";
require_once "../../config/db.php";
require_once "../../includes/auth_check.php";
require_once "../../includes/header.php";
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../includes/sidebar_admin.php"; ?>

        <div class="col-md-10 p-4">
            <h4>Staff Members</h4>
            <a href="add.php" class="btn btn-primary mb-3">
                <i class="bi bi-plus-circle"></i> Add Staff
            </a>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Role</th>
                        <th>Phone</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM staff ORDER BY full_name ASC");
                    $i = 1;
                    while($row = mysqli_fetch_assoc($res)) {
                        echo "<tr>
                            <td>{$i}</td>
                            <td>{$row['full_name']}</td>
                            <td>{$row['role']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['username']}</td>
                            <td>
                                <a href='edit.php?id={$row['staff_id']}' class='btn btn-sm btn-warning'>
                                    <i class='bi bi-pencil-square'></i> Edit
                                </a>
                                <a href='delete.php?id={$row['staff_id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'>
                                    <i class='bi bi-trash'></i> Delete
                                </a>
                            </td>
                        </tr>";
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once "../../includes/footer.php"; ?>
