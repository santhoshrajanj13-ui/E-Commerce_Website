<?php
/**
 * Product Listing Page
 * Module: Product Listing / Search / Category Filter (Yudhith - Frontend Developer)
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/helpers.php';

$pageTitle = "ShopEase - All Products";
include __DIR__ . '/includes/header.php';

$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");

$search = isset($_GET['search']) ? sanitize($conn, $_GET['search']) : '';
$categoryId = isset($_GET['category']) ? (int) $_GET['category'] : 0;

$sql = "SELECT * FROM products WHERE 1=1";
if ($search !== '') {
    $sql .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";
}
if ($categoryId > 0) {
    $sql .= " AND category_id = $categoryId";
}
$sql .= " ORDER BY id DESC";

$products = mysqli_query($conn, $sql);
$productCount = mysqli_num_rows($products);
?>

<div class="categories-bar">
  <a href="products.php" class="category-chip <?php echo $categoryId == 0 ? 'active' : ''; ?>">All</a>
  <?php
  mysqli_data_seek($categories, 0);
  while ($cat = mysqli_fetch_assoc($categories)):
  ?>
    <a href="products.php?category=<?php echo $cat['id']; ?>" class="category-chip <?php echo $categoryId == $cat['id'] ? 'active' : ''; ?>">
      <?php echo htmlspecialchars($cat['name']); ?>
    </a>
  <?php endwhile; ?>
</div>

<h2 class="section-title">
  <?php echo $search !== '' ? 'Search results for "' . htmlspecialchars($search) . '"' : 'All Products'; ?>
  (<?php echo $productCount; ?>)
</h2>

<?php if ($productCount === 0): ?>
  <div class="container">
    <p style="text-align:center; padding: 40px 0;">No products found. Try a different search or category.</p>
  </div>
<?php else: ?>
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
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
