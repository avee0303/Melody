CREATE DATABASE burger_king_admin;
USE burger_king_admin;

CREATE TABLE superadmin (
    email VARCHAR(100) PRIMARY KEY,
    id INT AUTO_INCREMENT UNIQUE NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin (
    email VARCHAR(100) PRIMARY KEY,
    id INT AUTO_INCREMENT UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15),
    role VARCHAR(50),
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE customers (
    email VARCHAR(100) PRIMARY KEY,
    id INT AUTO_INCREMENT UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('Pending', 'Processing', 'Completed', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE deliveries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    delivery_staff VARCHAR(255) NOT NULL,
    delivery_status ENUM('Pending', 'Out for Delivery', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    estimated_time DATETIME DEFAULT NULL,
    delivered_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);