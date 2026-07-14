<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/orders.php';

grd_admin_require_login();

$valid_statuses = ['Placed', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
$status_filter = $_GET['status'] ?? '';
if (!in_array($status_filter, $valid_statuses, true)) {
    $status_filter = '';
}

$orders = grd_admin_get_all_orders($status_filter ?: null);

$admin_active = 'orders';
$page_title = 'Orders — Admin — G.R.D Hing';
require __DIR__ . '/includes/layout-head.php';
?>

<div class="admin-card">
  <div class="admin-card-head">
    <h3>All Orders <span class="admin-count">(<?php echo count($orders); ?>)</span></h3>
    <div class="admin-filter-pills">
      <a href="orders.php" class="admin-filter-pill <?php echo $status_filter === '' ? 'active' : ''; ?>">All</a>
      <?php foreach ($valid_statuses as $s): ?>
        <a href="orders.php?status=<?php echo urlencode($s); ?>" class="admin-filter-pill <?php echo $status_filter === $s ? 'active' : ''; ?>"><?php echo $s; ?></a>
      <?php endforeach; ?>
    </div>
  </div>

  <?php if (empty($orders)): ?>
    <p class="admin-empty">No orders<?php echo $status_filter ? ' with status "' . htmlspecialchars($status_filter) . '"' : ''; ?> yet.</p>
  <?php else: ?>
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th>Order #</th>
          <th>Customer</th>
          <th>Date</th>
          <th>Items</th>
          <th>Total</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order): ?>
        <tr>
          <td><b>#<?php echo htmlspecialchars($order['order_number']); ?></b></td>
          <td><?php echo htmlspecialchars($order['name']); ?><br><span class="table-sub"><?php echo htmlspecialchars($order['email']); ?></span></td>
          <td><span class="table-sub"><?php echo date('d M Y', strtotime($order['created_at'])); ?></span></td>
          <td><span class="table-sub"><?php echo grd_order_item_count($order['id']); ?></span></td>
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
