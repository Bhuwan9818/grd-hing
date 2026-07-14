<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/orders.php';

grd_admin_require_login();

$id = (int)($_GET['id'] ?? 0);
$order = $id ? grd_get_order_by_id($id) : null;

if (!$order) {
    header('Location: orders.php');
    exit;
}

$status_updated = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && grd_admin_csrf_check($_POST['csrf_token'] ?? '')) {
    $new_status = $_POST['status'] ?? '';
    if (grd_admin_update_order_status($id, $new_status)) {
        $order = grd_get_order_by_id($id); // refresh
        $status_updated = true;
    }
}

$items = grd_get_order_items($order['id']);
$valid_statuses = ['Placed', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];

$admin_active = 'orders';
$page_title = 'Order #' . $order['order_number'] . ' — Admin — G.R.D Hing';
$admin_heading = 'Order #' . $order['order_number'];
require __DIR__ . '/includes/layout-head.php';
?>

<?php if ($status_updated): ?>
<div class="admin-flash admin-flash-success">Order status updated.</div>
<?php endif; ?>

<div class="admin-order-grid">

  <div class="admin-card">
    <div class="admin-card-head">
      <h3>Items</h3>
      <span class="status-badge status-<?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span>
    </div>
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

  <div class="admin-order-side">
    <div class="admin-card">
      <h3>Update Status</h3>
      <form method="post" class="checkout-form">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(grd_admin_csrf_token()); ?>">
        <div class="form-row">
          <label>Order Status
            <select name="status">
              <?php foreach ($valid_statuses as $s): ?>
                <option value="<?php echo $s; ?>" <?php echo $order['status'] === $s ? 'selected' : ''; ?>><?php echo $s; ?></option>
              <?php endforeach; ?>
            </select>
          </label>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Update Status</button>
      </form>
    </div>

    <div class="admin-card">
      <h3>Customer & Shipping</h3>
      <p class="admin-address-block">
        <b><?php echo htmlspecialchars($order['name']); ?></b><br>
        <?php echo htmlspecialchars($order['email']); ?><br>
        <?php echo htmlspecialchars($order['phone']); ?><br><br>
        <?php echo htmlspecialchars($order['address_line1']); ?><?php echo $order['address_line2'] ? ', ' . htmlspecialchars($order['address_line2']) : ''; ?><br>
        <?php echo htmlspecialchars($order['city']); ?>, <?php echo htmlspecialchars($order['state']); ?> — <?php echo htmlspecialchars($order['pincode']); ?>
        <?php if ($order['notes']): ?><br><br><i>Note: <?php echo htmlspecialchars($order['notes']); ?></i><?php endif; ?>
      </p>
    </div>

    <div class="admin-card">
      <h3>Payment</h3>
      <p class="admin-address-block">
        Method: <b>Cash on Delivery</b><br>
        Placed: <?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?><br>
        Last updated: <?php echo date('d M Y, h:i A', strtotime($order['updated_at'])); ?>
      </p>
    </div>
  </div>

</div>

<a href="orders.php" class="btn btn-ghost" style="margin-top:24px;">← Back To All Orders</a>

<?php require __DIR__ . '/includes/layout-foot.php'; ?>
