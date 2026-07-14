/* G.R.D Hing — homepage interactions */
document.addEventListener('DOMContentLoaded', function () {

  /* ---------- Mobile nav ---------- */
  var navToggle = document.getElementById('navToggle');
  var navLinks = document.querySelector('.nav-links');
  if (navToggle && navLinks) {
    navToggle.addEventListener('click', function () {
      var open = navLinks.classList.toggle('open');
      navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      navLinks.style.display = open ? 'flex' : '';
      if (open) {
        navLinks.style.position = 'absolute';
        navLinks.style.top = '84px';
        navLinks.style.left = '0';
        navLinks.style.right = '0';
        navLinks.style.background = '#FBF5E8';
        navLinks.style.flexDirection = 'column';
        navLinks.style.padding = '20px 28px';
        navLinks.style.borderBottom = '1px solid rgba(74,44,29,0.16)';
      }
    });
    navLinks.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () {
        if (window.innerWidth <= 880) {
          navLinks.style.display = '';
          navToggle.setAttribute('aria-expanded', 'false');
        }
      });
    });
  }

  /* ---------- Scroll reveal ---------- */
  var revealEls = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('in');
          entry.target.classList.remove('reveal-pending');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
    revealEls.forEach(function (el) {
      el.classList.add('reveal-pending');
      io.observe(el);
    });
  }

  /* ---------- Toast helper ---------- */
  var toast = document.getElementById('toast');
  var toastMsg = document.getElementById('toastMsg');
  var toastTimer = null;
  function showToast(message) {
    if (!toast) return;
    toastMsg.textContent = message;
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () { toast.classList.remove('show'); }, 2600);
  }

  /* ---------- Featured product quantity selector ---------- */
  var qtyVal = document.querySelector('.qty-val');
  var qtyMinus = document.querySelector('.qty-minus');
  var qtyPlus = document.querySelector('.qty-plus');
  var quantity = 1;
  if (qtyVal && qtyMinus && qtyPlus) {
    qtyMinus.addEventListener('click', function () {
      quantity = Math.max(1, quantity - 1);
      qtyVal.textContent = quantity;
    });
    qtyPlus.addEventListener('click', function () {
      quantity = Math.min(10, quantity + 1);
      qtyVal.textContent = quantity;
    });
  }

  /* ==========================================================
     MINI CART DRAWER
     Backed by the server-side session cart (includes/cart.php +
     api/cart-*.php) so it survives page navigation — add an item
     on the homepage, it's still there on checkout.php.
     ========================================================== */
  var API = (window.GRD_API_PREFIX || '') + 'api/';
  var cartOverlay = document.getElementById('cartOverlay');
  var cartDrawer = document.getElementById('cartDrawer');
  var cartBody = document.getElementById('cartBody');
  var cartBadge = document.getElementById('cartBadge');
  var cartSubtotal = document.getElementById('cartSubtotal');
  var cartBtn = document.getElementById('cartBtn');
  var cartClose = document.getElementById('cartClose');

  function money(n) { return '₹' + Number(n).toLocaleString('en-IN'); }

  function openCart() {
    if (cartOverlay && cartDrawer) {
      cartOverlay.classList.add('show');
      cartDrawer.classList.add('show');
    }
  }
  function closeCart() {
    if (cartOverlay && cartDrawer) {
      cartOverlay.classList.remove('show');
      cartDrawer.classList.remove('show');
    }
  }
  if (cartBtn) cartBtn.addEventListener('click', function (e) { e.preventDefault(); openCart(); fetchCart(); });
  if (cartClose) cartClose.addEventListener('click', closeCart);
  if (cartOverlay) cartOverlay.addEventListener('click', closeCart);

  function emptyCartMarkup() {
    var studs = '';
    for (var i = 0; i < 6; i++) {
      var angle = (i / 6) * 360;
      studs += '<span class="seal-diamond" style="transform:rotate(' + angle + 'deg) translate(0,calc(-1 * (var(--seal-size) / 2 - 7px))) rotate(-' + angle + 'deg);"></span>';
    }
    return (
      '<div class="cart-empty">' +
      '<div class="seal">' + studs + '</div>' +
      '<p>Your cart is empty — add a jar to get started.</p>' +
      '</div>'
    );
  }

  function renderCart(data) {
    if (cartBadge) {
      cartBadge.textContent = data.count;
      cartBadge.classList.toggle('show', data.count > 0);
    }
    if (cartSubtotal) cartSubtotal.textContent = money(data.subtotal);
    if (!cartBody) return;

    if (!data.items || data.items.length === 0) {
      cartBody.innerHTML = emptyCartMarkup();
      return;
    }

    cartBody.innerHTML = data.items.map(function (item) {
      return (
        '<div class="cart-line" data-id="' + item.id + '">' +
        '<div class="cart-line-thumb"><img src="' + (window.GRD_API_PREFIX || '') + item.image + '" alt="' + item.name + '"></div>' +
        '<div class="cart-line-info">' +
        '<b>' + item.name + '</b>' +
        '<span>' + item.weight + ' · ' + money(item.price) + '</span>' +
        '<div class="cart-line-qty">' +
        '<button type="button" class="cart-qty-minus" aria-label="Decrease quantity">–</button>' +
        '<span>' + item.qty + '</span>' +
        '<button type="button" class="cart-qty-plus" aria-label="Increase quantity">+</button>' +
        '</div>' +
        '<button type="button" class="cart-line-remove">Remove</button>' +
        '</div>' +
        '</div>'
      );
    }).join('');

    cartBody.querySelectorAll('.cart-line').forEach(function (line) {
      var id = line.getAttribute('data-id');
      var currentQty = parseInt(line.querySelector('.cart-line-qty span').textContent, 10);
      var minus = line.querySelector('.cart-qty-minus');
      var plus = line.querySelector('.cart-qty-plus');
      var remove = line.querySelector('.cart-line-remove');
      if (minus) minus.addEventListener('click', function () { updateQty(id, Math.max(1, currentQty - 1)); });
      if (plus) plus.addEventListener('click', function () { updateQty(id, Math.min(20, currentQty + 1)); });
      if (remove) remove.addEventListener('click', function () { removeItem(id); });
    });
  }

  function postJSON(url, body) {
    return fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: body,
      credentials: 'same-origin',
    }).then(function (r) { return r.json(); });
  }

  function fetchCart() {
    fetch(API + 'cart-get.php', { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (data) { if (data.success) renderCart(data); })
      .catch(function () { /* fails silently — badge stays at server-rendered count */ });
  }

  function addToCart(productId, qty, label) {
    postJSON(API + 'cart-add.php', 'product_id=' + encodeURIComponent(productId) + '&qty=' + encodeURIComponent(qty))
      .then(function (data) {
        if (data.success) {
          renderCart(data);
          openCart();
          if (label) showToast(label);
        } else {
          showToast(data.error || 'Could not add that to your cart.');
        }
      })
      .catch(function () { showToast('Network error — please try again.'); });
  }

  function updateQty(productId, qty) {
    postJSON(API + 'cart-update.php', 'product_id=' + encodeURIComponent(productId) + '&qty=' + encodeURIComponent(qty))
      .then(function (data) { if (data.success) renderCart(data); });
  }

  function removeItem(productId) {
    postJSON(API + 'cart-remove.php', 'product_id=' + encodeURIComponent(productId))
      .then(function (data) { if (data.success) renderCart(data); });
  }

  // Populate the drawer's item list on load (header already renders the
  // badge count server-side, but the drawer body itself needs this fetch).
  fetchCart();

  document.querySelectorAll('.add-to-cart').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var productId = btn.getAttribute('data-id');
      if (!productId) return;
      var name = btn.getAttribute('data-name') || 'Item';
      var weight = btn.getAttribute('data-weight') || '';
      var qty = btn.classList.contains('btn-mini') ? 1 : (quantity || 1);
      addToCart(productId, qty, qty + ' × ' + name + ' (' + weight + ') added to cart');
    });
  });

  var cartCheckout = document.getElementById('cartCheckout');
  if (cartCheckout) {
    cartCheckout.addEventListener('click', function (e) {
      var count = cartBadge ? parseInt(cartBadge.textContent, 10) || 0 : 0;
      if (count === 0) {
        e.preventDefault();
        showToast('Your cart is empty — add a jar first.');
      }
    });
  }

  /* ---------- Newsletter (dummy submit) ---------- */
  var newsletterForm = document.getElementById('newsletterForm');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function (e) {
      e.preventDefault();
      showToast('You\'re on the list! Watch your inbox.');
      newsletterForm.reset();
    });
  }

  /* ---------- Sticky nav shadow on scroll ---------- */
  var nav = document.querySelector('.navbar');
  if (nav) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 12) {
        nav.style.boxShadow = '0 12px 30px -18px rgba(36,20,8,0.35)';
      } else {
        nav.style.boxShadow = 'none';
      }
    });
  }

  /* ==========================================================
     PREMIUM TILT-ON-HOVER
     Subtle 3D tilt following the cursor — used on product cards
     and the hero/featured jar shots. Skipped on touch devices
     and for people who've asked for reduced motion.
     ========================================================== */
  var supportsHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function attachTilt(el, maxTilt) {
    if (!el || !supportsHover || reduceMotion) return;
    var rect;
    el.addEventListener('mouseenter', function () {
      rect = el.getBoundingClientRect();
    });
    el.addEventListener('mousemove', function (e) {
      if (!rect) rect = el.getBoundingClientRect();
      var px = (e.clientX - rect.left) / rect.width;
      var py = (e.clientY - rect.top) / rect.height;
      var rx = (0.5 - py) * maxTilt;
      var ry = (px - 0.5) * maxTilt;
      el.style.transform = 'perspective(1000px) rotateX(' + rx.toFixed(2) + 'deg) rotateY(' + ry.toFixed(2) + 'deg)';
    });
    el.addEventListener('mouseleave', function () {
      el.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
    });
  }

  document.querySelectorAll('.product-card').forEach(function (card) {
    attachTilt(card, 8);
  });
  attachTilt(document.querySelector('.hero-jar-wrap'), 10);
  attachTilt(document.querySelector('.featured-media img'), 6);

  /* ==========================================================
     PRODUCT PAGE — MAGNIFIER LENS + CLICK-TO-ZOOM LIGHTBOX
     Only runs when the elements exist (i.e. on product.php).
     Hover lens is desktop-only (fine pointer + enough screen
     width for the result panel to sit beside the image); the
     round zoom button always opens the fullscreen lightbox,
     which is how touch/narrow-screen visitors zoom instead.
     ========================================================== */
  var zoomContainer = document.getElementById('zoomContainer');
  var productImage = document.getElementById('productImage');

  if (zoomContainer && productImage) {
    var zoomLens = document.getElementById('zoomLens');
    var zoomResult = document.getElementById('zoomResult');
    var zoomTrigger = document.getElementById('zoomTrigger');
    var lightboxOverlay = document.getElementById('lightboxOverlay');
    var lightboxImage = document.getElementById('lightboxImage');
    var lightboxClose = document.getElementById('lightboxClose');

    var ZOOM_FACTOR = 2.4;
    var magnifierEnabled = supportsHover && !reduceMotion && window.innerWidth >= 1180;

    if (magnifierEnabled && zoomLens && zoomResult) {
      zoomResult.style.backgroundImage = 'url(' + productImage.src + ')';

      var rect, lensSize = 160;

      zoomContainer.addEventListener('mouseenter', function () {
        rect = productImage.getBoundingClientRect();
        zoomResult.style.backgroundSize = (rect.width * ZOOM_FACTOR) + 'px ' + (rect.height * ZOOM_FACTOR) + 'px';
        zoomResult.style.top = rect.top + 'px';
        zoomResult.style.left = (rect.right + 28) + 'px';
        // If the result panel would run off the right edge of the screen, skip showing it
        if (rect.right + 28 + 420 > window.innerWidth) {
          zoomResult.style.left = (rect.left - 420 - 28) + 'px';
        }
        zoomLens.classList.add('show');
        zoomResult.classList.add('show');
      });

      zoomContainer.addEventListener('mousemove', function (e) {
        if (!rect) rect = productImage.getBoundingClientRect();
        var x = e.clientX - rect.left;
        var y = e.clientY - rect.top;
        // clamp lens within the image bounds
        x = Math.max(0, Math.min(x, rect.width));
        y = Math.max(0, Math.min(y, rect.height));

        zoomLens.style.left = (rect.left + x - lensSize / 2) + 'px';
        zoomLens.style.top = (rect.top + y - lensSize / 2) + 'px';

        var bgX = -(x * ZOOM_FACTOR - 210);
        var bgY = -(y * ZOOM_FACTOR - 210);
        zoomResult.style.backgroundPosition = bgX + 'px ' + bgY + 'px';
      });

      zoomContainer.addEventListener('mouseleave', function () {
        zoomLens.classList.remove('show');
        zoomResult.classList.remove('show');
      });
    }

    function openLightbox() {
      lightboxImage.src = productImage.src;
      lightboxImage.alt = productImage.alt;
      lightboxOverlay.classList.add('show');
    }
    function closeLightbox() {
      lightboxOverlay.classList.remove('show');
    }

    if (zoomTrigger) zoomTrigger.addEventListener('click', openLightbox);
    productImage.addEventListener('click', openLightbox);
    if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
    if (lightboxOverlay) {
      lightboxOverlay.addEventListener('click', function (e) {
        if (e.target === lightboxOverlay) closeLightbox();
      });
    }
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeLightbox();
    });
  }
});
