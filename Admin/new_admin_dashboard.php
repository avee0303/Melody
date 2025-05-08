<?php
// database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'burger4.0';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get total count
function getTotal($conn, $table) {
    $sql = "SELECT COUNT(*) AS total FROM $table";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}
// Function to get total order price
function getTotalOrderPrice($conn) {
    $sql = "SELECT SUM(total_price) AS total_price FROM orders";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total_price'] ?? 0;
}

// Fetch totals
$staffTotal = getTotal($conn, 'admin');
$orderTotal = getTotal($conn, 'orders');
$customerTotal = getTotal($conn, 'customer');
$deliveryTotal = getTotal($conn, 'deliveries');
$categoryTotal = getTotal($conn, 'categories');
$productTotal = getTotal($conn, 'products');
$totalOrderPrice = getTotalOrderPrice($conn);

// Fetch order list
$orderList = [];
$sql = "SELECT id, customer_email,checkout_id, total_price FROM orders";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orderList[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .dashboard { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .card h2 { margin: 0; font-size: 2.5em; color: #333; }
        .card p { margin: 10px 0 0; color: #666; }
    </style>
</head>
<body>

<h1>Admin Dashboard</h1>
<link rel="stylesheet" href="css/styles.css">

<div class="dashboard">
    <a href="manage_orders.php" class="card-link">
        <div class="card">
            <h2><?= $orderTotal ?></h2>
            <p>Orders</p>
        </div>
    </a>
    <a href="manage_customers.php" class="card-link">
        <div class="card">
            <h2><?= $customerTotal ?></h2>
            <p>Customers</p>
        </div>
    </a>
    <a href="manage_deliveries.php" class="card-link">
        <div class="card">
            <h2><?= $deliveryTotal ?></h2>
            <p>Deliveries</p>
        </div>
    </a>
    <a href="manage_categories.php" class="card-link">
        <div class="card">
            <h2><?= $categoryTotal ?></h2>
            <p>Categories</p>
        </div>
    </a>
    <a href="manage_products.php" class="card-link">
        <div class="card">
            <h2><?= $productTotal ?></h2>
            <p>Products</p>
        </div>
    </a>
    <div class="card">
        <h2>RM<?= number_format($totalOrderPrice, 2) ?></h2>
        <p>Total Sales</p>
    </div>

    <h2>Order List</h2>
    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; background:#fff; border-collapse: collapse; margin-top: 20px;">
        <thead style="background:#eee;">
            <tr>
                <th>Order ID</th>
                <th>Customer Email</th>
                <th>Checkout ID</th>
                <th>Total Price (RM)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orderList)): ?>
                <?php foreach ($orderList as $order): ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= $order['customer_email'] ?></td>
                        <td><?= $order['checkout_id'] ?></td>
                        <td><?= number_format($order['total_price'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" style="text-align:center;">No orders found.</td></tr>
            <?php endif; ?>
        </tbody>
</table>

</div>

</body>
</html>
