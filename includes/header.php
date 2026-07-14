<?php
require_once __DIR__ . '/cart.php';
require_once __DIR__ . '/auth.php';
grd_cart_start();
$grd_cart_count = grd_cart_count();
$grd_customer = grd_current_customer();

/**
 * Header include: <head> + sticky navbar.
 * Uses relative image paths — keep this file included from files
 * that live at the project root (e.g. index.php, product.php) or
 * pass $grd_asset_prefix (e.g. '../') for files one level down
 * (account/, admin/).
 *
 * Pages can set $page_title / $page_description before including this
 * file to override the defaults below (see product.php for an example).
 */
if (!isset($grd_asset_prefix)) {
    $grd_asset_prefix = '';
}
if (!isset($page_title)) {
    $page_title = 'G.R.D Hing — Bandhani Hing Churan | Pure, Plant-Based Asafoetida';
}
if (!isset($page_description)) {
    $page_description = 'G.R.D Hing brings authentic Bandhani hing churan to your kitchen — plant-based, additive-free, and ground for real Indian cooking.';
}
// Section links (#shop, #benefits, etc.) only work directly on the homepage —
// from any other page they need to point back to index.php first.
$grd_on_home = basename($_SERVER['SCRIPT_NAME']) === 'index.php' && $grd_asset_prefix === '';
$grd_home_href = $grd_on_home ? '#top' : $grd_asset_prefix . 'index.php';
function grd_section_href($hash) {
    global $grd_on_home, $grd_asset_prefix;
    return $grd_on_home ? '#' . $hash : $grd_asset_prefix . 'index.php#' . $hash;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($page_title); ?></title>
<meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,400;1,9..144,500&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?php echo $grd_asset_prefix; ?>css/style.css">
</head>
<body>

<div class="grain" aria-hidden="true"></div>

<header class="navbar">
  <div class="container">
    <a href="<?php echo $grd_home_href; ?>" class="brand">
      <img src="<?php echo $grd_asset_prefix; ?>images/logo.png" alt="G.R.D Hing emblem — bullock cart mark">
      <div class="brand-text">
        <div class="brand-name">G.R.D Hing</div>
        <div class="brand-sub">Panditji Hing Kayam</div>
      </div>
    </a>

    <nav class="nav-links" aria-label="Primary">
      <a href="<?php echo $grd_home_href; ?>">Home</a>
      <a href="<?php echo grd_section_href('shop'); ?>">Shop</a>
      <a href="<?php echo grd_section_href('benefits'); ?>">Benefits</a>
      <a href="<?php echo grd_section_href('kitchen'); ?>">Recipes</a>
      <a href="<?php echo grd_section_href('reviews'); ?>">Reviews</a>
    </nav>

    <div class="nav-actions">
      <a class="icon-btn" href="<?php echo $grd_asset_prefix; ?>account/<?php echo $grd_customer ? 'dashboard.php' : 'login.php'; ?>" aria-label="<?php echo $grd_customer ? 'My account' : 'Login'; ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      </a>
      <button class="icon-btn" id="cartBtn" aria-label="View cart">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        <span class="cart-badge<?php echo $grd_cart_count > 0 ? ' show' : ''; ?>" id="cartBadge"><?php echo $grd_cart_count; ?></span>
      </button>
      <button class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>
