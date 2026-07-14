<?php
require_once __DIR__ . '/../includes/auth.php';

$redirect = $_GET['redirect'] ?? 'account/dashboard.php';
// only allow relative redirects within the site
if (preg_match('#^https?://#i', $redirect) || strpos($redirect, '..') !== false) {
    $redirect = 'account/dashboard.php';
}

if (grd_current_customer()) {
    header('Location: ../' . $redirect);
    exit;
}

grd_auth_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $error = 'Your session expired — please try again.';
    } else {
        $result = grd_login_customer($_POST['email'] ?? '', $_POST['password'] ?? '');
        if ($result['success']) {
            header('Location: ../' . $redirect);
            exit;
        }
        $error = $result['error'];
    }
}

$grd_asset_prefix = '../';
$page_title = 'Log In — G.R.D Hing';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($page_title); ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,400;1,9..144,500&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/style.css">
</head>
<body class="auth-body">
  <div class="auth-card">
    <a class="auth-brand" href="../index.php">
      <img src="../images/logo.png" alt="G.R.D Hing emblem">
      <div class="brand-text">
        <div class="brand-name">G.R.D Hing</div>
        <div class="brand-sub">Panditji Hing Kayam</div>
      </div>
    </a>

    <h1>Welcome Back</h1>
    <p class="auth-sub">Log in to track your orders and check out faster.</p>

    <?php if ($error): ?>
      <div class="form-errors" role="alert"><ul><li><?php echo htmlspecialchars($error); ?></li></ul></div>
    <?php endif; ?>

    <form method="post" class="checkout-form">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
      <div class="form-row">
        <label>Email
          <input type="email" name="email" required autofocus value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </label>
      </div>
      <div class="form-row">
        <label>Password
          <input type="password" name="password" required>
        </label>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:6px;">Log In</button>
    </form>

    <p class="auth-switch">Don't have an account? <a href="register.php<?php echo $redirect !== 'account/dashboard.php' ? '?redirect=' . urlencode($redirect) : ''; ?>">Create one</a></p>
    <a href="../index.php" class="auth-back-link">← Back to the site</a>
  </div>
</body>
</html>
