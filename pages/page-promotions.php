<?php
/**
 * Template Name: Page Promotions
 *
 * Affiche les promotions actives avec tous les détails.
 * Si aucune promotion active, affiche un message avec CTA.
 *
 * Sections communes (pré-codées, éditables dans l'admin) :
 *   - Hero promotion (dynamique)
 *   - Vidéo (dynamique)
 *   - Blocs de contenu (dynamiques)
 *   - Produits en promotion (dynamiques)
 *   - Comment ça marche (commun)
 *   - Pourquoi en profiter / Avantages (commun)
 *   - FAQ (commun)
 *   - CTA final (commun)
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();

$promo = dm_get_current_promotion();

// Common sections data (editable in admin, seeded with defaults)
$how_it_works = dm_get_promo_how_it_works();
$benefits     = dm_get_promo_benefits();
$faq          = dm_get_promo_faq();

// Icon map for SVG rendering
$icon_map = array(
    'cart'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>',
    'check'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>',
    'truck'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
    'coffee'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>',
    'fish'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6.5 12c.94-3.46 4.94-6 8.5-6 3.56 0 6.06 2.54 7 6-.94 3.47-3.44 6-7 6s-7.56-2.53-8.5-6z"/><line x1="18" y1="12" x2="22" y2="12"/><path d="M2 12c0-2 2-3 3-3"/><path d="M2 12c0 2 2 3 3 3"/></svg>',
    'shield'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
    'star'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
    'clock'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
    'flame'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>',
    'leaf'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/><path d="M2 21c0-3 1.85-5.36 5.08-6"/></svg>',
    'heart'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
    'utensils'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 2v7c0 1.1.9 2 2 2h0a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>',
    'package'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 21.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>',
);

function dm_render_promo_icon($icon_name, $icon_map)
{
    $key = $icon_name ?: 'star';
    return $icon_map[$key] ?? $icon_map['star'];
}
?>

<main id="main" class="site-main dm-promotions-page">

<?php if ($promo) :

    $title       = $promo['title'] ?? '';
    $description = $promo['description'] ?? '';
    $badge       = $promo['badge'] ?? '';
    $image       = $promo['image'] ?? '';
    $video       = $promo['video'] ?? '';
    $percentage  = $promo['percentage'] ?? '';
    $end_date    = $promo['end_date'] ?? '';
    $products    = $promo['products'] ?? array();
    $content     = $promo['content'] ?? array();

    $is_youtube = (strpos($video, 'youtube') !== false || strpos($video, 'youtu.be') !== false);
    $is_vimeo   = (strpos($video, 'vimeo') !== false);
    $is_video   = (preg_match('/\.(mp4|webm|ogg|mov)(\?.*)?$/i', $video));

    // Convertir les URLs YouTube normales en URLs embed
    $video_embed_url = $video;
    if ($is_youtube) {
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video, $m)) {
            $video_embed_url = 'https://www.youtube.com/embed/' . $m[1];
        } elseif (strpos($video, 'youtube.com/embed/') === false) {
            // URL non reconnue mais contient youtube — forcer embed
            $video_embed_url = str_replace('watch?v=', 'embed/', $video);
        }
    }
    // Convertir les URLs Vimeo normales en URLs embed
    if ($is_vimeo && strpos($video, 'player.vimeo.com') === false) {
        if (preg_match('/vimeo\.com\/(\d+)/', $video, $m)) {
            $video_embed_url = 'https://player.vimeo.com/video/' . $m[1];
        }
    }

    // Pré-calcul : produits valides + catégories (pour KPIs et filtres)
    $valid_products = array();
    $promo_categories = array();
    foreach ($products as $pid) {
        $post = get_post($pid);
        if (!$post || $post->post_type !== 'product' || $post->post_status !== 'publish') continue;
        $product_obj = wc_get_product($pid);
        if (!$product_obj) continue;
        $cat_slugs = array();
        $terms = get_the_terms($pid, 'product_cat');
        if ($terms && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $cat_slugs[] = $term->slug;
                if (!isset($promo_categories[$term->slug])) {
                    $promo_categories[$term->slug] = $term->name;
                }
            }
        }
        $valid_products[] = array('product' => $product_obj, 'pid' => $pid, 'cats' => $cat_slugs);
    }

    // KPIs
    $kpi_count = count($valid_products);
    $kpi_percent = !empty($percentage) ? intval($percentage) : 0;
    $kpi_days_left = '';
    if (!empty($end_date)) {
        $end_ts = strtotime($end_date);
        if ($end_ts !== false) {
            $diff = $end_ts - current_time('timestamp');
            $kpi_days_left = $diff > 0 ? ceil($diff / DAY_IN_SECONDS) : 0;
        }
    }
?>

<!-- Hero Promotion -->
<section class="dm-promo-hero" <?php echo !empty($image) ? 'style="background-image: linear-gradient(rgba(2,66,118,0.85), rgba(2,66,118,0.92)), url(' . esc_url($image) . ');"' : ''; ?>>
    <div class="dm-container">
        <div class="dm-promo-hero-content reveal-el">
            <?php if (!empty($badge)) : ?>
                <span class="dm-promo-badge"><?php echo esc_html($badge); ?></span>
            <?php endif; ?>
            <h1 class="dm-promo-hero-title"><?php echo esc_html($title); ?></h1>
            <?php if (!empty($description)) : ?>
                <p class="dm-promo-hero-desc"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
            <?php if (!empty($percentage)) : ?>
                <div class="dm-promo-hero-percent">
                    <span class="dm-promo-percent-value"><?php echo esc_html($percentage); ?>%</span>
                    <span class="dm-promo-percent-label">de réduction</span>
                </div>
            <?php endif; ?>
            <?php if (!empty($end_date)) : ?>
                <div class="dm-promo-countdown" data-enddate="<?php echo esc_attr($end_date); ?>">
                    <span class="dm-promo-countdown-label">Offre valide pendant :</span>
                    <div class="dm-promo-countdown-timer">
                        <div class="dm-promo-countdown-unit"><span class="dm-cd-days">0</span><small>Jours</small></div>
                        <div class="dm-promo-countdown-unit"><span class="dm-cd-hours">0</span><small>Heures</small></div>
                        <div class="dm-promo-countdown-unit"><span class="dm-cd-mins">0</span><small>Min</small></div>
                        <div class="dm-promo-countdown-unit"><span class="dm-cd-secs">0</span><small>Sec</small></div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="dm-promo-hero-cta">
                <a href="<?php echo esc_url(home_url('/shop')); ?>" class="btn-orange">Voir le catalogue
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Bande KPIs -->
<section class="dm-promo-kpis-section">
    <div class="dm-container">
        <div class="dm-promo-kpis reveal-el">
            <div class="dm-promo-kpi">
                <div class="dm-promo-kpi-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                </div>
                <div class="dm-promo-kpi-value"><?php echo esc_html($kpi_count); ?></div>
                <div class="dm-promo-kpi-label"><?php echo $kpi_count > 1 ? 'Produits en promo' : 'Produit en promo'; ?></div>
            </div>
            <?php if ($kpi_percent > 0) : ?>
            <div class="dm-promo-kpi">
                <div class="dm-promo-kpi-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="5" x2="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>
                </div>
                <div class="dm-promo-kpi-value">-<?php echo esc_html($kpi_percent); ?>%</div>
                <div class="dm-promo-kpi-label">de réduction</div>
            </div>
            <?php endif; ?>
            <?php if ($kpi_days_left !== '') : ?>
            <div class="dm-promo-kpi">
                <div class="dm-promo-kpi-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="dm-promo-kpi-value"><?php echo esc_html($kpi_days_left); ?></div>
                <div class="dm-promo-kpi-label"><?php echo intval($kpi_days_left) > 1 ? 'Jours restants' : 'Jour restant'; ?></div>
            </div>
            <?php endif; ?>
            <div class="dm-promo-kpi">
                <div class="dm-promo-kpi-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <div class="dm-promo-kpi-value">24-48h</div>
                <div class="dm-promo-kpi-label">Livraison Dakar</div>
            </div>
        </div>
    </div>
</section>

<!-- Vidéo -->
<?php if (!empty($video)) : ?>
<section class="dm-section dm-promo-video-section">
    <div class="dm-container">
        <div class="dm-promo-video-wrapper reveal-el">
            <?php if ($is_youtube || $is_vimeo) : ?>
                <iframe src="<?php echo esc_url($video_embed_url); ?>" frameborder="0" allowfullscreen></iframe>
            <?php elseif ($is_video) : ?>
                <video controls preload="metadata" poster="<?php echo esc_url($image); ?>">
                    <source src="<?php echo esc_url($video); ?>" type="video/mp4" />
                </video>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Blocs de contenu -->
<?php if (!empty($content)) : ?>
<section class="dm-section dm-promo-content-section">
    <div class="dm-container">
        <?php foreach ($content as $block) :
            $b_type  = $block['type'] ?? 'text';
            $b_title = $block['title'] ?? '';
            $b_text  = $block['text'] ?? '';
            $b_list  = $block['list_items'] ?? array();
        ?>
        <div class="dm-promo-content-block reveal-el">
            <?php if ($b_type === 'title' && !empty($b_title)) : ?>
                <h2 class="dm-promo-content-title"><?php echo esc_html($b_title); ?></h2>
            <?php elseif ($b_type === 'text') : ?>
                <?php if (!empty($b_title)) : ?><h3 class="dm-promo-content-subtitle"><?php echo esc_html($b_title); ?></h3><?php endif; ?>
                <?php if (!empty($b_text)) : ?><p class="dm-promo-content-text"><?php echo esc_html($b_text); ?></p><?php endif; ?>
            <?php elseif ($b_type === 'list') : ?>
                <?php if (!empty($b_title)) : ?><h3 class="dm-promo-content-subtitle"><?php echo esc_html($b_title); ?></h3><?php endif; ?>
                <?php if (!empty($b_list)) : ?>
                <ul class="dm-promo-content-list">
                    <?php foreach ($b_list as $li) : ?>
                        <li><?php echo esc_html($li); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Produits en promotion -->
<?php if (!empty($valid_products)) : ?>
<section class="dm-section dm-promo-products-section">
    <div class="dm-container">
        <div class="section-head reveal-el">
            <p class="section-label">Offres</p>
            <h2 class="section-title">Produits <span class="accent-orange">en promotion</span></h2>
        </div>

        <?php if (count($promo_categories) > 1) : ?>
        <!-- Barre de filtres -->
        <div class="dm-promo-filters reveal-el">
            <button type="button" class="dm-promo-filter is-active" data-filter="all">Tout voir</button>
            <?php foreach ($promo_categories as $slug => $name) : ?>
                <button type="button" class="dm-promo-filter" data-filter="<?php echo esc_attr($slug); ?>"><?php echo esc_html($name); ?></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="dm-promo-products-grid">
            <?php
            $shown = 0;
            foreach ($valid_products as $vp) :
                $product = $vp['product'];
                $pid     = $vp['pid'];
                $cats    = implode(' ', $vp['cats']);
                $shown++;
            ?>
            <div class="dm-promo-product-card reveal-el" data-categories="<?php echo esc_attr($cats); ?>" style="transition-delay:<?php echo esc_attr(($shown - 1) * 80); ?>ms;">
                <a href="<?php echo esc_url(get_permalink($pid)); ?>" class="dm-promo-product-link">
                    <div class="dm-promo-product-img">
                        <?php echo $product->get_image('medium'); ?>
                        <?php if ($product->is_on_sale()) : ?>
                            <span class="dm-promo-product-badge"><?php echo esc_html(dm_get_promo_badge_text($pid, $percentage)); ?></span>
                        <?php endif; ?>
                    </div>
                </a>
                <div class="dm-promo-product-info">
                    <h3 class="dm-promo-product-name"><a href="<?php echo esc_url(get_permalink($pid)); ?>" style="text-decoration:none;color:inherit;"><?php echo esc_html($product->get_name()); ?></a></h3>
                    <div class="dm-promo-product-price">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    <?php if ($product->is_in_stock() && $product->is_purchasable()) : ?>
                        <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" data-product_id="<?php echo esc_attr($pid); ?>" data-product_sku="<?php echo esc_attr($product->get_sku()); ?>" class="dm-promo-add-to-cart ajax_add_to_cart add_to_cart_button" aria-label="<?php echo esc_attr(sprintf('Ajouter %s au panier', $product->get_name())); ?>" rel="nofollow">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            <span>Ajouter</span>
                        </a>
                    <?php else : ?>
                        <span class="dm-promo-out-of-stock">Rupture de stock</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============ SECTION COMMUNE : Comment ça marche ============ -->
<section class="dm-section dm-promo-how-section">
    <div class="dm-container">
        <div class="section-head reveal-el">
            <p class="section-label">Simple &amp; rapide</p>
            <h2 class="section-title">Comment <span class="accent-orange">ça marche</span></h2>
            <p class="section-subtitle">Profitez de nos promotions en quelques étapes simples.</p>
        </div>
        <div class="dm-promo-how-grid">
            <?php foreach ($how_it_works as $i => $step) : ?>
            <div class="dm-promo-how-card reveal-el" style="transition-delay:<?php echo esc_attr($i * 100); ?>ms;">
                <div class="dm-promo-how-num"><?php echo esc_html(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></div>
                <div class="dm-promo-how-icon">
                    <?php echo dm_render_promo_icon($step['icon'] ?? 'star', $icon_map); ?>
                </div>
                <h3 class="dm-promo-how-title"><?php echo esc_html($step['title'] ?? ''); ?></h3>
                <p class="dm-promo-how-desc"><?php echo esc_html($step['desc'] ?? ''); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ SECTION COMMUNE : Pourquoi en profiter ============ -->
<section class="dm-section dm-promo-benefits-section">
    <div class="dm-container">
        <div class="section-head reveal-el">
            <p class="section-label">Nos garanties</p>
            <h2 class="section-title">Pourquoi <span class="accent-orange">en profiter</span></h2>
            <p class="section-subtitle">Délices de la Mer, une qualité reconnue depuis 2016.</p>
        </div>
        <div class="dm-promo-benefits-grid">
            <?php foreach ($benefits as $i => $benefit) : ?>
            <div class="dm-promo-benefit-card reveal-el" style="transition-delay:<?php echo esc_attr($i * 100); ?>ms;">
                <div class="dm-promo-benefit-icon">
                    <?php echo dm_render_promo_icon($benefit['icon'] ?? 'star', $icon_map); ?>
                </div>
                <h3 class="dm-promo-benefit-title"><?php echo esc_html($benefit['title'] ?? ''); ?></h3>
                <p class="dm-promo-benefit-desc"><?php echo esc_html($benefit['desc'] ?? ''); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ SECTION COMMUNE : FAQ ============ -->
<section class="dm-section dm-promo-faq-section">
    <div class="dm-container">
        <div class="section-head reveal-el">
            <p class="section-label">Questions fréquentes</p>
            <h2 class="section-title">Vous vous demandez <span class="accent-orange">comment ça fonctionne ?</span></h2>
        </div>
        <div class="dm-promo-faq-list">
            <?php foreach ($faq as $i => $item) : ?>
            <details class="dm-promo-faq-item reveal-el" <?php echo $i === 0 ? 'open' : ''; ?>>
                <summary class="dm-promo-faq-q">
                    <span><?php echo esc_html($item['q'] ?? ''); ?></span>
                    <svg class="dm-promo-faq-chevron" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </summary>
                <div class="dm-promo-faq-a">
                    <p><?php echo esc_html($item['a'] ?? ''); ?></p>
                </div>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ CTA FINAL ============ -->
<section class="dm-promo-cta-final">
    <div class="dm-container">
        <div class="dm-promo-cta-content reveal-el">
            <h2 class="dm-promo-cta-title">Ne manquez pas cette offre !</h2>
            <p class="dm-promo-cta-text">Profitez de nos promotions pendant qu'elles sont encore disponibles. Qualité, fraîcheur et prix réduits — tout ce qu'il faut pour se régaler.</p>
            <div class="dm-promo-cta-actions">
                <a href="<?php echo esc_url(home_url('/shop')); ?>" class="btn-orange">
                    Commander maintenant
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-outline-light">Retour à l'accueil</a>
            </div>
        </div>
    </div>
</section>

<?php else : ?>

<!-- Aucune promotion active -->
<section class="dm-promo-empty">
    <div class="dm-container">
        <div class="dm-promo-empty-content reveal-el">
            <div class="dm-promo-empty-icon">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
            </div>
            <h1 class="dm-promo-empty-title">Aucune promotion en ce moment</h1>
            <p class="dm-promo-empty-text">Il n'y a pas de promotion active actuellement. Revenez bientôt pour profiter de nos offres exceptionnelles !</p>
            <div class="dm-promo-empty-cta">
                <a href="<?php echo esc_url(home_url('/shop')); ?>" class="btn-orange">Découvrir le catalogue
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-outline">Retour à l'accueil</a>
            </div>
        </div>
    </div>
</section>

<?php endif; ?>

</main>

<?php get_footer(); ?>
