<?php
require_once __DIR__ . '/includes/product-data.php';
require_once __DIR__ . '/includes/helpers.php';

$products = grd_get_products();
$flagship = null;
foreach ($products as $p) {
    if ($p['badge'] === 'Bestseller') { $flagship = $p; break; }
}
if (!$flagship && $products) { $flagship = $products[0]; }

$page_title = 'G.R.D Hing — Bandhani Hing Churan | Pure, Plant-Based Asafoetida';
$page_description = 'G.R.D Hing brings authentic Bandhani hing churan to your kitchen — plant-based, additive-free, and ground for real Indian cooking.';
require __DIR__ . '/includes/header.php';
?>

<main id="top">

  <!-- ================= HERO ================= -->
  <section class="hero">
    <div class="container">
      <div class="hero-copy reveal">
        <span class="eyebrow">Panditji Hing Kayam</span>
        <h1>Real <em>Bandhani</em> Hing, Ground The Traditional Way</h1>
        <p class="lede">Pure, plant-based asafoetida — hand-blended in small batches using a Rajasthani recipe passed down through generations. One pinch is all a proper dal ever needed.</p>
        <div class="hero-cta">
          <a href="#shop" class="btn btn-primary">Shop The Collection</a>
          <a href="#story" class="btn btn-ghost">Our Story</a>
        </div>
        <div class="hero-stats">
          <div class="stat"><b>100%</b><span>Plant-Based</span></div>
          <div class="stat"><b>0</b><span>Artificial Additives</span></div>
          <div class="stat"><b>5000+</b><span>Kitchens Trust Us</span></div>
        </div>
      </div>
      <div class="hero-visual reveal">
        <div class="hero-jar-wrap">
          <img src="images/jar-hero.png" alt="G.R.D Hing — Bandhani Hing Churan jar">
        </div>
        <div class="hero-stamp">
          <?php echo grd_seal('<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M2 12h20"/></svg>'); ?>
        </div>
        <div class="powder-scatter" aria-hidden="true">
          <span style="width:10px;height:10px;top:14%;left:12%;"></span>
          <span style="width:6px;height:6px;top:68%;left:8%;"></span>
          <span style="width:14px;height:14px;top:78%;left:80%;"></span>
          <span style="width:8px;height:8px;top:22%;left:86%;"></span>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= TRUST STRIP ================= -->
  <section class="trust">
    <div class="container trust-grid">
      <div class="trust-item reveal">
        <?php echo grd_seal('<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 2 7l10 5 10-5-10-5Z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>', 5); ?>
        <div><h4>Small-Batch Blended</h4><p>Ground fresh in limited batches, never mass-produced.</p></div>
      </div>
      <div class="trust-item reveal">
        <?php echo grd_seal('<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>', 5); ?>
        <div><h4>No Artificial Additives</h4><p>Just resin, gum arabic, wheat flour, and edible oil.</p></div>
      </div>
      <div class="trust-item reveal">
        <?php echo grd_seal('<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M18.7 8 12 14.7l-3-3L4 16.7"/></svg>', 5); ?>
        <div><h4>Strong, Honest Aroma</h4><p>A single pinch carries an entire pot of dal.</p></div>
      </div>
      <div class="trust-item reveal">
        <?php echo grd_seal('<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8Z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>', 5); ?>
        <div><h4>Delivered To Your Door</h4><p>Cash on delivery, shipped straight from Delhi NCR.</p></div>
      </div>
    </div>
  </section>

  <!-- ================= SHOP ================= -->
  <section class="section" id="shop">
    <div class="container">
      <div class="section-head reveal">
        <span class="eyebrow">Shop The Collection</span>
        <h2>Every Jar Ground With The Same Care</h2>
        <p>From the everyday kitchen jar to the smallest trial pack — every size is the same Bandhani-style blend, just sized differently.</p>
      </div>

      <?php if ($flagship): ?>
      <div class="featured-card reveal">
        <div class="featured-media">
          <span class="ribbon">Bestseller</span>
          <img src="<?php echo htmlspecialchars($flagship['image']); ?>" alt="<?php echo htmlspecialchars($flagship['name']); ?>">
        </div>
        <div class="featured-body">
          <span class="eyebrow">Flagship Jar</span>
          <a href="product.php?id=<?php echo (int)$flagship['id']; ?>" class="featured-title-link"><h3><?php echo htmlspecialchars($flagship['name']); ?> — <?php echo htmlspecialchars($flagship['weight']); ?></h3></a>
          <p class="desc"><?php echo htmlspecialchars($flagship['tagline']); ?></p>
          <div class="price-row">
            <span class="price">₹<?php echo number_format($flagship['price'], 0); ?></span>
            <?php if ($flagship['mrp'] > $flagship['price']): ?>
              <span class="mrp">₹<?php echo number_format($flagship['mrp'], 0); ?></span>
              <span class="save">Save <?php echo round((1 - $flagship['price'] / $flagship['mrp']) * 100); ?>%</span>
            <?php endif; ?>
          </div>
          <div class="featured-actions">
            <button
              class="btn btn-primary add-to-cart"
              data-id="<?php echo (int)$flagship['id']; ?>"
              data-name="<?php echo htmlspecialchars($flagship['name']); ?>"
              data-weight="<?php echo htmlspecialchars($flagship['weight']); ?>"
              data-price="<?php echo $flagship['price']; ?>"
              data-color="<?php echo htmlspecialchars($flagship['jar_color']); ?>"
              data-image="<?php echo htmlspecialchars($flagship['image']); ?>"
            >Add To Cart</button>
            <a href="product.php?id=<?php echo (int)$flagship['id']; ?>" class="btn btn-ghost-dark">View Details</a>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <div class="product-grid">
        <?php foreach ($products as $p): ?>
          <?php echo grd_product_card($p); ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ================= OUR PROCESS ================= -->
  <section class="section" id="story" style="padding-top:0;">
    <div class="container">
      <div class="section-head center reveal">
        <span class="eyebrow">From Resin To Jar</span>
        <h2>Traceable, Start To Finish</h2>
        <p>Real Bandhani hing takes four honest steps — no shortcuts, no fillers beyond what the traditional recipe calls for.</p>
      </div>
      <div class="process-row">
        <div class="process-step reveal">
          <span class="process-num">01</span>
          <h3>Sourcing The Resin</h3>
          <p>Raw asafoetida resin is sourced from trusted growers, chosen for aroma strength and purity.</p>
        </div>
        <div class="process-step reveal">
          <span class="process-num">02</span>
          <h3>Bandhani-Style Blending</h3>
          <p>Blended by hand using the traditional Rajasthani method our family has followed for generations.</p>
        </div>
        <div class="process-step reveal">
          <span class="process-num">03</span>
          <h3>Stone-Grinding</h3>
          <p>Ground slowly on stone to protect the aroma, instead of fast machine milling that burns it off.</p>
        </div>
        <div class="process-step reveal">
          <span class="process-num">04</span>
          <h3>Sealing At Peak Aroma</h3>
          <p>Packed the moment it's ready, so the jar that reaches you smells exactly as strong as the day it was made.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= BENEFITS ================= -->
  <section class="section benefits" id="benefits">
    <div class="container">
      <div class="section-head center reveal">
        <span class="eyebrow">Why Real Hing Matters</span>
        <h2>More Than Just A Kitchen Staple</h2>
        <p>Bandhani hing has been used in Indian kitchens for its flavour and its digestive benefits for centuries.</p>
      </div>
      <div class="benefit-grid">
        <div class="benefit-item reveal">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
          <h4>Aids Digestion</h4>
          <p>A traditional aid for bloating and digestive comfort after a heavy meal.</p>
        </div>
        <div class="benefit-item reveal">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a7 7 0 0 0-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 0 0-7-7Z"/><circle cx="12" cy="9" r="2.5"/></svg>
          <h4>Onion-Garlic Alternative</h4>
          <p>A staple substitute in Jain and satvik cooking for depth without onion or garlic.</p>
        </div>
        <div class="benefit-item reveal">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
          <h4>Goes Further</h4>
          <p>A single pinch in hot ghee is enough to season an entire pot — no over-pouring needed.</p>
        </div>
        <div class="benefit-item reveal">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M8 12h8M12 8v8"/></svg>
          <h4>No Fillers, No Guesswork</h4>
          <p>Just resin, gum arabic, wheat flour, and edible oil — nothing hidden on the label.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= KITCHEN / LIFESTYLE ================= -->
  <section class="section lifestyle" id="kitchen">
    <div class="container">
      <div class="lifestyle-media reveal">
        <img src="images/lifestyle-dal.png" alt="A pot of dal tempered with G.R.D Hing">
      </div>
      <div class="lifestyle-copy reveal">
        <span class="eyebrow">In Your Kitchen</span>
        <h2>Built For Everyday Cooking, Not Just Special Occasions</h2>
        <p>Bandhani hing churan is at its best the moment it hits hot ghee — that first sizzle is the tell. Here's where a pinch makes the biggest difference.</p>
        <ul class="recipe-list">
          <li><span class="mark">✓</span> Tadka for dal, sambar, and kadhi</li>
          <li><span class="mark">✓</span> Sabzi and curry bases, right after the cumin seeds crackle</li>
          <li><span class="mark">✓</span> Pickles and papad, for that unmistakable tang</li>
          <li><span class="mark">✓</span> Jain and satvik dishes, as an onion-garlic replacement</li>
        </ul>
        <a href="#shop" class="btn btn-primary" style="margin-top:30px;">Shop The Collection</a>
      </div>
    </div>
  </section>

  <!-- ================= TESTIMONIALS ================= -->
  <section class="testimonials" id="reviews">
    <div class="container">
      <div class="section-head center reveal">
        <span class="eyebrow">From Real Kitchens</span>
        <h2>What People Are Saying</h2>
      </div>
      <div class="testi-grid">
        <div class="testi-card reveal">
          <?php echo grd_seal('', 4); ?>
          <p>"The aroma hit the moment I opened the jar — nothing like the hing I used to buy from the supermarket. One pinch and the whole kitchen smells like my grandmother's."</p>
          <div class="testi-who">
            <div class="testi-avatar">R</div>
            <div><b>Ritu Malhotra</b><span>Home Cook, Delhi</span></div>
          </div>
        </div>
        <div class="testi-card reveal">
          <?php echo grd_seal('', 4); ?>
          <p>"I switched to G.R.D Hing for our Jain thali prep and haven't looked back. Strong, consistent, and it never clumps in the jar like other brands."</p>
          <div class="testi-who">
            <div class="testi-avatar">S</div>
            <div><b>Suresh Jain</b><span>Restaurant Owner</span></div>
          </div>
        </div>
        <div class="testi-card reveal">
          <?php echo grd_seal('', 4); ?>
          <p>"Ordered the trial pack out of curiosity and ended up buying the 100g jar the same week. This is what real hing is supposed to taste like."</p>
          <div class="testi-who">
            <div class="testi-avatar">P</div>
            <div><b>Priya Nair</b><span>Home Cook, Gurgaon</span></div>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
