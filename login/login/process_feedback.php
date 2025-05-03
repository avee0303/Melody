<?php
session_start();

// Database config
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "users_db";

// Create DB connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    $_SESSION['feedback_status'] = 'error';
    header("Location: feedback.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $rating = intval($_POST['rating']);
    $visit_date = $_POST['visit_date'];
    $feedback_type = $conn->real_escape_string(trim($_POST['feedback_type']));
    $message = $conn->real_escape_string(trim($_POST['message']));

    // Validate required fields
    if (empty($name) || empty($email) || empty($rating) || empty($visit_date) || empty($feedback_type) || empty($message)) {
        $_SESSION['feedback_status'] = 'error';
        header("Location: feedback.php");
        exit();
    }

    // Insert into DB
    $sql = "INSERT INTO feedback (name, email, rating, visit_date, feedback_type, message)
            VALUES ('$name', '$email', '$rating', '$visit_date', '$feedback_type', '$message')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['feedback_status'] = 'success';
    } else {
        $_SESSION['feedback_status'] = 'error';
    }

    $conn->close();
    header("Location: feedback.php");
    exit();
} else {
    $_SESSION['feedback_status'] = 'error';
    header("Location: feedback.php");
    exit();
}
