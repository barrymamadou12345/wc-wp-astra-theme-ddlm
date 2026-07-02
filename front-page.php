<?php
/**
 * Page d'accueil — Délices de la Mer
 *
 * Sections :
 *   - Hero (image + titre + CTA + stats)
 *   - WhyUs (4 features)
 *   - HowItWorks (4 steps)
 *   - ServicesPreview (3 pôles)
 *   - Featured Products (WooCommerce)
 *   - StatsSection "Notre impact" (non-duplicatable + galerie)
 *   - PartnersBar (logos partenaires)
 *   - TestimonialsSection (témoignages)
 *   - CTA
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();

$home_url = home_url('/');

$hero_label   = get_option('dm_hero_label', 'Fabrication artisanale depuis 2016');
$hero_title   = get_option('dm_hero_title', "Une symbiose\nde saveurs");
$hero_desc    = get_option('dm_hero_desc', "Snacks croustillants, beignets dorés et produits fumés d'exception. Une restauration saine et de qualité, du Sénégal à votre assiette.");
$hero_image   = get_option('dm_hero_image', 'https://media.base44.com/images/public/6a335bc52ea23c09416a685d/eb6f541af_generated_08510815.png');
$hero_stat_1v = get_option('dm_hero_stat_1v', '2016');
$hero_stat_1l = get_option('dm_hero_stat_1l', 'Depuis');
$hero_stat_2v = get_option('dm_hero_stat_2v', '40+');
$hero_stat_2l = get_option('dm_hero_stat_2l', 'Employés');
$hero_stat_3v = get_option('dm_hero_stat_3v', '10+');
$hero_stat_3l = get_option('dm_hero_stat_3l', 'Partenaires');

$why_us_items = get_option('dm_why_us', array(
    array('icon' => 'shield', 'title' => 'Qualité certifiée', 'desc' => 'Respect strict des normes HACCP et traçabilité complète de nos ingrédients.'),
    array('icon' => 'clock', 'title' => 'Fraîcheur quotidienne', 'desc' => 'Produits préparés chaque jour dans nos ateliers pour une fraîcheur incomparable.'),
    array('icon' => 'truck', 'title' => 'Livraison rapide', 'desc' => 'Livraison en 24-48h à Dakar, avec un service fiable et ponctuel.'),
    array('icon' => 'leaf', 'title' => 'Ingrédients naturels', 'desc' => 'Des recettes sans additifs, avec des ingrédients soigneusement sélectionnés.'),
));

$how_steps = get_option('dm_how_steps', array(
    array('num' => '01', 'icon' => 'cart', 'title' => 'Commandez en ligne', 'desc' => 'Parcourez notre catalogue et ajoutez vos snacks préférés au panier en quelques clics.'),
    array('num' => '02', 'icon' => 'clipboard', 'title' => 'Choisissez le paiement', 'desc' => 'Payez facilement via Orange Money, Wave ou en espèces à la livraison.'),
    array('num' => '03', 'icon' => 'truck', 'title' => 'Livraison rapide', 'desc' => 'Recevez votre commande en 24-48h à Dakar, directement chez vous ou au bureau.'),
    array('num' => '04', 'icon' => 'coffee', 'title' => 'Régalez-vous !', 'desc' => 'Dégustez des snacks croustillants préparés avec des ingrédients frais et de qualité.'),
));

$services = get_option('dm_services', array(
    array('icon' => 'utensils', 'title' => 'Restauration Événementielle', 'desc' => 'Cocktails, mariages, séminaires — nous sublimons vos événements avec nos plateaux de snacks raffinés.', 'link' => '/services', 'image' => 'https://media.base44.com/images/public/6a335bc52ea23c09416a685d/d0f743324_generated_99b5c623.png'),
    array('icon' => 'building', 'title' => 'Gestion de Cantines', 'desc' => 'Un service B2B clé en main pour les entreprises qui souhaitent offrir une restauration de qualité.', 'link' => '/services', 'image' => 'https://media.base44.com/images/public/6a335bc52ea23c09416a685d/90a8e37e2_generated_e0fb4722.png'),
    array('icon' => 'package', 'title' => 'Produits Traiteur & Fumés', 'desc' => 'Poulet fumé, Kong fumé, Pigeon fumé — des saveurs authentiques prêtes à déguster.', 'link' => '/services', 'image' => 'https://media.base44.com/images/public/6a335bc52ea23c09416a685d/6b081792e_generated_e1eee356.png'),
));

$stats = get_option('dm_stats', array(
    array('value' => '8+', 'label' => "Années d'expérience"),
    array('value' => '40+', 'label' => 'Employés passionnés'),
    array('value' => '10+', 'label' => 'Partenaires distributeurs'),
    array('value' => '1000+', 'label' => 'Clients satisfaits'),
));

$stats_images = get_option('dm_stats_images', array(
    'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?w=400&h=500&fit=crop',
    'https://images.unsplash.com/photo-1556911220-bff31c812dba?w=400&h=400&fit=crop',
    'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=400&fit=crop',
    'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400&h=500&fit=crop',
));

$testimonials = dm_get_testimonials();

$promo = dm_get_current_promotion();

$partners = get_option('dm_partners', array(
    array('name' => 'Novotel', 'logo' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=200&h=200&fit=crop&crop=center'),
    array('name' => 'Carrefour', 'logo' => 'https://images.unsplash.com/photo-1534723452862-4c874018d66d?w=200&h=200&fit=crop&crop=center'),
    array('name' => 'Auchan', 'logo' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=200&h=200&fit=crop&crop=center'),
    array('name' => 'TotalEnergies', 'logo' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=200&h=200&fit=crop&crop=center'),
    array('name' => 'Shell', 'logo' => 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?w=200&h=200&fit=crop&crop=center'),
    array('name' => 'Pullman', 'logo' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=200&h=200&fit=crop&crop=center'),
    array('name' => 'Seter', 'logo' => 'https://images.unsplash.com/photo-1570125909232-eb263c4e96cb?w=200&h=200&fit=crop&crop=center'),
    array('name' => 'Terrou-Bi', 'logo' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=200&h=200&fit=crop&crop=center'),
    array('name' => "Sen'Eau", 'logo' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=200&h=200&fit=crop&crop=center'),
    array('name' => 'EDK', 'logo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=200&fit=crop&crop=center'),
));
?>

<!-- ============ HERO SECTION ============ -->
<section class="dm-hero">
    <div class="dm-hero-bg">
        <?php if (!empty($hero_image)) : ?>
            <img src="<?php echo esc_url($hero_image); ?>" alt="Délices de la Mer" />
        <?php endif; ?>
    </div>
    <div class="dm-hero-overlay"></div>
    <div class="dm-hero-content">
        <div class="dm-hero-inner">
            <span class="dm-hero-badge">🇸🇳 <?php echo esc_html($hero_label); ?></span>
            <h1 class="dm-hero-title">
                <?php
                $title_lines = explode("\n", $hero_title);
                $first_line = $title_lines[0] ?? '';
                $second_line = $title_lines[1] ?? '';
                $highlight = 'symbiose';
                if (stripos($first_line, $highlight) !== false) {
                    $first_line = str_ireplace($highlight, '<span class="accent-orange">' . $highlight . '</span>', esc_html($first_line));
                    echo $first_line;
                } else {
                    echo esc_html($first_line);
                }
                if ($second_line) {
                    echo '<br>' . esc_html($second_line);
                }
                ?>
            </h1>
            <p class="dm-hero-desc"><?php echo esc_html($hero_desc); ?></p>
            <div class="dm-hero-actions">
                <a href="<?php echo esc_url(home_url('/shop')); ?>" class="btn-orange dm-hero-cta">
                    Découvrir nos produits
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
                <a href="<?php echo esc_url(home_url('/services')); ?>" class="btn-outline dm-hero-btn-light">Nos services</a>
            </div>
            <div class="dm-hero-stats">
                <div class="dm-hero-stat">
                    <div class="dm-hero-stat-value"><?php echo esc_html($hero_stat_1v); ?></div>
                    <div class="dm-hero-stat-label"><?php echo esc_html($hero_stat_1l); ?></div>
                </div>
                <div class="dm-hero-stat">
                    <div class="dm-hero-stat-value"><?php echo esc_html($hero_stat_2v); ?></div>
                    <div class="dm-hero-stat-label"><?php echo esc_html($hero_stat_2l); ?></div>
                </div>
                <div class="dm-hero-stat">
                    <div class="dm-hero-stat-value"><?php echo esc_html($hero_stat_3v); ?></div>
                    <div class="dm-hero-stat-label"><?php echo esc_html($hero_stat_3l); ?></div>
                </div>
            </div>
        </div>

        <!-- ============ PROMOTION CARD (right side of hero) ============ -->
        <?php if ($promo) :
            $p_id          = $promo['id'] ?? '';
            $p_title       = $promo['title'] ?? '';
            $p_desc        = $promo['description'] ?? '';
            $p_badge       = $promo['badge'] ?? '';
            $p_percentage  = $promo['percentage'] ?? '';
            $p_products    = $promo['products'] ?? array();
            $promo_url     = home_url('/promotions');
        ?>
        <div class="dm-promo-card" data-promo-id="<?php echo esc_attr($p_id); ?>">
            <div class="dm-promo-petals"></div>
            <div class="dm-promo-bells">
                <span class="dm-promo-bell">🔔</span>
                <span class="dm-promo-bell">🔔</span>
                <span class="dm-promo-bell">🔔</span>
            </div>
            <div class="dm-promo-card-inner">
                <!-- Barre réduite -->
                <div class="dm-promo-card-bar">
                    <div class="dm-promo-card-bar-left">
                        <span class="dm-promo-card-bar-dot"></span>
                        <span class="dm-promo-card-bar-text">Promotion en cours</span>
                    </div>
                    <a href="<?php echo esc_url($promo_url); ?>" class="dm-promo-card-bar-btn">Voir</a>
                </div>

                <!-- Contenu déplié -->
                <div class="dm-promo-card-expanded">
                    <div class="dm-promo-card-scroll">
                    <?php if (!empty($p_badge)) : ?>
                        <span class="dm-promo-card-badge"><?php echo esc_html($p_badge); ?></span>
                    <?php endif; ?>
                    <h3 class="dm-promo-card-title"><?php echo esc_html($p_title); ?></h3>
                    <?php if (!empty($p_desc)) : ?>
                        <p class="dm-promo-card-desc"><?php echo esc_html($p_desc); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($p_percentage)) : ?>
                        <div class="dm-promo-card-percent">
                            <span class="dm-promo-card-percent-num"><?php echo esc_html($p_percentage); ?>%</span>
                            <span class="dm-promo-card-percent-label">de réduction</span>
                        </div>
                    <?php endif; ?>

                    <?php
                    if (!empty($p_products)) :
                    ?>
                    <div class="dm-promo-card-products-scroll">
                        <div class="dm-promo-card-products">
                        <?php foreach ($p_products as $pid) :
                            $post_obj = get_post($pid);
                            if (!$post_obj || $post_obj->post_type !== 'product' || $post_obj->post_status !== 'publish') continue;
                            $product_obj = wc_get_product($pid);
                            if (!$product_obj) continue;
                            $img_url = wp_get_attachment_image_url($product_obj->get_image_id(), 'thumbnail');
                        ?>
                        <a href="<?php echo esc_url($promo_url); ?>" class="dm-promo-card-product">
                            <?php if ($img_url) : ?>
                                <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($product_obj->get_name()); ?>" class="dm-promo-card-product-img" />
                            <?php endif; ?>
                            <div class="dm-promo-card-product-name"><?php echo esc_html($product_obj->get_name()); ?></div>
                        </a>
                        <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    </div><!-- /scroll -->

                    <div class="dm-promo-card-actions">
                        <a href="<?php echo esc_url($promo_url); ?>" class="dm-promo-card-cta">
                            Voir promo
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </a>
                        <button type="button" class="dm-promo-card-text-btn dm-promo-card-minimize">Réduire</button>
                        <button type="button" class="dm-promo-card-text-btn dm-promo-card-close">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <!-- Scroll indicator -->
    <div class="dm-hero-scroll">
        <div class="dm-hero-scroll-mouse">
            <div class="dm-hero-scroll-dot"></div>
        </div>
    </div>
    <!-- Bottom wave integrated in hero -->
    <div class="dm-hero-wave">
        <svg viewBox="0 0 1200 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,40 C300,80 600,0 900,50 C1050,65 1150,30 1200,40 L1200,80 L0,80 Z" fill="var(--cream)"></path>
        </svg>
    </div>
</section>

<!-- ============ WHY US ============ -->
<section class="dm-section bg-cream">
    <div class="dm-container">
        <div class="section-head reveal-el">
            <p class="section-label">Pourquoi nous choisir</p>
            <h2 class="section-title">L'Excellence à chaque <span class="accent-orange">bouchée</span></h2>
            <p class="section-subtitle">Ce qui fait de Délices de la Mer le choix préféré des Sénégalais pour leurs snacks et apéros.</p>
        </div>
        <div class="dm-why-grid">
            <?php foreach ($why_us_items as $i => $item) :
                $icon = $item['icon'] ?? 'shield';
            ?>
            <div class="dm-why-card reveal-el" style="transition-delay: <?php echo esc_attr($i * 80); ?>ms;">
                <div class="dm-why-icon">
                    <?php echo dm_get_icon_svg($icon); ?>
                </div>
                <h3 class="dm-why-title"><?php echo esc_html($item['title']); ?></h3>
                <p class="dm-why-desc"><?php echo esc_html($item['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ HOW IT WORKS ============ -->
<section class="dm-section dm-how-section dm-section-light-navy">
    <div class="dm-how-bg-glow dm-how-glow-1"></div>
    <div class="dm-how-bg-glow dm-how-glow-2"></div>
    <div class="dm-container dm-how-container">
        <div class="section-head reveal-el">
            <p class="section-label">Simple &amp; rapide</p>
            <h2 class="section-title">Comment ça marche ?</h2>
            <p class="section-subtitle">Commander vos snacks préférés n'a jamais été aussi simple.</p>
        </div>
        <div class="dm-how-grid">
            <?php foreach ($how_steps as $i => $step) : ?>
            <div class="dm-how-card reveal-el" style="transition-delay: <?php echo esc_attr($i * 80); ?>ms;">
                <div class="dm-how-icon">
                    <?php echo dm_get_icon_svg($step['icon'] ?? 'cart'); ?>
                </div>
                <span class="dm-how-num"><?php echo esc_html($step['num']); ?></span>
                <h3 class="dm-how-title"><?php echo esc_html($step['title']); ?></h3>
                <p class="dm-how-desc"><?php echo esc_html($step['desc']); ?></p>
                <?php if ($i < count($how_steps) - 1) : ?>
                <div class="dm-how-connector"></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="dm-how-cta reveal-el">
            <a href="<?php echo esc_url(home_url('/shop')); ?>" class="dm-how-cta-link">Commander maintenant →</a>
        </div>
    </div>
</section>

<!-- ============ SERVICES PREVIEW ============ -->
<section class="dm-section bg-cream">
    <div class="dm-container">
        <div class="section-head reveal-el">
            <p class="section-label">Ce que nous faisons</p>
            <h2 class="section-title">Nos 3 Pôles de <span class="accent-orange">Service</span></h2>
            <p class="section-subtitle">De l'événementiel à la vente de produits, nous couvrons tous vos besoins en restauration de qualité.</p>
        </div>
        <div class="dm-services-grid">
            <?php foreach ($services as $i => $svc) :
                $icon = $svc['icon'] ?? 'utensils';
                $link = $svc['link'] ?? '/services';
                $image = $svc['image'] ?? '';
            ?>
            <a href="<?php echo esc_url(home_url($link)); ?>" class="dm-service-card reveal-el" style="transition-delay: <?php echo esc_attr($i * 80); ?>ms;">
                <?php if (!empty($image)) : ?>
                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($svc['title']); ?>" class="dm-service-img" />
                <?php else : ?>
                    <div class="dm-service-img-placeholder"></div>
                <?php endif; ?>
                <div class="dm-service-overlay"></div>
                <div class="dm-service-body">
                    <div class="dm-service-icon">
                        <?php echo dm_get_icon_svg($icon); ?>
                    </div>
                    <h3 class="dm-service-title"><?php echo esc_html($svc['title']); ?></h3>
                    <p class="dm-service-desc"><?php echo esc_html($svc['desc']); ?></p>
                    <span class="dm-service-link">En savoir plus
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ FEATURED PRODUCTS (WooCommerce) ============ -->
<?php
$dm_products_html = '';
if (class_exists('WooCommerce')) {
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 6,
        'tax_query'      => array(array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'featured',
            'operator' => 'IN',
        )),
    );
    $featured = new WP_Query($args);
    if (empty($featured->posts)) {
        $args = array('post_type' => 'product', 'posts_per_page' => 6, 'orderby' => 'date', 'order' => 'DESC');
        $featured = new WP_Query($args);
    }
    if ($featured->have_posts()) {
        ob_start();
        echo '<ul class="products columns-3">';
        while ($featured->have_posts()) : $featured->the_post();
            wc_get_template_part('content', 'product');
        endwhile;
        echo '</ul>';
        wp_reset_postdata();
        $dm_products_html = ob_get_clean();
    }
}
?>
<section class="dm-section bg-muted dm-home-products">
    <div class="dm-container">
        <div class="section-head reveal-el">
            <p class="section-label">Nos best-sellers</p>
            <h2 class="section-title">Produits <span class="accent-orange">Phares</span></h2>
            <p class="section-subtitle">Découvrez nos snacks les plus appréciés, fabriqués avec des ingrédients frais et de qualité.</p>
        </div>
        <?php if (!empty($dm_products_html)) : ?>
            <div class="woocommerce dm-featured-products reveal-el">
                <?php echo $dm_products_html; ?>
            </div>
        <?php else : ?>
            <div class="dm-products-empty">
                <p>Aucun produit disponible pour le moment. Ajoutez des produits dans WooCommerce pour les voir apparaître ici.</p>
            </div>
        <?php endif; ?>
        <div class="dm-products-cta reveal-el">
            <a href="<?php echo esc_url(home_url('/shop')); ?>" class="btn-orange">Voir tout le catalogue
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>
</section>

<!-- ============ APÉRITIFS SALÉS & TARIFS ============ -->
<section class="dm-section dm-snacks-menu-section dm-section-light-navy">
    <div class="dm-container">
        <div class="section-head reveal-el">
            <p class="section-label">Nos tarifs</p>
            <h2 class="section-title">Apéritifs Salés <span class="accent-orange">&amp; Tarifs</span></h2>
            <p class="section-subtitle">Découvrez notre gamme de snacks et apéritifs salés, préparés artisanalement chaque jour.</p>
        </div>
        <div class="dm-snacks-menu-grid reveal-el">
            <div class="dm-snacks-menu-col">
                <?php
                $snacks_items = array(
                    array('name' => 'Nems bœuf x10', 'price' => '2 250 Fr'),
                    array('name' => 'Nems crevettes x10', 'price' => '2 500 Fr'),
                    array('name' => 'Nems bourek x10', 'price' => '2 500 Fr'),
                    array('name' => 'Beignets crevette x10', 'price' => '2 750 Fr'),
                    array('name' => 'Pastels x10', 'price' => '2 000 Fr'),
                    array('name' => 'Fataya x10', 'price' => '2 000 Fr'),
                    array('name' => 'Samoussa x10', 'price' => '2 500 Fr'),
                    array('name' => 'Pain chinois x10', 'price' => '2 500 Fr'),
                );
                foreach ($snacks_items as $item) :
                ?>
                <div class="dm-snacks-menu-row">
                    <span class="dm-snacks-menu-name"><?php echo esc_html($item['name']); ?></span>
                    <span class="dm-snacks-menu-dots"></span>
                    <span class="dm-snacks-menu-price"><?php echo esc_html($item['price']); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="dm-snacks-menu-col">
                <?php
                $snacks_items_2 = array(
                    array('name' => 'Rissoles x10', 'price' => '2 500 Fr'),
                    array('name' => 'Quiches x10', 'price' => '2 250 Fr'),
                    array('name' => 'Pizza x10', 'price' => '2 250 Fr'),
                    array('name' => 'Akkaras paquet 250g', 'price' => '1 500 Fr'),
                    array('name' => 'Tacos x1', 'price' => '350 Fr'),
                    array('name' => 'Beignets saucisses x1', 'price' => '350 Fr'),
                    array('name' => 'Apéros salés plateau x50', 'price' => '15 000 Fr'),
                );
                foreach ($snacks_items_2 as $item) :
                ?>
                <div class="dm-snacks-menu-row">
                    <span class="dm-snacks-menu-name"><?php echo esc_html($item['name']); ?></span>
                    <span class="dm-snacks-menu-dots"></span>
                    <span class="dm-snacks-menu-price"><?php echo esc_html($item['price']); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="dm-snacks-menu-cta reveal-el">
            <a href="<?php echo esc_url(home_url('/shop')); ?>" class="btn-orange">Commander maintenant
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>
</section>

<!-- ============ NOTRE IMPACT (Stats Section) ============ -->
<section class="dm-section bg-cream dm-stats-section" id="notre-impact">
    <div class="dm-container">
        <div class="dm-stats-grid">
            <!-- Left: stats content -->
            <div class="dm-stats-content reveal-el">
                <p class="section-label" style="text-align:left;">Notre impact</p>
                <h2 class="section-title" style="text-align:left;">Une entreprise sénégalaise qui <span class="accent-orange">grandit</span> avec vous</h2>
                <p class="dm-stats-intro">Depuis 2016, nous accompagnons les familles sénégalaises, les hôtels de luxe et les grandes enseignes avec des produits de qualité. Notre croissance est le reflet de la confiance que vous nous accordez.</p>
                <div class="dm-stats-list">
                    <?php
                    $stat_icons = array('award', 'users', 'store');
                    foreach (array_slice($stats, 0, 3) as $si => $stat) :
                    ?>
                    <div class="dm-stat-item">
                        <div class="dm-stat-icon"><?php echo dm_get_icon_svg($stat_icons[$si] ?? 'star'); ?></div>
                        <div class="dm-stat-value"><?php echo esc_html($stat['value']); ?></div>
                        <div class="dm-stat-label"><?php echo esc_html($stat['label']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <a href="<?php echo esc_url(home_url('/a-propos')); ?>" class="btn-outline dm-stats-btn">En savoir plus
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
            <!-- Right: 4 images gallery (staggered) -->
            <div class="dm-stats-gallery reveal-el">
                <div class="dm-stats-gallery-col">
                    <div class="dm-stats-gallery-item dm-stats-gallery-tall">
                        <?php $img = $stats_images[0] ?? ''; ?>
                        <?php if (!empty($img)) : ?>
                            <img src="<?php echo esc_url($img); ?>" alt="Galerie 1" />
                        <?php else : ?>
                            <div class="dm-stats-gallery-placeholder">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="dm-stats-gallery-item dm-stats-gallery-square">
                        <?php $img = $stats_images[1] ?? ''; ?>
                        <?php if (!empty($img)) : ?>
                            <img src="<?php echo esc_url($img); ?>" alt="Galerie 2" />
                        <?php else : ?>
                            <div class="dm-stats-gallery-placeholder">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="dm-stats-gallery-col dm-stats-gallery-col-offset">
                    <div class="dm-stats-gallery-item dm-stats-gallery-square">
                        <?php $img = $stats_images[2] ?? ''; ?>
                        <?php if (!empty($img)) : ?>
                            <img src="<?php echo esc_url($img); ?>" alt="Galerie 3" />
                        <?php else : ?>
                            <div class="dm-stats-gallery-placeholder">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="dm-stats-gallery-item dm-stats-gallery-tall">
                        <?php $img = $stats_images[3] ?? ''; ?>
                        <?php if (!empty($img)) : ?>
                            <img src="<?php echo esc_url($img); ?>" alt="Galerie 4" />
                        <?php else : ?>
                            <div class="dm-stats-gallery-placeholder">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ PARTNERS BAR ============ -->
<section class="dm-partners-section">
    <div class="dm-container dm-partners-inner">
        <div class="section-head reveal-el">
            <p class="section-label">Ils nous font confiance</p>
            <h2 class="section-title">Retrouvez-nous chez nos partenaires</h2>
        </div>
        <div class="dm-partners-grid">
            <?php foreach ($partners as $idx => $partner) :
                $p_name = is_array($partner) ? ($partner['name'] ?? '') : $partner;
                $p_logo = is_array($partner) ? ($partner['logo'] ?? '') : '';
                if (empty($p_name) && empty($p_logo)) continue;
            ?>
            <div class="dm-partner-card reveal-el" style="transition-delay: <?php echo esc_attr($idx * 60); ?>ms;">
                <div class="dm-partner-logo">
                    <?php if (!empty($p_logo)) : ?>
                        <img src="<?php echo esc_url($p_logo); ?>" alt="<?php echo esc_attr($p_name); ?>" />
                    <?php else : ?>
                        <?php echo esc_html(strtoupper(substr($p_name, 0, 2))); ?>
                    <?php endif; ?>
                </div>
                <span class="dm-partner-name"><?php echo esc_html($p_name); ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Wave bottom — transition vers la section suivante -->
    <div class="dm-partners-wave-bottom">
        <svg viewBox="0 0 1200 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,40 C300,80 600,0 900,50 C1050,65 1150,30 1200,40 L1200,80 L0,80 Z" fill="var(--cream)"></path>
        </svg>
    </div>
</section>

<!-- ============ TESTIMONIALS ============ -->
<section class="dm-section bg-cream">
    <div class="dm-container">
        <div class="section-head reveal-el">
            <p class="section-label">Témoignages</p>
            <h2 class="section-title">Ce que disent <span class="accent-orange">nos clients</span></h2>
            <p class="section-subtitle">La satisfaction de nos clients est notre plus grande fierté.</p>
        </div>
        <div class="dm-testimonials-carousel" id="testimonials-carousel">
            <div class="dm-testimonials-track" id="testimonials-track">
                <?php foreach ($testimonials as $tm) :
                    $tm_name = $tm['name'] ?? '';
                    $tm_role = $tm['role'] ?? '';
                    $tm_location = $tm['location'] ?? '';
                    $tm_text = $tm['text'] ?? '';
                    $tm_rating = intval($tm['rating'] ?? 5);
                    $tm_photo = $tm['photo'] ?? '';
                    if (empty($tm_name) && empty($tm_text)) continue;
                ?>
                <div class="dm-testimonial-card">
                    <div class="dm-testimonial-quote-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="1.5" opacity="0.2"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"/></svg>
                    </div>
                    <p class="dm-testimonial-text">"<?php echo esc_html($tm_text); ?>"</p>
                    <div class="dm-testimonial-stars">
                        <?php for ($s = 0; $s < 5; $s++) : ?>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="<?php echo $s < $tm_rating ? 'var(--orange)' : 'none'; ?>" stroke="<?php echo $s < $tm_rating ? 'var(--orange)' : 'var(--border)'; ?>" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <?php endfor; ?>
                    </div>
                    <div class="dm-testimonial-author">
                        <?php if (!empty($tm_photo)) : ?>
                        <div class="dm-testimonial-avatar" style="background:none;">
                            <img src="<?php echo esc_url($tm_photo); ?>" alt="<?php echo esc_attr($tm_name); ?>" style="width:100%;height:100%;border-radius:50%;object-fit:cover;" />
                        </div>
                        <?php else : ?>
                        <div class="dm-testimonial-avatar"><?php echo esc_html(strtoupper(substr($tm_name, 0, 1))); ?></div>
                        <?php endif; ?>
                        <div class="dm-testimonial-info">
                            <div class="dm-testimonial-name"><?php echo esc_html($tm_name); ?></div>
                            <div class="dm-testimonial-role"><?php echo esc_html($tm_role); ?><?php echo $tm_location ? ' · ' . esc_html($tm_location) : ''; ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ============ CTA ============ -->
<section class="dm-cta-section">
    <div class="dm-cta-bg"></div>
    <div class="dm-cta-overlay"></div>
    <div class="dm-container dm-cta-inner reveal-el">
        <h2 class="section-title">Prêt à régaler vos papilles ?</h2>
        <p class="dm-cta-text">Commandez en ligne et faites-vous livrer directement à Dakar. Paiement simple via Orange Money.</p>
        <div class="dm-cta-actions">
            <a href="<?php echo esc_url(home_url('/shop')); ?>" class="btn-orange">Commander maintenant</a>
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn-outline">Nous contacter</a>
        </div>
    </div>
</section>

<!-- ============ CONTACT RAPIDE (transition sans fond vers footer) ============ -->
<section class="dm-home-contact-strip">
    <div class="dm-container">
        <div class="dm-contact-strip-grid reveal-el">
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="dm-contact-strip-item">
                <span class="dm-contact-strip-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                </span>
                <span class="dm-contact-strip-text">
                    <strong>WhatsApp</strong>
                    <small><?php echo esc_html(dm_get_phone()); ?></small>
                </span>
            </a>
            <a href="<?php echo esc_url(home_url('/points-de-vente')); ?>" class="dm-contact-strip-item">
                <span class="dm-contact-strip-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </span>
                <span class="dm-contact-strip-text">
                    <strong>Points de vente</strong>
                    <small>Trouvez-nous près de vous</small>
                </span>
            </a>
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="dm-contact-strip-item">
                <span class="dm-contact-strip-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </span>
                <span class="dm-contact-strip-text">
                    <strong>Contact</strong>
                    <small>Une question ? Écrivez-nous</small>
                </span>
            </a>
        </div>
    </div>
</section>

<!-- Scroll reveal + testimonials carousel script -->
<script>
(function() {
    // --- 1. Reveal au scroll ---
    var els = document.querySelectorAll('.reveal-el');
    if (!('IntersectionObserver' in window)) {
        els.forEach(function(el) { el.classList.add('is-visible'); });
    } else {
        var obs = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
        els.forEach(function(el) { obs.observe(el); });
    }

    // --- 2. Testimonials Carousel (infinite loop) ---
    var tmsTrack = document.getElementById('testimonials-track');
    var tmsCarousel = document.getElementById('testimonials-carousel');
    if (tmsTrack && tmsCarousel) {
        var tmsItems = tmsTrack.querySelectorAll('.dm-testimonial-card');
        var tmsOriginalCount = tmsItems.length;

        if (tmsOriginalCount > 0) {
            // Clone all items for seamless infinite loop
            for (var i = 0; i < tmsOriginalCount; i++) {
                var clone = tmsItems[i].cloneNode(true);
                clone.setAttribute('aria-hidden', 'true');
                tmsTrack.appendChild(clone);
            }

            var tmsSpeed = 0.5;
            var tmsPaused = false;
            var tmsRAF = null;

            function tmsGetHalfWidth() {
                return tmsTrack.scrollWidth / 2;
            }

            function tmsAnimate() {
                if (!tmsPaused) {
                    tmsTrack.scrollLeft += tmsSpeed;
                    if (tmsTrack.scrollLeft >= tmsGetHalfWidth()) {
                        tmsTrack.scrollLeft -= tmsGetHalfWidth();
                    }
                }
                tmsRAF = requestAnimationFrame(tmsAnimate);
            }

            tmsCarousel.addEventListener('mouseenter', function() { tmsPaused = true; });
            tmsCarousel.addEventListener('mouseleave', function() { tmsPaused = false; });

            tmsRAF = requestAnimationFrame(tmsAnimate);
        }
    }
})();
</script>

<?php
get_footer();
