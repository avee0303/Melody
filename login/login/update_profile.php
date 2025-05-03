<?php
session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate inputs
    $errors = [];
    if (empty($first_name)) $errors[] = "First name is required";
    if (empty($last_name)) $errors[] = "Last name is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
    if (!preg_match('/^(\+60|60|0)\d{8,10}$/', $phone)) $errors[] = "Invalid phone format";

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "users_db";

    try {
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            throw new Exception("Database connection failed");
        }

        // Check if password change is requested
        $password_changed = false;
        if (!empty($current_password)) {
            // Verify current password
            $sql = "SELECT password FROM users WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (!password_verify($current_password, $user['password'])) {
                    $errors[] = "Current password is incorrect";
                } elseif (empty($new_password)) {
                    $errors[] = "New password is required";
                } elseif (strlen($new_password) < 8) {
                    $errors[] = "New password must be at least 8 characters long";
                } elseif ($new_password !== $confirm_password) {
                    $errors[] = "New passwords do not match";
                } else {
                    // Hash the new password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $password_changed = true;
                }
            }
            $stmt->close();
        }

        if (!empty($errors)) {
            $_SESSION['error_message'] = implode("<br>", $errors);
            header("Location: profile.php");
            exit();
        }

        // Update user data
        if ($password_changed) {
            $sql = "UPDATE users SET first_name=?, last_name=?, email=?, phone=?, gender=?, dob=?, password=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssi", $first_name, $last_name, $email, $phone, $gender, $dob, $hashed_password, $user_id);
        } else {
            $sql = "UPDATE users SET first_name=?, last_name=?, email=?, phone=?, gender=?, dob=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $phone, $gender, $dob, $user_id);
        }
        
        if ($stmt->execute()) {
            // Update session data
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['email'] = $email;
            $_SESSION['phone'] = $phone;
            $_SESSION['gender'] = $gender;
            $_SESSION['dob'] = $dob;
            
            $_SESSION['success_message'] = "Profile updated successfully!" . ($password_changed ? " Password has been changed." : "");
        } else {
            throw new Exception("Failed to update profile");
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    } finally {
        if (isset($stmt)) $stmt->close();
        if (isset($conn)) $conn->close();
    }

    header("Location: profile.php");
    exit();
}
?>