<?php
require_once __DIR__ . '/../includes/cart.php';
header('Content-Type: application/json');

grd_cart_start();
echo json_encode(grd_cart_json_payload());
