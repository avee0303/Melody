<?php
session_start();
include 'db_connect.php';

// Handle Staff Addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_staff'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO staff (name, email, phone, role, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $role, $password);

    if ($stmt->execute()) {
        header("Location: manage_staff.php?success=Staff added successfully");
        exit();
    } else {
        $error = "Error adding staff.";
    }
}

// Fetch Staff Members
$staffQuery = $conn->query("SELECT * FROM staff");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Manage Staff</h2>
    
    <?php if (isset($_GET['success'])) echo "<p class='success'>{$_GET['success']}</p>"; ?>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <input type="text" name="role" placeholder="Role (e.g., Manager)" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="add_staff">Add Staff</button>
    </form>

    <h3>Staff List</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while ($staff = $staffQuery->fetch_assoc()): ?>
        <tr>
            <td><?= $staff['id'] ?></td>
            <td><?= $staff['name'] ?></td>
            <td><?= $staff['email'] ?></td>
            <td><?= $staff['phone'] ?></td>
            <td><?= $staff['role'] ?></td>
            <td>
                <a href="edit_staff.php?id=<?= $staff['id'] ?>">Edit</a>
                <a href="delete_staff.php?id=<?= $staff['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>