# 🛒 ShopEase — E-Commerce Website Project

A complete, working e-commerce web application built with **HTML, CSS, JavaScript, PHP, and MySQL**, developed as a 6-member team project.

---

## 👥 Team Roles & Responsibilities

| Name | Role | Modules |
|---|---|---|
| **Trisha** | Project Manager, GitHub Manager & UI/UX Designer | Planning, GitHub, UI/UX, Documentation, Team page |
| **Santhosh** | Frontend Developer | Home page, Navbar, Footer, Responsive design |
| **Yudhith** | Frontend Developer | Product listing, Product details, Search, Category filter |
| **Abhinav** | Backend Developer | Registration, Login, Authentication, Session management, Password encryption |
| **Priyan** | Backend & Database Developer | MySQL design, Shopping cart, Order management, Order status |
| **Sudaran** | Testing & Integration Engineer | Payment demo, Testing, Bug fixing, Integration & deployment |

(See the in-app **Team Roles** page at `team.php` for the same info rendered on the site.)

---

## 🧰 Technology Stack

- HTML5, CSS3 (fully responsive, mobile-friendly)
- Vanilla JavaScript
- PHP 7.4+ / 8.x (procedural, mysqli)
- MySQL / MariaDB
- Runs on **XAMPP** (Apache + MySQL)

---

## 📁 Project Structure

```
ecommerce-website/
├── admin/
│   ├── admin_header.php / admin_footer.php   (admin layout)
│   ├── login.php          (admin login)
│   ├── logout.php
│   ├── dashboard.php      (admin dashboard with stats)
│   ├── products.php       (add/delete products)
│   ├── orders.php         (view + update order status)
│   └── users.php          (view registered users)
├── assets/
│   ├── css/style.css      (all styling, responsive)
│   ├── js/script.js       (nav toggle, quantity, alerts)
│   └── images/            (placeholder folder for real images)
├── config/
│   └── db.php             (MySQL connection settings)
├── includes/
│   ├── header.php         (HTML head + navbar include)
│   ├── footer.php
│   ├── navbar.php
│   ├── session.php        (auth/session helper functions)
│   └── helpers.php        (category emoji icons, date format)
├── database/
│   └── ecommerce.sql      (full schema + sample data)
├── index.php               (Home page)
├── products.php            (Product listing + search + filter)
├── product-details.php     (Single product page)
├── register.php             (User registration)
├── login.php                (User login)
├── logout.php
├── cart.php                 (Shopping cart)
├── checkout.php              (Shipping address / checkout)
├── payment.php               (Demo payment + order creation)
├── my-orders.php              (Customer order history)
├── team.php                    (Team roles page)
└── README.md
```

---

## 🚀 Setup Instructions (XAMPP / localhost)

### 1. Install XAMPP
Download and install XAMPP from https://www.apachefriends.org (if not already installed).

### 2. Copy the project folder
Extract this ZIP and copy the whole `ecommerce-website` folder into your XAMPP `htdocs` directory:

- **Windows:** `C:\xampp\htdocs\ecommerce-website`
- **macOS:** `/Applications/XAMPP/htdocs/ecommerce-website`
- **Linux:** `/opt/lampp/htdocs/ecommerce-website`

### 3. Start Apache and MySQL
Open the **XAMPP Control Panel** and click **Start** next to:
- Apache
- MySQL

### 4. Import the database (via phpMyAdmin)
1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click **Import** in the top menu.
3. Click **Choose File** and select `database/ecommerce.sql` from this project.
4. Click **Go** at the bottom of the page.
5. This will automatically create a database named **`ecommerce_db`** with all tables and sample data (12 sample products, categories, a demo user, and an admin account).

> Alternatively, using the MySQL command line:
> ```
> mysql -u root -p < database/ecommerce.sql
> ```

### 5. Configure database connection (only if needed)
Open `config/db.php`. The defaults already match a standard XAMPP install:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ecommerce_db');
```
Only change these if your MySQL root user has a password set.

### 6. Run the project
Open your browser and visit:

```
http://localhost/ecommerce-website/
```

That's it — the store is live on your machine! 🎉

---

## 🔑 Default Login Credentials

### Customer (Demo) Account
- **URL:** `http://localhost/ecommerce-website/login.php`
- **Email:** `user@example.com`
- **Password:** `user123`

*(You can also register a brand-new account any time via the Register page — passwords are securely hashed with PHP's `password_hash()`/bcrypt.)*

### Admin Account
- **URL:** `http://localhost/ecommerce-website/admin/login.php`
- **Username:** `admin`
- **Password:** `admin123`

From the admin dashboard you can:
- View sales/user/order statistics
- Add or delete products
- View and update order status (Pending → Processing → Shipped → Delivered / Cancelled)
- View registered customers

---

## 🧩 Website Modules Included

- ✅ Home Page
- ✅ Navbar & Footer (responsive)
- ✅ Product Listing Page
- ✅ Product Details Page
- ✅ Search & Category Filtering
- ✅ User Registration (with validation + hashed passwords)
- ✅ User Login (session-based authentication)
- ✅ Session Management (login/logout guarding on protected pages)
- ✅ Shopping Cart (add / update quantity / remove)
- ✅ Checkout Page (shipping address)
- ✅ Payment Demo Page (simulated payment, creates real order records)
- ✅ My Orders Page (order history with itemized details & status)
- ✅ Admin Login
- ✅ Admin Dashboard (stats: users, products, orders, revenue)
- ✅ Order Status Update (admin side)
- ✅ Team Roles Page
- ✅ Fully Responsive Design (mobile, tablet, desktop)

---

## 🖼️ Product Images

To keep the ZIP lightweight and immediately runnable, product "images" are rendered
as clean emoji-icon placeholders (defined in `includes/helpers.php` → `categoryEmoji()`)
instead of binary image files. To use real photos:

1. Add your image files to `assets/images/` (e.g. `product1.jpg`).
2. In `products.php`, `product-details.php`, and `index.php`, replace:
   ```php
   <div class="product-image"><?php echo categoryEmoji($p['category_id']); ?></div>
   ```
   with:
   ```php
   <div class="product-image"><img src="assets/images/<?php echo htmlspecialchars($p['image']); ?>"></div>
   ```
   (The `image` column already exists in the `products` table for this purpose.)

---

## 🔒 Security Notes

- Passwords are hashed with bcrypt via PHP's `password_hash()` / verified with `password_verify()`.
- All SQL queries use prepared statements (`mysqli_prepare`) for inputs coming from forms, and `mysqli_real_escape_string` for query-string filters.
- Sessions are used to protect cart, checkout, payment, my-orders, and all admin pages — visiting them while logged out redirects to the login page.
- This project is for **educational/demo purposes**. The payment page is a simulated demo only — do not connect it to real payment credentials.

---

## 🐞 Troubleshooting

| Problem | Solution |
|---|---|
| "Database connection failed" | Make sure MySQL is running in XAMPP and you imported `database/ecommerce.sql`. |
| Blank white page | Enable PHP error display in `php.ini` (`display_errors = On`) or check `xampp/apache/logs/error.log`. |
| Styles not loading | Make sure you're accessing the project via `http://localhost/ecommerce-website/` and not by opening the HTML files directly from disk. |
| "Table doesn't exist" | Re-import `database/ecommerce.sql` — it will create the `ecommerce_db` database and all tables. |

---

## 📌 Credits

Built by the 6-member team as outlined in the **Team Roles & Responsibilities** chart (see `team.php` in the running app).
