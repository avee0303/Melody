<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="container">
    <form action="reset_password.php" method="POST">
        <h1>Verify OTP</h1>
        <input type="number" name="otp" placeholder="Enter OTP" required>
        <input type="password" name="new_password" placeholder="New Password" minlength="6" required>
        <button type="submit">Reset Password</button>
    </form>
</div>
</body>
</html>
