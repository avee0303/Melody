<?php
session_start();
include("config/db_connect.php");

// Check if superadmin is logged in
if (!isset($_SESSION['superadmin_id'])) {
    header("Location: superadmin_login.php");
    exit();
}

// Fetch categories
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Categories</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Category List</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Category ID</th>
                <th>Category Name</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>