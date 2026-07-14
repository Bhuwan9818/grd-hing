<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/product-data.php';

grd_admin_require_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$editing = $id !== null;
$product = $editing ? grd_admin_get_product($id) : null;

if ($editing && !$product) {
    header('Location: products.php');
    exit;
}

// Defaults for a fresh "add" form, or the existing row for "edit".
$values = [
    'name'          => $product['name'] ?? '',
    'product_group' => $product['group'] ?? '',
    'tagline'       => $product['tagline'] ?? '',
    'description'   => $product['description'] ?? '',
    'price'         => $product['price'] ?? '',
    'mrp'           => $product['mrp'] ?? '',
    'weight'        => $product['weight'] ?? '',
    'badge'         => $product['badge'] ?? '',
    'jar_color'     => $product['jar_color'] ?? '#4A2C1D',
    'ingredients'   => $product ? implode(', ', $product['ingredients']) : '',
    'image'         => $product['image'] ?? '',
    'is_active'     => $product['is_active'] ?? true,
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!grd_admin_csrf_check($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Your session expired — please try submitting again.';
    }

    $values['name']          = trim($_POST['name'] ?? '');
    $values['product_group'] = trim($_POST['product_group'] ?? '');
    $values['tagline']       = trim($_POST['tagline'] ?? '');
    $values['description']   = trim($_POST['description'] ?? '');
    $values['price']         = $_POST['price'] ?? '';
    $values['mrp']           = $_POST['mrp'] ?? '';
    $values['weight']        = trim($_POST['weight'] ?? '');
    $values['badge']         = trim($_POST['badge'] ?? '');
    $values['jar_color']     = trim($_POST['jar_color'] ?? '#4A2C1D');
    $values['ingredients']   = trim($_POST['ingredients'] ?? '');
    $values['is_active']     = isset($_POST['is_active']) ? 1 : 0;

    if ($values['name'] === '') $errors[] = 'Product name is required.';
    if ($values['product_group'] === '') $errors[] = 'Product group is required (use the same group for size variants of one product).';
    if ($values['weight'] === '') $errors[] = 'Weight / pack size is required.';
    if (!is_numeric($values['price']) || $values['price'] < 0) $errors[] = 'Price must be a valid number.';
    if (!is_numeric($values['mrp']) || $values['mrp'] < 0) $errors[] = 'MRP must be a valid number.';
    if ($values['description'] === '') $errors[] = 'Description is required.';

    // ---- Image: either a new upload, or keep the existing one ----
    $image_path = $product['image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $file = $_FILES['image'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Image upload failed. Please try again.';
        } else {
            $allowed = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $mime = mime_content_type($file['tmp_name']);
            if (!isset($allowed[$ext]) || $mime !== $allowed[$ext]) {
                $errors[] = 'Image must be a JPG, PNG, or WEBP file.';
            } elseif ($file['size'] > 5 * 1024 * 1024) {
                $errors[] = 'Image must be smaller than 5MB.';
            } else {
                $dir = __DIR__ . '/../images/products';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                $filename = grd_slugify($values['name'] . '-' . $values['weight']) . '-' . time() . '.' . $ext;
                $dest = $dir . '/' . $filename;
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $image_path = 'images/products/' . $filename;
                } else {
                    $errors[] = 'Could not save the uploaded image.';
                }
            }
        }
    } elseif (!$editing) {
        $errors[] = 'Please upload a product image.';
    }

    if (empty($errors)) {
        $ingredients_arr = array_map('trim', explode(',', $values['ingredients']));
        $data = [
            'product_group' => $values['product_group'],
            'name'          => $values['name'],
            'tagline'       => $values['tagline'],
            'description'   => $values['description'],
            'price'         => (float)$values['price'],
            'mrp'           => (float)$values['mrp'],
            'weight'        => $values['weight'],
            'badge'         => $values['badge'],
            'image'         => $image_path,
            'jar_color'     => $values['jar_color'],
            'ingredients'   => $ingredients_arr,
            'is_active'     => $values['is_active'],
        ];

        if ($editing) {
            grd_admin_update_product($id, $data);
        } else {
            grd_admin_create_product($data);
        }
        header('Location: products.php?saved=1');
        exit;
    }
}

$admin_active = 'products';
$page_title = ($editing ? 'Edit Product' : 'Add Product') . ' — Admin — G.R.D Hing';
$admin_heading = $editing ? 'Edit Product' : 'Add New Product';
require __DIR__ . '/includes/layout-head.php';
?>

<?php if ($errors): ?>
<div class="form-errors" role="alert" style="max-width:none;margin-bottom:24px;">
  <ul><?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<div class="admin-card">
  <form method="post" enctype="multipart/form-data" class="checkout-form admin-form">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(grd_admin_csrf_token()); ?>">

    <div class="form-row two-col">
      <label>Product Name
        <input type="text" name="name" required value="<?php echo htmlspecialchars($values['name']); ?>">
      </label>
      <label>Product Group <span class="optional">(same group = size variants)</span>
        <input type="text" name="product_group" required placeholder="e.g. bandhani-churan" value="<?php echo htmlspecialchars($values['product_group']); ?>">
      </label>
    </div>

    <div class="form-row">
      <label>Tagline
        <input type="text" name="tagline" placeholder="Short one-line tagline shown on cards" value="<?php echo htmlspecialchars($values['tagline']); ?>">
      </label>
    </div>

    <div class="form-row">
      <label>Description
        <textarea name="description" rows="4" required><?php echo htmlspecialchars($values['description']); ?></textarea>
      </label>
    </div>

    <div class="form-row two-col">
      <label>Price (₹)
        <input type="number" step="0.01" min="0" name="price" required value="<?php echo htmlspecialchars((string)$values['price']); ?>">
      </label>
      <label>MRP (₹) <span class="optional">(same as price if no discount)</span>
        <input type="number" step="0.01" min="0" name="mrp" required value="<?php echo htmlspecialchars((string)$values['mrp']); ?>">
      </label>
    </div>

    <div class="form-row two-col">
      <label>Weight / Pack Size
        <input type="text" name="weight" required placeholder="e.g. 50g Jar" value="<?php echo htmlspecialchars($values['weight']); ?>">
      </label>
      <label>Badge <span class="optional">(optional)</span>
        <input type="text" name="badge" placeholder="e.g. Bestseller" value="<?php echo htmlspecialchars($values['badge']); ?>">
      </label>
    </div>

    <div class="form-row two-col">
      <label>Ingredients <span class="optional">(comma-separated)</span>
        <input type="text" name="ingredients" placeholder="Asafoetida, Gum Arabic, ..." value="<?php echo htmlspecialchars($values['ingredients']); ?>">
      </label>
      <label>Jar Color <span class="optional">(hex, used as accent)</span>
        <input type="color" name="jar_color" value="<?php echo htmlspecialchars($values['jar_color']); ?>" style="height:48px;padding:6px;">
      </label>
    </div>

    <div class="form-row">
      <label>Product Image <span class="optional"><?php echo $editing ? '(leave blank to keep current image)' : ''; ?></span>
        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
      </label>
      <?php if ($values['image']): ?>
        <div class="admin-image-preview">
          <img src="../<?php echo htmlspecialchars($values['image']); ?>" alt="Current product image">
          <span>Current image</span>
        </div>
      <?php endif; ?>
    </div>

    <div class="form-row">
      <label class="checkbox-row">
        <input type="checkbox" name="is_active" <?php echo $values['is_active'] ? 'checked' : ''; ?>>
        Visible on the live site
      </label>
    </div>

    <div class="admin-form-actions">
      <a href="products.php" class="btn btn-ghost">Cancel</a>
      <button type="submit" class="btn btn-primary"><?php echo $editing ? 'Save Changes' : 'Add Product'; ?></button>
    </div>
  </form>
</div>

<?php require __DIR__ . '/includes/layout-foot.php'; ?>
