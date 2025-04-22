<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM products WHERE id = $id");
}

header("Location: manage_products.php?success=Product deleted successfully");
exit();
?>