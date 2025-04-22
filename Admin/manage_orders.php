<?php
session_start();
include 'db_connect.php';

// Fetch Orders
$orderQuery = $conn->query("SELECT * FROM orders");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Manage Orders</h2>

    <?php if (isset($_GET['success'])) echo "<p class='success'>{$_GET['success']}</p>"; ?>

    <h3>Order List</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($order = $orderQuery->fetch_assoc()): ?>
        <tr>
            <td><?= $order['id'] ?></td>
            <td><?= $order['customer_name'] ?></td>
            <td><?= $order['product_name'] ?></td>
            <td><?= $order['quantity'] ?></td>
            <td>$<?= number_format($order['total_price'], 2) ?></td>
            <td><?= $order['status'] ?></td>
            <td>
                <a href="edit_order.php?id=<?= $order['id'] ?>">Update Status</a>
                <a href="delete_order.php?id=<?= $order['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>