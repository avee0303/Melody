<?php
session_start();
include("config/db_connect.php");

// Check if superadmin is logged in
if (!isset($_SESSION['superadmin_id'])) {
    header("Location: superadmin_login.php");
    exit();
}

// Fetch deliveries
$sql = "SELECT * FROM deliveries";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Deliveries</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Delivery List</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Staff</th>
                <th>Status</th>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['order_id']) ?></td>
                    <td><?= htmlspecialchars($row['staff']) ?></td>
                    <td><?= htmlspecialchars($row['delivery_status']) ?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['time']) ?></td>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>