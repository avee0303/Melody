<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="navbar">
        <img src="images/4.0logo.jpg" alt="4.0 Burger Logo" class="logo">
        Burger King Admin Panel
    </div>

    <h2>Welcome, <?php echo $_SESSION['admin_username']; ?>!</h2>
    <a href="logout.php">Logout</a>

    <script src="js/scripts.js" defer></script>
</body>
</html>