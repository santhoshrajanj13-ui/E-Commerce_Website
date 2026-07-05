<?php
/**
 * Admin - Registered Users
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

requireAdmin();

$pageTitle = "Manage Users - ShopEase Admin";
$activePage = "users";
include __DIR__ . '/admin_header.php';

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
?>

<h2>Registered Users</h2>

<table class="admin-table">
  <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Registered On</th></tr></thead>
  <tbody>
    <?php while ($u = mysqli_fetch_assoc($users)): ?>
      <tr>
        <td><?php echo $u['id']; ?></td>
        <td><?php echo htmlspecialchars($u['name']); ?></td>
        <td><?php echo htmlspecialchars($u['email']); ?></td>
        <td><?php echo htmlspecialchars($u['phone']); ?></td>
        <td><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include __DIR__ . '/admin_footer.php'; ?>
