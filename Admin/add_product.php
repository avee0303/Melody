<?php
include("config/db_connect.php");

$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_name = $_POST['category'];
    $image = '';

    // Price validation
    if ($price <= 0) {
        $error = "Price must be greater than 0.";
    }

    // Image upload logic
    if (!empty($_FILES["image"]["name"]) && !isset($error)) {
        $target_dir = "uploads/";
        $image_name = uniqid() . '_' . basename($_FILES["image"]["name"]);
        $image_path = $target_dir . $image_name;
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_type = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        if (!in_array($file_type, $allowed_types)) {
            $error = "Invalid file type. Only JPG, JPEG, PNG, and GIF allowed.";
        } elseif ($_FILES["image"]["size"] > 2 * 1024 * 1024) {
            $error = "File too large. Max 2MB allowed.";
        } elseif (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
            $error = "Image upload failed. Please check directory permissions.";
        } else {
            $image = $image_path;
        }
    }

    if (!isset($error)) {
        // Get category ID from name
        $category_stmt = $conn->prepare("SELECT id FROM categories WHERE name = ?");
        $category_stmt->bind_param("s", $category_name);
        $category_stmt->execute();
        $category_result = $category_stmt->get_result();
        
        if ($category_result->num_rows > 0) {
            $category_data = $category_result->fetch_assoc();
            $categories_id = $category_data['id'];

            $stmt = $conn->prepare("INSERT INTO products (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdss", $name, $description, $price, $category, $image);

            if ($stmt->execute()) {
                header("Location: manage_products.php?success=Product added successfully");
                exit();
            } else {
                $error = "Error adding product: " . $conn->error;
            }
        } else {
            $error = "Selected category not found.";
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

        .form-vertical input, 
        .form-vertical select,
        .form-vertical textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        .form-vertical button {
            padding: 10px 20px;
            background-color: rgb(227, 166, 88);
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-vertical button:hover {
            background-color: rgb(196, 144, 1);
        }

        .error {
            color: red;
            text-align: center;
            padding: 10px;
            background-color: #ffeeee;
            border-radius: 4px;
            margin-bottom: 20px;
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
            <textarea id="description" name="description" rows="3" required></textarea>
        </div>

        <div>
            <label for="price">Price (RM)</label>
            <input type="number" step="0.01" id="price" name="price" min="0.01" required>
        </div>

        <div>
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="">Select Category</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($cat['name']) ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label for="image">Product Image</label>
            <input type="file" id="image" name="image" accept="image/*">
            <small>Maximum 2MB (JPG, PNG, GIF)</small>
        </div>

        <div>
            <button type="submit">Add Product</button>
        </div>
    </form>
</body>
</html>
