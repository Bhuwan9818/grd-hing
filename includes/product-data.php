<?php
/**
 * Real product data for G.R.D Hing — Bandhani Hing Churan across its
 * actual pack sizes, plus whole Hing Dana (resin).
 *
 * Prices are the brand's actual unit MRPs. TODO: replace grd_get_products()
 * with a MySQL query once the admin panel + DB are built; every other
 * function here (grd_get_product, grd_get_related_products, grd_get_variants)
 * just filters this array, so they'll keep working unchanged as long as
 * the query returns rows shaped the same way.
 *
 * 'group' ties together pack-size variants of the same product (e.g. the
 * 100g/50g/10g/5g Bandhani Hing Churan jars) so the product page can offer
 * a size switcher. Products with a unique group (like the Hing Dana) just
 * won't show a switcher.
 *
 * Bulk/wholesale carton pricing (e.g. 25pc, 12pc boxes) isn't shown here
 * since this is the consumer-facing site — worth a separate B2B page later.
 */

function grd_get_products() {
    return [
        [
            'id'          => 1,
            'slug'        => 'bandhani-hing-churan-100g',
            'group'       => 'bandhani-churan',
            'name'        => 'Bandhani Hing Churan',
            'tagline'     => 'Our signature jar — strong, pure, unmistakably Bandhani.',
            'description' => "This is the jar that started G.R.D. A full 100g of Bandhani-style hing churan, blended by hand in small batches using the traditional Rajasthani method, then packed while the aroma is still at its peak. Built for a kitchen that cooks daily — one pinch in hot ghee is enough to carry an entire pot of dal.",
            'price'       => 455,
            'mrp'         => 455,
            'weight'      => '100g Jar',
            'badge'       => 'Bestseller',
            'photo'       => true,
            'image'       => 'images/jar-hero.webp',
            'jar_color'   => '#4A2C1D',
            'ingredients' => ['Asafoetida', 'Gum Arabic', 'Refined Wheat Flour', 'Edible Oil'],
        ],
        [
            'id'          => 2,
            'slug'        => 'bandhani-hing-churan-50g',
            'group'       => 'bandhani-churan',
            'name'        => 'Bandhani Hing Churan',
            'tagline'     => 'The everyday jar for a kitchen that cooks daily.',
            'description' => "A 50g jar sized for regular use — enough to last a family kitchen through weeks of dal, sabzi and tadka without running out mid-recipe. Same small-batch Bandhani blend as our flagship jar, just in a size built for the everyday cook.",
            'price'       => 235,
            'mrp'         => 235,
            'weight'      => '50g Jar',
            'badge'       => 'Value Pack',
            'photo'       => true,
            'image'       => 'images/hing-50g.webp',
            'jar_color'   => '#8E2A1F',
            'ingredients' => ['Asafoetida', 'Gum Arabic', 'Refined Wheat Flour', 'Edible Oil'],
        ],
        [
            'id'          => 3,
            'slug'        => 'bandhani-hing-churan-10g',
            'group'       => 'bandhani-churan',
            'name'        => 'Bandhani Hing Churan',
            'tagline'     => 'Our most-gifted size — easy to carry, easy to share.',
            'description' => "Our most-gifted size. Small enough to slip into a gift hamper or care package, big enough to actually be useful in someone's kitchen — this 10g jar is how a lot of people get introduced to real Bandhani hing.",
            'price'       => 68,
            'mrp'         => 68,
            'weight'      => '10g Jar',
            'badge'       => 'Most Popular',
            'photo'       => true,
            'image'       => 'images/hing-10g.webp',
            'jar_color'   => '#4A2C1D',
            'ingredients' => ['Asafoetida', 'Gum Arabic', 'Refined Wheat Flour', 'Edible Oil'],
        ],
        [
            'id'          => 4,
            'slug'        => 'bandhani-hing-churan-5g',
            'group'       => 'bandhani-churan',
            'name'        => 'Bandhani Hing Churan',
            'tagline'     => 'Never used real hing before? Start here.',
            'description' => "Never used real Bandhani hing before? This 5g trial jar is the cheapest way to find out what a proper pinch actually does to a dal — no commitment, just enough to test it in your own kitchen before you buy a bigger jar.",
            'price'       => 36,
            'mrp'         => 36,
            'weight'      => '5g Jar',
            'badge'       => 'Trial Pack',
            'photo'       => true,
            'image'       => 'images/hing-5g.webp',
            'jar_color'   => '#8E2A1F',
            'ingredients' => ['Asafoetida', 'Gum Arabic', 'Refined Wheat Flour', 'Edible Oil'],
        ],
        [
            'id'          => 5,
            'slug'        => 'hing-dana-whole-resin-3g',
            'group'       => 'hing-dana',
            'name'        => 'Hing Dana (Whole Resin)',
            'tagline'     => 'Uncut resin for those who grind their own at home.',
            'description' => "Whole, uncut hing resin for cooks who prefer to grind their own rather than buy it pre-blended. No gum arabic, no wheat flour — just the raw resin, for a stronger and more concentrated hit than churan when you break off and crush a piece yourself.",
            'price'       => 150,
            'mrp'         => 150,
            'weight'      => '3g Pack',
            'badge'       => 'Pure Resin',
            'photo'       => true,
            'image'       => 'images/hing-dana.webp',
            'jar_color'   => '#B8791F',
            'ingredients' => ['Asafoetida Resin (100%)'],
        ],
    ];
}

/** Find a single product by id. Returns null if not found. */
function grd_get_product($id) {
    foreach (grd_get_products() as $p) {
        if ((string)$p['id'] === (string)$id) return $p;
    }
    return null;
}

/** All pack-size variants sharing a product's group, sorted by price desc. */
function grd_get_variants($product) {
    $variants = array_filter(grd_get_products(), function ($p) use ($product) {
        return $p['group'] === $product['group'];
    });
    usort($variants, function ($a, $b) { return $b['price'] <=> $a['price']; });
    return array_values($variants);
}

/** A handful of other products to cross-sell, excluding the current one and its own variants. */
function grd_get_related_products($product, $limit = 4) {
    $related = array_filter(grd_get_products(), function ($p) use ($product) {
        return $p['group'] !== $product['group'];
    });
    return array_slice(array_values($related), 0, $limit);
}
