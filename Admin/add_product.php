<?php
include("config/db_connect.php");

$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $image = '';

    // Price validation (ensure price is positive and greater than 0)
    if ($price <= 0) {
        $error = "Price must be greater than 0.";
    }

    // Image upload logic
    if (!empty($_FILES["image"]["name"]) && !isset($error)) {
        $target_dir = "uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $image_path = $target_dir . $image_name;
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_type = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        if (!in_array($file_type, $allowed_types)) {
            $error = "Invalid file type. Only JPG, JPEG, PNG, and GIF allowed.";
        } elseif ($_FILES["image"]["size"] > 2 * 1024 * 1024) {
            $error = "File too large. Max 2MB allowed.";
        } elseif (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
            $error = "Image upload failed.";
        } else {
            $image = $image_path;
        }
    }

    if (!isset($error)) {
        $stmt = $conn->prepare("INSERT INTO product (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $name, $description, $price, $category, $image);

        if ($stmt->execute()) {
            header("Location: manage_products.php?success=Product added successfully");
            exit();
        } else {
            $error = "Error adding product.";
        }
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
    <style>
        .form-vertical {
            max-width: 400px;
            margin: 0 auto;
        }

        .form-vertical div {
            margin-bottom: 20px;
        }

        .form-vertical label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-vertical input, .form-vertical select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        .form-vertical img {
            margin-top: 5px;
            width: 80px;
        }

        .form-vertical button {
            padding: 10px 20px;
            background-color: rgb(227, 166, 88);
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .form-vertical button:hover {
            background-color: rgb(196, 144, 1);
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Add Product</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" enctype="multipart/form-data" class="form-vertical">
        <div>
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div>
            <label for="description">Description</label>
            <input type="text" id="description" name="description" required>
        </div>

        <div>
            <label for="price">Price</label>
            <input type="number" step="0.01" id="price" name="price" required>
        </div>

        <div>
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="">Select Category</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['name'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label for="image">Image</label>
            <input type="file" id="image" name="image">
        </div>

        <div>
            <button type="submit">Add Product</button>
        </div>
    </form>

    <script src="js/scripts.js" defer></script>
</body>
</html>