<?php
/**
 * Shared admin chrome: <head>, sidebar nav, topbar. Include after
 * setting $admin_active ('dashboard'|'orders'|'products') and
 * $page_title, optionally $admin_heading (defaults to a title per section).
 */
require_once __DIR__ . '/auth.php';
$grd_admin = grd_admin_current_admin();

if (!isset($page_title)) { $page_title = 'Admin — G.R.D Hing'; }
if (!isset($admin_active)) { $admin_active = 'dashboard'; }
if (!isset($admin_heading)) {
    $headings = ['dashboard' => 'Dashboard', 'orders' => 'Orders', 'products' => 'Products'];
    $admin_heading = $headings[$admin_active] ?? 'Admin';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($page_title); ?></title>
<meta name="robots" content="noindex, nofollow">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,400;1,9..144,500&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/style.css">
</head>
<body class="admin-body">

<div class="admin-shell">

  <aside class="admin-sidebar" id="adminSidebar">
    <a href="index.php" class="admin-brand">
      <img src="../images/logo.png" alt="G.R.D Hing emblem">
      <div class="brand-text">
        <div class="brand-name" style="color:var(--parchment-lt);">G.R.D Hing</div>
        <div class="brand-sub">Admin Panel</div>
      </div>
    </a>

    <nav class="admin-nav" aria-label="Admin">
      <a href="index.php" class="<?php echo $admin_active === 'dashboard' ? 'active' : ''; ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
        Dashboard
      </a>
      <a href="orders.php" class="<?php echo $admin_active === 'orders' ? 'active' : ''; ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        Orders
      </a>
      <a href="products.php" class="<?php echo $admin_active === 'products' ? 'active' : ''; ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        Products
      </a>
    </nav>

    <div class="admin-sidebar-foot">
      <a href="../index.php" target="_blank" class="admin-view-site">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><path d="M15 3h6v6"/><path d="M10 14 21 3"/></svg>
        View Live Site
      </a>
      <a href="logout.php" class="admin-view-site">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Log Out
      </a>
    </div>
  </aside>

  <div class="admin-main">
    <header class="admin-topbar">
      <button class="nav-toggle admin-menu-toggle" id="adminMenuToggle" aria-label="Open menu">
        <span></span><span></span><span></span>
      </button>
      <h1><?php echo htmlspecialchars($admin_heading); ?></h1>
      <div class="admin-topbar-user">
        <span><?php echo htmlspecialchars($grd_admin['name'] ?? 'Admin'); ?></span>
        <div class="admin-avatar"><?php echo strtoupper(substr($grd_admin['name'] ?? 'A', 0, 1)); ?></div>
      </div>
    </header>

    <main class="admin-content">
