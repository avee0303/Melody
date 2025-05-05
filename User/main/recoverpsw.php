<?php session_start() ?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login Form</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
    margin: 0;
    font-family: 'Helvetica Neue', Arial, sans-serif; /* Clean modern font */
    font-size: 1rem;
    line-height: 1.6;
    color: #333;
    background-color: #D1B29D; /* Soft brown background */
    text-align: center;
}

.navbar {
    background-color:rgb(234, 168, 124); /* Green background for navbar */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.navbar-brand {
    color: white;
    font-size: 1.5rem;
    font-weight: bold;
}

.card {
    border-radius: 15px; /* Rounded corners for card */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
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

input[type="text"], input[type="submit"] {
    width: 100%;
    padding: 14px;
    border-radius: 10px; /* Rounded inputs */
    border: 1px solid #ddd;
    margin-bottom: 20px;
    font-size: 1rem;
    box-sizing: border-box;
}

input[type="text"]:focus {
    border-color: #4CAF50; /* Green border on focus */
    outline: none;
}

input[type="submit"] {
    background-color: #D1B29D; /* Soft brown color for button */
    color: white;
    font-size: 1.1rem;
    font-weight: bold;
    border: none;
    cursor: pointer;
    border-radius: 25px; /* Rounded button */
    transition: all 0.3s ease; /* Smooth transition */
    padding: 14px 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

input[type="submit"]:hover {
    background-color: #C29A7A; /* Darker brown on hover */
    transform: translateY(-2px); /* Button lifts slightly on hover */
}

input[type="submit"]:active {
    background-color: #B08C62; /* Even darker brown on active */
    transform: translateY(1px); /* Button depresses on click */
}

.container {
    max-width: 600px;
    margin: 0 auto;
}

.form-group label {
    font-weight: bold;
    color: #333;
}

footer {
    margin-top: 50px;
    font-size: 0.9rem;
    color: #888;
}

    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="#">User Password Recovery</a>
    </div>
</nav>

<main>
    <div class="container">
        <div class="card">
            <div class="card-header">Password Recovery</div>
            <div class="card-body">
                <form action="#" method="POST" name="recover_psw">
                    <div class="form-group row">
                        <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                        <div class="col-md-8">
                            <input type="text" id="email_address" class="form-control" name="email" required autofocus>
                        </div>
                    </div>

                    <input type="submit" value="Recover" name="recover">
                </form>
            </div>
        </div>
    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</body>
</html>


<?php 
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  
  require 'vendor/autoload.php';
    if(isset($_POST["recover"])){
        $conn = mysqli_connect("localhost", "root", "", "users_db");
        $email = $_POST["email"];

        $sql = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        $query = mysqli_num_rows($sql);
        $fetch = mysqli_fetch_assoc($sql);

        if(mysqli_num_rows($sql) <= 0){
            ?>
            <script>
                alert("<?php  echo "Sorry, no emails exist "?>");
            </script>
            <?php
        }else{
            // generate token by binaryhexa 
            $token = bin2hex(random_bytes(50));

            // session_start();
            $_SESSION['token'] = $token;
            $_SESSION['email'] = $email;

            $mail = new PHPMailer;

            $mail->isSMTP();
            $mail->Host='smtp.gmail.com';
            $mail->Port=587;
            $mail->SMTPAuth=true;
            $mail->SMTPSecure='tls';

            // h-hotel account
            $mail->Username='aveepang2005@gmail.com';
            $mail->Password='axbjovcemeampzas';

            // send by h-hotel email
            $mail->setFrom('aveepang2005@gmail.com', 'Password Reset');
            // get email from input
            $mail->addAddress($_POST["email"]);

            // HTML body
            $mail->isHTML(true);
            $mail->Subject="Recover your password";
            $mail->Body="<b>Dear User</b>
            <h3>We received a request to reset your password.</h3>
            <p>Kindly click the below link to reset your password</p>
            <a href='http://localhost/FYP/main/resetpassword.php?token=$token'>Reset Password</a>
            <br><br>
            <p>With regards,</p>
            <b>AVEE - TECH SYSTEM </b>";

            if(!$mail->send()){
                ?>
                    <script>
                        alert("<?php echo " Invalid Email "?>");
                    </script>
                <?php
            }else{
                ?>
                    <script>
                        window.location.replace("notification.html");
                    </script>
                <?php
            }
        }
    }
?>
