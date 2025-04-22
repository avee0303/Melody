<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM customers WHERE id = $id");
}

header("Location: manage_customers.php?success=Customer deleted successfully");
exit();
?>