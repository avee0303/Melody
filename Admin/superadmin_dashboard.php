<?php
session_start();
if (!isset($_SESSION['superadmin_id'])) {
    header("Location: superadmin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Dashboard</title>
    
    <link rel="stylesheet" href="css/styles.css">

    <style>
        .button-container {
            margin-top: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .admin-button {
            background-color: #ff6600;
            border: none;
            color: white;
            padding: 15px 25px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .admin-button:hover {
            background-color: #e65c00;
        }

        .logout-link {
            display: inline-block;
            margin-top: 40px;
            color: #fff;
            background-color: #cc0000;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
        }

        .logout-link:hover {
            background-color: #a80000;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <img src="images/4.0logo.jpg" alt="4.0 Burger Logo" class="logo">
        Burger King Superadmin Panel
    </div>

    <h2>Welcome, Superadmin!</h2>
    
    <div class="button-container">
        <a href="manage_staffs.php" class="admin-button">Manage Staffs</a>
        <a href="view_customers.php" class="admin-button">View Customers</a>
        <a href="view_categories.php" class="admin-button">View Categories</a>
        <a href="view_products.php" class="admin-button">View Products</a>
        <a href="view_orders.php" class="admin-button">View Orders</a>
        <a href="view_deliveries.php" class="admin-button">View Deliveries</a>
    </div>

    <a href="superadmin_logout.php" class="logout-link">Logout</a>

    <script src="js/scripts.js" defer></script>
</body>
</html>