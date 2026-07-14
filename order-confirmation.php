<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/orders.php';

$order_number = $_GET['order'] ?? '';
$order = $order_number ? grd_get_order_by_number($order_number) : null;

if (!$order) {
    header('Location: index.php');
    exit;
}

$items = grd_get_order_items($order['id']);
$customer = grd_current_customer();

$page_title = 'Order Confirmed — G.R.D Hing';
require __DIR__ . '/includes/header.php';
?>

<main id="top">
  <section class="section" style="padding-top:48px;">
    <div class="container">

      <div class="confirm-hero reveal">
        <div class="confirm-check">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
        </div>
        <span class="eyebrow">Order Placed</span>
        <h1 style="margin-top:14px;font-size:clamp(28px,3.6vw,38px);">Thank you, <?php echo htmlspecialchars(explode(' ', $order['name'])[0]); ?>!</h1>
        <p style="margin-top:14px;color:var(--ink-soft);font-size:16px;">
          Your order has been placed and will be paid for in cash on delivery. We'll start getting it ready right away.
        </p>
        <div class="confirm-order-number">Order #<?php echo htmlspecialchars($order['order_number']); ?></div>
      </div>

      <div class="confirm-grid">
        <div class="order-summary-card reveal">
          <h3>Order Summary</h3>
          <div class="order-summary-lines">
            <?php foreach ($items as $item): ?>
              <div class="order-summary-line">
                <div class="order-summary-thumb"><img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>"></div>
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

        <div class="confirm-address-card reveal">
          <h3>Delivery Address</h3>
          <p class="admin-address-block">
            <b><?php echo htmlspecialchars($order['name']); ?></b><br>
            <?php echo htmlspecialchars($order['phone']); ?><br><br>
            <?php echo htmlspecialchars($order['address_line1']); ?><?php echo $order['address_line2'] ? ', ' . htmlspecialchars($order['address_line2']) : ''; ?><br>
            <?php echo htmlspecialchars($order['city']); ?>, <?php echo htmlspecialchars($order['state']); ?> — <?php echo htmlspecialchars($order['pincode']); ?>
          </p>

          <div style="margin-top:26px;display:flex;flex-direction:column;gap:12px;">
            <?php if ($customer): ?>
              <a href="account/order.php?id=<?php echo (int)$order['id']; ?>" class="btn btn-primary" style="width:100%;justify-content:center;">Track This Order</a>
            <?php else: ?>
              <a href="account/register.php" class="btn btn-primary" style="width:100%;justify-content:center;">Create Account To Track Orders</a>
            <?php endif; ?>
            <a href="index.php#shop" class="btn btn-ghost" style="width:100%;justify-content:center;">Continue Shopping</a>
          </div>
        </div>
      </div>

    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
