-- =========================================================
-- G.R.D Hing — Database Schema
-- Run this once against a fresh database to set everything up:
--   mysql -u root -p grd_hing < sql/schema.sql
-- (create the database first: CREATE DATABASE grd_hing;)
-- =========================================================

SET NAMES utf8mb4;

-- ---------------------------------------------------------
-- Products — replaces the old hardcoded array in product-data.php.
-- 'product_group' ties pack-size variants together (e.g. the 100g/
-- 50g/10g/5g Bandhani Hing Churan jars) so the product page can offer
-- a size switcher; a product with a unique group just won't show one.
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS products (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    slug           VARCHAR(191) NOT NULL UNIQUE,
    product_group  VARCHAR(100) NOT NULL,
    name           VARCHAR(191) NOT NULL,
    tagline        VARCHAR(255) NOT NULL DEFAULT '',
    description    TEXT NOT NULL,
    price          DECIMAL(10,2) NOT NULL,
    mrp            DECIMAL(10,2) NOT NULL,
    weight         VARCHAR(100) NOT NULL,
    badge          VARCHAR(100) NOT NULL DEFAULT '',
    image          VARCHAR(255) NOT NULL,
    jar_color      VARCHAR(20)  NOT NULL DEFAULT '#4A2C1D',
    ingredients    TEXT NOT NULL,   -- JSON array of strings, always set explicitly by the app
    is_active      TINYINT(1) NOT NULL DEFAULT 1,
    created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Customers — for the login-based user dashboard.
-- Orders can also be placed as a guest (customer_id NULL in orders),
-- and the dashboard matches on email too so a guest order still shows
-- up once that person creates/logs into an account with the same email.
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS customers (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(191) NOT NULL,
    email         VARCHAR(191) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone         VARCHAR(20)  NOT NULL DEFAULT '',
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Admin users — deliberately a separate table from customers so a
-- customer account can never accidentally get admin access.
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS admin_users (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(191) NOT NULL,
    email         VARCHAR(191) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Orders — customer_id nullable to allow guest checkout.
-- Shipping details are stored directly on the order (not a separate
-- address table) since that's all this MVP needs.
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS orders (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    order_number   VARCHAR(20) NOT NULL UNIQUE,
    customer_id    INT NULL,
    status         ENUM('Placed','Processing','Shipped','Delivered','Cancelled') NOT NULL DEFAULT 'Placed',
    payment_method VARCHAR(20) NOT NULL DEFAULT 'cod',
    subtotal       DECIMAL(10,2) NOT NULL,
    shipping_fee   DECIMAL(10,2) NOT NULL DEFAULT 0,
    total          DECIMAL(10,2) NOT NULL,
    name           VARCHAR(191) NOT NULL,
    email          VARCHAR(191) NOT NULL,
    phone          VARCHAR(20)  NOT NULL,
    address_line1  VARCHAR(255) NOT NULL,
    address_line2  VARCHAR(255) NOT NULL DEFAULT '',
    city           VARCHAR(100) NOT NULL,
    state          VARCHAR(100) NOT NULL,
    pincode        VARCHAR(12)  NOT NULL,
    notes          VARCHAR(500) NOT NULL DEFAULT '',
    created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Order line items — snapshots product name/weight/price at the time
-- of purchase, so editing a product later never rewrites history.
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS order_items (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    order_id     INT NOT NULL,
    product_id   INT NULL,
    product_name VARCHAR(191) NOT NULL,
    weight       VARCHAR(100) NOT NULL,
    image        VARCHAR(255) NOT NULL DEFAULT '',
    price        DECIMAL(10,2) NOT NULL,
    qty          INT NOT NULL,
    line_total   DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- Seed data — the 5 real products already on the site
-- =========================================================
INSERT INTO products (slug, product_group, name, tagline, description, price, mrp, weight, badge, image, jar_color, ingredients) VALUES
('bandhani-hing-churan-100g', 'bandhani-churan', 'Bandhani Hing Churan',
 'Our signature jar — strong, pure, unmistakably Bandhani.',
 'This is the jar that started G.R.D. A full 100g of Bandhani-style hing churan, blended by hand in small batches using the traditional Rajasthani method, then packed while the aroma is still at its peak. Built for a kitchen that cooks daily — one pinch in hot ghee is enough to carry an entire pot of dal.',
 455.00, 455.00, '100g Jar', 'Bestseller', 'images/jar-hero.webp', '#4A2C1D',
 '["Asafoetida","Gum Arabic","Refined Wheat Flour","Edible Oil"]'),

('bandhani-hing-churan-50g', 'bandhani-churan', 'Bandhani Hing Churan',
 'The everyday jar for a kitchen that cooks daily.',
 'A 50g jar sized for regular use — enough to last a family kitchen through weeks of dal, sabzi and tadka without running out mid-recipe. Same small-batch Bandhani blend as our flagship jar, just in a size built for the everyday cook.',
 235.00, 235.00, '50g Jar', 'Value Pack', 'images/hing-50g.webp', '#8E2A1F',
 '["Asafoetida","Gum Arabic","Refined Wheat Flour","Edible Oil"]'),

('bandhani-hing-churan-10g', 'bandhani-churan', 'Bandhani Hing Churan',
 'Our most-gifted size — easy to carry, easy to share.',
 'Our most-gifted size. Small enough to slip into a gift hamper or care package, big enough to actually be useful in someone''s kitchen — this 10g jar is how a lot of people get introduced to real Bandhani hing.',
 68.00, 68.00, '10g Jar', 'Most Popular', 'images/hing-10g.webp', '#4A2C1D',
 '["Asafoetida","Gum Arabic","Refined Wheat Flour","Edible Oil"]'),

('bandhani-hing-churan-5g', 'bandhani-churan', 'Bandhani Hing Churan',
 'Never used real hing before? Start here.',
 'Never used real Bandhani hing before? This 5g trial jar is the cheapest way to find out what a proper pinch actually does to a dal — no commitment, just enough to test it in your own kitchen before you buy a bigger jar.',
 36.00, 36.00, '5g Jar', 'Trial Pack', 'images/hing-5g.webp', '#8E2A1F',
 '["Asafoetida","Gum Arabic","Refined Wheat Flour","Edible Oil"]'),

('hing-dana-whole-resin-3g', 'hing-dana', 'Hing Dana (Whole Resin)',
 'Uncut resin for those who grind their own at home.',
 'Whole, uncut hing resin for cooks who prefer to grind their own rather than buy it pre-blended. No gum arabic, no wheat flour — just the raw resin, for a stronger and more concentrated hit than churan when you break off and crush a piece yourself.',
 150.00, 150.00, '3g Pack', 'Pure Resin', 'images/hing-dana.webp', '#B8791F',
 '["Asafoetida Resin (100%)"]');

-- =========================================================
-- Default admin login — CHANGE THIS PASSWORD after first login.
-- Email: admin@grdhing.com   Password: admin123
-- (hash below is password_hash('admin123', PASSWORD_DEFAULT))
-- =========================================================
INSERT INTO admin_users (name, email, password_hash) VALUES
('G.R.D Admin', 'admin@grdhing.com', '$2y$10$jPrWiMHNV5TnRt2QQGvkVeq8K.VZfi9JEOuD0roSlemS5z6hBupsK');
