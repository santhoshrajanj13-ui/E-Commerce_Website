<?php
/**
 * My Orders Page
 * Module: Order Management (Priyan - Backend & Database Developer)
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/helpers.php';

requireLogin();
$userId = currentUserId();

$pageTitle = "My Orders - ShopEase";
include __DIR__ . '/includes/header.php';

$orders = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = $userId ORDER BY created_at DESC");
?>

<div class="container">
  <h2 class="section-title">My Orders</h2>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Your order has been placed successfully!</div>
  <?php endif; ?>

  <?php if (mysqli_num_rows($orders) === 0): ?>
    <p style="text-align:center; padding: 40px 0;">You haven't placed any orders yet. <a href="products.php">Start Shopping</a></p>
  <?php else: ?>
    <?php while ($order = mysqli_fetch_assoc($orders)): ?>
      <div class="cart-summary" style="max-width: 100%; margin-bottom: 20px;">
        <div class="row">
          <span><strong>Order #<?php echo $order['id']; ?></strong></span>
          <span class="status status-<?php echo $order['status']; ?>"><?php echo $order['status']; ?></span>
        </div>
        <div class="row"><span>Placed On</span><span><?php echo formatDate($order['created_at']); ?></span></div>
        <div class="row"><span>Payment Method</span><span><?php echo htmlspecialchars($order['payment_method']); ?></span></div>
        <div class="row"><span>Shipping Address</span><span><?php echo htmlspecialchars($order['shipping_address']); ?></span></div>

        <?php
        $itemsRes = mysqli_query($conn, "SELECT oi.quantity, oi.price, p.name, p.category_id
                                          FROM order_items oi JOIN products p ON oi.product_id = p.id
                                          WHERE oi.order_id = " . $order['id']);
        ?>
        <table class="cart-table" style="margin-top:14px;">
          <thead><tr><th>Product</th><th>Qty</th><th>Price</th></tr></thead>
          <tbody>
            <?php while ($it = mysqli_fetch_assoc($itemsRes)): ?>
              <tr>
                <td><?php echo categoryEmoji($it['category_id']); ?> <?php echo htmlspecialchars($it['name']); ?></td>
                <td><?php echo $it['quantity']; ?></td>
                <td>₹<?php echo number_format($it['price'], 2); ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
        <div class="row total"><span>Total</span><span>₹<?php echo number_format($order['total_amount'], 2); ?></span></div>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
