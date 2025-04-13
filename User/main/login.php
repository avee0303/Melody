<?php
// To show success or error message if coming from register_handler.php
$success_message = $success_message ?? '';
$error_message = $error_message ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login / Register</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<!-- Message outside the container -->
<?php if ($success_message): ?>
    <div style="color: green; font-weight: bold; margin: 10px; text-align: center;">
        <?= $success_message ?>
    </div>
<?php elseif ($error_message): ?>
    <div style="color: red; font-weight: bold; margin: 10px; text-align: center;">
        <?= $error_message ?>
    </div>
<?php endif; ?>

<nav>
    <a href="main.html">
        <img src="back.png" alt="back">
    </a>
</nav>

<div class="container" id="container">
    <div class="form-container sign-up">
        <form action="register_handler.php" method="POST">
            <h1>Create Account</h1>
            <span>Use your email for registration</span>
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
    </div>

    <div class="form-container sign-in">
        <form action="login_handler.php" method="POST">
            <h1>Sign In</h1>
            <span>Use your email and password</span>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <a href="#" class="forgot-password">Forget Your Password?</a>
            <button type="submit">Sign In</button>
        </form>
    </div>

    <div class="toggle-container">
        <div class="toggle">
            <div class="toggle-panel toggle-left">
                <h1>Welcome Back!</h1>
                <p>Enter your personal details to LOGIN</p>
                <button class="hidden" id="login">Sign In</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Hello, Friend!</h1>
                <p>Register with your personal details</p>
                <button class="hidden" id="register">Sign Up</button>
            </div>
        </div>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>
