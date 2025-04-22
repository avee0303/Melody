<?php
session_start();
include("config/db_connect.php");

// Handle Add Customer
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_customer'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }
    // Validate phone: must start with +60 and be 12 or 13 characters total
    elseif (!preg_match("/^\+60\d{9,10}$/", $phone)) {
        $error = "Phone number must start with +60 and be 12 or 13 digits in total.";
    }
    // Validate password
    elseif (
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||         // at least one uppercase
        !preg_match('/[a-z]/', $password) ||         // at least one lowercase
        !preg_match('/[0-9]/', $password) ||         // at least one number
        !preg_match('/[\W_]/', $password) ||         // at least one special char
        stripos($password, $email) !== false         // should not contain email
    ) {
        $error = "Password must be at least 8 characters, include upper and lower case letters, a number, a special character, and not contain your email.";
    }
    else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO customers (name, email, phone, address , password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $address, $hashedPassword);

        if ($stmt->execute()) {
            // Optional: Refresh to clear form data after adding
            header("Location: manage_customers.php");
            exit();
        } else {
            $error = "Error adding customer: " . $stmt->error;
        }
    }
}

// Fetch Customers
$customerQuery = $conn->query("SELECT * FROM customers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers</title>

    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Manage Customers</h2>

    <?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">
    <input type="text" name="name" placeholder="Full Name" required>
    <br><br>

    <input type="email" name="email" placeholder="Email" required>
    <br><br>

    <input type="text" name="phone" placeholder="Phone(e.g., +601122223333)" required>
    <br>
    <small style="color: gray;">*Phone number must start with +60 and be 12 or 13 digits in total.</small>
    <br><br>

    <input type="text" name="address" placeholder="Address" required>
    <br><br>

    <input type="password" name="password" placeholder="Password" required>
    <br>
    <small style="color: gray;">*Password must be at least 8 characters, include upper and lower case letters, a number, a special character, and not contain your email.</small>
    <br><br>

    <button type="submit" name="add_customer">Add</button>
    </form>

    <h3>Customer List</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
        <?php while ($customer = $customerQuery->fetch_assoc()): ?>
        <tr>
            <td><?= $customer['id'] ?></td>
            <td><?= $customer['name'] ?></td>
            <td><?= $customer['email'] ?></td>
            <td><?= $customer['phone'] ?></td>
            <td><?= $customer['address'] ?></td>
            <td>
                <a href="edit_customer.php?id=<?= $customer['id'] ?>">Edit</a>
                <a href="delete_customer.php?id=<?= $customer['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <script src="js/scripts.js" defer></script>
</body>
</html>