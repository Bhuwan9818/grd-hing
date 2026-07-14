<?php
require_once __DIR__ . '/../includes/cart.php';
header('Content-Type: application/json');

$product_id = $_POST['product_id'] ?? null;
$qty        = $_POST['qty'] ?? null;

if (!$product_id || $qty === null) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing product_id or qty']);
    exit;
}

grd_cart_set_qty($product_id, $qty);
echo json_encode(grd_cart_json_payload());
