<?php
session_start();
include("config/db_connect.php");

// Handle Staff Addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_staff'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $status = $_POST['status'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!preg_match("/^\+60\d{9,10}$/", $phone)) {
        $error = "Phone number must start with +60 and be 12 or 13 digits in total.";
    } elseif (
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[\W_]/', $password) ||
        stripos($password, $email) !== false
    ) {
        $error = "Password must meet complexity requirements and not contain your email.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO admin (name, email, phone, password, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $hashedPassword, $status);

        if ($stmt->execute()) {
            header("Location: manage_staffs.php");
            exit();
        } else {
            $error = "Error adding staff.";
        }
    }
}

// Fetch Staff Members
$staffQuery = $conn->query("SELECT * FROM admin");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        img {
            width: 80px;
            height: auto;
        }

        .logout-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h2>Manage Staffs</h2>

    <?php if (isset($_GET['success'])): ?>
        <p style="color: green; font-weight: bold;">
            <?= htmlspecialchars($_GET['success']) ?>
        </p>
    <?php endif; ?>

    <?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="text" name="phone" placeholder="Phone (e.g., +601122223333)" required><br>
        <small style="color: gray;">*Phone must start with +60 and be 12 or 13 digits.</small><br><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <small style="color: gray;">*Password must meet all security rules and not contain email.</small><br><br>
        
        <label>Status: </label>
        <select name="status" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select><br><br>

        <button type="submit" name="add_staff">Add</button>
    </form>

    <h3  style="text-align:center;">Staff List</h3>
    <table border="1">
        <tr>
            <th>Staff ID</th>
            <th>Staff Name</th>
            <th>Staff Email</th>
            <th>Staff Phone</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($staff = $staffQuery->fetch_assoc()): ?>
        <tr>
            <td><?= $staff['id'] ?></td>
            <td><?= $staff['name'] ?></td>
            <td><?= $staff['email'] ?></td>
            <td><?= $staff['phone'] ?></td>
            <td style="color: <?= $staff['status'] == 'active' ? 'green' : 'red' ?>">
                <?= ucfirst($staff['status']) ?>
            </td>
            <td>
                <a href="edit_staff.php?id=<?= $staff['id'] ?>">Edit</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="new_superadmin_dashboard.php" class="logout-link">Back to Dashboard</a>

    <script src="js/scripts.js" defer></script>
</body>
</html>