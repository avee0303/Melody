<?php
$servername = "localhost"; 
$username = "root";        
$password = "";           
$dbname = "users_db"; 
session_start();
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$address = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userId = $_POST['users_id'];
    $cartId = $_POST['cart_id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $paymentMethod = $_POST['paymentMethod'];
    $tip = $_POST['tip'];
    $deliveryCharge = $_POST['deliveryCharge'];
    $totalAmount = $_POST['totalAmount'];
    $checkoutLat = $_POST['checkoutLat'];
    $checkoutLng = $_POST['checkoutLng'];

    $stmt = $conn->prepare("INSERT INTO orders (user_id, cart_id, name, address,payment_method, tip, delivery_charge, total_amount, checkout_lat, checkout_lng)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "iissssddss", 
        $userId,
        $cartId,
        $name,
        $address,
        $paymentMethod,
        $tip,
        $deliveryCharge,
        $totalAmount,
        $checkoutLat,
        $checkoutLng
    );

    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}


if (!isset($userId)) {
    $userId = $_SESSION['user_id'] ?? null;
}

$userid=$_SESSION['user_id'];
$getrecord=mysqli_query($conn,"SELECT * FROM users WHERE id='$userid'");
$fetch=mysqli_fetch_assoc($getrecord);

$conn->close();
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

        .main-box {
            background-color: #f0e6d2; 
            padding: 40px 20px;
            margin: 20px auto;
            max-width: 1000px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
    <div class="main-box">
        <div class="checkout-container">
            <h2>Checkout</h2>

<div class="section">
                <h3>Delivery Address</h3>
                <div class="address-box">
                <p><strong id="displayAddress"><?php echo $fetch["address"]; ?></strong></p>
        <button onclick="openAddressForm()">Change</button>
                </div>
                <div id="addressForm" style="display: none;">
                    <input type="text" id="newAddress" placeholder="Enter new address">
                    <button onclick="saveAddress()">Save</button>
                    <button onclick="closeAddressForm()">Cancel</button>
                </div>
            </div>


            <div>
                <h3>Order Time</h3>
                <p>Date: <span id="visibleDate"></span></p>
                <p>Time: <span id="visibleTime"></span></p>
            </div>

            <div class="section">
                <h3>Delivery Options</h3>
                <label><input type="radio" name="delivery" value="-1.40" onclick="updateDelivery(this)"> Economy (20-35 min) - RM 1.40 off</label><br>
                <label><input type="radio" name="delivery" value="0" checked onclick="updateDelivery(this)"> Standard (9-25 min)</label><br>
                <label><input type="radio" name="delivery" value="2.40" onclick="updateDelivery(this)"> Priority (5-20 min) + RM 2.40</label>
            </div>

            <h3>Payment Method</h3>
            <label>
                <input type="radio" name="paymentMethod" value="credit-card" onchange="togglePaymentDetails()">
                Credit Card
            </label><br>

            <label>
                <input type="radio" name="paymentMethod" value="TNG ewallet" onchange="togglePaymentDetails()">
                TNG eWallet
            </label><br>

            <label>
                <input type="radio" name="paymentMethod" value="cash" onchange="togglePaymentDetails()">
                Cash
            </label><br>

            <div id="paymentDetails"></div>

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

        

        </div>
    </div>
    <form id="checkoutForm" method="POST" onsubmit="placeOrder(event)">
  <div class="main-box">
    <div class="checkout-container">
      
      <button class="submit-btn" type="submit">Place Order</button>
    </div>
  </div>
  <input type="hidden" name="users_id" value=<?php echo $fetch["id"]; ?> >
  <input type="hidden" name="cart_id" value="123">
  <input type="hidden" name="name" id="formName">
  <input type="hidden" name="address" id="formAddress">
  <input type="hidden" name="paymentMethod" id="formPayment">
  <input type="hidden" name="tip" id="formTip">
  <input type="hidden" name="deliveryCharge" id="formDelivery">
  <input type="hidden" name="totalAmount" id="formTotal">
  <input type="hidden" name="checkoutLat" id="formLat" value="0">
  <input type="hidden" name="checkoutLng" id="formLng" value="0">
  <input type="hidden" name="date" id="checkoutDate">
  <input type="hidden" name="time" id="checkoutTime">

</form>


</body>
</html>

    <script>
    let orders = [];
    let deliveryCharge = 3.99;
    let tipAmount = 0;
    let discount = 0;

    window.onload = function () {
        loadCartItems(); 
        const now = new Date();

        const date = now.toISOString().split('T')[0]; 

        
        const time = now.toTimeString().split(' ')[0];

        
        const dateElement = document.getElementById("visibleDate");
        const timeElement = document.getElementById("visibleTime");

        if (dateElement && timeElement) {
            
            dateElement.textContent = date;
            timeElement.textContent = time;

            
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
        updateOrderSummary(subtotal); 
    }

  
    const promoCodes = {
        "DISCOUNT10": 10.00, 
        "SAVE5": 5.00        
    };

function updateOrderSummary(subtotal) {
    let packagingFee = 2.00;
    let serviceTax = 0.24;
    let total = subtotal + tipAmount + deliveryCharge - discount + packagingFee + serviceTax;
    let orderSummaryDiv = document.getElementById('orderSummary');

    orderSummaryDiv.innerHTML = "";

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

    document.getElementById("formTip").value = tipAmount.toFixed(2);
}


    function togglePaymentDetails() {
    let selected = document.querySelector('input[name="paymentMethod"]:checked');
    let details = document.getElementById('paymentDetails');

    if (selected && selected.value === "credit-card") {
        details.innerHTML = `
            <input type="text" id="cardNumber" placeholder="Card Number" name="cardNumber" maxlength="19" required><br>
            <input type="text" id="expDate" placeholder="Expiration Date (MM/YY)" name="expDate"
                pattern="^(0[1-9]|1[0-2])\\/\\d{2}$" maxlength="5" title="Format must be MM/YY" required><br>
            <input type="text" placeholder="Security Code (CVV)" name="cvv"
                pattern="\\d{3}" maxlength="3" inputmode="numeric" title="Must be 3 digits" required>
        `;

        const expDateInput = document.getElementById('expDate');
expDateInput.addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^0-9]/g, '');

    if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4);
    }

    value = value.slice(0, 5); 
    e.target.value = value;

 
    if (value.length === 5) {
        const [monthStr, yearStr] = value.split('/');
        const year = parseInt(yearStr);
        const month = parseInt(monthStr);

        if (year > 28) {
            alert("Expiration year cannot be after 2028.");
            e.target.value = ''; 
        } else if (year === 28 && month > 12) {
            alert("Invalid month in 2028.");
            e.target.value = '';
        }
    }
});

        const cardNumberInput = document.getElementById('cardNumber');
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            let formattedValue = '';

            for (let i = 0; i < value.length; i += 4) {
                formattedValue += value.slice(i, i + 4) + ' ';
            }

            e.target.value = formattedValue.trim().slice(0, 19); 
        });
    } else {
        details.innerHTML = '';
    }
   
}



function openAddressForm() {
    document.getElementById('addressForm').style.display = 'block';
}

function closeAddressForm() {
    document.getElementById('addressForm').style.display = 'none';
}

function saveAddress() {
    let newAddress = document.getElementById('newAddress').value.trim();
    let currentAddress = document.getElementById('displayAddress').innerText;

    if (!newAddress) {
        alert("⚠️ Please enter your new address!");
        return;
    }

    if (newAddress === currentAddress) {
        alert("⚠️ Your new address must be different from the current address!");
        return;
    }

    // Update address on display
    document.getElementById('displayAddress').innerText = newAddress;

    // Optional: update hidden input field if used in a form
    let formAddressInput = document.getElementById("formAddress");
    if (formAddressInput) {
        formAddressInput.value = newAddress;
    }

    closeAddressForm();
}

    
    
    function placeOrder(event) {
    event.preventDefault(); 

    const selectedPayment = document.querySelector('input[name="paymentMethod"]:checked');
    const paymentMethod = selectedPayment ? selectedPayment.value : "cash";

    // If credit card is selected, validate card details
    if (paymentMethod === "credit-card") {
        const cardNumber = document.getElementById("cardNumber").value.trim();
        const expDate = document.getElementById("expDate").value.trim();
        const cvv = document.querySelector('input[name="cvv"]').value.trim();

        if (!cardNumber || !expDate || !cvv) {
            alert("⚠️ Please complete all credit card information before placing the order.");
            return;
        }

        // Optional: basic format checks
        if (cardNumber.replace(/\s/g, '').length < 16) {
            alert("⚠️ Card number must be at least 16 digits.");
            return;
        }

        if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expDate)) {
            alert("⚠️ Expiration date format is invalid.");
            return;
        }

        if (!/^\d{3}$/.test(cvv)) {
            alert("⚠️ CVV must be 3 digits.");
            return;
        }
    }


    document.getElementById("formName").value = "User Name";
    document.getElementById("formAddress").value = document.getElementById("displayAddress").innerText;
    document.getElementById("formPayment").value = paymentMethod;
    document.getElementById("formTip").value = tipAmount.toFixed(2);
    document.getElementById("formDelivery").value = deliveryCharge.toFixed(2);
    document.getElementById("formTotal").value = document.getElementById("totalAmount").innerText;


    alert("✅ Order successfully placed!");

    document.getElementById("checkoutForm").submit();
}



    </script>
</body>
</html>