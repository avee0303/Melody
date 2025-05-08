<?php
session_start();

// Get the current count of items in the cart
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Return as JSON
echo json_encode(['count' => $cart_count]);
?>