<?php

$success_message = '';
$error_message = '';

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $conn = mysqli_connect("localhost", "root", "", "payment");

    $sql = "SELECT * FROM users WHERE email = '" . $email . "'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        $error_message = "Email not found.";
    } else {
        $user = mysqli_fetch_object($result);

        if (!password_verify($password, $user->password)) {
            $error_message = "Password is not correct";
        } elseif ($user->email_verified_at == null) {
            $error_message = "Please verify your email <a href='email-verification.php?email=" . $email . "'>from here</a>";
        } else {
            $success_message = "Login successful! Welcome back!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login / Register</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

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
    <!-- Sign Up Form -->
    <div class="form-container sign-up" id="sign-up-form">
        <form action="register2.php" method="POST">
            <h1>Create Account</h1>
            <span>Use your email for registration</span>
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Sign Up</button>
        </form>
    </div>

    <!-- Sign In Form -->
    <div class="form-container sign-in" id="sign-in-form">
        <form action="" method="POST">
            <h1>Sign In</h1>
            <span>Use your email and password</span>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <a href="#" class="forgot-password">Forget Your Password?</a>
            <button type="submit" name="login">Sign In</button>
        </form>
    </div>

    <div class="toggle-container">
        <div class="toggle">
            <div class="toggle-panel toggle-left">
                <h1>Welcome Back!</h1>
                <p>Enter your personal details to LOGIN</p>
                <button class="hidden" id="login-btn">Sign In</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Hello, Friend!</h1>
                <p>Register with your personal details</p>
                <button type="button" id="register-btn">Sign Up</button>

            </div>
        </div>
    </div>
</div>

<script>
    const container = document.getElementById('container');
const registerBtn = document.getElementById('register-btn');
const loginBtn = document.getElementById('login-btn');

registerBtn.addEventListener('click', () => {
    window.location.href = 'register2.php'; 
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("right-panel-active");
});

</script>

</body>
</html>
