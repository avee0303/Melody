<?php
include("config/db_connect.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM admin WHERE id = $id");
}

header("Location: manage_staffs.php?success=Staff deleted successfully");
exit();
?>