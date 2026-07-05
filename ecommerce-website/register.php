<?php
/**
 * User Registration
 * Module: User Registration & Password Encryption (Abhinav - Backend Developer)
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/session.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($conn, $_POST['name']);
    $email = sanitize($conn, $_POST['email']);
    $phone = sanitize($conn, $_POST['phone']);
    $address = sanitize($conn, $_POST['address']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($name === '' || $email === '' || $password === '') {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "An account with this email already exists.";
        } else {
            // Password Encryption using PHP password_hash (bcrypt)
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $hashedPassword, $phone, $address);
            if (mysqli_stmt_execute($stmt)) {
                $success = "Registration successful! You can now log in.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}

$pageTitle = "Register - ShopEase";
include __DIR__ . '/includes/header.php';
?>

<div class="form-wrapper">
  <h2>Create an Account</h2>

  <?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
  <?php if ($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

  <form method="POST" action="register.php">
    <div class="form-group">
      <label>Full Name *</label>
      <input type="text" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
    </div>
    <div class="form-group">
      <label>Email *</label>
      <input type="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
    </div>
    <div class="form-group">
      <label>Phone</label>
      <input type="text" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
    </div>
    <div class="form-group">
      <label>Address</label>
      <textarea name="address" rows="2"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
    </div>
    <div class="form-group">
      <label>Password * (min 6 characters)</label>
      <input type="password" name="password" required>
    </div>
    <div class="form-group">
      <label>Confirm Password *</label>
      <input type="password" name="confirm_password" required>
    </div>
    <button type="submit" class="btn btn-block">Register</button>
  </form>

  <div class="form-footer">
    Already have an account? <a href="login.php">Login here</a>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
