<?php
session_start();
include("config/db_connect.php");

// Fetch deliveries
$sql = "SELECT * FROM deliveries";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Deliveries</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        img {
            width: 80px;
            height: auto;
        }

        .logout-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Delivery List</h2>
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

<a href="new_superadmin_dashboard.php" class="logout-link">Back to Dashboard</a>

</html>