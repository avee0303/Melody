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
    $phone = $_POST["phone"];
    $gender = $_POST["gender"];
    $dob = $_POST["dob"];

    $conn = mysqli_connect("localhost", "root", "", "database");

    if ($conn) {
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $error_message = "❌ Email is already registered.";
        } else {
            // Only send email if user is not registered
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'aveepang2005@gmail.com';  
                $mail->Password = 'axbjovcemeampzas';        
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('aveepang2005@gmail.com', 'Melody-Tech System');
                $mail->addAddress($email, $name);
                $mail->isHTML(true);
                $verification_code = mt_rand(100000, 999999);
                $mail->Subject = 'Email Verification';
                $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';

                $mail->send();

                $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users(name, email, password, phone, gender, dob, verification_code, email_verified_at) 
                        VALUES('$name', '$email', '$encrypted_password', '$phone', '$gender', '$dob', '$verification_code', NULL)";

                if (mysqli_query($conn, $sql)) {
                    $success_message = "✅ Registered successfully! Please check your email for the verification code.";
                    header("Location: email-verification.php?email=" . urlencode($email));
                    exit();
                } else {
                    $error_message = "❌ Error: Unable to register. Please try again.";
                }
            } catch (Exception $e) {
                $error_message = "❌ Mailer Error: {$mail->ErrorInfo}";
            }
        }
        mysqli_close($conn);
    } else {
        $error_message = "❌ Database connection failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="login.css"> <!-- Optional if you have a separate login.css -->
    <style>
        /* Same styles from your login page */
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
            height: 100vh;
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
            min-height: 600px;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container h1 {
            margin-bottom: 10px;
            font-size: 28px;
            color: rgb(80, 35, 20);
        }

        .container span {
            font-size: 14px;
            color: gray;
            margin-bottom: 20px;
        }

        .container form {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 400px;
            align-items: center;
        }

        .container input,
        .container select,
        .container input[type="date"] {
            background-color: #eee;
            border: none;
            margin: 8px 0;
            padding: 12px 15px;
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

        .container button {
            background-color: rgb(177, 143, 91);
            color: #fff;
            font-size: 14px;
            padding: 12px 45px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .container button:hover {
            background-color: rgb(134, 85, 13);
        }

        .message {
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
            padding: 10px 20px;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
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

        .signin-link {
            margin-top: 20px;
            font-size: 14px;
        }

        .signin-link a {
            color: rgb(80, 35, 20);
            font-weight: bold;
            text-decoration: none;
        }

        .signin-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<nav>
    <!-- You can add a logo or links here -->
</nav>
<div class="container">
    <h1>Create Account</h1>
    <span>Use your details to register</span>

    <!-- PHP message alerts -->
    <?php if (!empty($error_message)): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
    <input type="text" name="name" placeholder="Name" required 
           style="font-size: 16px; padding: 10px;">
    <input type="email" name="email" placeholder="Email" required 
           style="font-size: 16px; padding: 10px;">
    
    <div style="display: flex; align-items: center; gap: 5px; margin: 8px 0;">
        <input type="text" value="+601" readonly 
               style="width: 60px; background-color: #eee; border: none; padding: 10px 10px; border-radius: 8px; font-size: 16px;">
        <input type="tel" name="phone" id="phone" placeholder="23456789" 
               pattern="^\d{7,8}$"
               title="Please enter 7–8 digits after +601"
               style="flex: 1; background-color: #eee; border: none; padding: 10px 15px; font-size: 16px; border-radius: 8px; outline: none;"
               required>
    </div>

    <select name="gender" required style="font-size: 16px; padding: 10px;">
        <option value="" disabled selected>Select Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select>

    <input type="date" name="dob" required 
           style="font-size: 16px; padding: 10px;">

    <input type="password" id="password" name="password" placeholder="Password"
           pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&]).{8,}$"
           title="Password must be at least 8 characters long, include a letter, a number, and a special character."
           minlength="8"
           required 
           style="font-size: 16px; padding: 10px;">
    
            <!-- Password requirements -->
    <div id="password-requirements" style="margin-top:10px; font-size:14px;">
        <p id="length" style="color: red;">✖ Minimum 8 characters</p>
        <p id="letter" style="color: red;">✖ At least one letter (a–z, A–Z)</p>
        <p id="number" style="color: red;">✖ At least one number (0–9)</p>
        <p id="special" style="color: red;">✖ At least one special symbol (!@#$%^&*)</p>
    </div>

    <button type="submit" name="register" 
            style="font-size: 16px; padding: 12px 20px;">Sign Up</button>
</form>

    <div class="signin-link">
        <p>Already have an account? <a href="login2.php">Sign In</a></p>
    </div>
</div>
<script>
const passwordInput = document.getElementById('password');
const length = document.getElementById('length');
const letter = document.getElementById('letter');
const number = document.getElementById('number');
const special = document.getElementById('special');

passwordInput.addEventListener('input', function() {
    const value = passwordInput.value;

    // Check minimum length
    if (value.length >= 8) {
        length.style.color = 'teal';
        length.innerHTML = '✔ Minimum 8 characters';
    } else {
        length.style.color = 'red';
        length.innerHTML = '✖ Minimum 8 characters';
    }

    // Check for at least one letter
    if (/[A-Za-z]/.test(value)) {
        letter.style.color = 'teal';
        letter.innerHTML = '✔ At least one letter (a–z, A–Z)';
    } else {
        letter.style.color = 'red';
        letter.innerHTML = '✖ At least one letter (a–z, A–Z)';
    }

    // Check for at least one number
    if (/\d/.test(value)) {
        number.style.color = 'teal';
        number.innerHTML = '✔ At least one number (0–9)';
    } else {
        number.style.color = 'red';
        number.innerHTML = '✖ At least one number (0–9)';
    }

    // Check for at least one special character
    if (/[@$!%*#?&]/.test(value)) {
        special.style.color = 'teal';
        special.innerHTML = '✔ At least one special symbol (!@#$%^&*)';
    } else {
        special.style.color = 'red';
        special.innerHTML = '✖ At least one special symbol (!@#$%^&*)';
    }
});

</script>

</body>
</html>