<?php
/**
 * Renders the signature "seal" badge — a dotted ring with diamond
 * studs echoing the real G.R.D bullock-cart emblem. Reused as the
 * one consistent motif across hero, trust strip, and testimonials.
 *
 * @param string $icon_svg  Inner SVG markup (viewBox 0 0 24 24 recommended)
 * @param int    $studs     Number of diamond studs around the ring
 */
function grd_seal($icon_svg = '', $studs = 6) {
    $out = '<div class="seal">';
    for ($i = 0; $i < $studs; $i++) {
        $angle = ($i / $studs) * 360;
        $out .= '<span class="seal-diamond" style="transform:rotate(' . $angle . 'deg) translate(0,calc(-1 * (var(--seal-size) / 2 - 7px))) rotate(-' . $angle . 'deg);"></span>';
    }
    if ($icon_svg) {
        $out .= '<div class="seal-icon">' . $icon_svg . '</div>';
    }
    $out .= '</div>';
    return $out;
}

/**
 * Renders a small CSS/HTML jar illustration for dummy products that
 * don't yet have real product photography — keeps every card visually
 * consistent with the real jar's silhouette (white body, coloured
 * label, gold-toned lid) until real photos are uploaded.
 */
function grd_jar_illustration($jar_color, $label_text) {
    ob_start();
    ?>
    <div class="jar-illustration" aria-hidden="true">
      <div class="jar-lid" style="background:linear-gradient(180deg,#E7BD6B,var(--turmeric-dp));"></div>
      <div class="jar-body">
        <div class="jar-label" style="background:<?php echo htmlspecialchars($jar_color); ?>;">
          <?php echo htmlspecialchars($label_text); ?>
        </div>
      </div>
    </div>
    <?php
    return ob_get_clean();
}
