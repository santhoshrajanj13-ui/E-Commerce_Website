<?php
/**
 * Product Details Page
 * Module: Product Details (Yudhith - Frontend Developer)
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/helpers.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");

if (mysqli_num_rows($result) === 0) {
    header("Location: products.php");
    exit();
}
$product = mysqli_fetch_assoc($result);

$catRes = mysqli_query($conn, "SELECT name FROM categories WHERE id = " . (int)$product['category_id']);
$catName = $catRes && mysqli_num_rows($catRes) ? mysqli_fetch_assoc($catRes)['name'] : 'General';

$pageTitle = $product['name'] . " - ShopEase";
include __DIR__ . '/includes/header.php';

// Related products (same category)
$related = mysqli_query($conn, "SELECT * FROM products WHERE category_id = " . (int)$product['category_id'] . " AND id != $id LIMIT 4");
?>

<div class="container">
  <?php if (isset($_GET['added'])): ?>
    <div class="alert alert-success">Product added to cart!</div>
  <?php endif; ?>

  <div class="product-details">
    <div class="product-image"><?php echo categoryEmoji($product['category_id']); ?></div>
    <div>
      <div class="product-info category-tag"><?php echo htmlspecialchars($catName); ?></div>
      <h1><?php echo htmlspecialchars($product['name']); ?></h1>
      <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
      <div class="price">₹<?php echo number_format($product['price'], 2); ?></div>
      <p>Availability:
        <?php if ($product['stock'] > 0): ?>
          <strong style="color: var(--success);">In Stock (<?php echo $product['stock']; ?> left)</strong>
        <?php else: ?>
          <strong style="color: var(--danger);">Out of Stock</strong>
        <?php endif; ?>
      </p>

      <?php if ($product['stock'] > 0): ?>
        <form action="cart.php" method="POST" style="margin-top:20px;">
          <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
          <input type="hidden" name="action" value="add">
          <div style="display:flex; align-items:center; margin-bottom: 16px;">
            <button type="button" class="btn btn-outline btn-sm" onclick="changeQty(-1)">-</button>
            <input type="number" id="qtyInput" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="qty-input" style="margin: 0 10px;">
            <button type="button" class="btn btn-outline btn-sm" onclick="changeQty(1)">+</button>
          </div>
          <button type="submit" class="btn">🛒 Add to Cart</button>
        </form>
      <?php endif; ?>
    </div>
  </div>

  <?php if (mysqli_num_rows($related) > 0): ?>
    <h2 class="section-title">Related Products</h2>
    <div class="product-grid">
      <?php while ($r = mysqli_fetch_assoc($related)): ?>
        <div class="product-card">
          <a href="product-details.php?id=<?php echo $r['id']; ?>">
            <div class="product-image"><?php echo categoryEmoji($r['category_id']); ?></div>
          </a>
          <div class="product-info">
            <h3><?php echo htmlspecialchars($r['name']); ?></h3>
            <div class="price">₹<?php echo number_format($r['price'], 2); ?></div>
            <a href="product-details.php?id=<?php echo $r['id']; ?>" class="btn btn-outline btn-block">View Details</a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
