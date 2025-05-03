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

// Fetch user data (removed gender, added address)
$sql = "SELECT first_name, last_name, email, phone, dob, address FROM users WHERE id=?";
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
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Details</title>
    <style>
    :root {
        --primary-color: #8a6d3b;
        --danger-color: #d9534f;
        --danger-hover: #c9302c;
        --text-dark: #333;
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
        color: var(--text-dark);
    }

    .button-container {
        display: flex;
        justify-content: center;
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
    }

    .action-button.back {
        background-color: var(--primary-color);
    }

    .action-button.logout {
        background-color: var(--danger-color);
        margin-left: 20px;
    }

    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .action-button.back:hover {
        background-color: #7a5c2b;
    }

    .action-button.logout:hover {
        background-color: var(--danger-hover);
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
        
        .action-button.logout {
            margin-left: 0;
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
    }
    </style>
</head>
<body>

<div class="top-nav">
    <a href="profile.php" class="nav-button">Back</a>
</div>

<h1>Personal Details</h1>

<div class="profile-container">
    <div class="profile-details">
        <div class="detail-label">First Name:</div>
        <div class="detail-value"><?= htmlspecialchars($user['first_name']) ?></div>
        
        <div class="detail-label">Last Name:</div>
        <div class="detail-value"><?= htmlspecialchars($user['last_name']) ?></div>
        
        <div class="detail-label">Email:</div>
        <div class="detail-value"><?= htmlspecialchars($user['email']) ?></div>
        
        <div class="detail-label">Phone:</div>
        <div class="detail-value"><?= htmlspecialchars($user['phone']) ?></div>
        
        <div class="detail-label">Date of Birth:</div>
        <div class="detail-value"><?= htmlspecialchars($user['dob']) ?></div>
        
        <div class="detail-label">Address:</div>
        <div class="detail-value"><?= htmlspecialchars($user['address']) ?></div>
    </div>

    <div class="button-container">
        <a href="editpersonal.php" class="action-button back">EDIT PROFILE</a>
    </div>
</div>

</body>
</html>