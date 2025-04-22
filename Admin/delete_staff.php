<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM staff WHERE id = $id");
}

header("Location: manage_staff.php?success=Staff deleted successfully");
exit();
?>