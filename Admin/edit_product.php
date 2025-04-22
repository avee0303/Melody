<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image = $product['image'];

        // Image Update
        if (!empty($_FILES["image"]["name"])) {
            $image = "uploads/" . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $image);
        }

        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?");
        $stmt->bind_param("ssdsi", $name, $description, $price, $image, $id);

        if ($stmt->execute()) {
            header("Location: manage_products.php?success=Product updated successfully");
            exit();
        } else {
            $error = "Error updating product.";
        }
    }
} else {
    header("Location: manage_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
</head>
<body>
    <h2>Edit Product</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" value="<?= $product['name'] ?>" required>

        <label>Description:</label>
        <textarea name="description" required><?= $product['description'] ?></textarea>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>

        <label>Image:</label>
        <input type="file" name="image">
        <img src="<?= $product['image'] ?>" width="50">

        <button type="submit">Update Product</button>
    </form>
</body>
</html>