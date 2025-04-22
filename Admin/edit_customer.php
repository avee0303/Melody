<?php
include 'db_connect.php';

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
</head>
<body>
    <h2>Edit Customer</h2>
    
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="name" value="<?= $customer['name'] ?>" required>
        <input type="email" name="email" value="<?= $customer['email'] ?>" required>
        <input type="text" name="phone" value="<?= $customer['phone'] ?>" required>
        <input type="text" name="address" value="<?= $customer['address'] ?>" required>
        <button type="submit">Update Customer</button>
    </form>
</body>
</html>