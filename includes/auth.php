<?php
require_once __DIR__ . '/db.php';

/**
 * Customer-facing auth — deliberately separate from admin auth in
 * admin/includes/auth.php. Uses its own session key ('customer_id')
 * and its own DB table ('customers'), so a customer account can
 * never gain admin access, and vice versa.
 */
function grd_auth_start() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/** Returns the logged-in customer's row (id, name, email, phone, created_at), or null. */
function grd_current_customer() {
    grd_auth_start();
    if (empty($_SESSION['customer_id'])) return null;
    static $cached = null;
    if ($cached !== null) return $cached;
    $stmt = grd_db()->prepare("SELECT id, name, email, phone, created_at FROM customers WHERE id = ?");
    $stmt->execute([$_SESSION['customer_id']]);
    $cached = $stmt->fetch() ?: null;
    return $cached;
}

/**
 * Redirects to the login page if not logged in, preserving the page
 * the visitor was trying to reach so login.php can send them back.
 *
 * @param string $login_page  Path to login.php relative to the calling page
 *                             (e.g. 'login.php' when called from account/).
 */
function grd_require_login($login_page = 'login.php') {
    if (!grd_current_customer()) {
        grd_auth_start();
        $current = basename($_SERVER['SCRIPT_NAME']);
        $qs = $_SERVER['QUERY_STRING'] ?? '';
        $redirect = 'account/' . $current . ($qs ? '?' . $qs : '');
        header('Location: ' . $login_page . '?redirect=' . urlencode($redirect));
        exit;
    }
}

/** Returns ['success'=>bool, 'error'=>string|null] */
function grd_login_customer($email, $password) {
    $email = trim(strtolower($email));
    if ($email === '' || $password === '') {
        return ['success' => false, 'error' => 'Please enter your email and password.'];
    }
    $stmt = grd_db()->prepare("SELECT id, password_hash FROM customers WHERE email = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch();
    if (!$row || !password_verify($password, $row['password_hash'])) {
        return ['success' => false, 'error' => 'Incorrect email or password.'];
    }
    grd_auth_start();
    // Prevent session fixation across the customer/admin boundary.
    session_regenerate_id(true);
    $_SESSION['customer_id'] = (int)$row['id'];
    return ['success' => true, 'error' => null];
}

/**
 * Creates a new customer account and logs them in immediately.
 * Returns ['success'=>bool, 'error'=>string|null]
 */
function grd_register_customer($name, $email, $password, $phone = '') {
    $name = trim($name);
    $email = trim(strtolower($email));
    $phone = trim($phone);

    if ($name === '') return ['success' => false, 'error' => 'Full name is required.'];
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => 'A valid email address is required.'];
    }
    if (strlen($password) < 6) {
        return ['success' => false, 'error' => 'Password must be at least 6 characters.'];
    }
    if ($phone !== '' && !preg_match('/^[0-9]{10}$/', $phone)) {
        return ['success' => false, 'error' => 'Phone must be a 10-digit mobile number.'];
    }

    $check = grd_db()->prepare("SELECT COUNT(*) FROM customers WHERE email = ?");
    $check->execute([$email]);
    if ((int)$check->fetchColumn() > 0) {
        return ['success' => false, 'error' => 'An account with that email already exists — try logging in instead.'];
    }

    $stmt = grd_db()->prepare(
        "INSERT INTO customers (name, email, password_hash, phone) VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $phone]);
    $id = (int)grd_db()->lastInsertId();

    grd_auth_start();
    session_regenerate_id(true);
    $_SESSION['customer_id'] = $id;
    return ['success' => true, 'error' => null];
}

function grd_logout_customer() {
    grd_auth_start();
    unset($_SESSION['customer_id']);
}
