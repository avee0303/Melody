<?php
include("config/db_connect.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $staff = $conn->query("SELECT * FROM admin WHERE id = $id")->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $status = $_POST['status'];
        $stmt = $conn->prepare("UPDATE admin SET name=?, email=?, phone=?, status=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $email, $phone, $status, $id);

        if ($stmt->execute()) {
            header("Location: manage_staffs.php?success=Staff updated successfully");
            exit();
        } else {
            $error = "Error updating staff.";
        }
    }
} else {
    header("Location: manage_staffs.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff</title>

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
    <h2>Update Staff Information</h2>
    
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" class="form-vertical">
    <div>
        <label for="name">Full Name</label><br>
        <input type="text" id="name" name="name" value="<?= $staff['name'] ?>" readonly>
    </div>

    <div>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" value="<?= $staff['email'] ?>" readonly>
    </div>

    <div>
        <label for="phone">Phone</label><br>
        <input type="text" id="phone" name="phone" value="<?= $staff['phone'] ?>" required>
    </div>

    <div>
        <label for="status">Status</label><br>
        <select id="status" name="status" required>
            <option value="Active" <?= $staff['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
            <option value="Inactive" <?= $staff['status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>
    </div>

    <div>
        <button type="submit">Update</button>
    </div>
</form>
    
    <script src="js/scripts.js" defer></script>
</body>
</html>