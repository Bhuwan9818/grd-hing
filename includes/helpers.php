<?php
/**
 * Renders the signature "seal" badge — a dotted ring with diamond
 * studs echoing the real G.R.D bullock-cart emblem. Reused as the
 * one consistent motif across hero, trust strip, and testimonials.
 *
 * @param string $icon_svg  Inner SVG markup (viewBox 0 0 24 24 recommended)
 * @param int    $studs     Number of diamond studs around the ring
 */
function grd_seal($icon_svg = '', $studs = 6) {
    $out = '<div class="seal">';
    for ($i = 0; $i < $studs; $i++) {
        $angle = ($i / $studs) * 360;
        $out .= '<span class="seal-diamond" style="transform:rotate(' . $angle . 'deg) translate(0,calc(-1 * (var(--seal-size) / 2 - 7px))) rotate(-' . $angle . 'deg);"></span>';
    }
    if ($icon_svg) {
        $out .= '<div class="seal-icon">' . $icon_svg . '</div>';
    }
    $out .= '</div>';
    return $out;
}

/**
 * Renders one product card — used on the homepage shop grid and on
 * product.php's "You Might Also Like" section, so both stay in sync
 * automatically instead of maintaining two copies of this markup.
 */
function grd_product_card($p) {
    ob_start();
    ?>
    <div class="product-card reveal">
      <span class="tag"><?php echo htmlspecialchars($p['badge']); ?></span>
      <a href="product.php?id=<?php echo urlencode($p['id']); ?>" class="product-thumb-link">
        <div class="product-thumb">
          <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?> — <?php echo htmlspecialchars($p['weight']); ?>" loading="lazy">
        </div>
      </a>
      <a href="product.php?id=<?php echo urlencode($p['id']); ?>" class="product-card-title">
        <h4><?php echo htmlspecialchars($p['name']); ?></h4>
      </a>
      <p class="p-tag"><?php echo htmlspecialchars($p['tagline']); ?></p>
      <p class="p-price">₹<?php echo $p['price']; ?> <span style="font-size:12px;color:var(--ink-soft);font-family:var(--body);">/ <?php echo htmlspecialchars($p['weight']); ?></span></p>
      <button
        class="btn-mini add-to-cart"
        data-id="<?php echo (int)$p['id']; ?>"
        data-name="<?php echo htmlspecialchars($p['name']); ?>"
        data-weight="<?php echo htmlspecialchars($p['weight']); ?>"
        data-price="<?php echo $p['price']; ?>"
        data-color="<?php echo htmlspecialchars($p['jar_color']); ?>"
        data-image="<?php echo htmlspecialchars($p['image']); ?>"
      >Add To Cart</button>
    </div>
    <?php
    return ob_get_clean();
}
