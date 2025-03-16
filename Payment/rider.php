<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rider_orders";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['accept'])) {
        $id = $_POST['accept'];
        $conn->query("UPDATE orders SET status='accepted' WHERE id=$id");
    } elseif (isset($_POST['reject'])) {
        $id = $_POST['reject'];
        $conn->query("DELETE FROM orders WHERE id=$id");
    }
}

$newOrders = $conn->query("SELECT * FROM orders WHERE status='pending'");
$acceptedOrders = $conn->query("SELECT * FROM orders WHERE status='accepted'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Order System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #333;
        }
        .order {
            background: #ffffff;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #007bff;
            text-align: left;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 5px;
            transition: 0.3s;
        }
        .accept-btn {
            background: #28a745;
            color: white;
        }
        .reject-btn {
            background: #dc3545;
            color: white;
        }
        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>New Orders</h2>
        <div id="newOrders"></div>

        <h2>Accepted Orders</h2>
        <div id="acceptedOrders"></div>
    </div>

    <script>
        let orders = [
            { id: 1, details: "Order #1 - Whopper Meal", pickup: "Burger King, Melaka Raya", delivery: "No. 5, Taman Melaka Perdana, Melaka" },
            { id: 2, details: "Order #2 - Double Cheeseburger", pickup: "Burger King, Ayer Keroh", delivery: "No. 12, Bukit Beruang, Melaka" },
            { id: 3, details: "Order #3 - BBQ Beef Burger", pickup: "Burger King, Kota Laksamana", delivery: "Apartment 8, Jalan Klebang, Melaka" }
        ];
        let acceptedOrders = [];

        function loadOrders() {
            let newOrdersDiv = document.getElementById("newOrders");
            newOrdersDiv.innerHTML = "";
            orders.forEach(order => {
                let orderDiv = document.createElement("div");
                orderDiv.className = "order";
                orderDiv.innerHTML = `
                    <p><strong>${order.details}</strong></p>
                    <p>Pickup: ${order.pickup}</p>
                    <p>Delivery: ${order.delivery}</p>
                    <button class="btn accept-btn" onclick="acceptOrder(${order.id})">Accept</button>
                    <button class="btn reject-btn" onclick="rejectOrder(${order.id})">Reject</button>
                `;
                newOrdersDiv.appendChild(orderDiv);
            });
        }

        function acceptOrder(orderId) {
            let orderIndex = orders.findIndex(order => order.id === orderId);
            if (orderIndex !== -1) {
                acceptedOrders.push(orders[orderIndex]);
                orders.splice(orderIndex, 1);
                loadOrders();
                loadAcceptedOrders();
            }
        }

        function rejectOrder(orderId) {
            orders = orders.filter(order => order.id !== orderId);
            loadOrders();
        }

        function loadAcceptedOrders() {
            let acceptedOrdersDiv = document.getElementById("acceptedOrders");
            acceptedOrdersDiv.innerHTML = acceptedOrders.map(order => `
                <div class="order" style="border-left: 5px solid #28a745;">
                    <p><strong>${order.details}</strong></p>
                    <p>Pickup: ${order.pickup}</p>
                    <p>Delivery: ${order.delivery}</p>
                </div>
            `).join('');
        }

        loadOrders();
    </script>
</body>
</html>