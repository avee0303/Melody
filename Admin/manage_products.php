<?php
session_start();
include 'db_connect.php';

// Fetch Products
$productQuery = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Manage Products</h2>

    <?php if (isset($_GET['success'])) echo "<p class='success'>{$_GET['success']}</p>"; ?>

    <a href="add_product.php">Add New Product</a>

    <h3>Product List</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php while ($product = $productQuery->fetch_assoc()): ?>
        <tr>
            <td><?= $product['id'] ?></td>
            <td><img src="<?= $product['image'] ?>" width="50"></td>
            <td><?= $product['name'] ?></td>
            <td><?= $product['description'] ?></td>
            <td>$<?= $product['price'] ?></td>
            <td>
                <a href="edit_product.php?id=<?= $product['id'] ?>">Edit</a>
                <a href="delete_product.php?id=<?= $product['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>