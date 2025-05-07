CREATE TABLE superadmin (
    id INT AUTO_INCREMENT NOT NULL,
    email VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (email),
    UNIQUE (id)
);

CREATE TABLE admin (
    id INT AUTO_INCREMENT NOT NULL,
    email VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    PRIMARY KEY (email),
    UNIQUE (id)
);

CREATE TABLE customer (
    id INT AUTO_INCREMENT NOT NULL,
    email VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    password VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    PRIMARY KEY (email),
    UNIQUE (id)
);

CREATE TABLE password_reset (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    customer_email VARCHAR(255),
    admin_email VARCHAR(255),
    superadmin_email VARCHAR(255),
    token VARCHAR(255) NOT NULL,
    FOREIGN KEY (customer_email) REFERENCES customer(email) ON DELETE SET NULL,
    FOREIGN KEY (admin_email) REFERENCES admin(email) ON DELETE SET NULL,
    FOREIGN KEY (superadmin_email) REFERENCES superadmin(email) ON DELETE SET NULL
);

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    quantity INT(15) NOT NULL,
    product_id INT(15),
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
);

CREATE TABLE checkout (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    cart_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    tip DECIMAL(10, 2) NOT NULL,
    delivery_charge DECIMAL(10, 2) NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    checkout_lat DECIMAL(9, 6) NOT NULL,
    checkout_lng DECIMAL(9, 6) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `order` (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    quantity INT(15) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    status ENUM('Pending', 'Processing', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Pending',
    customer_email VARCHAR(100),
    checkout_id INT(15),
    FOREIGN KEY (customer_email) REFERENCES customer(email) ON DELETE SET NULL,
    FOREIGN KEY (checkout_id) REFERENCES checkout(id) ON DELETE SET NULL
);

CREATE TABLE deliveries (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    staff VARCHAR(255) NOT NULL,
    delivery_status ENUM('Pending', 'Out for Delivery', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    date DATE NOT NULL,
    time TIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    order_id INT(15),
    FOREIGN KEY (order_id) REFERENCES `order`(id) ON DELETE SET NULL
);

CREATE TABLE history (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    image VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    checkout_id INT(15),
    FOREIGN KEY (checkout_id) REFERENCES checkout(id) ON DELETE SET NULL
);


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    dob DATE NULL,
    address VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);