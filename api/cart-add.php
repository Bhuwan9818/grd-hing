<?php
require_once __DIR__ . '/../includes/cart.php';
header('Content-Type: application/json');

$product_id = $_POST['product_id'] ?? null;
$qty        = $_POST['qty'] ?? 1;

if (!$product_id || !grd_get_product($product_id)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Product not found']);
    exit;
}

grd_cart_add($product_id, $qty);
echo json_encode(grd_cart_json_payload());
