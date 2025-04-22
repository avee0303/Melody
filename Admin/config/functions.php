<?php
session_start();
require_once "db_connect.php";

// Function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Function to check admin login
function checkAdminLogin() {
    if (!isset($_SESSION['admin_logged_in'])) {
        redirect("login.php");
    }
}

// Function to sanitize input
function sanitize($conn, $data) {
    return mysqli_real_escape_string($conn, trim($data));
}

// Function to fetch all rows from a table
function fetchAll($conn, $table) {
    $query = "SELECT * FROM $table";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Function to fetch single row by ID
function fetchById($conn, $table, $id) {
    $query = "SELECT * FROM $table WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

// Function to display success message
function successMessage($message) {
    return "<div class='alert alert-success'>$message</div>";
}

// Function to display error message
function errorMessage($message) {
    return "<div class='alert alert-danger'>$message</div>";
}
?>