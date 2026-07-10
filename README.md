# G.R.D Hing — Homepage

A premium homepage for the G.R.D Hing brand (Panditji Hing Kayam), built with PHP + HTML + CSS + vanilla JS. Product data is dummy for now, structured so it's a one-line swap to pull from MySQL once the admin panel is ready.

## Structure

```
index.php                    Main homepage — pulls in header, hero, shop, process, benefits, kitchen, testimonials, footer
includes/header.php          <head>, fonts, sticky navbar
includes/footer.php          Newsletter CTA band, footer, cart drawer + toast markup, script tag
includes/helpers.php         grd_seal() — the reusable stamp/seal badge component
                              grd_jar_illustration() — CSS jar mockup for products without real photos
includes/product-data.php    Dummy product array (grd_get_products()) — replace with a DB query later
css/style.css                All styles, design tokens at the top (:root)
js/main.js                   Mobile nav, scroll-reveal, cart drawer, tilt-hover, quantity selector, toast
images/                      logo.png (extracted from your HING.pdf), jar-hero.png, lifestyle-dal.png
```

## Running it locally

You need PHP installed. Then, from this folder:

```
php -S localhost:8000
```

Open `http://localhost:8000` in your browser.

## Notes for going dynamic later

- Swap `grd_get_products()` in `includes/product-data.php` for a MySQL query — the array shape (`id`, `name`, `tagline`, `price`, `mrp`, `weight`, `badge`, `photo`, `image`/`jar_color`+`label_text`) is already what the template expects, so `index.php` shouldn't need changes.
- The mini-cart in `js/main.js` is in-memory only (no backend) — swap `addToCart`/`renderCart` for real session/API calls once the cart/DB layer exists.
- The newsletter form is also a dummy submit for now.
- The 4 non-hing products use a CSS-drawn jar (see `grd_jar_illustration()`) so the shelf looks consistent until you have real photography for them — just flip `photo` to `true` and add an `image` path once you do.

## Design notes

- Palette and type are pulled directly from your actual jar label (umber brown, turmeric gold, parchment cream, spice-red accent) rather than a generic template look.
- The circular "seal" badge (bullock-cart ring + diamond studs) is the one recurring signature element — used in the hero stamp, trust icons, and testimonial marks — so it reads as one consistent brand mark throughout, echoing your real G.R.D logo.

## v2 — Premium upgrade (benchmarked against Pushp Masale)

After reviewing pushpmasale.com, the homepage was upgraded to read as a boutique DTC spice brand rather than a busy corporate catalog site:

- **Typography** — swapped to Fraunces (an editorial serif with a real italic, used for the "Real" emphasis in the hero) + Work Sans, replacing the earlier poster-style serif. Eyebrows are now tracked-caps sans rather than a second serif, so the whole page reads cleaner with fewer competing type styles.
- **New "Our Process" section** — a 4-step "From Resin To Jar" story (sourcing → Bandhani-style blending → stone-grinding → sealing) told through large italic numerals and a connecting line instead of stock photography. This is the kind of traceability storytelling premium single-origin spice brands use, and it's something Pushp's site doesn't have at all.
- **Real mini-cart drawer** — clicking "Add to Cart" now slides in an actual cart panel (line items, qty steppers, subtotal, checkout) instead of just a toast. Pushp's site has no on-site cart — it redirects out to a separate store — so this makes G.R.D feel like a real, modern e-commerce experience by comparison. Cart state is in-memory only (JS), ready to wire up to a real backend later.
- **Tilt-on-hover micro-interactions** — the hero jar, featured product photo, and the four product cards now tilt subtly toward the cursor (disabled automatically on touch devices and for people with reduced-motion preferences set).
- **Staggered reveals** — grid sections (trust strip, products, benefits, testimonials) now cascade in one-by-one on scroll instead of appearing all at once, a small but noticeable premium polish detail.
