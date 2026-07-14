<?php
require_once __DIR__ . '/includes/cart.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/orders.php';

grd_cart_start();
grd_auth_start();

function grd_checkout_fail($errors, $old) {
    $_SESSION['checkout_errors'] = $errors;
    $_SESSION['checkout_old'] = $old;
    header('Location: checkout.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit;
}

$items = grd_cart_items();
if (empty($items)) {
    header('Location: index.php#shop');
    exit;
}

$old = [
    'name'          => trim($_POST['name'] ?? ''),
    'email'         => trim($_POST['email'] ?? ''),
    'phone'         => trim($_POST['phone'] ?? ''),
    'address_line1' => trim($_POST['address_line1'] ?? ''),
    'address_line2' => trim($_POST['address_line2'] ?? ''),
    'city'          => trim($_POST['city'] ?? ''),
    'state'         => trim($_POST['state'] ?? ''),
    'pincode'       => trim($_POST['pincode'] ?? ''),
    'notes'         => trim($_POST['notes'] ?? ''),
];

if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
    grd_checkout_fail(['Your session expired — please review your details and submit again.'], $old);
}

$errors = [];
if ($old['name'] === '') $errors[] = 'Full name is required.';
if ($old['email'] === '' || !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email address is required.';
if (!preg_match('/^[0-9]{10}$/', $old['phone'])) $errors[] = 'Phone must be a 10-digit mobile number.';
if ($old['address_line1'] === '') $errors[] = 'Address line 1 is required.';
if ($old['city'] === '') $errors[] = 'City is required.';
if ($old['state'] === '') $errors[] = 'State is required.';
if (!preg_match('/^[0-9]{6}$/', $old['pincode'])) $errors[] = 'Pincode must be 6 digits.';

// Only Cash on Delivery is accepted right now.
$payment_method = $_POST['payment_method'] ?? 'cod';
if ($payment_method !== 'cod') {
    $errors[] = 'Only Cash on Delivery is available at the moment.';
}

if ($errors) {
    grd_checkout_fail($errors, $old);
}

$customer = grd_current_customer();
$order = grd_create_order($old, $items, $customer['id'] ?? null);

grd_cart_clear();
unset($_SESSION['checkout_errors'], $_SESSION['checkout_old']);

header('Location: order-confirmation.php?order=' . urlencode($order['order_number']));
exit;
