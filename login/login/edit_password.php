<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Password validation
    if (strlen($new_password) < 8) {
        $error = "Password must be at least 8 characters long";
    } elseif (!preg_match('/[A-Z]/', $new_password)) {
        $error = "Password must contain at least one uppercase letter";
    } elseif (!preg_match('/[a-z]/', $new_password)) {
        $error = "Password must contain at least one lowercase letter";
    } elseif (!preg_match('/[0-9]/', $new_password)) {
        $error = "Password must contain at least one number";
    } elseif ($new_password !== $confirm_password) {
        $error = "New passwords don't match!";
    } else {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "burger4.0";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (password_verify($old_password, $user['password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_stmt->bind_param("si", $hashed_password, $user_id);
            $update_stmt->execute();
            $update_stmt->close();
            $success = "Password changed successfully!";
        } else {
            $error = "Current password is incorrect!";
        }
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Change Password</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #fcefdc;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
    }

    .container {
      background-color: white;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      padding: 30px;
      width: 100%;
      max-width: 500px;
      box-sizing: border-box;
    }

    h1 {
      text-align: center;
      font-size: 2em;
      margin-bottom: 20px;
      color: #5a321d;
    }

    .requirements {
      background-color: #f7f7f7;
      border-left: 5px solid #a87745;
      padding: 15px;
      margin-bottom: 25px;
      border-radius: 10px;
    }

    .requirements ul {
      padding-left: 20px;
      margin: 0;
    }

    .top-nav {
        position: absolute;
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

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
      color: #5a321d;
    }

    .input-group {
      position: relative;
      margin-bottom: 20px;
    }

    input[type="password"] {
      width: 100%;
      padding: 12px 40px 12px 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      box-sizing: border-box;
      font-size: 1rem;
    }

    .input-group .toggle-visibility {
      position: absolute;
      right: 10px;
      top: 10px;
      background: none;
      border: none;
      cursor: pointer;
      font-size: 1.2em;
      color: #777;
    }

    .buttons {
      display: flex;
      justify-content: space-between;
      gap: 10px;
    }

    .buttons button {
      flex: 1;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      color: white;
    }

    .update-btn {
      background-color: #a87745;
    }

    .cancel-btn {
      background-color: #5e5e5e;
    }

    .update-btn:hover {
      background-color: #916332;
    }

    .cancel-btn:hover {
      background-color: #444;
    }

    .message {
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
    }

    .message.error {
      color: red;
    }

    .message.success {
      color: green;
    }
  </style>
</head>
<body>

<div class="top-nav">
    <a href="password.php" class="nav-button">Back</a>
</div>

<div class="container">
  <h1>Change Password</h1>

  <?php if ($error): ?>
    <p class="message error"><?php echo htmlspecialchars($error); ?></p>
  <?php elseif ($success): ?>
    <p class="message success"><?php echo htmlspecialchars($success); ?></p>
  <?php endif; ?>

  <div class="requirements">
    <strong>Password Requirements:</strong>
    <ul>
      <li>At least 8 characters long</li>
      <li>Contains at least one uppercase letter</li>
      <li>Contains at least one lowercase letter</li>
      <li>Contains at least one number</li>
    </ul>
  </div>

  <form id="passwordForm" method="POST">
    <div class="input-group">
      <label for="old_password">Current Password</label>
      <input type="password" id="old_password" name="old_password" required />
      <button type="button" class="toggle-visibility" onclick="toggleVisibility('old_password')">
      </button>
    </div>

    <div class="input-group">
      <label for="new_password">New Password</label>
      <input type="password" id="new_password" name="new_password" required />
      <button type="button" class="toggle-visibility" onclick="toggleVisibility('new_password')">
      </button>
    </div>

    <div class="input-group">
      <label for="confirm_password">Confirm New Password</label>
      <input type="password" id="confirm_password" name="confirm_password" required />
      <button type="button" class="toggle-visibility" onclick="toggleVisibility('confirm_password')">
      </button>
    </div>

    <div class="buttons">
      <button type="submit" class="update-btn">Change Password</button>
    </div>
  </form>
</div>

<script>
function toggleVisibility(id) {
  const input = document.getElementById(id);
  const icon = input.nextElementSibling.querySelector('i');
  if (input.type === "password") {
    input.type = "text";
    icon.classList.replace('fa-eye', 'fa-eye-slash');
  } else {
    input.type = "password";
    icon.classList.replace('fa-eye-slash', 'fa-eye');
  }
}
</script>

</body>
</html>
