<?php
/**
 * Dummy product data for G.R.D Hing homepage.
 * TODO: replace with MySQL query once the admin panel + DB are built.
 * Expected table: products(id, name, tagline, price, mrp, weight, jar_color, badge, is_real_photo, image_path)
 */

function grd_get_products() {
    return [
        [
            'id'        => 1,
            'name'      => 'Bandhani Hing Churan',
            'tagline'   => 'Our signature blend — strong, pure, unmistakably Bandhani.',
            'price'     => 189,
            'mrp'       => 249,
            'weight'    => '100g Jar',
            'badge'     => 'Bestseller',
            'photo'     => true,
            'image'     => 'images/jar-hero.png',
            'jar_color' => '#4A2C1D',
        ],
        [
            'id'        => 2,
            'name'      => 'Lakadong Turmeric Powder',
            'tagline'   => 'High-curcumin turmeric, stone-ground in small batches.',
            'price'     => 149,
            'mrp'       => 189,
            'weight'    => '200g Jar',
            'badge'     => 'Coming Soon',
            'photo'     => false,
            'jar_color' => '#C1521C',
            'label_text'=> 'HALDI',
        ],
        [
            'id'        => 3,
            'name'      => 'Guntur Red Chilli Powder',
            'tagline'   => 'Deep colour, balanced heat, no artificial dye.',
            'price'     => 159,
            'mrp'       => 199,
            'weight'    => '200g Jar',
            'badge'     => 'Coming Soon',
            'photo'     => false,
            'jar_color' => '#8E2A1F',
            'label_text'=> 'MIRCHI',
        ],
        [
            'id'        => 4,
            'name'      => 'Whole Coriander Powder',
            'tagline'   => 'Sun-dried coriander, freshly milled for aroma.',
            'price'     => 99,
            'mrp'       => 129,
            'weight'    => '200g Jar',
            'badge'     => 'Coming Soon',
            'photo'     => false,
            'jar_color' => '#6B7B3A',
            'label_text'=> 'DHANIA',
        ],
        [
            'id'        => 5,
            'name'      => 'Kayam Garam Masala',
            'tagline'   => 'A 12-spice house blend, roasted and ground fresh.',
            'price'     => 179,
            'mrp'       => 219,
            'weight'    => '100g Jar',
            'badge'     => 'Coming Soon',
            'photo'     => false,
            'jar_color' => '#5C3A22',
            'label_text'=> 'GARAM MASALA',
        ],
    ];
}
