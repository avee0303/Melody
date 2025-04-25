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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
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


<form method="POST">
    <input type="text" name="name" placeholder="Enter name" required />
    <input type="email" name="email" placeholder="Enter email" required />
    <input type="password" name="password" placeholder="Enter password" required />
    <input type="submit" name="register" value="Register" />
</form>

<p>Already have an account? <a href="login2.php">Sign In</a></p>

</body>
</html>
