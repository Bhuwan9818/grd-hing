<?php
require_once __DIR__ . '/../../includes/db.php';

/**
 * Admin-facing auth — deliberately separate from customer auth in
 * includes/auth.php. Uses its own session key ('admin_id') and its
 * own DB table ('admin_users'), so a customer account can never gain
 * admin access, and vice versa.
 */
function grd_admin_auth_start() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function grd_admin_current_admin() {
    grd_admin_auth_start();
    if (empty($_SESSION['admin_id'])) return null;
    static $cached = null;
    if ($cached !== null) return $cached;
    $stmt = grd_db()->prepare("SELECT id, name, email, created_at FROM admin_users WHERE id = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    $cached = $stmt->fetch() ?: null;
    return $cached;
}

/** Redirects to the admin login page if not logged in as admin. */
function grd_admin_require_login() {
    if (!grd_admin_current_admin()) {
        grd_admin_auth_start();
        $_SESSION['admin_redirect_to'] = basename($_SERVER['SCRIPT_NAME']);
        header('Location: login.php');
        exit;
    }
}

/** Returns ['success'=>bool, 'error'=>string|null] */
function grd_admin_login($email, $password) {
    $email = trim(strtolower($email));
    $stmt = grd_db()->prepare("SELECT id, password_hash FROM admin_users WHERE email = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch();
    if (!$row || !password_verify($password, $row['password_hash'])) {
        return ['success' => false, 'error' => 'Incorrect email or password.'];
    }
    grd_admin_auth_start();
    // Prevent session fixation across the customer/admin boundary.
    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int)$row['id'];
    return ['success' => true, 'error' => null];
}

function grd_admin_logout() {
    grd_admin_auth_start();
    unset($_SESSION['admin_id']);
}

/* ---------- CSRF helpers (separate token key from the customer site) ---------- */

function grd_admin_csrf_token() {
    grd_admin_auth_start();
    if (empty($_SESSION['admin_csrf_token'])) {
        $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['admin_csrf_token'];
}

function grd_admin_csrf_check($token) {
    grd_admin_auth_start();
    return !empty($_SESSION['admin_csrf_token']) && is_string($token) && hash_equals($_SESSION['admin_csrf_token'], $token);
}
