<?php
$servername = "localhost";
$username = "root";  // Change if using another username
$password = "";  // Change if using a password
$dbname = "burger_king_admin";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>