<?php
require_once __DIR__ . '/includes/product-data.php';
require_once __DIR__ . '/includes/helpers.php';
$products = grd_get_products();
$featured = $products[0];
$others   = array_slice($products, 1);
require __DIR__ . '/includes/header.php';
?>

<main id="top">

  <!-- ============================= HERO ============================= -->
  <section class="hero">
    <div class="powder-scatter" aria-hidden="true">
      <span style="width:120px;height:120px;top:6%;left:2%;"></span>
      <span style="width:60px;height:60px;top:70%;left:6%;"></span>
      <span style="width:40px;height:40px;top:40%;left:1%;"></span>
    </div>
    <div class="container">
      <div class="hero-copy reveal">
        <span class="eyebrow">Authentic Bandhani Hing</span>
        <h1>One Pinch Of <em>Real</em> Hing Changes The Dish</h1>
        <p class="lede">G.R.D Bandhani Hing Churan is milled the traditional way — pure, plant-based, and strong enough that a single pinch carries the whole tadka. No fillers, no shortcuts.</p>
        <div class="hero-cta">
          <a href="#shop" class="btn btn-primary">Shop Hing Churan</a>
          <a href="#kitchen" class="btn btn-ghost">See It In A Dal</a>
        </div>
        <div class="hero-stats">
          <div class="stat"><b>100%</b><span>Plant-Based</span></div>
          <div class="stat"><b>Zero</b><span>Artificial Fillers</span></div>
          <div class="stat"><b>1 Pinch</b><span>Is Enough</span></div>
        </div>
      </div>
      <div class="hero-visual reveal">
        <div class="hero-jar-wrap">
          <img src="images/jar-hero.png" alt="G.R.D Bandhani Hing Churan jar, 100g">
          <div class="hero-stamp"><?php echo grd_seal('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l2.4 6.6H21l-5.4 4 2.1 6.6L12 15.8 6.3 19.2l2.1-6.6L3 8.6h6.6z"/></svg>', 8); ?></div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================= TRUST STRIP ============================= -->
  <section class="trust">
    <div class="container trust-grid stagger">
      <div class="trust-item reveal">
        <?php echo grd_seal('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4.5 8-11.8A8 8 0 0 0 4 10.2C4 17.5 12 22 12 22z"/><path d="M12 15a4 4 0 0 0 4-4c-2.5 0-4 1-4 4z"/></svg>'); ?>
        <div><h4>Pure &amp; Plant-Based</h4><p>No fillers, only real hing resin.</p></div>
      </div>
      <div class="trust-item reveal">
        <?php echo grd_seal('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3h6l1 5-4 4-4-4z"/><path d="M7 12l-3 7a2 2 0 0 0 2 3h12a2 2 0 0 0 2-3l-3-7"/><line x1="16" y1="17" x2="16.01" y2="17"/></svg>'); ?>
        <div><h4>No Artificial Additives</h4><p>Nothing synthetic, ever added.</p></div>
      </div>
      <div class="trust-item reveal">
        <?php echo grd_seal('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18c-1-3 1-4 0-7-1 3-3 3-3 6a3 3 0 0 0 6 0c0-4-3-4-2-9 2 2 4 5 4 9a5 5 0 0 1-5 5"/></svg>'); ?>
        <div><h4>Strong, Honest Aroma</h4><p>A pinch carries the whole pot.</p></div>
      </div>
      <div class="trust-item reveal">
        <?php echo grd_seal('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19h16"/><path d="M6 19V9a2 2 0 0 1 2-2h1"/><path d="M18 19V9a2 2 0 0 0-2-2h-1"/><path d="M9 7V5a3 3 0 0 1 6 0v2"/></svg>'); ?>
        <div><h4>Built For Indian Kitchens</h4><p>Balanced for dal, sabzi &amp; tadka.</p></div>
      </div>
    </div>
  </section>

  <!-- ============================= SHOP ============================= -->
  <section class="section" id="shop">
    <div class="container">
      <div class="section-head reveal">
        <span class="eyebrow">Our Collection</span>
        <h2>Every Jar, Ground With Intent</h2>
        <p>Hing Churan leads the shelf today — turmeric, chilli, coriander and our house garam masala are on their way, made with the same standard.</p>
      </div>

      <div class="featured-card reveal">
        <div class="featured-media">
          <span class="ribbon"><?php echo htmlspecialchars($featured['badge']); ?></span>
          <img src="<?php echo htmlspecialchars($featured['image']); ?>" alt="<?php echo htmlspecialchars($featured['name']); ?> jar">
        </div>
        <div class="featured-body">
          <span class="eyebrow">Flagship Product</span>
          <h3><?php echo htmlspecialchars($featured['name']); ?></h3>
          <p class="desc"><?php echo htmlspecialchars($featured['tagline']); ?> Hand-blended in small batches and packed while the aroma is at its peak — this is the jar that started G.R.D.</p>
          <div class="price-row">
            <span class="price">₹<?php echo $featured['price']; ?></span>
            <span class="mrp">₹<?php echo $featured['mrp']; ?></span>
            <span class="save">Save <?php echo round((1 - $featured['price']/$featured['mrp'])*100); ?>%</span>
          </div>
          <div class="featured-actions">
            <div class="qty-select">
              <button type="button" class="qty-minus" aria-label="Decrease quantity">–</button>
              <span class="qty-val">1</span>
              <button type="button" class="qty-plus" aria-label="Increase quantity">+</button>
            </div>
            <button class="btn btn-primary add-to-cart" data-name="<?php echo htmlspecialchars($featured['name']); ?>" data-price="<?php echo $featured['price']; ?>" data-color="<?php echo htmlspecialchars($featured['jar_color']); ?>" data-weight="<?php echo htmlspecialchars($featured['weight']); ?>">Add To Cart</button>
          </div>
        </div>
      </div>

      <div class="product-grid stagger">
        <?php foreach ($others as $p): ?>
        <div class="product-card reveal">
          <span class="tag"><?php echo htmlspecialchars($p['badge']); ?></span>
          <?php echo grd_jar_illustration($p['jar_color'], $p['label_text']); ?>
          <h4><?php echo htmlspecialchars($p['name']); ?></h4>
          <p class="p-tag"><?php echo htmlspecialchars($p['tagline']); ?></p>
          <p class="p-price">₹<?php echo $p['price']; ?> <span style="font-size:12px;color:var(--ink-soft);font-family:var(--body);">/ <?php echo htmlspecialchars($p['weight']); ?></span></p>
          <button class="btn-mini" disabled>Notify Me</button>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============================= PROVENANCE / PROCESS ============================= -->
  <section class="section provenance" id="story">
    <div class="container">
      <div class="section-head reveal">
        <span class="eyebrow">Our Process</span>
        <h2>From Resin To Jar, Nothing Rushed</h2>
        <p>Bandhani hing isn't a shortcut spice — it's a craft passed down through generations of blenders. Here's what actually goes into the jar.</p>
      </div>

      <div class="process-row stagger">
        <div class="process-step reveal">
          <span class="process-num">01</span>
          <h3>Sourced At The Root</h3>
          <p>Resin tapped from Ferula roots grown in the cold highlands where the plant's aroma is strongest — the only place real hing comes from.</p>
        </div>
        <div class="process-step reveal">
          <span class="process-num">02</span>
          <h3>Cut The Bandhani Way</h3>
          <p>Aged resin is blended by hand using the traditional Rajasthani method — just enough carrier, never stretched further to cut cost.</p>
        </div>
        <div class="process-step reveal">
          <span class="process-num">03</span>
          <h3>Stone-Ground, Small Batch</h3>
          <p>Milled in batches small enough that no jar sits waiting in a warehouse losing its aroma before it reaches you.</p>
        </div>
        <div class="process-step reveal">
          <span class="process-num">04</span>
          <h3>Sealed At Peak Aroma</h3>
          <p>Packed within days of grinding, so what opens in your kitchen is as strong as the day it was milled.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================= BENEFITS ============================= -->
  <section class="section benefits" id="benefits">
    <div class="container">
      <div class="section-head center reveal">
        <span class="eyebrow">Why Hing, Why G.R.D</span>
        <h2>Good For The Pot. Good For The Gut.</h2>
        <p>Hing has been a staple of Indian digestion-friendly cooking for generations — here's what a pinch is actually doing.</p>
      </div>
      <div class="benefit-grid stagger">
        <div class="benefit-item reveal">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M8 3a5 5 0 0 0-5 5c0 6 5 8 9 13 4-5 9-7 9-13a5 5 0 0 0-8-4"/></svg>
          <h4>Improves Digestive Health</h4>
          <p>A traditional aid for heavy, oily meals.</p>
        </div>
        <div class="benefit-item reveal">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M9 3h6l1 5-4 4-4-4z"/><path d="M7 12l-3 7a2 2 0 0 0 2 3h12a2 2 0 0 0 2-3l-3-7"/></svg>
          <h4>Relieves Gas &amp; Acidity</h4>
          <p>Used for generations to settle the stomach.</p>
        </div>
        <div class="benefit-item reveal">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 22s8-4.5 8-11.8A8 8 0 0 0 4 10.2C4 17.5 12 22 12 22z"/></svg>
          <h4>Natural Antibacterial Properties</h4>
          <p>Prized in Ayurveda for its resin compounds.</p>
        </div>
        <div class="benefit-item reveal">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 12a8 8 0 1 0 16 0 8 8 0 0 0-16 0z"/><path d="M12 8v4l3 2"/></svg>
          <h4>Supports Gut Health</h4>
          <p>A gentle, everyday aid to digestion.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================= LIFESTYLE / KITCHEN ============================= -->
  <section class="section lifestyle" id="kitchen">
    <div class="container">
      <div class="lifestyle-media reveal">
        <img src="images/lifestyle-dal.png" alt="Dal, roti and rice made with G.R.D Hing Churan">
      </div>
      <div class="lifestyle-copy reveal">
        <span class="eyebrow">From Our Kitchen</span>
        <h2>The Same Pinch, Every Dal, Every Time</h2>
        <p>Drop it into hot ghee before the dal hits the pan and the whole kitchen knows dinner's close. That's the test we hold every batch to.</p>
        <ul class="recipe-list">
          <li><span class="mark">1</span> Heat ghee, add cumin till it crackles.</li>
          <li><span class="mark">2</span> Add a pinch of Bandhani Hing Churan off the heat.</li>
          <li><span class="mark">3</span> Pour over simmered dal and finish with coriander.</li>
        </ul>
        <div class="hero-cta" style="margin-top:30px;">
          <a href="#shop" class="btn btn-primary">Get The Jar</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================= TESTIMONIALS ============================= -->
  <section class="testimonials" id="reviews">
    <div class="container">
      <div class="section-head center reveal">
        <span class="eyebrow">From Real Kitchens</span>
        <h2>What Home Cooks Are Saying</h2>
      </div>
      <div class="testi-grid stagger">
        <div class="testi-card reveal">
          <?php echo grd_seal('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7.5 9.5C7.5 7 9 5.5 11.5 5.5v2c-1.4 0-2 .7-2 1.8h2V13H7.5V9.5zm8 0c0-2.5 1.5-4 4-4v2c-1.4 0-2 .7-2 1.8h2V13h-4V9.5z"/></svg>', 4); ?>
          <p>"The aroma hits the second the lid opens. I've cut the quantity I use in half compared to my old brand."</p>
          <div class="testi-who">
            <div class="testi-avatar">R</div>
            <div><b>Radhika M.</b><span>Home Cook, Jaipur</span></div>
          </div>
        </div>
        <div class="testi-card reveal">
          <?php echo grd_seal('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7.5 9.5C7.5 7 9 5.5 11.5 5.5v2c-1.4 0-2 .7-2 1.8h2V13H7.5V9.5zm8 0c0-2.5 1.5-4 4-4v2c-1.4 0-2 .7-2 1.8h2V13h-4V9.5z"/></svg>', 4); ?>
          <p>"No weird chemical smell like some hing I've bought before. This one actually tastes like the hing my grandmother used."</p>
          <div class="testi-who">
            <div class="testi-avatar">S</div>
            <div><b>Sanjay T.</b><span>Home Cook, Delhi</span></div>
          </div>
        </div>
        <div class="testi-card reveal">
          <?php echo grd_seal('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7.5 9.5C7.5 7 9 5.5 11.5 5.5v2c-1.4 0-2 .7-2 1.8h2V13H7.5V9.5zm8 0c0-2.5 1.5-4 4-4v2c-1.4 0-2 .7-2 1.8h2V13h-4V9.5z"/></svg>', 4); ?>
          <p>"Ordered it for my mother-in-law's dal recipe and now it's the only hing allowed in the house."</p>
          <div class="testi-who">
            <div class="testi-avatar">P</div>
            <div><b>Priya K.</b><span>Home Cook, Lucknow</span></div>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
