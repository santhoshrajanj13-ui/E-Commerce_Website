-- =========================================================
-- E-Commerce Website Project - Database
-- Import this file via phpMyAdmin.
-- This script creates the database "ecommerce_db" automatically.
-- =========================================================

CREATE DATABASE IF NOT EXISTS ecommerce_db;
USE ecommerce_db;

-- ---------------------------------------------------------
-- Table: users  (Module owner: Abhinav - Registration/Login)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    address VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------
-- Table: admins (Admin login)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Default admin credentials -> username: admin | password: admin123
INSERT INTO admins (username, password) VALUES
('admin', '$2b$12$tL11FjNAnPiPuw640jPbse4RSZH3B1G76vvl0yBGTkeMU6LrEUdB6');
-- NOTE: hash above corresponds to plain-text password "admin123" (bcrypt)

-- ---------------------------------------------------------
-- Table: categories (Yudhith - Category filter)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

INSERT INTO categories (name) VALUES
('Electronics'),
('Fashion'),
('Home & Kitchen'),
('Books'),
('Sports');

-- ---------------------------------------------------------
-- Table: products (Yudhith - Product listing/details)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT 'no-image.png',
    category_id INT,
    stock INT DEFAULT 100,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Sample Products
INSERT INTO products (name, description, price, image, category_id, stock) VALUES
('Wireless Bluetooth Headphones', 'Over-ear wireless headphones with noise cancellation and 20hr battery life.', 1999.00, 'product1.png', 1, 50),
('Smartwatch Pro', 'Fitness smartwatch with heart-rate monitor, GPS and 7-day battery.', 3499.00, 'product2.png', 1, 40),
('Men Casual Shirt', 'Cotton slim-fit casual shirt, available in multiple colors.', 799.00, 'product3.png', 2, 100),
('Women Summer Dress', 'Light, breathable floral summer dress.', 1299.00, 'product4.png', 2, 80),
('Non-Stick Frying Pan', '28cm non-stick frying pan, induction compatible.', 899.00, 'product5.png', 3, 60),
('Electric Kettle 1.5L', 'Stainless steel electric kettle with auto shut-off.', 1199.00, 'product6.png', 3, 45),
('The Silent Patient (Novel)', 'Bestselling psychological thriller novel.', 399.00, 'product7.png', 4, 70),
('Atomic Habits (Book)', 'Self-help book on building good habits.', 349.00, 'product8.png', 4, 90),
('Yoga Mat', 'Anti-slip 6mm yoga mat with carry strap.', 599.00, 'product9.png', 5, 100),
('Football Size 5', 'Standard match-quality football size 5.', 699.00, 'product10.png', 5, 60),
('4K Ultra HD Smart LED TV 43-inch', 'Smart TV with built-in streaming apps and voice remote.', 24999.00, 'product11.png', 1, 20),
('Running Shoes', 'Lightweight breathable running shoes for men & women.', 1599.00, 'product12.png', 5, 75);

-- ---------------------------------------------------------
-- Table: cart (Priyan - Shopping cart)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- ---------------------------------------------------------
-- Table: orders (Priyan - Order management)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'Demo Payment',
    status ENUM('Pending','Processing','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
    shipping_address VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ---------------------------------------------------------
-- Table: order_items
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Default demo user -> email: user@example.com | password: user123
INSERT INTO users (name, email, password, phone, address) VALUES
('Demo User', 'user@example.com', '$2b$12$uiUWfO5w2g053BlOSBk/XeGiGZotxG.Rc6KuOxLEwyDaL7rduwYIq', '9876543210', '12 Demo Street, Chennai');
