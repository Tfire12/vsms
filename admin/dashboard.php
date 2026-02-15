<?php
require_once "../config/config.php";
require_once "../config/db.php";
require_once "../includes/auth_check.php";
require_once "../includes/header.php";

// --- Prepare Data for Charts ---

// Monthly Revenue (last 12 months)
$months = [];
$revenues = [];
$res = mysqli_query($conn, "
    SELECT DATE_FORMAT(payment_date, '%Y-%m') AS month, SUM(amount) AS revenue
    FROM payments
    WHERE payment_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY month
    ORDER BY month ASC
");
while ($row = mysqli_fetch_assoc($res)) {
    $months[] = $row['month'];
    $revenues[] = $row['revenue'];
}

// Vehicles per Type
$vehicle_types = [];
$vehicle_counts = [];
$res = mysqli_query($conn, "
    SELECT vehicle_type, COUNT(*) AS count
    FROM vehicles
    GROUP BY vehicle_type
");
while ($row = mysqli_fetch_assoc($res)) {
    $vehicle_types[] = $row['vehicle_type'] ?: 'Unknown';
    $vehicle_counts[] = $row['count'];
}

// Services performed per month (last 12 months)
$service_months = [];
$service_counts = [];
$res = mysqli_query($conn, "
    SELECT DATE_FORMAT(service_date, '%Y-%m') AS month, COUNT(*) AS count
    FROM service_records
    WHERE service_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY month
    ORDER BY month ASC
");
while ($row = mysqli_fetch_assoc($res)) {
    $service_months[] = $row['month'];
    $service_counts[] = $row['count'];
}

// JSON encode for Chart.js
$months_json = json_encode($months);
$revenues_json = json_encode($revenues);
$vehicle_types_json = json_encode($vehicle_types);
$vehicle_counts_json = json_encode($vehicle_counts);
$service_months_json = json_encode($service_months);
$service_counts_json = json_encode($service_counts);

// --- Summary Cards ---
$total_customers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total_customers FROM customers"))['total_customers'];
$total_vehicles = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total_vehicles FROM vehicles"))['total_vehicles'];
$total_services = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total_services FROM services"))['total_services'];
$total_staff    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total_staff FROM staff"))['total_staff'];
$total_revenue  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) AS total_revenue FROM payments"))['total_revenue'];
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../includes/sidebar_admin.php"; ?>

        <div class="col-md-10 p-4">
            <h3>Admin Dashboard</h3>
            <p>Welcome, <strong><?= $_SESSION['name'] ?></strong></p>

            <!-- Cards Summary -->
            <div class="row g-3 mt-3">
                <div class="col-md-3">
                    <div class="card shadow text-center p-3">
                        <h6>Total Customers</h6>
                        <h4><?= $total_customers ?></h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow text-center p-3">
                        <h6>Total Vehicles</h6>
                        <h4><?= $total_vehicles ?></h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow text-center p-3">
                        <h6>Total Services</h6>
                        <h4><?= $total_services ?></h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow text-center p-3">
                        <h6>Total Staff</h6>
                        <h4><?= $total_staff ?></h4>
                    </div>
                </div>

                <div class="col-md-3 mt-3">
                    <div class="card shadow text-center p-3 bg-success text-white">
                        <h6>Total Revenue</h6>
                        <h4>TZS <?= number_format($total_revenue, 2) ?></h4>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row mt-4 g-3">
                <div class="col-md-6">
                    <div class="card shadow p-3">
                        <h5>Revenue in Last 12 Months</h5>
                        <canvas id="revenueChart" height="150"></canvas>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow p-3">
                        <h5>Vehicles per Type</h5>
                        <canvas id="vehicleChart" height="150"></canvas>
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <div class="card shadow p-3">
                        <h5>Services Performed (Last 12 Months)</h5>
                        <canvas id="serviceChart" height="150"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Revenue Chart
var ctxRevenue = document.getElementById('revenueChart').getContext('2d');
new Chart(ctxRevenue, {
    type: 'bar',
    data: {
        labels: <?= $months_json ?>,
        datasets: [{
            label: 'Revenue (TZS)',
            data: <?= $revenues_json ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

// Vehicle Type Chart
var ctxVehicle = document.getElementById('vehicleChart').getContext('2d');
new Chart(ctxVehicle, {
    type: 'pie',
    data: {
        labels: <?= $vehicle_types_json ?>,
        datasets: [{
            label: 'Vehicle Count',
            data: <?= $vehicle_counts_json ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)'
            ]
        }]
    },
    options: { responsive: true }
});

// Services Chart
var ctxService = document.getElementById('serviceChart').getContext('2d');
new Chart(ctxService, {
    type: 'line',
    data: {
        labels: <?= $service_months_json ?>,
        datasets: [{
            label: 'Services Performed',
            data: <?= $service_counts_json ?>,
            fill: false,
            borderColor: 'rgba(255, 159, 64, 1)',
            tension: 0.1
        }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>

<?php require_once "../includes/footer.php"; ?>
