<?php
require_once __DIR__ . '/db.php';

/**
 * All order-related DB access lives here so checkout, the customer
 * account dashboard, and the admin panel all read/write orders the
 * exact same way.
 */

/** Generates a short public-facing order number, e.g. GRD-7BE87C. */
function grd_generate_order_number() {
    return 'GRD-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
}

/**
 * Creates an order + its line items in one transaction.
 *
 * @param array $shipping  name, email, phone, address_line1, address_line2,
 *                          city, state, pincode, notes
 * @param array $items     grd_cart_items() shape: [ ['product'=>[...], 'qty'=>n, 'line_total'=>n], ... ]
 * @param int|null $customer_id
 * @return array The newly created order row (with order_number, id, etc.)
 */
function grd_create_order($shipping, $items, $customer_id = null) {
    $db = grd_db();
    $subtotal = 0;
    foreach ($items as $item) { $subtotal += $item['line_total']; }
    $shipping_fee = 0; // free shipping for now
    $total = $subtotal + $shipping_fee;

    $db->beginTransaction();
    try {
        $order_number = grd_generate_order_number();
        // guard against the (extremely unlikely) collision
        $check = $db->prepare("SELECT COUNT(*) FROM orders WHERE order_number = ?");
        while (true) {
            $check->execute([$order_number]);
            if ((int)$check->fetchColumn() === 0) break;
            $order_number = grd_generate_order_number();
        }

        $stmt = $db->prepare(
            "INSERT INTO orders
                (order_number, customer_id, status, payment_method, subtotal, shipping_fee, total,
                 name, email, phone, address_line1, address_line2, city, state, pincode, notes)
             VALUES (?, ?, 'Placed', 'cod', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $order_number, $customer_id, $subtotal, $shipping_fee, $total,
            $shipping['name'], $shipping['email'], $shipping['phone'],
            $shipping['address_line1'], $shipping['address_line2'] ?? '',
            $shipping['city'], $shipping['state'], $shipping['pincode'],
            $shipping['notes'] ?? '',
        ]);
        $order_id = (int)$db->lastInsertId();

        $item_stmt = $db->prepare(
            "INSERT INTO order_items (order_id, product_id, product_name, weight, image, price, qty, line_total)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        foreach ($items as $item) {
            $p = $item['product'];
            $item_stmt->execute([
                $order_id, $p['id'], $p['name'], $p['weight'], $p['image'],
                $p['price'], $item['qty'], $item['line_total'],
            ]);
        }

        $db->commit();
        return grd_get_order_by_id($order_id);
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}

function grd_get_order_by_id($id) {
    $stmt = grd_db()->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([(int)$id]);
    return $stmt->fetch() ?: null;
}

function grd_get_order_by_number($order_number) {
    $stmt = grd_db()->prepare("SELECT * FROM orders WHERE order_number = ?");
    $stmt->execute([$order_number]);
    return $stmt->fetch() ?: null;
}

function grd_get_order_items($order_id) {
    $stmt = grd_db()->prepare("SELECT * FROM order_items WHERE order_id = ? ORDER BY id ASC");
    $stmt->execute([(int)$order_id]);
    return $stmt->fetchAll();
}

/** Total quantity of items in an order — used on the account order list. */
function grd_order_item_count($order_id) {
    $stmt = grd_db()->prepare("SELECT COALESCE(SUM(qty),0) FROM order_items WHERE order_id = ?");
    $stmt->execute([(int)$order_id]);
    return (int)$stmt->fetchColumn();
}

/**
 * Every order tied to a logged-in customer — matched by customer_id
 * OR by email, so a guest order placed before creating an account
 * still shows up once they register with the same email.
 */
function grd_get_customer_orders($customer_id, $email) {
    $stmt = grd_db()->prepare(
        "SELECT * FROM orders WHERE customer_id = ? OR email = ? ORDER BY created_at DESC"
    );
    $stmt->execute([(int)$customer_id, strtolower(trim($email))]);
    return $stmt->fetchAll();
}

/**
 * Fetch one order for display, but only if it belongs to this
 * customer (by id or email) — so one customer can never view
 * another's order by guessing an id.
 */
function grd_get_customer_order($order_id, $customer_id, $email) {
    $stmt = grd_db()->prepare(
        "SELECT * FROM orders WHERE id = ? AND (customer_id = ? OR email = ?)"
    );
    $stmt->execute([(int)$order_id, (int)$customer_id, strtolower(trim($email))]);
    return $stmt->fetch() ?: null;
}

/* =========================================================
   Admin-only helpers
   ========================================================= */

function grd_admin_get_all_orders($status = null) {
    if ($status) {
        $stmt = grd_db()->prepare("SELECT * FROM orders WHERE status = ? ORDER BY created_at DESC");
        $stmt->execute([$status]);
    } else {
        $stmt = grd_db()->query("SELECT * FROM orders ORDER BY created_at DESC");
    }
    return $stmt->fetchAll();
}

function grd_admin_update_order_status($id, $status) {
    $valid = ['Placed', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
    if (!in_array($status, $valid, true)) return false;
    $stmt = grd_db()->prepare("UPDATE orders SET status = ? WHERE id = ?");
    return $stmt->execute([$status, (int)$id]);
}

function grd_admin_stats() {
    $db = grd_db();
    return [
        'orders'    => (int)$db->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
        'pending'   => (int)$db->query("SELECT COUNT(*) FROM orders WHERE status = 'Placed'")->fetchColumn(),
        'revenue'   => (float)$db->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status != 'Cancelled'")->fetchColumn(),
        'products'  => (int)$db->query("SELECT COUNT(*) FROM products WHERE is_active = 1")->fetchColumn(),
        'customers' => (int)$db->query("SELECT COUNT(*) FROM customers")->fetchColumn(),
    ];
}
