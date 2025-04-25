<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$email = trim($_POST['email'] ?? '');

$sql = "SELECT * FROM users WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['reset_email'] = $email;

    // Send OTP (use mail() or PHPMailer)
    $subject = "Your Password Reset OTP";
    $message = "Your OTP for password reset is: $otp";
    $headers = "From: no-reply@example.com";

    mail($email, $subject, $message, $headers);

    header("Location: verify_otp.php");
    exit();
} else {
    $_SESSION['error_message'] = "Email not found.";
    header("Location: forgot_password.php");
    exit();
}
