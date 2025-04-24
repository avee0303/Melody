<?php
session_start();
include("config/db_connect.php");

// Fetch Products
$productQuery = $conn->query("SELECT * FROM product");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Manage Products</h2>

    <?php if (isset($_GET['success'])): ?>
        <p style="color: green; font-weight: bold;">
            <?= htmlspecialchars($_GET['success']) ?>
        </p>
    <?php endif; ?>

    <?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <a href="add_product.php">Add New Product</a>

    <h3>Product List</h3>
    <table border="1">
        <tr>
            <th>Product ID</th>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Description</th>
            <th>Category</th> <!-- New column -->
            <th>Product Price (RM)</th>
            <th>Actions</th>
        </tr>
        <?php while ($product = $productQuery->fetch_assoc()): ?>
        <tr>
            <td><?= $product['id'] ?></td>
            <td>
                <img src="uploads/<?= htmlspecialchars($product['image']) ?>" width="50">
            </td>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= htmlspecialchars($product['description']) ?></td>
            <td><?= htmlspecialchars($product['category']) ?></td> <!-- Display category -->
            <td>RM <?= number_format($product['price'], 2) ?></td> <!-- Price formatting -->
            <td>
                <a href="edit_product.php?id=<?= $product['id'] ?>">Edit</a>
                <a href="delete_product.php?id=<?= $product['id'] ?>">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <script src="js/scripts.js" defer></script>
</body>
</html>