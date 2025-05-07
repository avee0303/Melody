<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Security</title>
    <style>
    :root {
        --primary-color: #8a6d3b;
        --danger-color: #d9534f;
        --text-dark: #333;
    }

    body {
        background-color: antiquewhite;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        margin: 0;
        padding: 0;
        color: #4b2f16;
    }

    .top-nav {
        position: relative;
        top: 20px;
        left: 20px;
        z-index: 1000;
    }

    .nav-button {
        background-color: #b71c1c;
        color: white;
        border: none;
        padding: 12px 24px;
        font-size: 16px;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .nav-button:hover {
        background-color: rgb(91, 31, 31);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    h1 {
        text-align: center;
        margin: 40px 0 30px;
        font-size: 2.5rem;
        color: #4b2f16;
        font-family: 'flame', "Cooper Black", "Helvetica Neue", Helvetica, Arial, sans-serif;
    }

    .security-container {
        max-width: 800px;
        margin: 0 auto 50px;
        padding: 30px;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        line-height: 1.6;
    }

    .security-container p {
        margin-bottom: 20px;
        font-size: 1.1rem;
    }

    .cta-button {
        display: inline-block;
        background-color: var(--primary-color);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        margin-top: 20px;
        transition: all 0.3s ease;
    }

    .cta-button:hover {
        background-color: #7a5c2b;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    @media (max-width: 768px) {
        .security-container {
            width: 90%;
            padding: 20px;
        }
        
        h1 {
            font-size: 2rem;
        }
    }
    </style>
</head>
<body>

<div class="top-nav">
    <a href="profile.php" class="nav-button">Back</a>
</div>

<h1>Password & Security</h1>

<div class="security-container">
    <h2>✅Two-Factor Authentication (2FA) is on.</h2>
    <p>Two-factor authentication adds an extra layer of security to ensure that you're the only person who can access your account, event if someone knows your password</p>
    
    <h2>Why Enable 2FA?</h2>
    <p>With two-factor authentication, even if someone gets your password, they won't be able to access your account without also having your phone. This dramatically increases your account security.</p>
    
    <h2>How It Works</h2>
    <p>When you logging in, a verification code will be sent to your email. This code then needs to be entered in the website in order to complete the login </p>
    
    <a href="edit_password.php" class="cta-button">Change Your Password      ></a>
</div>

</body>
</html>
