<?php
/**
 * Admin Layout Header (sidebar + top)
 * Include this after requireAdmin() check, with $pageTitle and $activePage set.
 */
if (!isset($activePage)) { $activePage = ''; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Admin - ShopEase'; ?></title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header class="site-header">
  <div class="navbar container">
    <a href="dashboard.php" class="logo">🛠️ ShopEase <span>Admin</span></a>
    <div>
      <span style="margin-right: 14px;">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
      <a href="logout.php" class="btn-nav">Logout</a>
    </div>
  </div>
</header>

<div class="admin-layout">
  <div class="admin-sidebar">
    <a href="dashboard.php" <?php echo $activePage === 'dashboard' ? 'style="font-weight:800;"' : ''; ?>>📊 Dashboard</a>
    <a href="products.php" <?php echo $activePage === 'products' ? 'style="font-weight:800;"' : ''; ?>>📦 Products</a>
    <a href="orders.php" <?php echo $activePage === 'orders' ? 'style="font-weight:800;"' : ''; ?>>🧾 Orders</a>
    <a href="users.php" <?php echo $activePage === 'users' ? 'style="font-weight:800;"' : ''; ?>>👤 Users</a>
    <a href="../index.php">🌐 View Site</a>
  </div>
  <div class="admin-content">
