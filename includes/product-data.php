<?php
require_once __DIR__ . '/db.php';

/**
 * Product data — now backed by the `products` table (see sql/schema.sql)
 * instead of a hardcoded array, so the admin panel's "add product" form
 * actually changes what shows up on the site.
 *
 * Every function here returns/accepts the same shape as before:
 * id, slug, group, name, tagline, description, price, mrp, weight,
 * badge, photo, image, jar_color, ingredients (array of strings).
 */

function grd_row_to_product($row) {
    if (!$row) return null;
    return [
        'id'          => (int)$row['id'],
        'slug'        => $row['slug'],
        'group'       => $row['product_group'],
        'name'        => $row['name'],
        'tagline'     => $row['tagline'],
        'description' => $row['description'],
        'price'       => (float)$row['price'],
        'mrp'         => (float)$row['mrp'],
        'weight'      => $row['weight'],
        'badge'       => $row['badge'],
        'photo'       => true,
        'image'       => $row['image'],
        'jar_color'   => $row['jar_color'],
        'ingredients' => json_decode($row['ingredients'], true) ?: [],
        'is_active'   => (bool)($row['is_active'] ?? 1),
    ];
}

/** All active products, shown on the homepage/shop. */
function grd_get_products() {
    $stmt = grd_db()->query("SELECT * FROM products WHERE is_active = 1 ORDER BY id ASC");
    return array_map('grd_row_to_product', $stmt->fetchAll());
}

/** Find a single active product by id. Returns null if not found. */
function grd_get_product($id) {
    $stmt = grd_db()->prepare("SELECT * FROM products WHERE id = ? AND is_active = 1");
    $stmt->execute([$id]);
    return grd_row_to_product($stmt->fetch());
}

/** All pack-size variants sharing a product's group, sorted by price desc. */
function grd_get_variants($product) {
    $stmt = grd_db()->prepare("SELECT * FROM products WHERE product_group = ? AND is_active = 1 ORDER BY price DESC");
    $stmt->execute([$product['group']]);
    return array_map('grd_row_to_product', $stmt->fetchAll());
}

/** A handful of other products to cross-sell, excluding the current one's own group. */
function grd_get_related_products($product, $limit = 4) {
    $stmt = grd_db()->prepare("SELECT * FROM products WHERE product_group != ? AND is_active = 1 ORDER BY id ASC LIMIT ?");
    $stmt->bindValue(1, $product['group']);
    $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return array_map('grd_row_to_product', $stmt->fetchAll());
}

/* =========================================================
   Admin-only helpers — used by admin/products.php etc.
   Not used anywhere on the public-facing pages.
   ========================================================= */

/** All products regardless of active/inactive status, for the admin list. */
function grd_admin_get_all_products() {
    $stmt = grd_db()->query("SELECT * FROM products ORDER BY id DESC");
    return array_map('grd_row_to_product', $stmt->fetchAll());
}

/** Fetch one product by id regardless of active status (admin edit form). */
function grd_admin_get_product($id) {
    $stmt = grd_db()->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return grd_row_to_product($stmt->fetch());
}

function grd_slugify($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

/**
 * Create a new product. $data keys: name, product_group, tagline,
 * description, price, mrp, weight, badge, image, jar_color, ingredients (array).
 * Returns the new product's id.
 */
function grd_admin_create_product($data) {
    $slug = grd_slugify($data['name'] . '-' . $data['weight']);
    // ensure slug uniqueness
    $base = $slug; $i = 2;
    $check = grd_db()->prepare("SELECT COUNT(*) FROM products WHERE slug = ?");
    while (true) {
        $check->execute([$slug]);
        if ((int)$check->fetchColumn() === 0) break;
        $slug = $base . '-' . $i++;
    }

    $stmt = grd_db()->prepare(
        "INSERT INTO products (slug, product_group, name, tagline, description, price, mrp, weight, badge, image, jar_color, ingredients, is_active)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $slug,
        $data['product_group'],
        $data['name'],
        $data['tagline'],
        $data['description'],
        $data['price'],
        $data['mrp'],
        $data['weight'],
        $data['badge'],
        $data['image'],
        $data['jar_color'],
        json_encode(array_values(array_filter($data['ingredients']))),
        $data['is_active'] ?? 1,
    ]);
    return (int)grd_db()->lastInsertId();
}

/** Update an existing product by id. Same $data shape as create. */
function grd_admin_update_product($id, $data) {
    $stmt = grd_db()->prepare(
        "UPDATE products SET product_group=?, name=?, tagline=?, description=?, price=?, mrp=?, weight=?, badge=?, image=?, jar_color=?, ingredients=?, is_active=?
         WHERE id = ?"
    );
    $stmt->execute([
        $data['product_group'],
        $data['name'],
        $data['tagline'],
        $data['description'],
        $data['price'],
        $data['mrp'],
        $data['weight'],
        $data['badge'],
        $data['image'],
        $data['jar_color'],
        json_encode(array_values(array_filter($data['ingredients']))),
        $data['is_active'] ?? 1,
        $id,
    ]);
}

function grd_admin_delete_product($id) {
    $stmt = grd_db()->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
}
