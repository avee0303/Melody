<?php
include 'db.php';

$rider_id = $_POST['rider_id'];
$order_id = $_POST['order_id'];

$sql = "UPDATE orders SET rider_id = '$rider_id', status = 'Accepted' WHERE id = '$order_id'";
mysqli_query($conn, $sql);

echo json_encode(["status" => "success", "message" => "Order assigned to rider"]);
?>
