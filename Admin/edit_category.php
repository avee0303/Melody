<?php
include("config/db_connect.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $category = $conn->query("SELECT * FROM categories WHERE id = $id")->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $imageName = $category['image']; // Keep current image by default

        // If new image uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $imageName = basename($_FILES['image']['name']);
            $uploadDir = 'uploads/';
            $targetFilePath = $uploadDir . $imageName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            move_uploaded_file($imageTmpPath, $targetFilePath);
        }

        $stmt = $conn->prepare("UPDATE categories SET name=?, image=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $imageName, $id);

        if ($stmt->execute()) {
            header("Location: manage_categories.php?success=Category updated successfully");
            exit();
        } else {
            $error = "Error updating category.";
        }
    }
} else {
    header("Location: manage_categories.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>

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
    <h2>Update Category</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" class="form-vertical" enctype="multipart/form-data">
    <div>
        <label for="name">Category Name</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
    </div>

    <div>
        <label for="image">Category Image</label>
        <?php if (!empty($category['image'])): ?>
            <div>
                <img src="uploads/<?= htmlspecialchars($category['image']) ?>" alt="Category Image" style="max-width: 150px; display:block; margin-bottom:10px;">
            </div>
        <?php endif; ?>
        <input type="file" id="image" name="image" accept="image/*">
        <small>Leave empty to keep existing image</small>
    </div>

    <div>
        <button type="submit">Update</button>
    </div>
</form>

    <script src="js/scripts.js" defer></script>
</body>
</html>