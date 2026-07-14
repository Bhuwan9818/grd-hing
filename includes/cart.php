<?php
require_once __DIR__ . '/product-data.php';

/**
 * Server-side cart, stored in $_SESSION so it survives page navigation
 * (unlike the old pure-JS in-memory cart, which reset on every reload).
 * Shape: $_SESSION['cart'] = [ product_id => qty, ... ]
 *
 * Call grd_cart_start() once near the top of any page that touches the
 * cart, before any output — it just wraps session_start().
 */
function grd_cart_start() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

function grd_cart_add($product_id, $qty = 1) {
    grd_cart_start();
    $product_id = (int)$product_id;
    $qty = max(1, (int)$qty);
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $qty;
    } else {
        $_SESSION['cart'][$product_id] = $qty;
    }
}

function grd_cart_set_qty($product_id, $qty) {
    grd_cart_start();
    $product_id = (int)$product_id;
    $qty = (int)$qty;
    if ($qty <= 0) {
        unset($_SESSION['cart'][$product_id]);
    } else {
        $_SESSION['cart'][$product_id] = $qty;
    }
}

function grd_cart_remove($product_id) {
    grd_cart_start();
    unset($_SESSION['cart'][(int)$product_id]);
}

function grd_cart_clear() {
    grd_cart_start();
    $_SESSION['cart'] = [];
}

/**
 * Returns cart line items joined with live product data:
 * [ ['product' => [...], 'qty' => n, 'line_total' => n], ... ]
 * Silently drops any product id that's been deleted/deactivated since
 * it was added, so a removed product can't break someone's cart.
 */
function grd_cart_items() {
    grd_cart_start();
    $items = [];
    foreach ($_SESSION['cart'] as $product_id => $qty) {
        $product = grd_get_product($product_id);
        if (!$product) continue; // product removed/deactivated since added
        $items[] = [
            'product'    => $product,
            'qty'        => $qty,
            'line_total' => $product['price'] * $qty,
        ];
    }
    return $items;
}

function grd_cart_count() {
    $count = 0;
    foreach (grd_cart_items() as $item) { $count += $item['qty']; }
    return $count;
}

function grd_cart_subtotal() {
    $total = 0;
    foreach (grd_cart_items() as $item) { $total += $item['line_total']; }
    return $total;
}

/** Shape used by every api/cart-*.php endpoint's JSON response. */
function grd_cart_json_payload() {
    $items = array_map(function ($item) {
        return [
            'id'         => $item['product']['id'],
            'name'       => $item['product']['name'],
            'weight'     => $item['product']['weight'],
            'price'      => $item['product']['price'],
            'image'      => $item['product']['image'],
            'qty'        => $item['qty'],
            'line_total' => $item['line_total'],
        ];
    }, grd_cart_items());

    $subtotal = array_sum(array_column($items, 'line_total'));
    $count = array_sum(array_column($items, 'qty'));

    return [
        'success'  => true,
        'items'    => $items,
        'count'    => $count,
        'subtotal' => $subtotal,
    ];
}
