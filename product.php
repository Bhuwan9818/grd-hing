<?php
require_once __DIR__ . '/includes/product-data.php';
require_once __DIR__ . '/includes/helpers.php';

$id      = $_GET['id'] ?? null;
$product = $id !== null ? grd_get_product($id) : null;

// ---- Not found: graceful fallback instead of a raw PHP error ----
if (!$product) {
    $page_title = 'Product Not Found — G.R.D Hing';
    require __DIR__ . '/includes/header.php';
    ?>
    <main id="top">
      <section class="section" style="text-align:center;">
        <div class="container" style="max-width:560px;">
          <span class="eyebrow">404</span>
          <h1 style="margin-top:16px;font-size:clamp(28px,4vw,38px);">We couldn't find that jar</h1>
          <p style="margin-top:16px;color:var(--ink-soft);">The link might be out of date, or the product may have been removed. Everything we currently sell is on the shop shelf.</p>
          <a href="index.php#shop" class="btn btn-primary" style="margin-top:30px;">Back To Shop</a>
        </div>
      </section>
    </main>
    <?php
    require __DIR__ . '/includes/footer.php';
    exit;
}

$variants = grd_get_variants($product);
$related  = grd_get_related_products($product);

$page_title       = $product['name'] . ' (' . $product['weight'] . ') — G.R.D Hing — ₹' . $product['price'];
$page_description = $product['tagline'];

require __DIR__ . '/includes/header.php';
?>

<main id="top">

  <div class="container">
    <nav class="breadcrumb reveal" aria-label="Breadcrumb">
      <a href="index.php">Home</a>
      <span>/</span>
      <a href="index.php#shop">Shop</a>
      <span>/</span>
      <span aria-current="page"><?php echo htmlspecialchars($product['name']); ?> — <?php echo htmlspecialchars($product['weight']); ?></span>
    </nav>
  </div>

  <!-- ============================= PRODUCT HERO ============================= -->
  <section class="product-hero">
    <div class="container product-hero-grid">
      <div class="product-hero-media reveal">
        <span class="ribbon"><?php echo htmlspecialchars($product['badge']); ?></span>
        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?> — <?php echo htmlspecialchars($product['weight']); ?>" id="productImage">
      </div>

      <div class="product-hero-info reveal">
        <span class="eyebrow">Bandhani Hing</span>
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <p class="lede"><?php echo htmlspecialchars($product['description']); ?></p>

        <?php if (count($variants) > 1): ?>
        <div class="variant-block">
          <span class="variant-label">Size</span>
          <div class="variant-pills">
            <?php foreach ($variants as $v): ?>
              <a href="product.php?id=<?php echo urlencode($v['id']); ?>"
                 class="variant-pill<?php echo $v['id'] === $product['id'] ? ' active' : ''; ?>">
                <?php echo htmlspecialchars($v['weight']); ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <div class="price-row" style="margin-top:22px;">
          <span class="price" style="color:var(--saffron-dp);">₹<?php echo $product['price']; ?></span>
          <?php if ($product['mrp'] > $product['price']): ?>
            <span class="mrp">₹<?php echo $product['mrp']; ?></span>
            <span class="save">Save <?php echo round((1 - $product['price']/$product['mrp'])*100); ?>%</span>
          <?php else: ?>
            <span class="save" style="background:var(--parchment);color:var(--saffron-dp);">MRP · Inclusive of all taxes</span>
          <?php endif; ?>
        </div>

        <div class="featured-actions" style="margin-top:26px;">
          <div class="qty-select" style="border-color:var(--line);">
            <button type="button" class="qty-minus" style="color:var(--umber-deep);" aria-label="Decrease quantity">–</button>
            <span class="qty-val" style="color:var(--umber-deep);">1</span>
            <button type="button" class="qty-plus" style="color:var(--umber-deep);" aria-label="Increase quantity">+</button>
          </div>
          <button class="btn btn-primary add-to-cart"
                  data-name="<?php echo htmlspecialchars($product['name']); ?>"
                  data-weight="<?php echo htmlspecialchars($product['weight']); ?>"
                  data-price="<?php echo $product['price']; ?>"
                  data-color="<?php echo htmlspecialchars($product['jar_color']); ?>"
                  data-image="<?php echo htmlspecialchars($product['image']); ?>">Add To Cart</button>
        </div>

        <ul class="mini-trust">
          <li>100% Plant-Based</li>
          <li>No Artificial Additives</li>
          <li>Small-Batch Ground</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- ============================= SPECS / INGREDIENTS ============================= -->
  <section class="section" style="padding-top:0;">
    <div class="container">
      <div class="specs-card reveal">
        <div class="specs-col">
          <h3>Ingredients</h3>
          <ul class="ingredient-list">
            <?php foreach ($product['ingredients'] as $ing): ?>
              <li><?php echo htmlspecialchars($ing); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="specs-col">
          <h3>Good To Know</h3>
          <ul class="spec-facts">
            <li><span>Net Weight</span><b><?php echo htmlspecialchars($product['weight']); ?></b></li>
            <li><span>Storage</span><b>Cool, dry place</b></li>
            <li><span>Best Used In</span><b><?php echo $product['group'] === 'hing-dana' ? 'Tempering, pickles, papad' : 'Dal, sabzi, tadka'; ?></b></li>
            <li><span>Made In</span><b>Hathras, Uttar Pradesh</b></li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================= RELATED PRODUCTS ============================= -->
  <?php if (!empty($related)): ?>
  <section class="section" style="padding-top:0;">
    <div class="container">
      <div class="section-head reveal">
        <span class="eyebrow">Keep Exploring</span>
        <h2>You Might Also Like</h2>
      </div>
      <div class="product-grid stagger">
        <?php foreach ($related as $r): ?>
          <?php echo grd_product_card($r); ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
