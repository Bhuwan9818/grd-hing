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
    // close mobile menu after a link is tapped
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

  /* ---------- Add to cart (dummy — no backend yet) ---------- */
  document.querySelectorAll('.add-to-cart').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var name = btn.getAttribute('data-name') || 'Item';
      var qty = quantity || 1;
      showToast(qty + ' × ' + name + ' added to cart');
    });
  });

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
});
