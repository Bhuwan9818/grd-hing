<?php
require_once __DIR__ . '/includes/cart.php';
require_once __DIR__ . '/includes/auth.php';

grd_cart_start();
$items = grd_cart_items();

if (empty($items)) {
    header('Location: index.php#shop');
    exit;
}

$subtotal = grd_cart_subtotal();
$shipping = 0; // free shipping for now
$total = $subtotal + $shipping;

$customer = grd_current_customer();

// simple CSRF token for the order form
grd_auth_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

// Re-fill values / show errors if place-order.php sent us back here
$checkout_errors = $_SESSION['checkout_errors'] ?? [];
$old = $_SESSION['checkout_old'] ?? [];
unset($_SESSION['checkout_errors'], $_SESSION['checkout_old']);

function grd_old($old, $customer, $key, $customer_key = null) {
    if (!empty($old[$key])) return $old[$key];
    if ($customer && !empty($customer[$customer_key ?? $key])) return $customer[$customer_key ?? $key];
    return '';
}

$page_title = 'Checkout — G.R.D Hing';
require __DIR__ . '/includes/header.php';
?>

<main id="top">
  <div class="container">
    <nav class="breadcrumb reveal" aria-label="Breadcrumb">
      <a href="index.php">Home</a>
      <span>/</span>
      <span aria-current="page">Checkout</span>
    </nav>
  </div>

  <section class="section" style="padding-top:20px;">
    <div class="container checkout-grid">

      <div class="checkout-form-col reveal">
        <span class="eyebrow">Delivery Details</span>
        <h1 style="margin-top:14px;font-size:clamp(26px,3.2vw,34px);">Where should we send it?</h1>

        <?php if ($checkout_errors): ?>
        <div class="form-errors" role="alert">
          <ul><?php foreach ($checkout_errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
        </div>
        <?php endif; ?>

        <form action="place-order.php" method="post" id="checkoutForm" class="checkout-form">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

          <div class="form-row">
            <label>Full Name
              <input type="text" name="name" required value="<?php echo htmlspecialchars(grd_old($old, $customer, 'name')); ?>">
            </label>
          </div>
          <div class="form-row two-col">
            <label>Email
              <input type="email" name="email" required value="<?php echo htmlspecialchars(grd_old($old, $customer, 'email')); ?>">
            </label>
            <label>Phone
              <input type="tel" name="phone" required pattern="[0-9]{10}" maxlength="10" placeholder="10-digit mobile number" value="<?php echo htmlspecialchars(grd_old($old, $customer, 'phone')); ?>">
            </label>
          </div>
          <div class="form-row">
            <label>Address Line 1
              <input type="text" name="address_line1" required placeholder="House no., street" value="<?php echo htmlspecialchars($old['address_line1'] ?? ''); ?>">
            </label>
          </div>
          <div class="form-row">
            <label>Address Line 2 <span class="optional">(optional)</span>
              <input type="text" name="address_line2" placeholder="Landmark, apartment, etc." value="<?php echo htmlspecialchars($old['address_line2'] ?? ''); ?>">
            </label>
          </div>
          <div class="form-row two-col">
            <label>City
              <input type="text" name="city" required value="<?php echo htmlspecialchars($old['city'] ?? ''); ?>">
            </label>
            <label>State
              <input type="text" name="state" required value="<?php echo htmlspecialchars($old['state'] ?? ''); ?>">
            </label>
          </div>
          <div class="form-row two-col">
            <label>Pincode
              <input type="text" name="pincode" required pattern="[0-9]{6}" maxlength="6" placeholder="6-digit PIN" value="<?php echo htmlspecialchars($old['pincode'] ?? ''); ?>">
            </label>
            <label>Order Notes <span class="optional">(optional)</span>
              <input type="text" name="notes" placeholder="e.g. leave at the door" value="<?php echo htmlspecialchars($old['notes'] ?? ''); ?>">
            </label>
          </div>

          <div class="payment-block">
            <span class="variant-label">Payment Method</span>
            <label class="payment-option active">
              <input type="radio" name="payment_method" value="cod" checked>
              <span class="payment-option-main">
                <b>Cash on Delivery</b>
                <span>Pay in cash when your order arrives</span>
              </span>
            </label>
            <label class="payment-option disabled">
              <input type="radio" disabled>
              <span class="payment-option-main">
                <b>UPI / Cards</b>
                <span>Coming soon</span>
              </span>
            </label>
          </div>

          <?php if (!$customer): ?>
          <p class="checkout-guest-note">
            Checking out as a guest. <a href="account/login.php?redirect=checkout.php">Log in</a> to track this order from your dashboard, or continue below — you can still create an account afterwards with the same email.
          </p>
          <?php endif; ?>

          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:10px;">
            Place Order — <?php echo 'Pay ₹' . number_format($total, 0) . ' on Delivery'; ?>
          </button>
        </form>
      </div>

      <div class="checkout-summary-col reveal">
        <div class="order-summary-card">
          <h3>Order Summary</h3>
          <div class="order-summary-lines">
            <?php foreach ($items as $item): $p = $item['product']; ?>
              <div class="order-summary-line">
                <div class="order-summary-thumb"><img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>"></div>
                <div class="order-summary-info">
                  <b><?php echo htmlspecialchars($p['name']); ?></b>
                  <span><?php echo htmlspecialchars($p['weight']); ?> × <?php echo (int)$item['qty']; ?></span>
                </div>
                <div class="order-summary-price">₹<?php echo number_format($item['line_total'], 0); ?></div>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="order-summary-totals">
            <div><span>Subtotal</span><span>₹<?php echo number_format($subtotal, 0); ?></span></div>
            <div><span>Shipping</span><span><?php echo $shipping > 0 ? '₹' . number_format($shipping, 0) : 'Free'; ?></span></div>
            <div class="order-summary-grand"><span>Total</span><span>₹<?php echo number_format($total, 0); ?></span></div>
          </div>
        </div>
      </div>

    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
