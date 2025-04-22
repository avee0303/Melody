<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM categories WHERE id = $id");
}

header("Location: manage_categories.php?success=Category deleted successfully");
exit();
?>