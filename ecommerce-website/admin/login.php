<?php
/**
 * Admin Login
 * Module: Admin Login (Abhinav / Priyan)
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

if (isAdminLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($conn, $_POST['username']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT id, username, password FROM admins WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid admin credentials.";
        }
    } else {
        $error = "Invalid admin credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login - ShopEase</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background: var(--primary-dark);">

<div class="form-wrapper" style="margin-top: 80px;">
  <h2>🔐 Admin Login</h2>

  <?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

  <form method="POST" action="login.php">
    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username" required>
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-block">Login as Admin</button>
  </form>

  <div class="form-footer">
    <small>Default admin credentials: <b>admin</b> / <b>admin123</b></small><br>
    <a href="../index.php">&larr; Back to Website</a>
  </div>
</div>

</body>
</html>
