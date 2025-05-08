<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "burger4.0";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$entered_otp = $_POST['otp'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$session_otp = $_SESSION['otp'] ?? null;
$email = $_SESSION['reset_email'] ?? '';

if ($entered_otp == $session_otp && !empty($email)) {
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashed_password, $email);

    if ($stmt->execute()) {
        unset($_SESSION['otp'], $_SESSION['reset_email']);
        $_SESSION['success_message'] = "Password has been reset. Please log in.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Failed to update password.";
    }
} else {
    $_SESSION['error_message'] = "Invalid OTP.";
}
header("Location: verify_otp.php");
exit();
