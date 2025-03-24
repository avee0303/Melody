<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "payment";

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
            background-color: #d2b48c;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: #8b5a2b;
            padding: 15px;
            text-align: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #f5deb3;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            border: 5px solid #8b5a2b;
        }
        #map {
            height: 300px;
            width: 100%;
            margin: 10px 0;
            border-radius: 8px;
            border: 2px solid #654321;
        }
        button {
            background-color: #8b5a2b;
            color: white;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            border: none;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
            transition: background 0.3s ease;
        }
        button:hover {
            background-color: #654321;
        }
        .info-box {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            font-size: 14px;
            text-align: left;
            border: 2px solid #8b5a2b;
        }
        .info-box p {
            margin: 5px 0;
        }
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }
            .navbar {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">Rider Delivery System</div>
    <div class="container">
        <h1>Rider Delivery</h1>
        <p>Click "Start Delivery" to begin navigation.</p>
        <div class="info-box">
            <h2>Current Delivery</h2>
            <p><strong>Pickup:</strong> <span id="pickupLocation">Loading...</span></p>
            <p><strong>Delivery:</strong> <span id="deliveryLocation">Loading...</span></p>
            <p><strong>Distance:</strong> <span id="distance">-</span> km</p>
            <p><strong>Time:</strong> <span id="duration">-</span> min</p>
            <button onclick="startDelivery()">Start Delivery</button>
        </div>
        <h2>Navigation</h2>
        <div id="map"></div>
    </div>

    <script>
        let map = L.map('map').setView([2.1896, 102.2501], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        let pickupMarker, deliveryMarker, routeLine;

        let orderData = {
            pickup: { lat: 2.1889, lng: 102.2490, name: "Burger King, Melaka" },
            delivery: { lat: 2.2064, lng: 102.2462, name: "No. 25, Taman Kota Laksamana, Melaka" }
        };

        document.getElementById("pickupLocation").innerText = orderData.pickup.name;
        document.getElementById("deliveryLocation").innerText = orderData.delivery.name;

        function startDelivery() {
            if (pickupMarker) map.removeLayer(pickupMarker);
            if (deliveryMarker) map.removeLayer(deliveryMarker);
            if (routeLine) map.removeLayer(routeLine);

            pickupMarker = L.marker([orderData.pickup.lat, orderData.pickup.lng]).addTo(map)
                .bindPopup("📍 Pickup Location").openPopup();
            deliveryMarker = L.marker([orderData.delivery.lat, orderData.delivery.lng]).addTo(map)
                .bindPopup("📦 Delivery Location").openPopup();

            drawRoute(orderData.pickup, orderData.delivery);
        }

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
                    document.getElementById("distance").innerText = (route.distance / 1000).toFixed(2);
                    document.getElementById("duration").innerText = Math.ceil(route.duration / 60);

                    if (routeLine) map.removeLayer(routeLine);
                    routeLine = L.geoJSON(route.geometry, { style: { color: "#8b5a2b", weight: 4 } }).addTo(map);
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
