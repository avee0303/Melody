<?php
session_start();
include("config/db_connect.php");

// Fetch Deliveries
$deliveryQuery = $conn->query("
    SELECT d.*, o.customer_name, o.product_name 
    FROM deliveries d 
    JOIN orders o ON d.order_id = o.id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Deliveries</title>

    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Manage Deliveries</h2>

    <?php if (isset($_GET['success'])) echo "<p class='success'>{$_GET['success']}</p>"; ?>

    <h3>Delivery List</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Product</th>
            <th>Delivery Staff</th>
            <th>Estimated Time</th>
            <th>Delivery Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($delivery = $deliveryQuery->fetch_assoc()): ?>
        <tr>
            <td><?= $delivery['id'] ?></td>
            <td><?= $delivery['customer_name'] ?></td>
            <td><?= $delivery['product_name'] ?></td>
            <td><?= $delivery['delivery_staff'] ?></td>
            <td><?= $delivery['estimated_time'] ?></td>
            <td><?= $delivery['delivery_status'] ?></td>
            <td>
                <a href="edit_delivery.php?id=<?= $delivery['id'] ?>">Update</a>
                <a href="delete_delivery.php?id=<?= $delivery['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <script src="js/scripts.js" defer></script>
</body>
</html>