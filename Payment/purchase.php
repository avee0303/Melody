<?php
$orders = [
    [
        "id" => 1,
        "image" => "burgerking.jpg",
        "name" => "Whopper Meal",
        "date" => "10 March, 14:30",
        "description" => "Flame-grilled beef patty with fresh lettuce, tomato & mayo",
        "price" => "RM 15.90"
    ],
    [
        "id" => 2,
        "image" => "doublewhopper.jpg",
        "name" => "Double Whopper Cheese Meal",
        "date" => "05 March, 12:15",
        "description" => "Two flame-grilled beef patties with double cheese & pickles",
        "price" => "RM 18.50"
    ],
    [
        "id" => 3,
        "image" => "chickenroyale.jpg",
        "name" => "Chicken Royale Meal",
        "date" => "25 February, 18:45",
        "description" => "Crispy chicken fillet with lettuce & mayo in a sesame bun",
        "price" => "RM 16.90"
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #d62300;
            font-size: 24px;
            margin-bottom: 15px;
        }

        /* Order Card */
        .order-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .order-card:hover {
            transform: scale(1.02);
        }

        .order-img {
            width: 120px;
            height: 120px;
            border-radius: 8px;
            object-fit: cover;
        }

        .order-info {
            text-align: center;
            margin-top: 10px;
        }

        .order-info h3 {
            color: #d62300;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .order-info p {
            font-size: 14px;
            color: #555;
            margin: 3px 0;
        }

        .order-price {
            font-weight: bold;
            color: #ff5722;
            font-size: 16px;
        }

        /* Reorder Button */
        .reorder-btn {
            background-color: #d62300;
            color: white;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            margin-top: 8px;
            transition: background 0.3s;
        }

        .reorder-btn:hover {
            background-color: #bb1d00;
        }

        /* Star Rating */
        .rating {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .rating span {
            font-size: 28px;
            color: gray;
            cursor: pointer;
            transition: color 0.3s, transform 0.2s;
        }

        .rating span.selected,
        .rating span:hover,
        .rating span:hover ~ span {
            color: orange;
            transform: scale(1.1);
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>🍔 Burger  - Order History</h2>

        <!-- Order 1 -->
        <div class="order-card" data-id="1">
            <img src="burgerking.jpg" class="order-img" alt="Whopper Meal">
            <div class="order-info">
                <h3>Whopper Meal</h3>
                <p>10 March, 14:30</p>
                <p>Flame-grilled beef patty with fresh lettuce, tomato & mayo</p>
                <p class="order-price">RM 15.90</p>
                <button class="reorder-btn">Reorder</button>
            </div>
            <!-- Star Rating -->
            <div class="rating" data-order="1">
                <span data-value="5">★</span>
                <span data-value="4">★</span>
                <span data-value="3">★</span>
                <span data-value="2">★</span>
                <span data-value="1">★</span>
            </div>
        </div>

        <!-- Order 2 -->
        <div class="order-card" data-id="2">
            <img src="doublewhopper.jpg" class="order-img" alt="Double Whopper">
            <div class="order-info">
                <h3>Double Whopper Cheese Meal</h3>
                <p>05 March, 12:15</p>
                <p>Two flame-grilled beef patties with double cheese & pickles</p>
                <p class="order-price">RM 18.50</p>
                <button class="reorder-btn">Reorder</button>
            </div>
            <!-- Star Rating -->
            <div class="rating" data-order="2">
                <span data-value="5">★</span>
                <span data-value="4">★</span>
                <span data-value="3">★</span>
                <span data-value="2">★</span>
                <span data-value="1">★</span>
            </div>
        </div>

        <!-- Order 3 -->
        <div class="order-card" data-id="3">
            <img src="chickenroyale.jpg" class="order-img" alt="Chicken Royale">
            <div class="order-info">
                <h3>Chicken Royale Meal</h3>
                <p>25 February, 18:45</p>
                <p>Crispy chicken fillet with lettuce & mayo in a sesame bun</p>
                <p class="order-price">RM 16.90</p>
                <button class="reorder-btn">Reorder</button>
            </div>
            <!-- Star Rating -->
            <div class="rating" data-order="3">
                <span data-value="5">★</span>
                <span data-value="4">★</span>
                <span data-value="3">★</span>
                <span data-value="2">★</span>
                <span data-value="1">★</span>
            </div>
        </div>

    </div>

    <script>
        // Load saved ratings from localStorage
        document.addEventListener("DOMContentLoaded", function () {
            const ratings = document.querySelectorAll(".rating");

            ratings.forEach(rating => {
                const orderId = rating.getAttribute("data-order");
                const savedRating = localStorage.getItem("rating-" + orderId);

                if (savedRating) {
                    highlightStars(rating, savedRating);
                }

                rating.addEventListener("click", function (event) {
                    if (event.target.tagName === "SPAN") {
                        const value = event.target.getAttribute("data-value");
                        localStorage.setItem("rating-" + orderId, value);
                        highlightStars(rating, value);
                    }
                });
            });
        });

        // Highlight stars based on selected rating
        function highlightStars(ratingElement, value) {
            const stars = ratingElement.querySelectorAll("span");
            stars.forEach(star => {
                if (star.getAttribute("data-value") <= value) {
                    star.classList.add("selected");
                } else {
                    star.classList.remove("selected");
                }
            });
        }
    </script>

</body>
</html>
