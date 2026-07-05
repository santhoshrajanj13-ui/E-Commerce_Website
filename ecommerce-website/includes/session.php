<?php
/**
 * Session Management & Auth Helpers
 * Module: Authentication & Session Management (Abhinav)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function requireAdmin() {
    if (!isAdminLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function currentUserId() {
    return isLoggedIn() ? $_SESSION['user_id'] : null;
}

function sanitize($conn, $value) {
    return mysqli_real_escape_string($conn, trim($value));
}
?>
