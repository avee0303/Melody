<?php
session_start();
$conn = new mysqli("localhost", "root", "", "payment");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure all required POST variables are set
    if (isset($_POST['customer_id'], $_POST['cart_id'], $_POST['name'], $_POST['address'], $_POST['postcode'], $_POST['paymentMethod'], $_POST['tip'], $_POST['deliveryCharge'], $_POST['totalAmount'], $_POST['checkoutLat'], $_POST['checkoutLng'])) {
        // Get POST data
        $customer_id = $_POST['customer_id'];
        $cart_id = $_POST['cart_id'];
        $name = $_POST['name'];
        $address = $_POST['address'];
        $postcode = $_POST['postcode'];
        $paymentMethod = $_POST['paymentMethod'];
        $tip = $_POST['tip'];
        $deliveryCharge = $_POST['deliveryCharge'];
        $totalAmount = $_POST['totalAmount'];
        $checkoutLat = $_POST['checkoutLat'];
        $checkoutLng = $_POST['checkoutLng'];

        // Insert query without checkout_date (MySQL will handle it automatically)
        $sql = "INSERT INTO checkout (customer_id, cart_id, name, address, postcode, payment_method, tip, delivery_charge, total_amount, checkout_lat, checkout_lng)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare and bind parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissdddssss", $customer_id, $cart_id, $name, $address, $postcode, $paymentMethod, $tip, $deliveryCharge, $totalAmount, $checkoutLat, $checkoutLng);
        
        // Execute and check if successful
        if ($stmt->execute()) {
            echo "Address and order saved successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: Missing required fields.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css">
    <style>   
          body {
            font-family: 'Arial', sans-serif;
            background-color: #f5e1c8;
            margin: 0;
            padding: 0;
        }
        .navbar {   
            background: #d62300;
            padding: 15px;
            text-align: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            border: 5px solid #d62300;
        }
        h2 {
            color: #8b5e3b;
            text-align: center;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h3 {
            margin-bottom: 10px;
            color: #6a4d32;
        }
        .address-box, #addressForm, .order-summary {
            background: #e6d3b5;
            padding: 10px;
            border-radius: 5px;
        }
        .btn {
            background: #8b5e3b;
            color: #fff;
            border: none;
            padding: 10px 15px;
            margin-top: 5px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .submit-btn {
            width: 100%;
            font-size: 18px;
            background: #6a4d32;
            color: #fff;
            padding: 15px;
        }
        select, input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #8b5e3b;
            border-radius: 5px;
        }
        .tip-buttons button {
            background: #b08a65;
            margin-right: 5px;
        }
        hr {
            border: 1px solid #8b5e3b;
        }
        .footer {
            text-align: center;
            padding: 15px;
            background: #d62300;
            color: white;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h2>Checkout</h2>
        
        <div class="section">
            <h3>Delivery Address</h3>
            <div class="address-box">
                <p><strong id="displayAddress">5 Jalan Bukit Indah 17</strong></p>
                <p id="displayPostcode">Johor, 85020</p>
                <button onclick="openAddressForm()">Change</button>
            </div>
            <div id="addressForm" style="display: none;">
                <input type="text" id="newAddress" placeholder="Enter new address">
                <input type="text" id="newPostcode" placeholder="Enter new postcode">
                <button onclick="saveAddress()">Save</button>
                <button onclick="closeAddressForm()">Cancel</button>
            </div>
        </div>
       
        <div>
            <h3>Order Time</h3>
            <p> Date: <span id="visibleDate"></span></p>
            <p>Time: <span id="visibleTime"></span></p>
        </div>

        <div class="section">
            <h3>Delivery Options</h3>
            <label><input type="radio" name="delivery" value="-1.40" onclick="updateDelivery(this)"> Economy (20-35 min) - RM 1.40 off</label><br>
            <label><input type="radio" name="delivery" value="0" checked onclick="updateDelivery(this)"> Standard (10-25 min)</label><br>
            <label><input type="radio" name="delivery" value="2.40" onclick="updateDelivery(this)"> Priority (5-20 min) + RM 2.40</label>
        </div>

        <div class="section">
            <h3>Payment Method</h3>
            <select id="paymentMethod" onchange="togglePaymentDetails()">
                <option value="tng">Touch 'n Go eWallet</option>
                <option value="credit-card">Credit/Debit Card</option>
                <option value="bank">Online Banking</option>
                <option value="cash">Cash on Delivery</option>
            </select>
            <div id="paymentDetails"></div>
        </div>

        <div class="section">
            <h3>Promo Code</h3>
            <input type="text" id="promoCode" name="promoCode" placeholder="Enter Promo Code">
            <button type="button" onclick="applyPromo()">Apply</button>
            <p id="discountInfo" style="color: green;"></p>
        </div>
            
        <div class="section">
            <div class="section">
                <h3>Tip Your Rider (Optional)</h3>
                <div class="tip-buttons">
                    <button onclick="addTip(1.00)">RM 1.00</button>
                    <button onclick="addTip(2.00)">RM 2.00</button>
                    <button onclick="addTip(3.00)">RM 3.00</button>
                    <button onclick="addTip(4.00)">RM 4.00</button>
                </div>
            </div>
        </div>

        <div class="section">
            <h3>Order Summary</h3>
            <div id="orderSummary"></div>
            <hr>
            <p><strong>Total: RM <span id="totalAmount">0.00</span></strong></p>
        </div>

        <button class="submit-btn" onclick="placeOrder()">Place Order</button>
    </div>

    <script>
    let orders = [];
    let deliveryCharge = 3.99;
    let tipAmount = 0;
    let discount = 0;

    // Unified window.onload function
    window.onload = function () {
        loadCartItems(); // Load cart items
        const now = new Date();

        // Get current date in YYYY-MM-DD format
        const date = now.toISOString().split('T')[0]; 

        // Get current time in HH:MM:SS format
        const time = now.toTimeString().split(' ')[0];

        // Check if the elements exist before trying to display the date and time
        const dateElement = document.getElementById("visibleDate");
        const timeElement = document.getElementById("visibleTime");

        if (dateElement && timeElement) {
            // Display date and time on the page
            dateElement.textContent = date;
            timeElement.textContent = time;

            // Optionally store the date and time in hidden inputs (if required for form submission)
            document.getElementById("checkoutDate").value = date;
            document.getElementById("checkoutTime").value = time;
        } else {
            console.error("Date and Time elements are missing in the HTML.");
        }
    };

    function loadCartItems() {
        let storedCartItems = JSON.parse(localStorage.getItem("cartItems")) || [];
        let orderSummaryDiv = document.getElementById('orderSummary');
        orderSummaryDiv.innerHTML = "";
        let subtotal = 0;

        storedCartItems.forEach(item => {
            let itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            orderSummaryDiv.innerHTML += `<p>${item.name} (x${item.quantity}) - RM ${itemTotal.toFixed(2)}</p>`;
        });

        orders = [...storedCartItems];
        updateOrderSummary(subtotal); // Pass the subtotal for further calculation
    }

    // Apply promo code functionality
    const promoCodes = {
        "DISCOUNT10": 10.00, // Direct discount of 10 RM
        "SAVE5": 5.00        // Direct discount of 5 RM
    };

    function updateOrderSummary(subtotal) {
        let packagingFee = 2.00;
        let serviceTax = 0.24;
        let total = subtotal + tipAmount + deliveryCharge - discount + packagingFee + serviceTax;
        let orderSummaryDiv = document.getElementById('orderSummary');

        // Clear previous order summary to prevent duplication
        orderSummaryDiv.innerHTML = "";

        // Re-add items to the order summary
        orders.forEach(item => {
            let itemTotal = item.price * (item.quantity || 1);
            orderSummaryDiv.innerHTML += `<p>${item.name} (x${item.quantity || 1}) - RM ${itemTotal.toFixed(2)}</p>`;
        });

        orderSummaryDiv.innerHTML += `<p>Packaging Fee - RM ${packagingFee.toFixed(2)}</p>`;
        orderSummaryDiv.innerHTML += `<p>Service Tax - RM ${serviceTax.toFixed(2)}</p>`;
        orderSummaryDiv.innerHTML += `<p>Delivery - RM ${deliveryCharge.toFixed(2)}</p>`;

        if (tipAmount > 0) {
            orderSummaryDiv.innerHTML += `<p>Tip - RM ${tipAmount.toFixed(2)}</p>`;
        }

        if (discount > 0) {
            orderSummaryDiv.innerHTML += `<p style="color:green;">Promo Discount - RM ${discount.toFixed(2)}</p>`;
        }

        document.getElementById('totalAmount').innerText = total.toFixed(2);
    }

    function applyPromo() {
    let promoCode = document.getElementById("promoCode").value.trim().toUpperCase();

    if (promoCodes.hasOwnProperty(promoCode)) {
        discount = promoCodes[promoCode];
        document.getElementById("discountInfo").innerText = `Promo Applied: ${promoCode} (-RM ${discount.toFixed(2)})`;
        document.getElementById("discountInfo").style.color = "green";
    } else {
        discount = 0;
        document.getElementById("discountInfo").innerText = "❌ Invalid or expired promo code!";
        document.getElementById("discountInfo").style.color = "red";
    }

    // Calculate current subtotal before calling updateOrderSummary
    let subtotal = orders.reduce((sum, item) => sum + item.price * (item.quantity || 1), 0);
    updateOrderSummary(subtotal);
}


    function updateDelivery(option) {
        deliveryCharge = 3.99 + parseFloat(option.value);
        let subtotal = orders.reduce((sum, item) => sum + item.price * (item.quantity || 1), 0);
        updateOrderSummary(subtotal);
    }

    function addTip(amount) {
        tipAmount = (tipAmount === amount) ? 0 : amount;
        let subtotal = orders.reduce((sum, item) => sum + item.price * (item.quantity || 1), 0);
        updateOrderSummary(subtotal);
    }

    function togglePaymentDetails() {
        let method = document.getElementById('paymentMethod').value;
        let details = document.getElementById('paymentDetails');
        details.innerHTML = method === "credit-card" ? '<input type="text" placeholder="Card Number">' : '';
    }

    function openAddressForm() {
        document.getElementById('addressForm').style.display = 'block';
    }

    function closeAddressForm() {
        document.getElementById('addressForm').style.display = 'none';
    }

    function saveAddress() {
        let newAddress = document.getElementById('newAddress').value.trim();
        let newPostcode = document.getElementById('newPostcode').value.trim();
        let currentAddress = document.getElementById('displayAddress').innerText;
        let currentPostcode = document.getElementById('displayPostcode').innerText;

        if (!newAddress || !newPostcode) {
            alert("⚠️ Please enter your new address and postcode!");
            return;
        }
        if (newAddress === currentAddress && newPostcode === currentPostcode) {
            alert("⚠️ Your new address must be different from the current address!");
            return;
        }

        document.getElementById('displayAddress').innerText = newAddress;
        document.getElementById('displayPostcode').innerText = newPostcode;
        closeAddressForm();
    }

    function placeOrder() {
        alert("Your order has been placed successfully!");
    }

    // Initialize order summary
    updateOrderSummary();
    </script>
</body>
