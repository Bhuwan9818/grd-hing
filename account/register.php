<?php
require_once __DIR__ . '/../includes/auth.php';

$redirect = $_GET['redirect'] ?? 'account/dashboard.php';
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
$old = ['name' => '', 'email' => '', 'phone' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['name'] = trim($_POST['name'] ?? '');
    $old['email'] = trim($_POST['email'] ?? '');
    $old['phone'] = trim($_POST['phone'] ?? '');

    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $error = 'Your session expired — please try again.';
    } elseif (($_POST['password'] ?? '') !== ($_POST['password_confirm'] ?? '')) {
        $error = 'Passwords do not match.';
    } else {
        $result = grd_register_customer($old['name'], $old['email'], $_POST['password'] ?? '', $old['phone']);
        if ($result['success']) {
            header('Location: ../' . $redirect);
            exit;
        }
        $error = $result['error'];
    }
}

$page_title = 'Create Account — G.R.D Hing';
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

    <h1>Create Your Account</h1>
    <p class="auth-sub">Track orders and check out faster next time.</p>

    <?php if ($error): ?>
      <div class="form-errors" role="alert"><ul><li><?php echo htmlspecialchars($error); ?></li></ul></div>
    <?php endif; ?>

    <form method="post" class="checkout-form">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
      <div class="form-row">
        <label>Full Name
          <input type="text" name="name" required autofocus value="<?php echo htmlspecialchars($old['name']); ?>">
        </label>
      </div>
      <div class="form-row">
        <label>Email
          <input type="email" name="email" required value="<?php echo htmlspecialchars($old['email']); ?>">
        </label>
      </div>
      <div class="form-row">
        <label>Phone <span class="optional">(optional)</span>
          <input type="tel" name="phone" pattern="[0-9]{10}" maxlength="10" placeholder="10-digit mobile number" value="<?php echo htmlspecialchars($old['phone']); ?>">
        </label>
      </div>
      <div class="form-row two-col">
        <label>Password
          <input type="password" name="password" required minlength="6">
        </label>
        <label>Confirm Password
          <input type="password" name="password_confirm" required minlength="6">
        </label>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:6px;">Create Account</button>
    </form>

    <p class="auth-switch">Already have an account? <a href="login.php<?php echo $redirect !== 'account/dashboard.php' ? '?redirect=' . urlencode($redirect) : ''; ?>">Log in</a></p>
    <a href="../index.php" class="auth-back-link">← Back to the site</a>
  </div>
</body>
</html>
