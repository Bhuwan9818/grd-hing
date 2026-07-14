<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/orders.php';

grd_require_login('login.php');
$customer = grd_current_customer();

$id = (int)($_GET['id'] ?? 0);
$order = $id ? grd_get_customer_order($id, $customer['id'], $customer['email']) : null;

if (!$order) {
    header('Location: dashboard.php');
    exit;
}

$items = grd_get_order_items($order['id']);

// 4-stage tracker: Placed -> Processing -> Shipped -> Delivered (or Cancelled)
$stages = ['Placed', 'Processing', 'Shipped', 'Delivered'];
$current_index = array_search($order['status'], $stages, true);
$is_cancelled = $order['status'] === 'Cancelled';

$grd_asset_prefix = '../';
$page_title = 'Order #' . $order['order_number'] . ' — G.R.D Hing';
require __DIR__ . '/../includes/header.php';
?>

<main id="top">
  <div class="container">
    <nav class="breadcrumb reveal" aria-label="Breadcrumb">
      <a href="../index.php">Home</a>
      <span>/</span>
      <a href="dashboard.php">My Account</a>
      <span>/</span>
      <span aria-current="page">Order #<?php echo htmlspecialchars($order['order_number']); ?></span>
    </nav>
  </div>

  <section class="section" style="padding-top:20px;">
    <div class="container account-shell">

      <aside class="account-sidebar reveal">
        <div class="account-sidebar-name"><?php echo htmlspecialchars($customer['name']); ?></div>
        <div class="account-sidebar-email"><?php echo htmlspecialchars($customer['email']); ?></div>
        <nav class="account-sidebar-nav">
          <a href="dashboard.php">My Orders</a>
          <a href="../index.php#shop">Continue Shopping</a>
          <a href="logout.php">Log Out</a>
        </nav>
      </aside>

      <div class="reveal">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:8px;">
          <span class="eyebrow">Order Details</span>
          <span class="status-badge status-<?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span>
        </div>
        <h1 style="margin-top:14px;font-size:clamp(26px,3.2vw,34px);">Order #<?php echo htmlspecialchars($order['order_number']); ?></h1>
        <p style="margin-top:8px;color:var(--ink-soft);font-size:14px;">Placed on <?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></p>

        <?php if ($is_cancelled): ?>
          <div class="order-tracker cancelled">
            <div class="order-tracker-step done">
              <div class="order-tracker-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></div>
              <div class="order-tracker-label">Cancelled</div>
            </div>
          </div>
        <?php else: ?>
          <div class="order-tracker">
            <?php foreach ($stages as $i => $stage): ?>
              <div class="order-tracker-step <?php echo $i < $current_index ? 'done' : ($i === $current_index ? 'current' : ''); ?>">
                <div class="order-tracker-dot">
                  <?php if ($i < $current_index): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                  <?php else: ?>
                    <?php echo $i + 1; ?>
                  <?php endif; ?>
                </div>
                <div class="order-tracker-label"><?php echo $stage; ?></div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="confirm-grid" style="margin-top:20px;">
          <div class="order-summary-card">
            <h3>Items</h3>
            <div class="order-summary-lines">
              <?php foreach ($items as $item): ?>
                <div class="order-summary-line">
                  <div class="order-summary-thumb"><img src="../<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>"></div>
                  <div class="order-summary-info">
                    <b><?php echo htmlspecialchars($item['product_name']); ?></b>
                    <span><?php echo htmlspecialchars($item['weight']); ?> × <?php echo (int)$item['qty']; ?></span>
                  </div>
                  <div class="order-summary-price">₹<?php echo number_format($item['line_total'], 0); ?></div>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="order-summary-totals">
              <div><span>Subtotal</span><span>₹<?php echo number_format($order['subtotal'], 0); ?></span></div>
              <div><span>Shipping</span><span><?php echo $order['shipping_fee'] > 0 ? '₹' . number_format($order['shipping_fee'], 0) : 'Free'; ?></span></div>
              <div class="order-summary-grand"><span>Total (COD)</span><span>₹<?php echo number_format($order['total'], 0); ?></span></div>
            </div>
          </div>

          <div class="confirm-address-card">
            <h3>Delivery Address</h3>
            <p class="admin-address-block">
              <b><?php echo htmlspecialchars($order['name']); ?></b><br>
              <?php echo htmlspecialchars($order['email']); ?><br>
              <?php echo htmlspecialchars($order['phone']); ?><br><br>
              <?php echo htmlspecialchars($order['address_line1']); ?><?php echo $order['address_line2'] ? ', ' . htmlspecialchars($order['address_line2']) : ''; ?><br>
              <?php echo htmlspecialchars($order['city']); ?>, <?php echo htmlspecialchars($order['state']); ?> — <?php echo htmlspecialchars($order['pincode']); ?>
              <?php if ($order['notes']): ?><br><br><i>Note: <?php echo htmlspecialchars($order['notes']); ?></i><?php endif; ?>
            </p>
          </div>
        </div>

        <a href="dashboard.php" class="btn btn-ghost" style="margin-top:28px;">← Back To My Orders</a>
      </div>

    </div>
  </section>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
