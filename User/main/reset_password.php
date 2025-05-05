<?php session_start();
$conn = mysqli_connect("localhost", "root", "", "users_db");
?>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style>
        body {
            margin: 0;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.6;
            color: #333;
            background-color: #D1B29D; /* Soft brown background */
        }

        .navbar {
            background-color: rgba(208, 135, 72, 0.66); /* Green background for navbar */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 80px;
            background-color: white;
            padding: 30px;
        }

        .card-header {
            font-size: 1.3rem;
            font-weight: bold;
            background-color: #f0f0f0;
            border-bottom: 1px solid #e0e0e0;
            color: #333;
        }

        .card-body {
            padding: 20px;
        }

        input[type="password"], input[type="submit"] {
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            font-size: 1rem;
            box-sizing: border-box;
            padding-right: 40px; /* Add space for the eye icon */
        }

        input[type="password"]:focus {
            border-color: #4CAF50; /* Green border on focus */
            outline: none;
        }

        input[type="submit"] {
            background-color: #D1B29D; /* Soft brown color */
            color: white;
            font-size: 1.1rem;
            font-weight: bold;
            border: none;
            cursor: pointer;
            border-radius: 25px;
            transition: all 0.3s ease;
            padding: 14px 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        input[type="submit"]:hover {
            background-color: #C29A7A; /* Darker brown on hover */
            transform: translateY(-2px);
        }

        input[type="submit"]:active {
            background-color: #B08C62; /* Even darker brown on active */
            transform: translateY(1px);
        }

        .form-group {
    margin-bottom: 30px; /* Adds more space between form fields */
}

.form-group label {
    font-weight: bold;
    font-size: 1.1rem; /* Increases the label size */
    color: #333;
    margin-bottom: 10px; /* Adds space below the label */
}

.input-container {
    position: relative;
    margin-bottom: 20px; /* Adds space between input fields */
    padding: 10px; /* Adds padding to the outer container */
    border-radius: 10px; /* Rounds the corners of the container */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Adds a shadow around the input */
}

input[type="password"] {
    width: 100%; /* Ensures it takes up the full width of its container */
    padding: 20px; /* Increases the padding for a larger input field */
    font-size: 1.2rem; /* Increases the font size */
    border-radius: 10px; 
    border: 1px solid #ddd;
    box-sizing: border-box;
    min-height: 50px; /* Makes sure the input field has a minimum height */
}

.bi-eye, .bi-eye-slash {
    position: absolute;
    right: 10px;
    top: 35px; /* Adjust vertical alignment */
    cursor: pointer;
}
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="#">Password Reset Form</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<main class="login-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Reset Your Password</div>
                    <div class="card-body">
                        <form action="#" method="POST" name="login">

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>
                                <div class="col-md-6">
                                    <input type="password" id="password" class="form-control" name="password" required autofocus>
                                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                                </div>
                            </div>

                            <div id="password-strength-feedback">
                                <small id="length" class="form-text text-muted">✔ Minimum 8 characters</small>
                                <small id="letter" class="form-text text-muted">✔ At least one letter (a–z, A–Z)</small>
                                <small id="number" class="form-text text-muted">✔ At least one number (0–9)</small>
                                <small id="special" class="form-text text-muted">✔ At least one special symbol (!@#$%^&*)</small>
                            </div>

                            <div class="col-md-6 offset-md-4">
                                <!-- Give the submit button an id -->
                                <input type="submit" value="Reset" name="reset" id="submit-button" disabled>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    const passwordInput = document.getElementById('password');
    const length = document.getElementById('length');
    const letter = document.getElementById('letter');
    const number = document.getElementById('number');
    const special = document.getElementById('special');
    const submitButton = document.getElementById('submit-button');

    passwordInput.addEventListener('input', function() {
        const value = passwordInput.value;

        let isValid = true;

        // Check minimum length
        if (value.length >= 8) {
            length.style.color = 'green';
            length.innerHTML = '✔ Minimum 8 characters';
        } else {
            length.style.color = 'red';
            length.innerHTML = '✖ Minimum 8 characters';
            isValid = false;
        }

        // Check for at least one letter
        if (/[A-Za-z]/.test(value)) {
            letter.style.color = 'green';
            letter.innerHTML = '✔ At least one letter (a–z, A–Z)';
        } else {
            letter.style.color = 'red';
            letter.innerHTML = '✖ At least one letter (a–z, A–Z)';
            isValid = false;
        }

        // Check for at least one number
        if (/\d/.test(value)) {
            number.style.color = 'green';
            number.innerHTML = '✔ At least one number (0–9)';
        } else {
            number.style.color = 'red';
            number.innerHTML = '✖ At least one number (0–9)';
            isValid = false;
        }

        // Check for at least one special character
        if (/[@$!%*#?&]/.test(value)) {
            special.style.color = 'green';
            special.innerHTML = '✔ At least one special symbol (!@#$%^&*)';
        } else {
            special.style.color = 'red';
            special.innerHTML = '✖ At least one special symbol (!@#$%^&*)';
            isValid = false;
        }

        // Enable or disable submit button
        submitButton.disabled = !isValid;
    });

    // Toggle password visibility
    const toggle = document.getElementById('togglePassword');
    toggle.addEventListener('click', function(){
        if(passwordInput.type === "password"){
            passwordInput.type = 'text';
        }else{
            passwordInput.type = 'password';
        }
        this.classList.toggle('bi-eye');
    });
</script>

</body>
</html>

<?php
    if(isset($_POST["reset"])){
        $conn = mysqli_connect("localhost", "root", "", "users_db");
        $psw = $_POST["password"];

        $token = $_SESSION['token'];
        $Email = $_SESSION['email'];

        $hash = password_hash( $psw , PASSWORD_DEFAULT );

        $sql = mysqli_query($conn, "SELECT * FROM users WHERE email='$Email'");
        $query = mysqli_num_rows($sql);
        $fetch = mysqli_fetch_assoc($sql);

        if($Email){
            $new_pass = $hash;
            mysqli_query($conn, "UPDATE users SET password='$new_pass' WHERE email='$Email'");
            ?>
            <script>
                window.location.replace("login.php");
                alert("<?php echo "Your password has been successfully reset"; ?>");
            </script>
            <?php
        } else {
            ?>
            <script>
                alert("<?php echo "Please try again"; ?>");
            </script>
            <?php
        }
    }
?>
