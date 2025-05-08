<?php
session_start();

function calculateTotal() {
    $total = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return number_format($total, 2);
}

// Handle quantity changes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['index'])) {
    $index = $_POST['index'];
    $action = $_POST['action'];

    if (isset($_SESSION['cart'][$index])) {
        if ($action === 'increase') {
            $_SESSION['cart'][$index]['quantity']++;
        } elseif ($action === 'decrease') {
            $_SESSION['cart'][$index]['quantity']--;
            if ($_SESSION['cart'][$index]['quantity'] <= 0) {
                unset($_SESSION['cart'][$index]);
            }
        } elseif ($action === 'remove') {
            unset($_SESSION['cart'][$index]);
        }

        // Prepare the response
        $response = [
            'success' => true,
            'new_quantity' => isset($_SESSION['cart'][$index]) ? $_SESSION['cart'][$index]['quantity'] : 0,
            'new_total' => isset($_SESSION['cart'][$index]) ? number_format($_SESSION['cart'][$index]['price'] * $_SESSION['cart'][$index]['quantity'], 2) : '0.00',
            'total' => calculateTotal(),
            'cart_count' => count($_SESSION['cart'])
        ];

        // Send the response as JSON
        echo json_encode($response);
        exit; // Stop further execution of the script
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Burger 4.0 - Shopping Cart</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #d2b48c;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: #8b5a2b;
            padding: 15px;
            text-align: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        .cart-container {
            max-width: 900px;
            margin: 50px auto;
            background: #f5deb3;
            padding: 20px;
            border-radius: 10px;
            border: 5px solid #8b5a2b;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #654321;
            padding: 15px 0;
        }
        .cart-item img {
            width: 80px;
            border-radius: 10px;
        }
        .item-details {
            flex-grow: 1;
            margin-left: 20px;
        }
        .quantity {
            display: flex;
            align-items: center;
        }
        .quantity button, .remove-btn {
            background: #8b5a2b;
            color: white;
            border: none;
            padding: 5px 10px;
            font-size: 16px;
            cursor: pointer;
            margin-left: 5px;
        }
        .remove-btn {
            background: #ff0000;
        }
        .checkout-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background: #654321;
            color: white;
            font-size: 18px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            padding: 15px;
            background: #654321;
            color: white;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="navbar">
    Burger 4.0 - Shopping Cart
    <span class="cart-count"><?= count($_SESSION['cart']) ?></span>
</div>

<div class="cart-container">
    <?php if (!empty($_SESSION['cart'])): ?>
        <?php foreach ($_SESSION['cart'] as $index => $item): ?>
            <div class="cart-item" id="item<?= $index ?>">
                <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>">
                <div class="item-details">
                    <h3><?= $item['name'] ?></h3>
                    <p id="itemTotal<?= $index ?>">RM <?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                </div>
                <div class="quantity">
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="index" value="<?= $index ?>">
                        <input type="hidden" name="action" value="decrease">
                        <button type="submit">-</button>
                    </form>
                    <span id="qty<?= $index ?>"><?= $item['quantity'] ?></span>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="index" value="<?= $index ?>">
                        <input type="hidden" name="action" value="increase">
                        <button type="submit">+</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="index" value="<?= $index ?>">
                        <input type="hidden" name="action" value="remove">
                        <button type="submit" class="remove-btn">Remove</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        <h3>Total: RM <?= calculateTotal() ?></h3>
        <form action="payment.php" method="post">
    <input type="hidden" name="checkout" value="true">
    <button class="checkout-btn" type="submit">Proceed to Checkout</button>
</form>

    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>

<div class="footer">&copy; 2025 Burger 4.0. All Rights Reserved.</div>

<!-- Include AJAX Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartForms = document.querySelectorAll('.cart-item form');

    cartForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent page reload on form submission
            const action = this.querySelector('input[name="action"]').value;
            const index = this.querySelector('input[name="index"]').value;
            
            // Send AJAX request
            fetch('cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams(new FormData(this)) // Sends form data
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update quantity
                    document.getElementById('qty' + index).textContent = data.new_quantity;
                    // Update total price for the item
                    document.getElementById('itemTotal' + index).textContent = `RM ${data.new_total}`;
                    // Update overall total
                    document.querySelector('.cart-container h3').textContent = `Total: RM ${data.total}`;
                    
                    // Update cart count in navbar
                    document.querySelector('.cart-count').textContent = data.cart_count;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>

</body>
</html>