<?php
/**
 * Database Connection
 * Module: Database Design (Priyan)
 *
 * Update these values only if your XAMPP MySQL setup is different
 * from the default (root user, no password).
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ecommerce_db');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error() .
        "<br><br>Make sure you have:<br>1. Started Apache & MySQL in XAMPP<br>" .
        "2. Imported database/ecommerce.sql via phpMyAdmin<br>" .
        "3. Database name is 'ecommerce_db'");
}
?>
