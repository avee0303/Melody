<?php
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if product_id is provided in the POST request
if (isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];

    // Fetch product details from the database (you can optimize this part)
    $conn = new mysqli("localhost", "root", "", "burger4.0");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query("SELECT * FROM products WHERE id = $product_id");
    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Check if the product already exists in the cart
        $product_exists = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product['id']) {
                $item['quantity'] += 1;  // Increase quantity
                $product_exists = true;
                break;
            }
        }

        // If the product doesn't exist in the cart, add it
        if (!$product_exists) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1,
            ];
        }

        // Get the updated cart count
        $cart_count = count($_SESSION['cart']);

        // Send a JSON response back to the front end
        echo json_encode(['success' => true, 'count' => $cart_count]);
    } else {
        // If product not found
        echo json_encode(['success' => false, 'message' => 'Product not found']);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Product ID is missing']);
}
?>
