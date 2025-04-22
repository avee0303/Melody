<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $delivery = $conn->query("SELECT * FROM deliveries WHERE id = $id")->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $delivery_staff = $_POST['delivery_staff'];
        $delivery_status = $_POST['delivery_status'];
        $estimated_time = $_POST['estimated_time'];

        $stmt = $conn->prepare("UPDATE deliveries SET delivery_staff=?, delivery_status=?, estimated_time=? WHERE id=?");
        $stmt->bind_param("sssi", $delivery_staff, $delivery_status, $estimated_time, $id);

        if ($stmt->execute()) {
            header("Location: manage_deliveries.php?success=Delivery updated successfully");
            exit();
        } else {
            $error = "Error updating delivery.";
        }
    }
} else {
    header("Location: manage_deliveries.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Delivery</title>
</head>
<body>
    <h2>Update Delivery</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <label>Delivery Staff:</label>
        <input type="text" name="delivery_staff" value="<?= $delivery['delivery_staff'] ?>" required>

        <label>Estimated Time:</label>
        <input type="datetime-local" name="estimated_time" value="<?= $delivery['estimated_time'] ?>">

        <label>Status:</label>
        <select name="delivery_status">
            <option value="Pending" <?= $delivery['delivery_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Out for Delivery" <?= $delivery['delivery_status'] == 'Out for Delivery' ? 'selected' : '' ?>>Out for Delivery</option>
            <option value="Delivered" <?= $delivery['delivery_status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
            <option value="Cancelled" <?= $delivery['delivery_status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>

        <button type="submit">Update</button>
    </form>
</body>
</html>