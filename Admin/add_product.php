<?php
include("config/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Image Upload
    $target_dir = "uploads/";
    $image_name = basename($_FILES["image"]["name"]);
    $image_path = $target_dir . $image_name;
    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
        $error = "Error uploading image.";
    }
    
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $description, $price, $image);

    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    $file_type = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

    if (!in_array($file_type, $allowed_types)) {
        $error = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
    } elseif ($_FILES["image"]["size"] > 2 * 1024 * 1024) { // Limit: 2MB
        $error = "File size too large. Max 2MB allowed.";
    } else {
    move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
    }

    if ($stmt->execute()) {
        header("Location: manage_products.php?success=Product added successfully");
        exit();
    } else {
        $error = "Error adding product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>

    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Add New Product</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
    <input type="text" name="name" placeholder="Name" required>
    <br><br>

    <input type="text" name="description" placeholder="Description" required>
    <br><br>

    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <br><br>

    <input type="file" name="image" placeholder="Image" required>
    <br><br>

    <button type="submit" name="add_product">Add</button>
</form>

    <script src="js/scripts.js" defer></script>
</body>
</html>