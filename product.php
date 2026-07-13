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
      <div class="product-hero-media reveal" id="zoomContainer">
        <span class="ribbon"><?php echo htmlspecialchars($product['badge']); ?></span>
        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?> — <?php echo htmlspecialchars($product['weight']); ?>" id="productImage">
        <div class="zoom-lens" id="zoomLens" aria-hidden="true"></div>
        <button type="button" class="zoom-trigger" id="zoomTrigger" aria-label="View larger image">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
        </button>
      </div>
      <div class="zoom-result" id="zoomResult" aria-hidden="true"></div>

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
                <span class="variant-pill-weight"><?php echo htmlspecialchars($v['weight']); ?></span>
                <span class="variant-pill-price">₹<?php echo $v['price']; ?></span>
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
        <div class="stock-line"><span class="stock-dot"></span> In Stock — ships in 1–2 days</div>

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
            <li>
              <span class="spec-left"><span class="spec-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3v18M5 8l7-5 7 5M5 8v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8"/></svg></span><span class="spec-label">Net Weight</span></span><b><?php echo htmlspecialchars($product['weight']); ?></b>
            </li>
            <li>
              <span class="spec-left"><span class="spec-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 6.5a8.38 8.38 0 0 1-3 6.5 8.38 8.38 0 0 1 3 6.5M4 6.5a8.38 8.38 0 0 0 3 6.5 8.38 8.38 0 0 0-3 6.5M6 3h12M6 21h12"/></svg></span><span class="spec-label">Storage</span></span><b>Cool, dry place</b>
            </li>
            <li>
              <span class="spec-left"><span class="spec-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg></span><span class="spec-label">Best Used In</span></span><b><?php echo $product['group'] === 'hing-dana' ? 'Tempering, pickles, papad' : 'Dal, sabzi, tadka'; ?></b>
            </li>
            <li>
              <span class="spec-left"><span class="spec-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span><span class="spec-label">Made In</span></span><b>Hathras, Uttar Pradesh</b>
            </li>
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

  <!-- ============================= IMAGE LIGHTBOX ============================= -->
  <div class="lightbox-overlay" id="lightboxOverlay">
    <button type="button" class="lightbox-close" id="lightboxClose" aria-label="Close zoomed image">
      <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <img src="" alt="" id="lightboxImage">
  </div>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
