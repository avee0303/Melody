<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="container">
    <form action="send_otp.php" method="POST">
        <h1>Forgot Password</h1>
        <p>Enter your registered email address</p>
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit">Send OTP</button>
    </form>
</div>
</body>
</html>
