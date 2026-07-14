# G.R.D Hing — Homepage

A premium homepage for the G.R.D Hing brand (Panditji Hing Kayam), built with PHP + HTML + CSS + vanilla JS.

## Structure

```
index.php                    Main homepage — pulls in header, hero, shop, process, benefits, kitchen, testimonials, footer
product.php                  Dynamic product detail page — reads ?id= and renders any product from product-data.php
includes/header.php          <head>, fonts, sticky navbar (accepts $page_title/$page_description per page)
includes/footer.php          Newsletter CTA band, footer, cart drawer + toast markup, script tag
includes/helpers.php         grd_seal() — the reusable stamp/seal badge component
                              grd_product_card() — shared product card markup (homepage grid + related products)
includes/product-data.php    Real product array + grd_get_product()/grd_get_variants()/grd_get_related_products()
css/style.css                All styles, design tokens at the top (:root)
js/main.js                   Mobile nav, scroll-reveal, cart drawer, tilt-hover, quantity selector, toast
images/                      logo.png, jar-hero.png, lifestyle-dal.png, plus real product photos (hing-*.png)
```

## Running it locally

You need PHP installed. Then, from this folder:

```
php -S localhost:8000
```

Open `http://localhost:8000` in your browser.

## Notes for going dynamic later

- Swap `grd_get_products()` in `includes/product-data.php` for a MySQL query — the array shape (`id`, `slug`, `group`, `name`, `tagline`, `description`, `price`, `mrp`, `weight`, `badge`, `photo`, `image`, `jar_color`, `ingredients`) is already what both `index.php` and `product.php` expect, so neither should need changes as long as the query returns rows shaped the same way.
- `product.php?id=N` is already fully dynamic — it looks the product up by ID, renders whichever pack-size variants share its `group` as a size switcher, and pulls "You Might Also Like" from every other group. Swapping the data source doesn't change any of that logic.
- The mini-cart in `js/main.js` is in-memory only (no backend) — swap `addToCart`/`renderCart` for real session/API calls once the cart/DB layer exists.
- The newsletter form is also a dummy submit for now.

## Design notes

- Palette and type are pulled directly from your actual jar label (umber brown, turmeric gold, parchment cream, spice-red accent) rather than a generic template look.
- The circular "seal" badge (bullock-cart ring + diamond studs) is the one recurring signature element — used in the hero stamp, trust icons, and testimonial marks — so it reads as one consistent brand mark throughout, echoing your real G.R.D logo.

## v2 — Premium upgrade (benchmarked against Pushp Masale)

After reviewing pushpmasale.com, the homepage was upgraded to read as a boutique DTC spice brand rather than a busy corporate catalog site:

- **Typography** — swapped to Fraunces (an editorial serif with a real italic, used for the "Real" emphasis in the hero) + Work Sans, replacing the earlier poster-style serif. Eyebrows are now tracked-caps sans rather than a second serif, so the whole page reads cleaner with fewer competing type styles.
- **New "Our Process" section** — a 4-step "From Resin To Jar" story (sourcing → Bandhani-style blending → stone-grinding → sealing) told through large italic numerals and a connecting line instead of stock photography. This is the kind of traceability storytelling premium single-origin spice brands use, and it's something Pushp's site doesn't have at all.
- **Real mini-cart drawer** — clicking "Add to Cart" now slides in an actual cart panel (line items, qty steppers, subtotal, checkout) instead of just a toast. Pushp's site has no on-site cart — it redirects out to a separate store — so this makes G.R.D feel like a real, modern e-commerce experience by comparison. Cart state is in-memory only (JS), ready to wire up to a real backend later.
- **Tilt-on-hover micro-interactions** — the hero jar, featured product photo, and product cards tilt subtly toward the cursor (disabled automatically on touch devices and for people with reduced-motion preferences set).
- **Staggered reveals** — grid sections (trust strip, products, benefits, testimonials) now cascade in one-by-one on scroll instead of appearing all at once.

## v3 — Real product catalog

The homepage now ships with your actual products instead of placeholders:

| Product | Weight | Price | Badge |
|---|---|---|---|
| Bandhani Hing Churan | 100g | ₹455 | Bestseller (flagship) |
| Bandhani Hing Churan | 50g | ₹235 | Value Pack |
| Bandhani Hing Churan | 10g | ₹68 | Most Popular |
| Bandhani Hing Churan | 5g | ₹36 | Trial Pack |
| Hing Dana (Whole Resin) | 3g | ₹150 | Pure Resin |

All five use your real product photography (cropped from the photos you shared) instead of CSS jar mockups, and all are fully purchasable — "Add to Cart" is wired up for every card, not just the flagship. The cart drawer treats each pack size as its own line item (so 100g and 50g don't merge into one row), and shows the real product photo per line.

Since you only gave one MRP per SKU (not a separate sale price), the site shows that single price honestly rather than inventing a fake "was ₹X" strikethrough — the "Save %" badge only appears if `mrp` is ever set higher than `price` in `product-data.php`.

**Note on bulk pricing:** your list also included wholesale carton prices (e.g. the 25-piece box, 6-piece box, 12-piece box). Those aren't shown on this consumer-facing homepage — if you want a wholesale/B2B ordering page later, that's a good candidate for a separate page using the same product-data pattern.

## v4 — Dynamic product pages

Every product now has its own real page at `product.php?id=N` instead of living only as a homepage card:

- **Truly dynamic, not five static files** — there's one `product.php`, and it renders whichever product the URL asks for by reading `?id=` and looking it up in `product-data.php`. Change a price or description in one place and every place that product appears (homepage card, its own page, related-product listings) updates together.
- **Size switcher** — the 4 Bandhani Hing Churan pack sizes share a `group` in the data, so each one's page shows pill buttons for the others (100g/50g/10g/5g). Clicking a size navigates to that product's own page with the correct price, image, and breadcrumb — this is genuinely server-rendered per URL, not a client-side illusion.
- **Ingredients & specs** — pulled from real label data (Asafoetida, Gum Arabic, Refined Wheat Flour, Edible Oil for the churan; pure resin for the Hing Dana), plus weight, storage, and usage notes.
- **"You Might Also Like"** — cross-sells the other product line at the bottom of each page, reusing the exact same card component as the homepage grid.
- **Full cart + breadcrumb + nav** — the mini-cart drawer, tilt-hover on the product photo, and header/footer all carry over from the homepage, so it feels like one site rather than a bolted-on page. Section links in the nav (Shop, Benefits, etc.) correctly point back to `index.php#section` when you're not already on the homepage.
- **Graceful 404** — an invalid or missing `?id=` shows a proper "we couldn't find that jar" page with a link back to the shop, instead of a broken PHP page.

## v5 — Zoom effect + premium polish on product pages

- **Hover magnifier (desktop)** — on wide screens with a mouse, hovering over the product photo shows a lens that follows your cursor and a larger zoomed panel beside it, so people can actually inspect the label detail before buying. Disabled automatically on touch devices, narrow screens (where the side panel wouldn't fit), and for anyone with reduced-motion preferences set.
- **Click-to-zoom lightbox (everywhere)** — a round zoom button sits on every product photo; clicking it (or the image itself, on desktop) opens a fullscreen view of the jar. This is what phone and tablet visitors get instead of the hover lens, since hovering isn't a thing on touch. Closes via the × button, clicking outside the image, or the Escape key.
- **Variant pills now show price** — each size option displays both the weight and its price, so switching sizes is more informative at a glance.
- **In-stock indicator** — a small green dot + "In Stock — ships in 1–2 days" line under the price, a standard trust signal real e-commerce sites use.
- **Icons on the spec list** — Net Weight, Storage, Best Used In, and Made In each now have a small line icon matching the rest of the site's icon style, instead of plain text rows.

One bug caught and fixed during testing: the zoomed-in result panel is a purely visual overlay, but it was rendered on top of the zoom button itself and silently blocked clicks on it while hovering. Fixed by making that panel `pointer-events: none` so clicks always pass through to whatever's underneath.

## v6 — Checkout, customer accounts & admin panel

The site is now a fully working (Cash on Delivery only) store, end to end: browse → add to cart → checkout → place order → track it, plus a separate admin panel to manage products and orders.

Most of the underlying page logic already existed from earlier work, but the files were all sitting flat at the project root with paths that assumed a folder structure that didn't actually exist yet, a few pages were missing outright, and there was **no CSS at all** for checkout, login, the account dashboard, or the admin panel. This pass reorganized everything into the right folders, wrote the missing pieces, added the missing styling, and tested the whole flow against a real MySQL database.

### File structure (final)

```
includes/          db.php, cart.php, auth.php (customer), orders.php,
                    product-data.php, header.php, footer.php, helpers.php
api/                cart-add.php, cart-get.php, cart-update.php, cart-remove.php
account/            login.php, register.php, logout.php, dashboard.php, order.php
admin/              index.php, login.php, logout.php, orders.php, order-view.php,
                    products.php, product-form.php, product-delete.php
admin/includes/     auth.php (admin), layout-head.php, layout-foot.php
sql/schema.sql
images/products/    uploaded product images land here (must be writable)
```

### Checkout (COD only)

- `checkout.php` redisplays with inline validation errors and re-filled values if a submission fails, instead of losing everything.
- `place-order.php` validates the shipping form server-side, checks the CSRF token, saves the order + line items (snapshotted, so editing a product later never rewrites order history), clears the cart, and redirects to the confirmation page. Only Cash on Delivery is accepted right now — the UPI/Cards option in the payment block stays visibly disabled until that's built.
- `order-confirmation.php` is the thank-you page, keyed by the order's public `order_number` (e.g. `GRD-7BE87C`), showing the items, totals, and shipping address, with a link into the dashboard if the buyer is logged in.
- `includes/orders.php` holds every order-related DB query (create, fetch by number/id, fetch a customer's orders, admin listing/status update, dashboard stats) so checkout, the account dashboard, and the admin panel all read/write orders the same way.

### Customer accounts (`account/`)

- **New:** `includes/auth.php` — this was referenced everywhere (checkout, login, dashboard) but didn't exist yet. It now has `grd_login_customer()`, `grd_register_customer()`, `grd_current_customer()`, `grd_require_login()`, and `grd_logout_customer()`, all backed by the `customers` table.
- `login.php`, `register.php`, `logout.php` — standard email/password auth. Supports `?redirect=` so logging in mid-checkout returns you to `checkout.php`.
- `dashboard.php` lists every order tied to the logged-in customer, matched by `customer_id` **or** by email — so a guest order placed before creating an account still shows up once they register with the same email.
- `order.php?id=` — single order detail with a 4-stage status tracker (Placed → Processing → Shipped → Delivered, or a Cancelled state). Looked up by id **and** ownership (customer_id/email match) so one customer can never view another's order by guessing an id.

### Admin panel (`admin/`)

Completely separate login/session from customer accounts (`admin_users` table, its own `admin_id` session key) — a customer account can never gain admin access, and vice versa.

- **New:** `admin/login.php`, `admin/logout.php` — didn't exist before, even though the auth functions (`grd_admin_login()` etc.) were already written and every admin page already redirected to `login.php` when logged out.
- **New:** `admin/index.php` — the dashboard: order count, pending count, revenue, active product count, customer count, plus the 8 most recent orders.
- **New:** `admin/orders.php` — the order list, filterable by status (Placed/Processing/Shipped/Delivered/Cancelled).
- `admin/order-view.php` — line items, shipping address, and a status dropdown that updates immediately and is what the customer sees on their own dashboard.
- `admin/products.php` (list, with active/hidden status and quick edit/delete), `admin/product-form.php` (shared add/edit form — handles image upload with type/size validation, or keeps the existing image on edit), `admin/product-delete.php`. Products with the same "Product Group" value show up as size-switcher variants on their product page, same as the original 4 Bandhani Hing Churan sizes.
- Shared admin chrome (sidebar nav, topbar, CSRF helper) lives in `admin/includes/auth.php` and `admin/includes/layout-head.php`/`layout-foot.php`.
- **Default seeded login:** `admin@grdhing.com` / `admin123` (from `sql/schema.sql`) — **change this password after first login**; there's no self-service "change password" screen yet, so update it directly in the `admin_users` table for now.

### Styling

`css/style.css` had zero rules for any of this (checkout, cart totals, auth cards, the account dashboard, order status badges/tracker, or the entire admin panel) — it was only ever styled through the homepage/product-page work. Added a full new section covering all of the above, using the same design tokens (colors, fonts, radius, shadows) as the rest of the site, plus responsive breakpoints for the admin sidebar, checkout grid, and account layout on mobile.

### Notes

- Uploaded product images are saved to `images/products/`; make sure that folder is writable by PHP on your host.
- CSRF tokens are checked on every state-changing form (checkout, login, register, admin product save/delete, admin status update).
- **Tested end-to-end** against a real MariaDB instance running `sql/schema.sql` unmodified: add to cart → update/remove cart lines → checkout → order saved in DB → confirmation page → order visible on the customer dashboard (including a guest order linking up after registering with the same email) → order visible in the admin panel → status change in admin reflected instantly on the customer's tracker → admin product add (with image upload) appears live on the homepage → admin product delete removes it. Every PHP file also passes `php -l` and every `require`/`include` path was verified to resolve.
- Run locally with PHP's built-in server, e.g.:
  ```
  GRD_DB_HOST=localhost GRD_DB_NAME=grd_hing GRD_DB_USER=grd_app GRD_DB_PASS=grd_app_pass php -S localhost:8000
  ```
  (or set the same values as real environment variables / edit the defaults in `includes/db.php`).
