<?php
/**
 * Diagnostic Gallery — inspect rendered HTML + console
 * URL: /wp-content/themes/astra-delices-de-la-mer/dm-gallery-diag.php
 */
define('WP_USE_THEMES', false);
require_once __DIR__ . '/../../../../wp-load.php';

echo "<!DOCTYPE html><html><head><meta charset='utf-8'>";
echo "<title>DM Gallery Diagnostic</title>";
echo "<style>body{font-family:monospace;padding:2rem;background:#f5f5f5;}</style>";
echo "</head><body>";

// 1. Vérifier les options
$items = get_option('dm_gallery_items', array());
$reactions = get_option('dm_gallery_reactions', array());

echo "<h2>1. Options</h2>";
echo "<p><strong>dm_gallery_items</strong>: " . count($items) . " items</p>";
echo "<p><strong>dm_gallery_reactions</strong>: " . count($reactions) . " entries</p>";

// 2. Lister les 3 premiers items
echo "<h2>2. Premiers items</h2>";
$reversed = array_reverse($items, true);
$count = 0;
foreach ($reversed as $idx => $item) {
    if ($count >= 3) break;
    echo "<div style='background:#fff;padding:1rem;margin:0.5rem 0;border-radius:8px;'>";
    echo "<strong>Index $idx</strong><br>";
    echo "URL: " . esc_html($item['url'] ?? 'N/A') . "<br>";
    echo "Title: " . esc_html($item['title'] ?? 'N/A') . "<br>";
    echo "Category: " . esc_html($item['category'] ?? 'N/A') . "<br>";
    $r = dm_get_item_reactions($idx);
    echo "Likes: {$r['likes']} | Dislikes: {$r['dislikes']} | Hearts: {$r['hearts']}";
    echo "</div>";
    $count++;
}

// 3. Vérifier les assets enqueued
echo "<h2>3. Assets</h2>";
$css_path = DM_THEME_DIR . '/assets/css/gallery.css';
$js_path  = DM_THEME_DIR . '/assets/js/gallery.js';
echo "<p>gallery.css existe: " . (file_exists($css_path) ? 'OUI (' . filesize($css_path) . ' bytes)' : 'NON') . "</p>";
echo "<p>gallery.js existe: " . (file_exists($js_path) ? 'OUI (' . filesize($js_path) . ' bytes)' : 'NON') . "</p>";
echo "<p>gallery.css mtime: " . date('Y-m-d H:i:s', filemtime($css_path)) . "</p>";
echo "<p>gallery.js mtime: " . date('Y-m-d H:i:s', filemtime($js_path)) . "</p>";

// 4. Vérifier le template
echo "<h2>4. Template</h2>";
$tpl = DM_THEME_DIR . '/pages/page-gallery.php';
echo "<p>page-gallery.php existe: " . (file_exists($tpl) ? 'OUI' : 'NON') . "</p>";

// 5. Afficher le HTML d'une carte (extrait du template)
echo "<h2>5. HTML d'une carte (extrait)</h2>";
if (!empty($reversed)) {
    $first_idx = array_key_first($reversed);
    $d = $reversed[$first_idx];
    $html = '<div class="dm-gallery-item" data-index="' . esc_attr($first_idx) . '">';
    $html .= '<div class="dm-gallery-item-media" data-image-url="' . esc_attr($d['url'] ?? '') . '">';
    $html .= '<img src="' . esc_url($d['url'] ?? '') . '" />';
    $html .= '<button type="button" class="dm-gallery-item-expand">[ICON]</button>';
    $html .= '<div class="dm-gallery-item-overlay"></div>';
    $html .= '</div></div>';
    echo "<pre style='background:#fff;padding:1rem;border-radius:8px;overflow:auto;'>" . esc_html($html) . "</pre>";
}

// 6. Console log script
echo "<h2>6. Console log (regardez la console du navigateur)</h2>";
echo "<script>
console.log('=== DM GALLERY DIAG ===');
console.log('Items count:', " . json_encode(count($items)) . ");
console.log('Reactions count:', " . json_encode(count($reactions)) . ");

// Inspect le DOM après 5 secondes
setTimeout(function() {
    var items = document.querySelectorAll('.dm-gallery-item');
    console.log('Gallery items in DOM:', items.length);
    
    items.forEach(function(item, i) {
        if (i >= 3) return;
        var expand = item.querySelector('.dm-gallery-item-expand');
        var media = item.querySelector('.dm-gallery-item-media');
        var img = item.querySelector('img');
        var styles = window.getComputedStyle(item);
        console.log('Item ' + i + ':', {
            'class': item.className,
            'data-index': item.getAttribute('data-index'),
            'expand_btn': expand ? expand.outerHTML : 'NOT FOUND',
            'media_data_url': media ? media.getAttribute('data-image-url') : 'N/A',
            'img_src': img ? img.src.substring(0, 80) : 'N/A',
            'computed_transform': styles.transform,
            'computed_transition': styles.transition,
            'computed_zIndex': styles.zIndex
        });
    });
    
    var lightbox = document.getElementById('dm-gallery-lightbox');
    if (lightbox) {
        var closeBtn = lightbox.querySelector('.dm-gallery-lightbox-close');
        console.log('Lightbox close button:', closeBtn ? closeBtn.outerHTML : 'NOT FOUND');
    } else {
        console.log('Lightbox: NOT FOUND');
    }
    
    // Check CSS rules for .dm-gallery-item
    var sheets = document.styleSheets;
    for (var s = 0; s < sheets.length; s++) {
        try {
            var rules = sheets[s].cssRules;
            for (var r = 0; r < rules.length; r++) {
                if (rules[r].selectorText && rules[r].selectorText.indexOf('dm-gallery-item') !== -1 && rules[r].selectorText.indexOf('hover') !== -1) {
                    console.log('CSS hover rule:', rules[r].selectorText, rules[r].cssText);
                }
                if (rules[r].selectorText && rules[r].selectorText.indexOf('dm-gallery-item-expand') !== -1) {
                    console.log('CSS expand rule:', rules[r].selectorText, rules[r].cssText);
                }
                if (rules[r].selectorText && rules[r].selectorText.indexOf('lightbox-close') !== -1) {
                    console.log('CSS close rule:', rules[r].selectorText, rules[r].cssText);
                }
            }
        } catch(e) {
            // CORS
        }
    }
}, 5000);
</script>";

echo "<p>Attendez 5 secondes puis regardez la console (F12 > Console).</p>";
echo "</body></html>";
