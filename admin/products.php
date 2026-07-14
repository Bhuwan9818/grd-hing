<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/product-data.php';

grd_admin_require_login();

$deleted = isset($_GET['deleted']);
$saved = isset($_GET['saved']);

$products = grd_admin_get_all_products();

$admin_active = 'products';
$page_title = 'Products — Admin — G.R.D Hing';
require __DIR__ . '/includes/layout-head.php';
?>

<?php if ($saved): ?>
<div class="admin-flash admin-flash-success">Product saved successfully.</div>
<?php endif; ?>
<?php if ($deleted): ?>
<div class="admin-flash admin-flash-success">Product deleted.</div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-head">
    <h3>All Products <span class="admin-count">(<?php echo count($products); ?>)</span></h3>
    <a href="product-form.php" class="btn btn-primary btn-sm">+ Add Product</a>
  </div>

  <?php if (empty($products)): ?>
    <p class="admin-empty">No products yet — add your first one.</p>
  <?php else: ?>
  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <th></th>
          <th>Name</th>
          <th>Weight</th>
          <th>Price</th>
          <th>Group</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $p): ?>
        <tr>
          <td><div class="table-thumb"><img src="../<?php echo htmlspecialchars($p['image']); ?>" alt=""></div></td>
          <td><b><?php echo htmlspecialchars($p['name']); ?></b><br><span class="table-sub"><?php echo htmlspecialchars($p['badge']); ?></span></td>
          <td><?php echo htmlspecialchars($p['weight']); ?></td>
          <td>₹<?php echo number_format($p['price'], 0); ?></td>
          <td><span class="table-sub"><?php echo htmlspecialchars($p['group']); ?></span></td>
          <td>
            <?php if ($p['is_active'] ?? true): ?>
              <span class="status-badge status-delivered">Active</span>
            <?php else: ?>
              <span class="status-badge status-cancelled">Hidden</span>
            <?php endif; ?>
          </td>
          <td class="table-actions">
            <a href="product-form.php?id=<?php echo (int)$p['id']; ?>" class="btn-mini-link">Edit</a>
            <form method="post" action="product-delete.php" onsubmit="return confirm('Delete this product? This cannot be undone.');" style="display:inline;">
              <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(grd_admin_csrf_token()); ?>">
              <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
              <button type="submit" class="btn-mini-link btn-mini-danger">Delete</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/layout-foot.php'; ?>
