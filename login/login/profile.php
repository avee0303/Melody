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

// Fetch user data
$sql = "SELECT * FROM users WHERE id=?";
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
    <title>User Profile</title>
    <style>
    body{
         background-color: antiquewhite;
    }

    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    }

    .container {
    height: 200px;
    background-color: rgb(80, 35, 20);
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    position: relative;
    }

    .container .title{
    margin: auto;
    font-family: 'flame', "Cooper Black", "Helvetica Neue", Helvetica, Arial, sans-serif;
    color: rgb(245, 235, 220);
    font-size: 55px;
    margin-top: 10px;
    }

    .top-nav {
        position: relative;
        top: 20px;
        left: 20px;
        z-index: 1000;
    }

    .nav-button {
        background-color:  #b71c1c;
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
        background-color:rgb(91, 31, 31);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .burger-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr); /* Exactly 3 items in one row */
      gap: 60px 40px; /* vertical and horizontal gap */
      max-width: 1000px;
      margin: auto;
      padding: 0 20px 60px;
      justify-items: center;
      padding-top: 50px;
    }


    .burger-item {
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      height: 320px; /* Ensure all cards have equal height */
      justify-content: start; /* Align items at top */
    }

    .burger-item img {
      max-height: 200px; /* Forces uniform image height */
      object-fit: contain; /* Prevent distortion */
      border-radius: 10px;
      transition: transform 0.3s;
    }

    .burger-item:hover img {
      transform: scale(1.05);
    }

    .burger-item p {
      margin-top: 10px;
      font-family: 'Gill Sans', sans-serif;
      font-size: 18px;
      font-weight: bold;
      color: #4b2f16;
    }

    .new-badge {
      position: absolute;
      background-color: red;
      color: white;
      font-size: 12px;
      font-weight: bold;
      padding: 3px 8px;
      border-radius: 50px;
      top: 8px;
      left: 8px;
    }

    .burger-card {
      position: relative;
    }
    </style>
</head>
<body>

<section class="container">
      <div>
        <h1 class="title">PROFILE PAGE</h1>
      </div>
</section>

<div class="top-nav">
    <a href="main2_page.php" class="nav-button">Back to Home</a>
</div>

<section class="burger-section">
  <div class="burger-grid">
    <div class="burger-item">
      <a href="personal.php" class="burger-card">
        <img src="profile-account.png" alt="Promotion">
      </a>
      <p>PERSONAL DETAILS</p>
    </div>

    <div class="burger-item">
      <a href="password.php" class="burger-card">
        <img src="unlock.png" alt="Burger">
      </a>
      <p>PASSWORD & SECURITY</p>
    </div>

    <div class="burger-item">
      <a href="#" onclick="confirmLogout()" class="burger-card">
        <img src="logout.png" alt="Sides">
      </a>
      <p>LOG OUT</p>
    </div>
  </div>
</section>

<script>
function confirmLogout() {
    if (confirm("Are you sure you want to log out?")) {
        window.location.href = "logout.php";
    }
}
</script>

</body>
</html>
