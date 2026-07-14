<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/orders.php';

grd_require_login('login.php');
$customer = grd_current_customer();
$orders = grd_get_customer_orders($customer['id'], $customer['email']);

$grd_asset_prefix = '../';
$page_title = 'My Account — G.R.D Hing';
require __DIR__ . '/../includes/header.php';
?>

<main id="top">
  <div class="container">
    <nav class="breadcrumb reveal" aria-label="Breadcrumb">
      <a href="../index.php">Home</a>
      <span>/</span>
      <span aria-current="page">My Account</span>
    </nav>
  </div>

  <section class="section" style="padding-top:20px;">
    <div class="container account-shell">

      <aside class="account-sidebar reveal">
        <div class="account-sidebar-name"><?php echo htmlspecialchars($customer['name']); ?></div>
        <div class="account-sidebar-email"><?php echo htmlspecialchars($customer['email']); ?></div>
        <nav class="account-sidebar-nav">
          <a href="dashboard.php" class="active">My Orders</a>
          <a href="../index.php#shop">Continue Shopping</a>
          <a href="logout.php">Log Out</a>
        </nav>
      </aside>

      <div class="reveal">
        <span class="eyebrow">My Orders</span>
        <h1 style="margin-top:14px;font-size:clamp(26px,3.2vw,34px);margin-bottom:26px;">Order History</h1>

        <?php if (empty($orders)): ?>
          <div class="account-empty">
            <p>You haven't placed any orders yet.</p>
            <p style="margin-top:10px;"><a href="../index.php#shop">Start shopping →</a></p>
          </div>
        <?php else: ?>
          <div class="order-list-card">
            <?php foreach ($orders as $order): ?>
              <div class="order-list-row">
                <div class="order-list-main">
                  <b>#<?php echo htmlspecialchars($order['order_number']); ?></b>
                  <?php $item_count = grd_order_item_count($order['id']); ?>
                  <span><?php echo date('d M Y', strtotime($order['created_at'])); ?> · <?php echo $item_count; ?> item<?php echo $item_count === 1 ? '' : 's'; ?></span>
                </div>
                <div class="order-list-right">
                  <span class="status-badge status-<?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span>
                  <span class="order-list-total">₹<?php echo number_format($order['total'], 0); ?></span>
                  <a href="order.php?id=<?php echo (int)$order['id']; ?>" class="btn-mini-link">View →</a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </section>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
