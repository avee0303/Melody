<?php
include("config/db_connect.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $customer = $conn->query("SELECT * FROM customers WHERE id = $id")->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        $stmt = $conn->prepare("UPDATE customers SET name=?, email=?, phone=?, address=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);
        
        if ($stmt->execute()) {
            header("Location: manage_customers.php?success=Customer updated successfully");
            exit();
        } else {
            $error = "Error updating customer.";
        }
    }
} else {
    header("Location: manage_customers.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>

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
    <h2>Update Customer Information</h2>
    
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" class="form-vertical">
    <div>
        <label for="name">Full Name</label><br>
        <input type="text" id="name" name="name" value="<?= $customer['name'] ?>" required>
    </div>

    <div>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" value="<?= $customer['email'] ?>" required>
    </div>

    <div>
        <label for="phone">Phone</label><br>
        <input type="text" id="phone" name="phone" value="<?= $customer['phone'] ?>" required>
    </div>

    <div>
        <label for="address">Address</label><br>
        <input type="text" id="address" name="address" value="<?= $customer['address'] ?>" required>
    </div>

    <div>
        <button type="submit">Update</button>
    </div>
</form>

    <script src="js/scripts.js" defer></script>
</body>
</html>
