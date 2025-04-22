<?php
session_start();
include("config/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Database query
    $query = "SELECT * FROM superadmin WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $id = $user['id'];
        $username = $user['username'];
        $hashed_password = $user['password'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_username'] = $username;
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="login-container">
        <img src="images/4.0logo.jpg" alt="Burger King Logo" class="logo">
        <h2>Admin Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>

    <script src="js/scripts.js" defer></script>
</body>
</html>