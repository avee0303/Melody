<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../main/login.php");
    exit();
}

// Dummy points (you can fetch this from DB if needed)
$user = $_SESSION['user'];
$points = 120; // Example points
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .profile-container {
            width: 400px;
            margin: 60px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        .info {
            margin-bottom: 15px;
        }
        .info label {
            font-weight: bold;
        }
        .info p {
            margin: 5px 0 0;
        }
        .language-select {
            margin: 20px 0;
        }
        .logout-btn {
            display: block;
            width: 100%;
            background: #e74c3c;
            color: white;
            text-align: center;
            padding: 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>

    <div class="info">
        <label>Email:</label>
        <p><?php echo htmlspecialchars($user['email']); ?></p>
    </div>

    <div class="info">
        <label>Points:</label>
        <p><?php echo $points; ?></p>
    </div>

    <div class="language-select">
        <label for="language">Select Language:</label>
        <select id="language" name="language">
            <option value="en">English</option>
            <option value="ms">Malay</option>
            <option value="zh">Chinese</option>
        </select>
    </div>

    <form action="logout.php" method="POST">
        <button class="logout-btn" type="submit">Logout</button>
    </form>
</div>

</body>
</html>
