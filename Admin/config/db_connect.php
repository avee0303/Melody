<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "burger4.0"; // change this

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
