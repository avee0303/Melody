<?php
session_start();
$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login / Register</title>
    <link rel="stylesheet" href="login.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: antiquewhite;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 75vh;
        }

        nav {
            width: 97%;
            height: 70px;
            background-color: blanchedalmond;
            display: flex;
            margin-top: 0%;
        }

        .container {
            background-color: #fff;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
            position: relative;
            overflow: hidden;
            width: 768px;
            max-width: 100%;
            min-height: 560px; /* increased height for extra inputs */
        }

        .container p {
            font-size: 14px;
            line-height: 20px;
            letter-spacing: 0.3px;
            margin: 20px 0;
        }

        .container span {
            font-size: 12px;
        }

        .container a {
            color: #333;
            font-size: 13px;
            text-decoration: none;
            margin: 15px 0 10px;
        }

        .container button {
            background-color: rgb(177, 143, 91);
            color: #fff;
            font-size: 12px;
            padding: 10px 45px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 10px;
            cursor: pointer;
        }

        .container button:hover {
            background-color: rgb(134, 85, 13);
        }

        .container button.hidden {
            background-color: transparent;
            border-color: #fff;
        }

        .container form {
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            height: 100%;
        }

        .container input,
        .container select,
        .container input[type="date"],
        .container textarea {
            background-color: #eee;
            border: none;
            margin: 8px 0;
            padding: 10px 15px;
            font-size: 13px;
            border-radius: 8px;
            width: 100%;
            outline: none;
            font-family: inherit;
            transition: border-color 0.3s;
        }

        .container select {
            background-image: url("data:image/svg+xml;utf8,<svg fill='gray' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
            padding-right: 35px;
        }

        .container textarea {
            resize: vertical;
            min-height: 80px;
        }

        .container input:focus,
        .container select:focus,
        .container textarea:focus {
            outline: none;
            border-color: rgb(80, 35, 20);
            box-shadow: 0 0 0 2px rgba(80, 35, 20, 0.2);
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .sign-in {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .container.active .sign-in {
            transform: translateX(100%);
        }

        .sign-up {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
        }

        .container.active .sign-up {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: move 0.6s;
        }

        @keyframes move {
            0%, 49.99% {
                opacity: 0;
                z-index: 1;
            }
            50%, 100% {
                opacity: 1;
                z-index: 5;
            }
        }

        .social-icons {
            margin: 20px 0;
        }

        .social-icons a {
            border: 1px solid #ccc;
            border-radius: 20%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin: 0 3px;
            width: 40px;
            height: 40px;
        }

        .toggle-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: all 0.6s ease-in-out;
            border-radius: 150px 0 0 100px;
            z-index: 1000;
        }

        .container.active .toggle-container {
            transform: translateX(-100%);
            border-radius: 0 150px 100px 0;
        }

        .toggle {
            background-color: rgb(80, 35, 20);
            height: 100%;
            color: #fff;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: all 0.6s ease-in-out;
        }

        .container.active .toggle {
            transform: translateX(50%);
        }

        .toggle-panel {
            position: absolute;
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 30px;
            text-align: center;
            top: 0;
            transform: translateX(0);
            transition: all 0.6s ease-in-out;
        }

        .toggle-left {
            transform: translateX(-200%);
        }

        .container.active .toggle-left {
            transform: translateX(0);
        }

        .toggle-right {
            right: 0;
            transform: translateX(0);
        }

        .container.active .toggle-right {
            transform: translateX(200%);
        }

        img {
            height: 30px;
            width: 30px;
        }

        .forgot-password:hover {
            color: rgb(226, 57, 57);
        }

        .message {
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
            padding: 10px 20px;
            border-radius: 8px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .success {
            color: green;
            background-color: #e6ffe6;
            border: 1px solid green;
            margin-bottom: 10px;
        }

        .error {
            color: red;
            background-color: #ffe6e6;
            border: 1px solid red;
            margin-bottom: 10px;
        }

        @media (max-width: 480px) {
            .container textarea {
                padding: 8px 10px;
                min-height: 70px;
            }
        }
    </style>
</head>
<body>

<nav>
    <a href="main.html">
        <img src="back.png" alt="back">
    </a>
</nav>

<div class="container" id="container">
    <div class="form-container sign-up">
        <form action="register_handler.php" method="POST">  
            <h1>Create Account</h1>
            <span>Use your details to register</span>
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="phone" placeholder="e.g., 0123456789 or 60123456789" 
                pattern="^(\+60|60|0)\d{8,10}$" required>
            <input type="date" name="dob" placeholder="Date of Birth">
            <textarea name="address" placeholder="Enter your full address (street, city, postal code)" required></textarea>
            <input type="password" name="password" placeholder="Password" minlength="6" required>
            <button type="submit">Sign Up</button>
        </form>
    </div>

    <div class="form-container sign-in">
        <form action="login_handler.php" method="POST">
            <h1>Sign In</h1>
            <span>Use your email and password</span>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <a href="recoverpsw.php" class="forgot-password">Forget Your Password?</a>
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

<?php if ($success_message): ?>
    <div class="message success"><?= htmlspecialchars($success_message) ?></div>
<?php elseif ($error_message): ?>
    <div class="message error"><?= htmlspecialchars($error_message) ?></div>
<?php endif; ?>

<script src="script.js"></script>
</body>
</html>
