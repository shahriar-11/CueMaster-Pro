<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Already logged in? go straight to dashboard
if (!empty($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'dashboard/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND status = 'active' LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role']      = $user['role'];

            header('Location: ' . BASE_URL . 'dashboard/index.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login · CueMaster Pro</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

<div class="login-wrapper">
  <div class="login-card glass-card">
    <div class="login-brand">
      <div class="cue-mark"><i class="fa-solid fa-circle-dot"></i></div>
      <h1>CueMaster Pro</h1>
      <p>Smart Snooker Club, Tournament &amp; Billing Management System</p>
    </div>

    <?php if ($error): ?>
      <div class="login-alert"><i class="fa-solid fa-circle-exclamation me-1"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
      <div class="mb-3">
        <label class="form-label-dark">Username</label>
        <input type="text" name="username" class="form-control-dark" placeholder="admin" required autofocus>
      </div>
      <div class="mb-4">
        <label class="form-label-dark">Password</label>
        <input type="password" name="password" class="form-control-dark" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn-neon w-100">
        <i class="fa-solid fa-right-to-bracket me-1"></i> Sign In
      </button>
    </form>

    <div class="login-hint">
      Default: <strong>admin</strong> / <strong>admin123</strong>
    </div>
  </div>
</div>

</body>
</html>
