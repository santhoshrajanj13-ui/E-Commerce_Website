<?php
/**
 * Logout
 * Module: Session Management (Abhinav - Backend Developer)
 */
require_once __DIR__ . '/includes/session.php';

session_unset();
session_destroy();

header("Location: login.php");
exit();
?>
