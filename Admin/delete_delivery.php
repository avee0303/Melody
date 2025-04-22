<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM deliveries WHERE id = $id");
}

header("Location: manage_deliveries.php?success=Delivery deleted successfully");
exit();
?>