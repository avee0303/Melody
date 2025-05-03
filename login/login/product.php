<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "users_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get category ID from URL
$category_id = (int)$_GET['category_id'];

// Get category info
$category = $conn->query("SELECT name FROM categories WHERE id=$category_id")->fetch_assoc();

// Get products for this category
$products = $conn->query("SELECT * FROM products WHERE category_id=$category_id AND is_active=1");

// Get all categories for navigation
$all_categories = $conn->query("SELECT id, name FROM categories WHERE is_active=1");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burger</title>
    <link rel="stylesheet" href="offer2.css">
    <style>
      body{
    background-color: antiquewhite;
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

nav ul li {
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

.login:hover{   
    color: black;
}

.login img {
    width: 24px; 
    height: 24px;
}


    </style>
</head>
<body>
  <nav>
      <ul>
        <li><a href="menu2.html">Menu</a></li>
        <li><a href="offer2.html">Offers</a></li>
        <li><a href="#">More</a>
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
    <h1 class="burger-title"><?= strtoupper(htmlspecialchars($category['name'])) ?></h1>
  
    <div class="categories">
        <?php while($cat = $all_categories->fetch_assoc()): ?>
            <a href="product.php?category_id=<?= $cat['id'] ?>" 
               <?= $cat['id'] == $category_id ? 'style="background-color:#e9cba7;color:#cc0000;"' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </a>
        <?php endwhile; ?>
    </div>
  
    <a href="menu2.php" class="back-button">← Back to Menu</a>
  
    <div class="burger-items">
        <?php while($product = $products->fetch_assoc()): ?>
        <div class="burger-card">
            <button class="add-cart">+</button>
            <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            <div class="burger-name"><?= htmlspecialchars($product['name']) ?></div>
            <div class="burger-desc"><?= htmlspecialchars($product['description']) ?></div>
            <div class="burger-price">RM <?= number_format($product['price'], 2) ?></div>
        </div>
        <?php endwhile; ?>
    </div>
</section>

</body>
</html>
<?php $conn->close(); ?>