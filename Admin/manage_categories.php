<?php
session_start();
include("config/db_connect.php");

// Fetch Categories
$categoryQuery = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>

    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Manage Categories</h2>

    <?php if (isset($_GET['success'])): ?>
        <p style="color: green; font-weight: bold;">
            <?= htmlspecialchars($_GET['success']) ?>
        </p>
    <?php endif; ?>

    <?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <a href="add_category.php">Add New Category</a>

    <h3>Category List</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Category Name</th>
            <th>Actions</th>
        </tr>
        <?php while ($category = $categoryQuery->fetch_assoc()): ?>
        <tr>
            <td><?= $category['id'] ?></td>
            <td><?= $category['name'] ?></td>
            <td>
                <a href="edit_category.php?id=<?= $category['id'] ?>">Edit</a>
                <a href="delete_category.php?id=<?= $category['id'] ?>">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="admin_dashboard.php" class="logout-link">Back to Dashboard</a>

    <script src="js/scripts.js" defer></script>
</body>
</html>