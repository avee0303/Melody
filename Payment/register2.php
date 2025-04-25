<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$success_message = '';
$error_message = '';

if (isset($_POST['register'])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'aveepang2005@gmail.com';  
        $mail->Password = 'axbjovcemeampzas';        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('aveepang2005@gmail.com', 'Avee-Tech System');
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $verification_code = mt_rand(100000, 999999);
        $mail->Subject = 'Email Verification';
        $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';

        $mail->send();

        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
        $conn = mysqli_connect("localhost", "root", "", "database");

        if ($conn) {
            $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
            if (mysqli_num_rows($check) > 0) {
                $error_message = "❌ Email is already registered.";
            } else {
                $sql = "INSERT INTO users(name, email, password, verification_code, email_verified_at) 
                        VALUES('$name', '$email', '$encrypted_password', '$verification_code', NULL)";
                if (mysqli_query($conn, $sql)) {
                    $success_message = "✅ Registered successfully! Please check your email for the verification code.";
                    header("Location: email-verification.php?email=" . urlencode($email));
                    exit();
                } else {
                    $error_message = "❌ Error: Unable to register. Please try again.";
                }
            }
        } else {
            $error_message = "❌ Database connection failed.";
        }
        

        mysqli_close($conn);
    } catch (Exception $e) {
        $error_message = "❌ Mailer Error: {$mail->ErrorInfo}";
    }
}
?><!DOCTYPE html>
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
            height: 100vh; /* Full height of the screen */
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
            min-height: 560px;
            padding: 40px;
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
            align-items: flex-start;
            justify-content: flex-start;
            flex-direction: column;
            padding: 0 40px;
            width: 100%;
        }

        .container input,
        .container select,
        .container input[type="date"] {
            background-color: #eee;
            border: none;
            margin: 8px 0;
            padding: 10px 15px;
            font-size: 13px;
            border-radius: 8px;
            width: 100%;
            outline: none;
        }

        .container select {
            background-image: url("data:image/svg+xml;utf8,<svg fill='gray' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
            padding-right: 35px;
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

    </style>
</head>

<body>

<h1>Create Account</h1>
<span>Use your details to register</span>
<form method="POST">
    <input type="text" name="first_name" placeholder="First Name" required>
    <input type="text" name="last_name" placeholder="Last Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="tel" name="phone" placeholder="e.g., 0123456789 or 60123456789" 
           pattern="^(\+60|60|0)\d{8,10}$" 
           title="Phone must be 10-12 digits (e.g., 0123456789 or 60123456789)" required>
    <select name="gender" required>
        <option value="" disabled selected>Select Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select>
    <input type="date" name="dob" required>
    <input type="password" name="password" placeholder="Password" minlength="6" required>
    <button type="submit" name="register">Sign Up</button>
</form>

<p>Already have an account? <a href="login2.php">Sign In</a></p>

</body>
</html>
