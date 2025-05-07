<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'burger4.0';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getTotal($conn, $table) {
    $sql = "SELECT COUNT(*) AS total FROM $table";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

$staffTotal = getTotal($conn, 'admin');
$orderTotal = getTotal($conn, 'orders');
$customerTotal = getTotal($conn, 'customer');
$deliveryTotal = getTotal($conn, 'deliveries');
$categoryTotal = getTotal($conn, 'categories');
$productTotal = getTotal($conn, 'products');

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Superadmin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        h1 { text-align: center; }
        .chart-container {
            width: 60%;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<h1>Superadmin Dashboard Overview</h1>

<div class="chart-container">
    <canvas id="dashboardChart"></canvas>
</div>

<script>
    const ctx = document.getElementById('dashboardChart').getContext('2d');

    const data = {
        labels: ['Staff', 'Orders', 'Customers', 'Deliveries', 'Categories', 'Products'],
        datasets: [{
            label: 'Totals',
            data: [
                <?= $staffTotal ?>,
                <?= $orderTotal ?>,
                <?= $customerTotal ?>,
                <?= $deliveryTotal ?>,
                <?= $categoryTotal ?>,
                <?= $productTotal ?>
            ],
            backgroundColor: [
                '#4e73df',
                '#1cc88a',
                '#36b9cc',
                '#f6c23e',
                '#e74a3b',
                '#858796'
            ]
        }]
    };

    const config = {
        type: 'pie',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                title: {
                    display: true,
                    text: 'Overview of Database Entities'
                },
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold', size: 14 },
                    formatter: (value, context) => value
                }
            }
        },
        plugins: [ChartDataLabels]
    };

    const chart = new Chart(ctx, config);

    // Redirect on slice click
    document.getElementById('dashboardChart').onclick = function(evt) {
        const points = chart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
        if (points.length) {
            const label = chart.data.labels[points[0].index];
            const redirectMap = {
                'Staff': 'manage_staffs.php',
                'Orders': 'view_orders.php',
                'Customers': 'view_customers.php',
                'Deliveries': 'view_deliveries.php',
                'Categories': 'view_categories.php',
                'Products': 'view_products.php'
            };

            if (redirectMap[label]) {
                window.location.href = redirectMap[label];
            }
        }
    };
</script>

</body>
</html>
