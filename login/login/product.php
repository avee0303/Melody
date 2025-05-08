<?php
session_start();
$conn = new mysqli("localhost", "root", "", "burger4.0");
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
$products = $conn->query("SELECT * FROM products WHERE category = '{$category['name']}'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($category['name']) ?> Products</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

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
        .nav-icons {
  display: flex;
  align-items: center;
  gap: 20px; /* Space between icons */
}

.cart-icon {
  position: relative;
  display: inline-block;
  text-decoration: none;
  color: #000;
  font-size: 24px; /* Icon size */
}

.cart-count {
  position: absolute;
  top: -5px;
  right: -10px;
  background-color: #ff5733; /* Cart count background */
  color: white;
  font-size: 12px; /* Cart count text size */
  padding: 2px 6px;
  border-radius: 50%;
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
        .burger-card {
    position: relative;
}

.image-container {
    position: relative;
}

.add-to-cart-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #fff;
    border: 2px solid #ff5733; /* Optional: border around the + */
    border-radius: 50%;
    font-size: 30px; /* Adjust the size of the + symbol */
    color: #ff5733; /* Color of the + symbol */
    width: 40px; /* Make the button circular */
    height: 40px; /* Make the button circular */
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10; /* Ensures the icon stays on top */
}

.add-to-cart-btn:hover {
    background-color: #ff5733; /* Optional: Change background color on hover */
    color: #fff; /* Change color of the + symbol on hover */
}

img {
    width: 100%; /* Ensure the image fills the container */
    height: auto;
    display: block;
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
  
        <div class="nav-icons">
  <!-- Cart Icon with Count (links to cart.php) -->
  <a href="cart.php" class="cart-icon">
    <i class="fas fa-shopping-cart"></i>
    <span class="cart-count">0</span>
  </a>

  <!-- Profile Icon with Image -->
  <a href="profile.php" class="login">
    <img src="user.png" alt="login" class="profile-icon">
    <p>PROFILE</p>
  </a>
</div>

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
                <!-- Add + Icon Positioned in the Top Right Corner of the Image -->
                <div class="image-container">
                <button class="add-to-cart-btn" data-id="<?= $product['id'] ?>">+</button>

                    <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                </div>
                
                <div class="burger-name"><?= $product['name'] ?></div>
                <div class="burger-desc"><?= $product['description'] ?></div>
                <div class="burger-price">RM <?= number_format($product['price'], 2) ?></div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="error-message">No products available in this category.</p>
    <?php endif; ?>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const cartCountEl = document.querySelector(".cart-count");

  // Fetch current cart count on page load
  fetch('get_cart_count.php')
    .then(response => response.json())
    .then(json => {
      if (json.count !== undefined) {
        cartCountEl.innerText = json.count;
      }
    })
    .catch(err => console.error("Error getting cart count:", err));

  const buttons = document.querySelectorAll(".add-to-cart-btn");

  buttons.forEach(button => {
    button.addEventListener("click", e => {
      e.preventDefault();  // prevent any default navigation/submission

      const productId = button.getAttribute("data-id");

      // Make a request to add the product to the cart
      fetch('add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `product_id=${productId}`
      })
      .then(response => response.json())   // assume JSON: { success: true, count: 3 }
      .then(json => {
        if (json.success && cartCountEl) {
          // Update the cart count in the UI
          cartCountEl.innerText = json.count;
        }
      })
      .catch(err => console.error("Add to cart failed:", err));
    });
  });
});
</script>


</body>
</html>

<?php $conn->close(); ?>
