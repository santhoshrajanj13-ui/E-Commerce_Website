<?php
/**
 * Payment Demo Page
 * Module: Payment Interface Demo (Sudaran - Testing & Integration Engineer)
 * NOTE: This is a DEMO payment page only. No real payment gateway
 * is integrated. It simulates a successful transaction and creates
 * the order record in the database.
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/session.php';

requireLogin();
$userId = currentUserId();

if (!isset($_SESSION['checkout_address']) || !isset($_SESSION['checkout_total'])) {
    header("Location: cart.php");
    exit();
}

$total = $_SESSION['checkout_total'];
$address = $_SESSION['checkout_address'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = isset($_POST['payment_method']) ? sanitize($conn, $_POST['payment_method']) : 'Demo Payment';

    // Fetch cart items
    $sql = "SELECT c.product_id, c.quantity, p.price, p.stock
            FROM cart c JOIN products p ON c.product_id = p.id
            WHERE c.user_id = $userId";
    $items = mysqli_query($conn, $sql);
    $cartItems = [];
    while ($row = mysqli_fetch_assoc($items)) {
        $cartItems[] = $row;
    }

    if (count($cartItems) === 0) {
        $error = "Your cart is empty.";
    } else {
        mysqli_begin_transaction($conn);
        try {
            $stmt = mysqli_prepare($conn, "INSERT INTO orders (user_id, total_amount, payment_method, status, shipping_address) VALUES (?, ?, ?, 'Pending', ?)");
            mysqli_stmt_bind_param($stmt, "idss", $userId, $total, $method, $address);
            mysqli_stmt_execute($stmt);
            $orderId = mysqli_insert_id($conn);

            foreach ($cartItems as $item) {
                $stmt2 = mysqli_prepare($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt2, "iiid", $orderId, $item['product_id'], $item['quantity'], $item['price']);
                mysqli_stmt_execute($stmt2);

                // Reduce stock
                mysqli_query($conn, "UPDATE products SET stock = GREATEST(0, stock - {$item['quantity']}) WHERE id = {$item['product_id']}");
            }

            // Clear cart
            mysqli_query($conn, "DELETE FROM cart WHERE user_id = $userId");

            mysqli_commit($conn);

            unset($_SESSION['checkout_address']);
            unset($_SESSION['checkout_total']);

            header("Location: my-orders.php?success=1");
            exit();
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = "Payment failed. Please try again.";
        }
    }
}

$pageTitle = "Payment - ShopEase";
include __DIR__ . '/includes/header.php';
?>

<div class="container">
  <div class="form-wrapper" style="max-width: 500px;">
    <h2>Payment (Demo)</h2>
    <p style="text-align:center; color: var(--muted); font-size: 13px;">
      This is a demo payment gateway for project/testing purposes. No real transaction occurs.
    </p>

    <?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

    <div class="cart-summary" style="margin-bottom: 20px;">
      <div class="row total"><span>Amount to Pay</span><span>₹<?php echo number_format($total, 2); ?></span></div>
    </div>

    <form method="POST" action="payment.php">
      <label style="font-weight:600; margin-bottom:10px; display:block;">Select Payment Method</label>
      <div class="payment-methods">
        <label class="payment-option selected">
          <input type="radio" name="payment_method" value="Credit/Debit Card" checked style="display:none;">
          💳<br>Card
        </label>
        <label class="payment-option">
          <input type="radio" name="payment_method" value="UPI" style="display:none;">
          📱<br>UPI
        </label>
        <label class="payment-option">
          <input type="radio" name="payment_method" value="Cash on Delivery" style="display:none;">
          💵<br>COD
        </label>
      </div>

      <div class="form-group">
        <label>Card / UPI ID (Demo - not validated)</label>
        <input type="text" placeholder="XXXX-XXXX-XXXX-XXXX or name@upi">
      </div>

      <button type="submit" class="btn btn-block btn-success">Pay ₹<?php echo number_format($total, 2); ?> Now</button>
    </form>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
