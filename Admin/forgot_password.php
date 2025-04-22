<?php
include("config/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50)); // Generate secure token

    // Check if email exists
    $query = $conn->prepare("SELECT * FROM superadmin WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // Insert into password reset table
        $conn->query("INSERT INTO password_resets (email, token) VALUES ('$email', '$token')");

        // Send email with reset link
        $resetLink = "http://yourwebsite.com/reset_password.php?token=$token";
        mail($email, "Password Reset Request", "Click the link to reset your password: $resetLink");

        $message = "Check your email for the reset link.";
    } else {
        $error = "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>

    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Forgot Password</h2>
    <?php if (isset($message)) echo "<p class='success'>$message</p>"; ?>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        <button type="submit">Send Reset Link</button>
    </form>

    <script src="js/scripts.js" defer></script>
</body>
</html>