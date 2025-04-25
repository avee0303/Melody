<?php
if (isset($_POST["verify_email"])) {
    $email = $_POST["email"];
    $verification_code = $_POST["verification_code"];
    $conn = mysqli_connect("localhost", "root", "", "database");

    $sql = "UPDATE users SET email_verified_at = NOW() WHERE email = '" . $email . "' AND verification_code = '" . $verification_code . "'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) == 0) {
        die("Verification code failed.");
    }

    header("Location: login2.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <link rel="stylesheet" href="login.css"> 
</head>
<body>

<?php if (isset($error_message)): ?>
    <div style="color: red; font-weight: bold; margin: 10px; text-align: center;">
        <?= $error_message ?>
    </div>
<?php endif; ?>

<!-- Verification Form -->
<div class="container" id="container">
    <div class="form-container sign-in" id="sign-in-form">
        <form method="POST">
            <h1>Email Verification</h1>
            <span>Enter the verification code sent to your email</span>
            <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>" required />
            <input type="text" name="verification_code" placeholder="Enter verification code" required />
            <input type="submit" name="verify_email" value="Verify Email" />
        </form>
    </div>
</div>

</body>
</html>
