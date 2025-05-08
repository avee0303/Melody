<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "burger4.0";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and sanitize form data
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$password = trim($_POST['password'] ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$dob = $_POST['dob'] ?? null;
$address = trim($_POST['address'] ?? '');

$errors = [];

// Validation
if (empty($first_name)) $errors[] = "First name is required.";
if (!preg_match('/^[a-zA-Z\s\-]+$/', $first_name)) $errors[] = "First name can only contain letters, spaces, and hyphens.";
if (empty($last_name)) $errors[] = "Last name is required.";
if (!preg_match('/^[a-zA-Z\s\-]+$/', $last_name)) $errors[] = "Last name can only contain letters, spaces, and hyphens.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";

// Password validation
if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
} elseif (!preg_match('/[A-Z]/', $password)) {
    $errors[] = "Password must contain at least one uppercase letter.";
} elseif (!preg_match('/[a-z]/', $password)) {
    $errors[] = "Password must contain at least one lowercase letter.";
} elseif (!preg_match('/[0-9]/', $password)) {
    $errors[] = "Password must contain at least one number.";
} elseif ($password !== $confirm_password) {
    $errors[] = "Passwords don't match.";
}

if (!preg_match('/^(\+60|60|0)\d{8,10}$/', $phone)) $errors[] = "Phone must be 10-12 digits (e.g., 0123456789 or 60123456789).";
if (empty($address)) $errors[] = "Address is required.";

// If validation fails
if (!empty($errors)) {
    $_SESSION['error_message'] = implode("<br>", $errors);
    $conn->close();
    header("Location: login.php");
    exit();
}

// Normalize phone number
$phone = preg_replace('/^(0)/', '+60', $phone);
$phone = preg_replace('/^(60)/', '+60', $phone);

// Check if email already exists
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['error_message'] = "An account with this email already exists.";
    $stmt->close();
    $conn->close();
    header("Location: login.php");
    exit();
}

// Insert user
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (first_name, last_name, email, password_hash, phone, dob, address) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $first_name, $last_name, $email, $hashed_password, $phone, $dob, $address);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Registration successful! Please log in.";
} else {
    $_SESSION['error_message'] = "Something went wrong. Please try again.";
}

$stmt->close();
$conn->close();
header("Location: login.php");
exit();
?>
