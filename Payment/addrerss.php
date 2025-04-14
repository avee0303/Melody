<?php
session_start();
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "payment");

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $postcode = isset($_POST['postcode']) ? trim($_POST['postcode']) : '';
    $paymentMethod = isset($_POST['paymentMethod']) ? trim($_POST['paymentMethod']) : '';
    $tip = isset($_POST['tip']) ? floatval($_POST['tip']) : 0.0;
    $deliveryCharge = isset($_POST['deliveryCharge']) ? floatval($_POST['deliveryCharge']) : 0.0;
    $totalAmount = isset($_POST['totalAmount']) ? floatval($_POST['totalAmount']) : 0.0;
    $checkoutLat = isset($_POST['checkoutLat']) ? floatval($_POST['checkoutLat']) : 0.0;
    $checkoutLng = isset($_POST['checkoutLng']) ? floatval($_POST['checkoutLng']) : 0.0;

    if (empty($name) || empty($address) || empty($postcode) || empty($paymentMethod)) {
        echo json_encode(["error" => "Required fields are missing."]);
        exit;
    }

    $sql = "INSERT INTO checkout (name, address, postcode, payment_method, tip, delivery_charge, total_amount, checkout_lat, checkout_lng)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(["error" => "Error preparing statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("ssssddddd", $name, $address, $postcode, $paymentMethod, $tip, $deliveryCharge, $totalAmount, $checkoutLat, $checkoutLng);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Address and order saved successfully!"]);
    } else {
        echo json_encode(["error" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
