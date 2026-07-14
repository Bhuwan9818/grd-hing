<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/orders.php';

grd_admin_require_login();

$stats = grd_admin_stats();
$recent_orders = array_slice(grd_admin_get_all_orders(), 0, 8);

$admin_active = 'dashboard';
$page_title = 'Dashboard — Admin — G.R.D Hing';
require __DIR__ . '/includes/layout-head.php';
?>

<div class="admin-stats-grid">
  <div class="admin-stat-card">
    <span class="admin-stat-label">Total Orders</span>
    <span class="admin-stat-value"><?php echo (int)$stats['orders']; ?></span>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-label">Pending Orders</span>
    <span class="admin-stat-value"><?php echo (int)$stats['pending']; ?></span>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-label">Revenue</span>
    <span class="admin-stat-value">₹<?php echo number_format($stats['revenue'], 0); ?></span>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-label">Active Products</span>
    <span class="admin-stat-value"><?php echo (int)$stats['products']; ?></span>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-label">Customers</span>
    <span class="admin-stat-value"><?php echo (int)$stats['customers']; ?></span>
  </div>
</div>

<div class="admin-card" style="margin-top:24px;">
  <div class="admin-card-head">
    <h3>Recent Orders</h3>
    <a href="orders.php" class="btn-mini-link">View All →</a>
  </div>

  <?php if (empty($recent_orders)): ?>
    <p class="admin-empty">No orders yet.</p>
  <?php else: ?>
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th>Order #</th>
          <th>Customer</th>
          <th>Date</th>
          <th>Total</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recent_orders as $order): ?>
        <tr>
          <td><b>#<?php echo htmlspecialchars($order['order_number']); ?></b></td>
          <td><?php echo htmlspecialchars($order['name']); ?><br><span class="table-sub"><?php echo htmlspecialchars($order['email']); ?></span></td>
          <td><span class="table-sub"><?php echo date('d M Y', strtotime($order['created_at'])); ?></span></td>
          <td>₹<?php echo number_format($order['total'], 0); ?></td>
          <td><span class="status-badge status-<?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span></td>
          <td class="table-actions"><a href="order-view.php?id=<?php echo (int)$order['id']; ?>" class="btn-mini-link">View →</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/layout-foot.php'; ?>
