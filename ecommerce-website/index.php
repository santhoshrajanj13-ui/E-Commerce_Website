<?php
/**
 * Home Page
 * Module: Home Page (Santhosh - Frontend Developer)
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/helpers.php';

$pageTitle = "ShopEase - Home";
include __DIR__ . '/includes/header.php';

// Fetch categories
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");

// Fetch featured products (latest 8)
$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC LIMIT 8");
?>

<section class="hero">
  <h1>Welcome to ShopEase</h1>
  <p>Discover amazing products at unbeatable prices — Electronics, Fashion, Home & more!</p>
  <a href="products.php" class="btn">Shop Now</a>
</section>

<div class="categories-bar">
  <a href="products.php" class="category-chip active">All</a>
  <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
    <a href="products.php?category=<?php echo $cat['id']; ?>" class="category-chip"><?php echo htmlspecialchars($cat['name']); ?></a>
  <?php endwhile; ?>
</div>

<h2 class="section-title">Featured Products</h2>
<div class="product-grid">
  <?php while ($p = mysqli_fetch_assoc($products)): ?>
    <div class="product-card">
      <a href="product-details.php?id=<?php echo $p['id']; ?>">
        <div class="product-image"><?php echo categoryEmoji($p['category_id']); ?></div>
      </a>
      <div class="product-info">
        <h3><?php echo htmlspecialchars($p['name']); ?></h3>
        <div class="price">₹<?php echo number_format($p['price'], 2); ?></div>
        <a href="product-details.php?id=<?php echo $p['id']; ?>" class="btn btn-outline btn-block">View Details</a>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
