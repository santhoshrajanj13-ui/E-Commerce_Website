<?php
/**
 * Admin - Order Management & Status Update
 * Module: Update Order Status (Priyan - Backend & Database Developer)
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = (int) $_POST['order_id'];
    $allowed = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
    $status = in_array($_POST['status'], $allowed) ? $_POST['status'] : 'Pending';
    mysqli_query($conn, "UPDATE orders SET status = '$status' WHERE id = $orderId");
    header("Location: orders.php?updated=1");
    exit();
}

$pageTitle = "Manage Orders - ShopEase Admin";
$activePage = "orders";
include __DIR__ . '/admin_header.php';

$orders = mysqli_query($conn, "SELECT o.*, u.name, u.email FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
?>

<h2>Manage Orders</h2>

<?php if (isset($_GET['updated'])): ?>
  <div class="alert alert-success">Order status updated successfully.</div>
<?php endif; ?>

<table class="admin-table">
  <thead>
    <tr><th>Order ID</th><th>Customer</th><th>Total</th><th>Payment</th><th>Date</th><th>Status</th><th>Update</th></tr>
  </thead>
  <tbody>
    <?php while ($order = mysqli_fetch_assoc($orders)): ?>
      <tr>
        <td>#<?php echo $order['id']; ?></td>
        <td><?php echo htmlspecialchars($order['name']); ?><br><small><?php echo htmlspecialchars($order['email']); ?></small></td>
        <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
        <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
        <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
        <td><span class="status status-<?php echo $order['status']; ?>"><?php echo $order['status']; ?></span></td>
        <td>
          <form method="POST" action="orders.php" style="display:flex; gap:6px;">
            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
            <select name="status">
              <?php foreach (['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'] as $s): ?>
                <option value="<?php echo $s; ?>" <?php echo $s === $order['status'] ? 'selected' : ''; ?>><?php echo $s; ?></option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-sm">Update</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include __DIR__ . '/admin_footer.php'; ?>
