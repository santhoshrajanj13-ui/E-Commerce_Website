<?php
/**
 * Admin Dashboard
 * Module: Admin Dashboard (Integration by Sudaran / DB by Priyan)
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

requireAdmin();

$pageTitle = "Admin Dashboard - ShopEase";
$activePage = "dashboard";

$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users"))['c'];
$totalProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM products"))['c'];
$totalOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM orders"))['c'];
$totalRevenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as s FROM orders WHERE status != 'Cancelled'"))['s'];
$totalRevenue = $totalRevenue ? $totalRevenue : 0;

include __DIR__ . '/admin_header.php';
?>

<h2>Dashboard Overview</h2>

<div class="stat-cards">
  <div class="stat-card">
    <p>Total Users</p>
    <h3><?php echo $totalUsers; ?></h3>
  </div>
  <div class="stat-card">
    <p>Total Products</p>
    <h3><?php echo $totalProducts; ?></h3>
  </div>
  <div class="stat-card">
    <p>Total Orders</p>
    <h3><?php echo $totalOrders; ?></h3>
  </div>
  <div class="stat-card">
    <p>Total Revenue</p>
    <h3>₹<?php echo number_format($totalRevenue, 2); ?></h3>
  </div>
</div>

<h3>Recent Orders</h3>
<table class="admin-table">
  <thead><tr><th>Order ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
  <tbody>
    <?php
    $recent = mysqli_query($conn, "SELECT o.*, u.name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 8");
    while ($row = mysqli_fetch_assoc($recent)):
    ?>
      <tr>
        <td>#<?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td>₹<?php echo number_format($row['total_amount'], 2); ?></td>
        <td><span class="status status-<?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
        <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include __DIR__ . '/admin_footer.php'; ?>
