<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/product-data.php';

grd_admin_require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && grd_admin_csrf_check($_POST['csrf_token'] ?? '')) {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        grd_admin_delete_product($id);
        header('Location: products.php?deleted=1');
        exit;
    }
}

header('Location: products.php');
exit;
