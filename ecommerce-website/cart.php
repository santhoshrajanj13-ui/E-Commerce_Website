<?php
/**
 * Shopping Cart
 * Module: Shopping Cart (Priyan - Backend & Database Developer)
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/helpers.php';

requireLogin();
$userId = currentUserId();

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $productId = (int) $_POST['product_id'];
    $quantity = max(1, (int) $_POST['quantity']);

    $check = mysqli_query($conn, "SELECT id, quantity FROM cart WHERE user_id = $userId AND product_id = $productId");
    if (mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);
        $newQty = $row['quantity'] + $quantity;
        mysqli_query($conn, "UPDATE cart SET quantity = $newQty WHERE id = " . $row['id']);
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iii", $userId, $productId, $quantity);
        mysqli_stmt_execute($stmt);
    }
    header("Location: product-details.php?id=$productId&added=1");
    exit();
}

// Handle Update Quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    foreach ($_POST['qty'] as $cartId => $qty) {
        $cartId = (int) $cartId;
        $qty = max(1, (int) $qty);
        mysqli_query($conn, "UPDATE cart SET quantity = $qty WHERE id = $cartId AND user_id = $userId");
    }
    header("Location: cart.php");
    exit();
}

// Handle Remove Item
if (isset($_GET['remove'])) {
    $cartId = (int) $_GET['remove'];
    mysqli_query($conn, "DELETE FROM cart WHERE id = $cartId AND user_id = $userId");
    header("Location: cart.php");
    exit();
}

$pageTitle = "Your Cart - ShopEase";
include __DIR__ . '/includes/header.php';

$sql = "SELECT c.id as cart_id, c.quantity, p.id as product_id, p.name, p.price, p.category_id, p.stock
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
$shipping = $subtotal > 0 ? 50 : 0;
$total = $subtotal + $shipping;
?>

<div class="container">
  <h2 class="section-title">Your Shopping Cart</h2>

  <?php if (count($cartItems) === 0): ?>
    <p style="text-align:center; padding: 40px 0;">Your cart is empty. <a href="products.php">Browse products</a></p>
  <?php else: ?>
    <form method="POST" action="cart.php">
      <input type="hidden" name="action" value="update">
      <table class="cart-table">
        <thead>
          <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr>
        </thead>
        <tbody>
          <?php foreach ($cartItems as $item): ?>
            <tr>
              <td><?php echo categoryEmoji($item['category_id']); ?> <?php echo htmlspecialchars($item['name']); ?></td>
              <td>₹<?php echo number_format($item['price'], 2); ?></td>
              <td><input type="number" name="qty[<?php echo $item['cart_id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>" class="qty-input"></td>
              <td>₹<?php echo number_format($item['line_total'], 2); ?></td>
              <td><a href="cart.php?remove=<?php echo $item['cart_id']; ?>" class="btn btn-danger btn-sm confirm-action">Remove</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <button type="submit" class="btn btn-outline">Update Cart</button>
    </form>

    <div class="cart-summary">
      <div class="row"><span>Subtotal</span><span>₹<?php echo number_format($subtotal, 2); ?></span></div>
      <div class="row"><span>Shipping</span><span>₹<?php echo number_format($shipping, 2); ?></span></div>
      <div class="row total"><span>Total</span><span>₹<?php echo number_format($total, 2); ?></span></div>
      <a href="checkout.php" class="btn btn-block" style="margin-top:14px;">Proceed to Checkout</a>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
