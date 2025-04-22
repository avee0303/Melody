<?php
include("config/db_connect.php");

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
    <title>Update Product</title>

    <link rel="stylesheet" href="css/styles.css">
</head>
<style>
    .form-vertical {
        max-width: 400px;
        margin: 0 auto;
    }

    .form-vertical div {
        margin-bottom: 20px; /* Increased space between inputs */
    }

    .form-vertical label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-vertical input {
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
    }

    .form-vertical button {
        padding: 10px 20px;
        background-color:rgb(227, 166, 88);
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 4px;
    }

    .form-vertical button:hover {
        background-color:rgb(196, 144, 1);
    }
</style>
<body>
    <h2>Update Product</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" class="form-vertical">
    <div>
        <label for="name">Product Name</label><br>
        <input type="text" id="name" name="name" value="<?= $product['name'] ?>" required>
    </div>

    <div>
        <label for="description">Description</label><br>
        <input type="text" id="description" name="description" value="<?= $product['description'] ?>" required>
    </div>

    <div>
        <label for="price">Price</label><br>
        <input type="number" id="price" name="price" value="<?= $product['price'] ?>" required>
    </div>

    <div>
        <label for="image">Image</label><br>
        <input type="file" id="image" name="image" img src="<?= $product['image'] ?>"width="50" required>
    </div>

    <div>
        <button type="submit">Update</button>
    </div>
</form>
    
    <script src="js/scripts.js" defer></script>
</body>
</html>