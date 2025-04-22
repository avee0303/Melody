<?php
include("config/db_connect.php");

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $query = $conn->prepare("SELECT * FROM password_resets WHERE token = ?");
    $query->bind_param("s", $token);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows == 0) {
        die("Invalid or expired token.");
    }

    $row = $result->fetch_assoc();
    $email = $row['email'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $conn->query("UPDATE admin SET password = '$new_password' WHERE email = '$email'");
        $conn->query("DELETE FROM password_resets WHERE email = '$email'"); // Remove token after reset

        echo "Password reset successful! <a href='admin_login.php'>Login here</a>";
        exit();
    }
} else {
    die("Invalid request.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>

    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Reset Password</h2>
    <form method="POST">
        <label>New Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Reset Password</button>
    </form>

    <script src="js/scripts.js" defer></script>
</body>
</html>