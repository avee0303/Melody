<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "database");
if ($conn->connect_error) {
    die("<h2 style='color:red; text-align:center;'>Database connection failed. Please try again later.</h2>");
}

// Initialize variables
$category = [];
$all_categories = [];
$products = [];
$category_id = 0;

// Validate and sanitize category_id
if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
    $category_id = (int)$_GET['category_id'];
}

if ($category_id <= 0) {
    die("<h2 style='color:red; text-align:center;'>Invalid category ID.</h2>");
}

// Get category information
$category_result = $conn->query("SELECT name FROM categories WHERE id = $category_id");
if ($category_result && $category_result->num_rows > 0) {
    $category = $category_result->fetch_assoc();
} else {
    die("<h2 style='color:red; text-align:center;'>Category not found.</h2>");
}

// Get all categories
$all_categories = $conn->query("SELECT id, name FROM categories");

// Get products for this category
$products = $conn->query("SELECT * FROM product WHERE category = '{$category['name']}'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($category['name']) ?> Products</title>
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
            position: relative;
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

        .burger-title {
            color: rgb(245, 235, 220);
            background-color: rgb(80, 35, 20);
            height: 190px;
            font-size: 70px;
            font-family: 'flame', "Cooper Black", "Helvetica Neue", Helvetica, Arial, sans-serif;
            margin-top: 0px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .categories {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .categories a {
            margin: 10px;
            padding: 10px 20px;
            background-color: white;
            border-radius: 10px;
            color: #8b5e3c;
            text-decoration: none;
            font-weight: bold;
        }

        .categories a:hover {
            background-color: #ffe2b0;
        }

        .burger-items {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }

        .burger-card {
            width: 220px;
            background-color: white;
            margin: 15px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            overflow: hidden;
            text-align: center;
        }

        .burger-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .burger-name {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0 5px;
        }

        .burger-desc {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
            padding: 0 10px;
        }

        .burger-price {
            font-size: 16px;
            color: #cc0000;
            margin-bottom: 10px;
        }

        .back-button {
        margin: 15px 1200px 15px 0px; /* top, right, bottom, left */
        display: inline-block;
        padding: 10px 20px;
        background-color: rgb(222, 73, 73);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: bold;
        transition: 0.3s;
        }


        .back-button:hover {
        background-color: rgb(180, 50, 50);
        }

        .error-message {
            color: red;
            text-align: center;
            margin: 20px;
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

<h1 class="burger-title"><?= strtoupper(htmlspecialchars($category['name'])) ?></h1>

<div style="text-align: left; margin-top: 20px; margin-left: 20px;">
    <a href="menu2.php" class="back-button">← Back to Menu</a>
</div>

<div class="categories">
    <?php if ($all_categories && $all_categories->num_rows > 0): ?>
        <?php while($cat = $all_categories->fetch_assoc()): ?>
            <a href="product.php?category_id=<?= $cat['id'] ?>"
               <?= ($cat['id'] == $category_id) ? 'style="background-color:#e9cba7;color:#cc0000;"' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </a>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="error-message">No categories found</p>
    <?php endif; ?>
</div>



<div class="burger-items">
    <?php if ($products && $products->num_rows > 0): ?>
        <?php while($product = $products->fetch_assoc()): ?>
            <div class="burger-card">
            <?php if (!empty($product['image'])): ?>
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <?php endif; ?>
                <div class="burger-name"><?= htmlspecialchars($product['name']) ?></div>
                <div class="burger-desc"><?= htmlspecialchars($product['description']) ?></div>
                <div class="burger-price">RM <?= number_format($product['price'], 2) ?></div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="error-message">No products available in this category.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php $conn->close(); ?>
