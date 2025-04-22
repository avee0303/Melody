<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $staff = $conn->query("SELECT * FROM staff WHERE id = $id")->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $role = $_POST['role'];

        $stmt = $conn->prepare("UPDATE staff SET name=?, email=?, phone=?, role=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $email, $phone, $role, $id);
        
        if ($stmt->execute()) {
            header("Location: manage_staff.php?success=Staff updated successfully");
            exit();
        } else {
            $error = "Error updating staff.";
        }
    }
} else {
    header("Location: manage_staff.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff</title>
</head>
<body>
    <h2>Edit Staff</h2>
    
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="name" value="<?= $staff['name'] ?>" required>
        <input type="email" name="email" value="<?= $staff['email'] ?>" required>
        <input type="text" name="phone" value="<?= $staff['phone'] ?>" required>
        <input type="text" name="role" value="<?= $staff['role'] ?>" required>
        <button type="submit">Update Staff</button>
    </form>
</body>
</html>