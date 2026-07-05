<?php
/**
 * Checkout Page
 * Module: Order Management (Priyan - Backend & Database Developer)
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/helpers.php';

requireLogin();
$userId = currentUserId();

$sql = "SELECT c.id as cart_id, c.quantity, p.id as product_id, p.name, p.price, p.category_id
        FROM cart c JOIN products p ON c.product_id = p.id
        WHERE c.user_id = $userId";
$items = mysqli_query($conn, $sql);
$cartItems = [];
$subtotal = 0;
while ($row = mysqli_fetch_assoc($items)) {
    $row['line_total'] = $row['price'] * $row['quantity'];
    $subtotal += $row['line_total'];
    $cartItems[] = $row;
}

if (count($cartItems) === 0) {
    header("Location: cart.php");
    exit();
}

$shipping = 50;
$total = $subtotal + $shipping;

// Get user's saved address
$userRes = mysqli_query($conn, "SELECT name, address, phone FROM users WHERE id = $userId");
$userData = mysqli_fetch_assoc($userRes);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = sanitize($conn, $_POST['address']);
    if ($address === '') {
        $error = "Shipping address is required.";
    } else {
        $_SESSION['checkout_address'] = $address;
        $_SESSION['checkout_total'] = $total;
        header("Location: payment.php");
        exit();
    }
}

$pageTitle = "Checkout - ShopEase";
include __DIR__ . '/includes/header.php';
?>

<div class="container">
  <h2 class="section-title">Checkout</h2>

  <?php if (isset($error)): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

  <div style="display:grid; grid-template-columns: 1.4fr 1fr; gap: 30px;">
    <div>
      <h3>Order Items</h3>
      <table class="cart-table">
        <thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead>
        <tbody>
          <?php foreach ($cartItems as $item): ?>
            <tr>
              <td><?php echo categoryEmoji($item['category_id']); ?> <?php echo htmlspecialchars($item['name']); ?></td>
              <td><?php echo $item['quantity']; ?></td>
              <td>₹<?php echo number_format($item['price'], 2); ?></td>
              <td>₹<?php echo number_format($item['line_total'], 2); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <h3>Shipping Details</h3>
      <form method="POST" action="checkout.php" id="checkoutForm">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" value="<?php echo htmlspecialchars($userData['name']); ?>" disabled>
        </div>
        <div class="form-group">
          <label>Phone</label>
          <input type="text" value="<?php echo htmlspecialchars($userData['phone']); ?>" disabled>
        </div>
        <div class="form-group">
          <label>Shipping Address *</label>
          <textarea name="address" rows="3" required><?php echo htmlspecialchars($userData['address']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-block">Continue to Payment</button>
      </form>
    </div>

    <div class="cart-summary">
      <div class="row"><span>Subtotal</span><span>₹<?php echo number_format($subtotal, 2); ?></span></div>
      <div class="row"><span>Shipping</span><span>₹<?php echo number_format($shipping, 2); ?></span></div>
      <div class="row total"><span>Total</span><span>₹<?php echo number_format($total, 2); ?></span></div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
