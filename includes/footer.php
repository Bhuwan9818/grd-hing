<?php if (!isset($grd_asset_prefix)) { $grd_asset_prefix = ''; } ?>
<section class="section" style="padding-top:0; padding-bottom:0;">
  <div class="container">
    <div class="cta-band reveal">
      <span class="eyebrow" style="color:var(--turmeric);">Stay Stocked</span>
      <h2 style="margin-top:14px;">Get 10% Off Your First Jar</h2>
      <p>Join the G.R.D kitchen list for restock alerts, recipes, and launch-day offers on new spices.</p>
      <form class="cta-form" id="newsletterForm">
        <input type="email" placeholder="you@email.com" required aria-label="Email address">
        <button type="submit" class="btn btn-primary">Subscribe</button>
      </form>
      <p class="cta-note">No spam. Just good food and the occasional discount.</p>
    </div>
  </div>
</section>

<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-col">
        <div class="footer-brand">
          <img src="<?php echo $grd_asset_prefix; ?>images/logo.png" alt="G.R.D Hing emblem">
          <span>G.R.D Hing</span>
        </div>
        <p class="desc">Bandhani hing churan, ground the way it's always been — pure, plant-based, and strong enough to matter with just a pinch.</p>
        <div class="footer-social">
          <a href="#" class="icon-btn" aria-label="Instagram" style="border-color:rgba(243,231,208,0.25);color:var(--parchment-lt);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><line x1="17.5" y1="6.5" x2="17.5" y2="6.5"/></svg>
          </a>
          <a href="#" class="icon-btn" aria-label="Facebook" style="border-color:rgba(243,231,208,0.25);color:var(--parchment-lt);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          </a>
          <a href="#" class="icon-btn" aria-label="WhatsApp" style="border-color:rgba(243,231,208,0.25);color:var(--parchment-lt);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
          </a>
        </div>
      </div>

      <div class="footer-col">
        <h5>Shop</h5>
        <ul>
          <li><a href="<?php echo grd_section_href('shop'); ?>">Hing Churan</a></li>
          <li><a href="<?php echo grd_section_href('shop'); ?>">Hing Dana</a></li>
          <li><a href="<?php echo grd_section_href('shop'); ?>">All Products</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h5>Company</h5>
        <ul>
          <li><a href="<?php echo grd_section_href('story'); ?>">Our Story</a></li>
          <li><a href="<?php echo grd_section_href('benefits'); ?>">Benefits</a></li>
          <li><a href="<?php echo grd_section_href('kitchen'); ?>">Recipes</a></li>
          <li><a href="<?php echo grd_section_href('reviews'); ?>">Reviews</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h5>Account</h5>
        <ul>
          <li><a href="<?php echo $grd_asset_prefix; ?>account/dashboard.php">Track Order</a></li>
          <li><a href="<?php echo $grd_asset_prefix; ?>account/login.php">Login / Register</a></li>
          <li><a href="#">Shipping Policy</a></li>
          <li><a href="#">Contact Us</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <span>&copy; <?php echo date('Y'); ?> G.R.D Hing. All rights reserved.</span>
      <span>Made with care in Delhi NCR, India.</span>
    </div>
  </div>
</footer>

<div class="cart-overlay" id="cartOverlay"></div>
<aside class="cart-drawer" id="cartDrawer" aria-label="Shopping cart">
  <div class="cart-head">
    <h3>Your Cart</h3>
    <button class="cart-close" id="cartClose" aria-label="Close cart">
      <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <div class="cart-body" id="cartBody">
    <!-- populated by js/main.js from api/cart-get.php -->
  </div>
  <div class="cart-foot">
    <div class="cart-subtotal"><span>Subtotal</span><b id="cartSubtotal">₹0</b></div>
    <a class="btn btn-primary" id="cartCheckout" href="<?php echo $grd_asset_prefix; ?>checkout.php" style="width:100%;justify-content:center;">Checkout</a>
  </div>
</aside>

<div class="toast" id="toast">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
  <span id="toastMsg">Added to cart</span>
</div>

<script>
  window.GRD_API_PREFIX = <?php echo json_encode($grd_asset_prefix); ?>;
</script>
<script src="<?php echo $grd_asset_prefix; ?>js/main.js"></script>
</body>
</html>
