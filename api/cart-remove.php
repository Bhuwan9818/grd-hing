<?php
require_once __DIR__ . '/../includes/cart.php';
header('Content-Type: application/json');

$product_id = $_POST['product_id'] ?? null;

if (!$product_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing product_id']);
    exit;
}

grd_cart_remove($product_id);
echo json_encode(grd_cart_json_payload());
