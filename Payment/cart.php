<?php
session_start();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        ["name" => "Whopper", "price" => 12.90, "quantity" => 1, "image" => "burger.jpg"],
        ["name" => "Fries", "price" => 5.50, "quantity" => 1, "image" => "fries.jpg"]
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $index = $_POST['index'];
        if ($_POST['action'] == 'increase') {
            $_SESSION['cart'][$index]['quantity']++;
        } elseif ($_POST['action'] == 'decrease') {
            if ($_SESSION['cart'][$index]['quantity'] > 1) {
                $_SESSION['cart'][$index]['quantity']--;
            } else {
                array_splice($_SESSION['cart'], $index, 1);
            }
        } elseif ($_POST['action'] == 'remove') {
            array_splice($_SESSION['cart'], $index, 1);
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return number_format($total, 2);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burger King - Shopping Cart</title>
    <style>
        body { 
            font-family: 'Arial', 
            sans-serif; 
            background-color: #d2b48c; 
            margin: 0; 
            padding: 0; 
        }
        .navbar 
        { 
            background: #8b5a2b; 
            padding: 15px; 
            text-align: center; 
            color: white; 
            font-size: 24px; 
            font-weight: bold; 
        }
        .cart-container 
        { 
            max-width: 900px; 
            margin: 50px auto; 
            background: #f5deb3; 
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); 
            border: 5px solid #8b5a2b; 
        }
        .cart-item 
        { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            border-bottom: 1px solid #654321; 
            padding: 15px 0;
         }
        .cart-item img 
        { 
            width: 80px; 
            border-radius: 10px; 
        }
        .item-details 
        { 
            flex-grow: 1; 
            margin-left: 20px; 
        }
        .quantity 
        { 
            display: flex; 
            align-items: center; 
        }
        .quantity button 
        { 
            background: #8b5a2b; 
            color: white; 
            border: none; 
            padding: 5px 10px; 
            font-size: 16px; 
            cursor: pointer; 
        }
        .remove-btn 
        { 
            background: #ff0000; 
            color: white; 
            border: none; 
            padding: 5px 10px; 
            font-size: 16px; 
            cursor: pointer; 
            margin-left: 10px; 
        }
        .checkout-btn 
        { 
            display: block; 
            width: 100%; 
            padding: 15px; 
            background: #654321; 
            color: white; 
            text-align: center; 
            font-size: 18px; 
            font-weight: bold; 
            border: none; 
            cursor: pointer; 
            margin-top: 20px; 
        }
        .footer 
        { 
            text-align: center;
            padding: 15px; 
            background: #654321; 
            color: white; 
            margin-top: 30px; 
        }
    </style>
</head>
<body>
    <div class="navbar">Burger King - Shopping Cart</div>

    <div class="cart-container" id="cartContainer">
        <div class="cart-item" id="item0">
            <img src="spicy.jpg" alt="Whopper">
            <div class="item-details">
                <h3>Spicy Tendercrips</h3>
                <p>RM 18.90</p>
            </div>
            <div class="quantity">
                <button onclick="updateQuantity(-1, 0)">-</button>
                <span id="qty0">1</span>
                <button onclick="updateQuantity(1, 0)">+</button>
            </div>
        </div>
        
        <h3>Total: RM <span id="totalPrice">18.40</span></h3>
        <button class="checkout-btn" onclick="checkout()">Proceed to Checkout</button>
    </div>
    
    <div class="footer">&copy; 2025 Burger King. All Rights Reserved.</div>

    <script>
        let prices = [12.90, 5.50];
        let quantities = [1, 1];

        function updateQuantity(change, index) {
            if (quantities[index] + change > 0) {
                quantities[index] += change;
                document.getElementById('qty' + index).innerText = quantities[index];
                updateTotal();
            } else {
                removeItem(index);
            }
        }

        function removeItem(index) {
            document.getElementById('item' + index).remove();
            prices[index] = 0;
            quantities[index] = 0;
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            for (let i = 0; i < prices.length; i++) {
                total += prices[i] * quantities[i];
            }
            document.getElementById('totalPrice').innerText = total.toFixed(2);
        }
        function checkout() {
    let cartItems = [
        {
            name: "Spicy Tendercrips",
            price: 18.90,
            quantity: parseInt(document.getElementById("qty0").innerText)
        }
    ];

    localStorage.setItem("cartItems", JSON.stringify(cartItems)); // Store in local storage
    window.location.href = 'payment.php';
}

    </script>
</body>
</html>
