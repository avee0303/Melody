<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "burger4.0";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get inputs
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['error_message'] = "Please fill in all fields.";
    $conn->close();
    header("Location: login.php");
    exit();
}

// Check user
$sql = "SELECT * FROM users WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password_hash'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['dob'] = $user['dob'];
        $_SESSION['address'] = $user['address'];  // Added address
        $_SESSION['logged_in'] = true;

        $stmt->close();
        $conn->close();
        header("Location: ../login/main2_page.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Incorrect password.";
        $stmt->close();
        $conn->close();
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "User not found.";
    $stmt->close();
    $conn->close();
    header("Location: login.php");
    exit();
}
?>
