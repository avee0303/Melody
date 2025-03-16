<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rider_delivery";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM orders ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);


$orderData = [];
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $orderData = [
        "pickup" => ["lat" => $row["pickup_lat"], "lng" => $row["pickup_lng"], "name" => $row["pickup_name"]],
        "delivery" => ["lat" => $row["delivery_lat"], "lng" => $row["delivery_lng"], "name" => $row["delivery_name"]]
    ];
} else {
    // Default data if no orders exist
    $orderData = [
        "pickup" => ["lat" => 3.1478, "lng" => 101.6953, "name" => "KLCC, Kuala Lumpur"],
        "delivery" => ["lat" => 3.1284, "lng" => 101.6337, "name" => "Mid Valley, Kuala Lumpur"]
    ];
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Delivery System</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        #map {
            height: 400px;
            width: 100%;
            margin: 10px auto;
            border-radius: 8px;
            border: 2px solid #ddd;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            border: none;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .info-box {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }
        .info-box p {
            margin: 5px 0;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Rider Delivery System</h1>
        <p>Click "Start Delivery" to begin navigation.</p>

        <div class="info-box">
            <h2>Current Delivery</h2>
            <p><strong>Pickup:</strong> <span id="pickupLocation">Loading...</span></p>
            <p><strong>Delivery:</strong> <span id="deliveryLocation">Loading...</span></p>
            <p><strong>Estimated Distance:</strong> <span id="distance">-</span> km</p>
            <p><strong>Estimated Time:</strong> <span id="duration">-</span> minutes</p>
            <button onclick="startDelivery()">Start Delivery</button>
        </div>

        <h2>Navigation</h2>
        <div id="map"></div>
    </div>

    <script>
        // 初始化地图
        let map = L.map('map').setView([3.139, 101.686], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        let pickupMarker, deliveryMarker, routeLine;

        // 订单数据（示例）
        let orderData = {
            pickup: { lat: 3.1478, lng: 101.6953, name: "KLCC, Kuala Lumpur" },
            delivery: { lat: 3.1284, lng: 101.6337, name: "Mid Valley, Kuala Lumpur" }
        };

        // 自动填充 Pickup & Delivery 地址
        document.getElementById("pickupLocation").innerText = orderData.pickup.name;
        document.getElementById("deliveryLocation").innerText = orderData.delivery.name;

        // 启动配送功能
        function startDelivery() {
            let pickup = orderData.pickup;
            let delivery = orderData.delivery;

            // 清除旧的标记和路线
            if (pickupMarker) map.removeLayer(pickupMarker);
            if (deliveryMarker) map.removeLayer(deliveryMarker);
            if (routeLine) map.removeLayer(routeLine);

            // 标记取货和送货地点
            pickupMarker = L.marker([pickup.lat, pickup.lng]).addTo(map)
                .bindPopup("📍 Pickup Location").openPopup();
            deliveryMarker = L.marker([delivery.lat, delivery.lng]).addTo(map)
                .bindPopup("📦 Delivery Location").openPopup();

            // 获取路线信息
            drawRoute(pickup, delivery);
        }

        // 调用 OSRM API 获取路径
        function drawRoute(start, end) {
            let url = `https://router.project-osrm.org/route/v1/driving/${start.lng},${start.lat};${end.lng},${end.lat}?overview=full&geometries=geojson`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.code !== "Ok") {
                        alert("🚨 Route fetching failed, please try again!");
                        return;
                    }

                    let route = data.routes[0];
                    let distance_km = (route.distance / 1000).toFixed(2);
                    let duration_min = Math.ceil(route.duration / 60);

                    // 显示预计距离和时间
                    document.getElementById("distance").innerText = distance_km;
                    document.getElementById("duration").innerText = duration_min;

                    // 清除旧路线并添加新路线
                    if (routeLine) map.removeLayer(routeLine);
                    routeLine = L.geoJSON(route.geometry, {
                        style: { color: "blue", weight: 5 }
                    }).addTo(map);

                    // 调整视图以适应路线
                    map.fitBounds(routeLine.getBounds());
                })
                .catch(error => {
                    alert("❌ Route data request failed!");
                    console.error("Error fetching route:", error);
                });
        }
    </script>

</body>
</html>
