<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - Burger 404</title>
    <style>
        :root {
            --primary-color: #9c6f34;
            --primary-hover: #b07427;
            --bg-color: antiquewhite;
            --container-color: blanchedalmond;
            --input-bg: #fff8e7;
            --border-color: #d5c7a3;
            --text-dark: #5e3c1b;
            --text-light: #7a5c40;
            --success-bg: #d4edda;
            --success-text: #155724;
            --error-bg: #f8d7da;
            --error-text: #721c24;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            margin: 0;
            padding: 0;
        }

        nav {
            width: 100%;
            height: 70px;
            background-color: var(--container-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: fixed;
            top: 0;
            z-index: 1000;
        }

        nav ul {
            display: flex;
            gap: 20px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            position: relative;
        }

        nav ul li a {
            font-size: 18px;
            font-weight: bold;
            color: var(--text-light);
            text-decoration: none;
        }

        nav ul li a:hover {
            color: rgb(222, 73, 73);
        }

        nav ul li ul {
            position: absolute;
            top: 30px;
            left: 0;
            background: var(--container-color);
            display: none;
            flex-direction: column;
            padding: 10px;
            border-radius: 8px;
        }

        nav ul li:hover ul {
            display: flex;
        }

        nav .logo {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        nav .logo img {
            width: 220px;
            padding-top: 10px;
        }

        nav .login {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: var(--text-light);
            font-weight: bold;
        }

        nav .login img {
            width: 28px;
            height: 28px;
        }

        header.feedback-header {
            background-color: rgb(80, 35, 20);
            color: rgb(245, 235, 220);
            height: 230px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-family: 'flame', "Cooper Black", sans-serif;
            padding-top: 70px;
            text-align: center;
            margin-top: 0;
        }

        .feedback-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: var(--container-color);
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .feedback-form {
            display: grid;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            color: var(--text-dark);
            margin-bottom: 6px;
            font-size: 1.05rem;
        }

        input, textarea, select {
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--input-bg);
            font-size: 1rem;
            font-family: inherit;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .rating-container {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .rating-option {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .submit-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 14px;
            font-size: 1.1rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s, transform 0.2s;
        }

        .submit-btn:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .message {
            padding: 15px;
            margin: 0 0 20px 0;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            display: none;
        }

        .success {
            background-color: var(--success-bg);
            color: var(--success-text);
        }

        .error {
            background-color: var(--error-bg);
            color: var(--error-text);
        }

        @media (max-width: 768px) {
            .feedback-container {
                margin: 20px;
                padding: 20px;
            }

            header.feedback-header {
                font-size: 36px;
                height: 200px;
            }

            nav {
                flex-wrap: wrap;
                height: auto;
                gap: 10px;
            }

            nav ul {
                flex-direction: column;
            }

            .rating-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<nav>
    <ul>
        <li><a href="menu2.html">Menu</a></li>
        <li><a href="offer2.html">Offers</a></li>
        <li>
            <a href="#">More</a>
            <ul>
                <li><a href="about.html">About Us</a></li>
                <li><a href="contact.html">Contact Us</a></li>
                <li><a href="career.html">Career</a></li>
            </ul>
        </li>
    </ul>
    <a href="main2_page.php" class="logo">
        <img src="burger_404_transparent.png" alt="Logo">
    </a>
    <a href="profile.php" class="login">
        <img src="user.png" alt="login">
        <p>PROFILE</p>
    </a>
</nav>

<header class="feedback-header">
    <h1>SHARE YOUR FEEDBACK</h1>
</header>

<div class="feedback-container">
    <?php
    if (isset($_SESSION['feedback_status'])) {
        if ($_SESSION['feedback_status'] === 'success') {
            echo '<div class="message success" style="display:block;">Thank you for your feedback! We appreciate your input.</div>';
        } else {
            echo '<div class="message error" style="display:block;">Something went wrong. Please try again.</div>';
        }
        unset($_SESSION['feedback_status']);
    }
    ?>

    <form class="feedback-form" action="process_feedback.php" method="POST">
        <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Overall Rating</label>
            <div class="rating-container">
                <?php
                $rating_labels = ['Very Poor', 'Poor', 'Average', 'Good', 'Excellent'];
                for ($i = 5; $i >= 1; $i--) {
                    echo '<div class="rating-option">
                        <input type="radio" id="rating'.$i.'" name="rating" value="'.$i.'" required>
                        <label for="rating'.$i.'">'.$i.' - '.$rating_labels[$i-1].'</label>
                    </div>';
                }
                ?>
            </div>
        </div>

        <div class="form-group">
            <label for="visit_date">Date of Visit</label>
            <input type="date" id="visit_date" name="visit_date" required>
        </div>

        <div class="form-group">
            <label for="feedback_type">Feedback Type</label>
            <select id="feedback_type" name="feedback_type" required>
                <option value="">Select feedback type</option>
                <option value="Compliment">Compliment</option>
                <option value="Complaint">Complaint</option>
                <option value="Suggestion">Suggestion</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="message">Your Feedback</label>
            <textarea id="message" name="message" required></textarea>
        </div>

        <button type="submit" class="submit-btn">Submit Feedback</button>
    </form>
</div>

</body>
</html>