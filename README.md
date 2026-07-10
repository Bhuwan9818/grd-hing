# G.R.D Hing — Homepage

A premium homepage for the G.R.D Hing brand (Panditji Hing Kayam), built with PHP + HTML + CSS + vanilla JS. Product data is dummy for now, structured so it's a one-line swap to pull from MySQL once the admin panel is ready.

## Structure

```
index.php                    Main homepage — pulls in header, hero, shop, benefits, kitchen, testimonials, footer
includes/header.php          <head>, fonts, sticky navbar
includes/footer.php          Newsletter CTA band, footer, toast markup, script tag
includes/helpers.php         grd_seal() — the reusable stamp/seal badge component
                              grd_jar_illustration() — CSS jar mockup for products without real photos
includes/product-data.php    Dummy product array (grd_get_products()) — replace with a DB query later
css/style.css                All styles, design tokens at the top (:root)
js/main.js                   Mobile nav, scroll-reveal, quantity selector, add-to-cart toast, newsletter
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
- "Add to Cart" and "Notify Me" are currently just a toast message (no backend) — wire these up once you have a cart/DB layer.
- The newsletter form is also a dummy submit for now.
- The 4 non-hing products use a CSS-drawn jar (see `grd_jar_illustration()`) so the shelf looks consistent until you have real photography for them — just flip `photo` to `true` and add an `image` path once you do.

## Design notes

- Palette and type are pulled directly from your actual jar label (umber brown, turmeric gold, parchment cream, spice-red accent) rather than a generic template look.
- The circular "seal" badge (bullock-cart ring + diamond studs) is the one recurring signature element — used in the hero stamp, trust icons, and testimonial marks — so it reads as one consistent brand mark throughout, echoing your real G.R.D logo.
