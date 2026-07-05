<?php
/**
 * Admin - Product Management
 * Module: Product Listing data management (supports Yudhith's frontend module)
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/helpers.php';

requireAdmin();

$error = '';
$success = '';

// Add new product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = sanitize($conn, $_POST['name']);
    $description = sanitize($conn, $_POST['description']);
    $price = (float) $_POST['price'];
    $categoryId = (int) $_POST['category_id'];
    $stock = (int) $_POST['stock'];

    if ($name === '' || $price <= 0) {
        $error = "Please provide a valid name and price.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO products (name, description, price, category_id, stock) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssdii", $name, $description, $price, $categoryId, $stock);
        mysqli_stmt_execute($stmt);
        $success = "Product added successfully.";
    }
}

// Delete product
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
    header("Location: products.php?deleted=1");
    exit();
}

$pageTitle = "Manage Products - ShopEase Admin";
$activePage = "products";
include __DIR__ . '/admin_header.php';

$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
$products = mysqli_query($conn, "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
?>

<h2>Manage Products</h2>

<?php if (isset($_GET['deleted'])): ?><div class="alert alert-success">Product deleted.</div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

<h3>Add New Product</h3>
<form method="POST" action="products.php" style="background:#fff; padding:20px; border-radius:10px; border:1px solid var(--border); margin-bottom:30px;">
  <input type="hidden" name="action" value="add">
  <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
    <div class="form-group">
      <label>Product Name</label>
      <input type="text" name="name" required>
    </div>
    <div class="form-group">
      <label>Category</label>
      <select name="category_id">
        <?php mysqli_data_seek($categories, 0); while ($cat = mysqli_fetch_assoc($categories)): ?>
          <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Price (₹)</label>
      <input type="number" step="0.01" name="price" required>
    </div>
    <div class="form-group">
      <label>Stock</label>
      <input type="number" name="stock" value="50" required>
    </div>
  </div>
  <div class="form-group">
    <label>Description</label>
    <textarea name="description" rows="2"></textarea>
  </div>
  <button type="submit" class="btn">Add Product</button>
</form>

<h3>All Products</h3>
<table class="admin-table">
  <thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Action</th></tr></thead>
  <tbody>
    <?php while ($p = mysqli_fetch_assoc($products)): ?>
      <tr>
        <td><?php echo $p['id']; ?></td>
        <td><?php echo categoryEmoji($p['category_id']); ?> <?php echo htmlspecialchars($p['name']); ?></td>
        <td><?php echo htmlspecialchars($p['category_name']); ?></td>
        <td>₹<?php echo number_format($p['price'], 2); ?></td>
        <td><?php echo $p['stock']; ?></td>
        <td><a href="products.php?delete=<?php echo $p['id']; ?>" class="btn btn-danger btn-sm confirm-action">Delete</a></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include __DIR__ . '/admin_footer.php'; ?>
