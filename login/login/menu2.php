<?php
// menu2.php
$conn = new mysqli("localhost", "root", "", "burger4.0");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get all active categories
$categories = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu Categories</title>
    <style>
        body {
            background-color: antiquewhite;
            font-family: Arial, sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        nav {
            width: 100%;
            height: 70px;
            background-color: blanchedalmond;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px; 
            position: fixed;
            z-index: 1000;
        }

        nav ul {
            display: flex;
            gap: 20px; 
            list-style: none;
        }

        nav ul li a {
            font-size: 20px;
            font-family: 'Impact';
            color: rgb(177, 143, 91);
            text-decoration: none;
        }

        nav .logo {
            display: flex;
            justify-content: center;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        nav .logo img {
            width: 220px;
            height: auto;
            padding-top: 25px;
        }

        nav ul li a:hover {
            color: rgb(222, 73, 73);
        }

        nav ul li ul {
            display: none;
            position: absolute;
            background-color: blanchedalmond;
            padding: 10px;
            border-radius: 8px;
            z-index: 1000; 
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.5); 
        }

        nav ul li:hover ul {
            display: block;
        }

        nav ul li ul li {
            width: 180px;
            border-radius: 4px;
            padding: 10px;
        }

        nav ul li ul li a {
            padding: 8px 14px;
        }

        nav ul li ul li a:hover {
            background-color: rgb(222, 213, 213);
        }

        .login {
            display: flex;
            flex-direction: column; 
            align-items: center; 
            text-decoration: none;
            color: grey;
            font-size: 16px;
            font-weight: bold;
            font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        .login:hover { color: black; }

        .login img {
            width: 24px; 
            height: 24px;
        }

        .burger-section {
            padding-top:50px;
            text-align: center;
            background-color: antiquewhite;
        }

        .burger-title {
            color: rgb(245, 235, 220);
            background-color: rgb(80, 35, 20);
            height: 200px;
            font-size: 55px;
            font-family: 'flame', "Cooper Black", "Helvetica Neue", Helvetica, Arial, sans-serif;
            margin-top: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .burger-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 60px 40px;
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
            height: 320px;
            justify-content: start;
        }

        .burger-item img {
            max-height: 200px;
            width: 100%;
            object-fit: cover;
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

        .burger-card {
            position: relative;
            width: 200px;
            height: 200px;
            border: 2px solid #d3b58f;
            border-radius: 15px;
            background-color: white;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
            text-decoration: none;
            overflow: hidden;
        }

        .burger-card img {
            height: 100%;
            width: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>

<nav>
    <ul>
        <li><a href="menu2.php">Menu</a></li>
        <li><a href="offer2.html">Offers</a></li>
        <li>
            <a href="#">More</a>
            <ul>
                <li><a href="about.html">About Us</a></li>
                <li><a href="contact.html">Contact Us</a></li>
                <li><a href="career.html">Career</a></li>
            </ul>
        </li>
    </ul>
    <a href="main2_page.php" class="logo">
        <img src="burger_404_transparent.png" alt="Logo">
    </a>
    <a href="profile.php" class="login">
        <img src="user.png" alt="login">
        <p>PROFILE</p>
    </a>
</nav>

<section class="burger-section">
    <div>
        <h1 class="burger-title">MENU</h1>
    </div>

    <div class="burger-grid">
        <?php while ($category = $categories->fetch_assoc()): ?>
            <div class="burger-item">
                <a href="product.php?category_id=<?= $category['id'] ?>" class="burger-card">
                    <img src="<?= htmlspecialchars($category['image']) ?>" alt="<?= htmlspecialchars($category['name']) ?>">
                </a>
                <p><?= htmlspecialchars($category['name']) ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</section>

</body>
</html>

<?php $conn->close(); ?>
