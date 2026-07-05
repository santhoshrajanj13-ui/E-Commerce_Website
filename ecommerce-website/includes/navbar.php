<?php
/**
 * Navbar
 * Module: Navbar (Santhosh - Frontend Developer)
 */
if (!isset($_SESSION)) { require_once __DIR__ . '/session.php'; }

$cartCount = 0;
if (isLoggedIn() && isset($conn)) {
    $uid = currentUserId();
    $res = mysqli_query($conn, "SELECT SUM(quantity) as total FROM cart WHERE user_id = $uid");
    if ($res) {
        $row = mysqli_fetch_assoc($res);
        $cartCount = $row['total'] ? $row['total'] : 0;
    }
}
?>
<header class="site-header">
  <div class="navbar container">
    <a href="index.php" class="logo">🛒 Shop<span>Ease</span></a>

    <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">☰</button>

    <nav class="nav-links" id="navLinks">
      <a href="index.php">Home</a>
      <a href="products.php">Products</a>
      <a href="team.php">Team</a>

      <form class="nav-search" action="products.php" method="GET">
        <input type="text" name="search" placeholder="Search products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">🔍</button>
      </form>

      <a href="cart.php" class="cart-link">🛒 Cart
        <?php if ($cartCount > 0): ?><span class="badge"><?php echo $cartCount; ?></span><?php endif; ?>
      </a>

      <?php if (isLoggedIn()): ?>
        <a href="my-orders.php">My Orders</a>
        <a href="logout.php" class="btn-nav">Logout (<?php echo htmlspecialchars($_SESSION['user_name']); ?>)</a>
      <?php else: ?>
        <a href="login.php" class="btn-nav">Login</a>
        <a href="register.php" class="btn-nav btn-nav-alt">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
