<?php
$success_message = '';
$error_message = '';

// Connect to DB
$conn = new mysqli("localhost", "root", "", "users_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            $success_message = "✅ Registered successfully!";
        } else {
            $error_message = "❌ Email may already exist or error occurred.";
        }
        $stmt->close();
    } else {
        $error_message = "❌ Database error: " . $conn->error;
    }
}
$conn->close();
?>

<!-- Redirect back to login.php with a success message -->
<?php include 'login.php'; ?>
