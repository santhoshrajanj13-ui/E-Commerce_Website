<?php
/**
 * User Login
 * Module: User Login & Authentication (Abhinav - Backend Developer)
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/session.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($conn, $_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT id, name, password FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}

$pageTitle = "Login - ShopEase";
include __DIR__ . '/includes/header.php';
?>

<div class="form-wrapper">
  <h2>Login to ShopEase</h2>

  <?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

  <form method="POST" action="login.php">
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" required>
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-block">Login</button>
  </form>

  <div class="form-footer">
    Don't have an account? <a href="register.php">Register here</a><br><br>
    <small>Demo login: <b>user@example.com</b> / <b>user123</b></small><br>
    <small>Admin? <a href="admin/login.php">Admin Login</a></small>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
