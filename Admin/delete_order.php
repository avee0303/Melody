<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM orders WHERE id = $id");
}

header("Location: manage_orders.php?success=Order deleted successfully");
exit();
?>