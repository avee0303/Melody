<?php
session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users_db";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$success_message = '';
$error_message = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $first_name = $conn->real_escape_string(trim($_POST['first_name']));
    $last_name = $conn->real_escape_string(trim($_POST['last_name']));
    $phone = $conn->real_escape_string(trim($_POST['phone']));
    $dob = $conn->real_escape_string(trim($_POST['dob']));
    $address = $conn->real_escape_string(trim($_POST['address']));

    // Basic validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($address)) {
        $error_message = "First name, last name, and address are required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        // Update database (removed gender, added address)
        $sql = "UPDATE users SET first_name=?, last_name=?, phone=?, dob=?, address=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $first_name, $last_name, $phone, $dob, $address, $user_id);
        
        if ($stmt->execute()) {
            $success_message = "Profile updated successfully!";
            // Refresh user data
            $sql = "SELECT first_name, last_name, phone, dob, address FROM users WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        } else {
            $error_message = "Error updating profile: " . $conn->error;
        }
        $stmt->close();
    }
} else {
    // Fetch current user data (removed gender, added address)
    $sql = "SELECT first_name, last_name, phone, dob, address FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit();
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Personal Details</title>
    <style>
    :root {
        --primary-color: #8a6d3b;
        --primary-hover: #7a5c2b;
        --danger-color: #d9534f;
        --danger-hover: #c9302c;
        --input-bg: #f9f9f9;
        --border-color: #ddd;
        --text-dark: #333;
        --success-bg: #dff0d8;
        --success-text: #3c763d;
        --error-bg: #f2dede;
        --error-text: #a94442;
    }

    body {
        background-color: antiquewhite;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        margin: 0;
        padding: 0;
        color: #4b2f16;
    }

    .top-nav {
        position: relative;
        top: 20px;
        left: 20px;
        z-index: 1000;
    }

    .nav-button {
        background-color: #b71c1c;
        color: white;
        border: none;
        padding: 12px 24px;
        font-size: 16px;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .nav-button:hover {
        background-color: rgb(91, 31, 31);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    h1 {
        text-align: center;
        margin: 40px 0 30px;
        font-size: 2.5rem;
        color: #4b2f16;
        font-family: 'flame', "Cooper Black", "Helvetica Neue", Helvetica, Arial, sans-serif;
    }

    .profile-container {
        max-width: 800px;
        margin: 0 auto 50px;
        padding: 30px;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .profile-details {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 20px;
        margin-bottom: 30px;
    }

    .detail-label {
        font-weight: bold;
        padding: 12px;
        text-align: right;
        color: #8a6d3b;
    }

    .detail-value {
        padding: 12px;
    }

    .button-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 30px;
    }

    .action-button {
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
        color: white;
        background-color: var(--primary-color);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .action-button.cancel {
        background-color: #8a6d3b;
    }

    .action-button:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .action-button.cancel:hover {
        background-color: #7a5c2b;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="date"],
    textarea {
        width: 100%;
        padding: 10px 12px;
        background-color: var(--input-bg);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 1rem;
        color: var(--text-dark);
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
        font-family: inherit;
        transition: border-color 0.3s;
    }

    input:focus, textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(156, 111, 52, 0.2);
    }

    .message {
        padding: 15px;
        margin: 0 0 25px 0;
        border-radius: 8px;
        text-align: center;
        font-weight: bold;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .success {
        background-color: var(--success-bg);
        color: var(--success-text);
        border: 1px solid #c3e6cb;
    }

    .error {
        background-color: var(--error-bg);
        color: var(--error-text);
        border: 1px solid #f5c6cb;
    }

    @media (max-width: 768px) {
        .profile-container {
            width: 95%;
            padding: 20px;
        }
        
        .profile-details {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .detail-label {
            text-align: left;
            padding-bottom: 5px;
            padding-left: 0;
        }
        
        .button-container {
            flex-direction: column;
            gap: 15px;
        }
        
        .action-button {
            width: 100%;
        }
        
        h1 {
            font-size: 2rem;
            margin-top: 70px;
            padding: 15px 0;
        }
    }

    @media (max-width: 480px) {
        .profile-container {
            padding: 15px;
        }
        
        .detail-label, .detail-value {
            padding: 10px;
        }
        
        input, textarea {
            padding: 8px 10px;
        }
    }
    </style>
</head>
<body>

<div class="top-nav">
    <a href="profile.php" class="nav-button">Back</a>
</div>

<h1>Edit Personal Details</h1>

<div class="profile-container">
    <?php if (!empty($success_message)): ?>
        <div class="message success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <div class="message error"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <form action="editpersonal.php" method="POST">
        <div class="profile-details">
            <div class="detail-label">First Name:</div>
            <div class="detail-value">
                <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
            </div>
            
            <div class="detail-label">Last Name:</div>
            <div class="detail-value">
                <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
            </div>

            <div class="detail-label">Phone:</div>
            <div class="detail-value">
                <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" 
                       pattern="^(\+60|60|0)\d{8,10}$" required>
            </div>
            
            <div class="detail-label">Date of Birth:</div>
            <div class="detail-value">
                <input type="date" name="dob" value="<?= htmlspecialchars($user['dob']) ?>">
            </div>
            
            <div class="detail-label">Address:</div>
            <div class="detail-value">
                <textarea name="address" required><?= htmlspecialchars($user['address']) ?></textarea>
            </div>
        </div>

        <div class="button-container">
            <button type="submit" class="action-button">Save Changes</button>
        </div>
    </form>
</div>

</body>
</html>
