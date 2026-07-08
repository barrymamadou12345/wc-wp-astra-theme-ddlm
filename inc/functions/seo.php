<?php
/**
 * SEO intégré au thème — meta tags, Open Graph, Twitter Cards, Schema.org JSON-LD.
 *
 * Fonctionnalités :
 *   - Meta description dynamique par page (gérable depuis l'admin)
 *   - Open Graph tags (Facebook, WhatsApp, LinkedIn)
 *   - Twitter Card tags
 *   - Schema.org JSON-LD : Organization, LocalBusiness/FoodEstablishment, BreadcrumbList
 *   - Canonical URL automatique
 *   - robots meta tag configurable
 *   - Page admin "SEO" sous "Délices Content"
 *
 * Helpers disponibles :
 *   dm_get_seo_title()       — titre SEO de la page courante
 *   dm_get_seo_description() — description SEO de la page courante
 *   dm_get_seo_image()       — image OG de la page courante
 *   dm_get_seo_og_type()     — type OG (website, product, article)
 *
 * Options admin :
 *   dm_seo_site_name          — nom du site pour OG
 *   dm_seo_default_image      — image OG par défaut (1200x630 min)
 *   dm_seo_twitter_handle     — handle Twitter (@handle)
 *   dm_seo_business_name      — nom officiel de l'entreprise
 *   dm_seo_business_logo      — URL du logo pour Schema.org
 *   dm_seo_business_type      — Type Schema.org (FoodEstablishment, Restaurant, etc.)
 *   dm_seo_business_price_range — fourchette de prix ($$)
 *   dm_seo_business_cuisine   — type de cuisine
 *   dm_seo_pages              — array: [slug => ['title' => ..., 'description' => ..., 'image' => ..., 'robots' => ...]]
 *   dm_seo_robots_global      — global robots directive (index,follow par défaut)
 */

if (! defined('ABSPATH')) {
    exit;
}

/* -------------------------------------------------------------------------- */
/* Defaults                                                                     */
/* -------------------------------------------------------------------------- */

function dm_seo_default_pages()
{
    return array(
        'home' => array(
            'title'       => "Les Délices de la Mer — Snacks & Produits de la Mer Artisanaux à Dakar",
            'description' => "Découvrez Les Délices de la Mer : snacks croustillants artisanaux, produits fumés au beurre de coco et restauration événementielle à Dakar. Livraison 24-48h, qualité premium depuis 2016.",
            'image'       => '',
            'robots'      => 'index, follow',
        ),
        'shop' => array(
            'title'       => "Catalogue — Les Délices de la Mer | Snacks & Produits de la Mer",
            'description' => "Parcourez notre catalogue de snacks artisanaux, produits fumés et spécialités sénégalaises. Akkara, fataya, nems, pastels et plus encore. Commandez en ligne avec livraison à Dakar.",
            'image'       => '',
            'robots'      => 'index, follow',
        ),
        'services' => array(
            'title'       => "Nos Services — Restauration Événementielle & Traiteur | Délices de la Mer",
            'description' => "Restauration événementielle, gestion de cantines B2B et produits traiteur fumés. Mariages, séminaires, cocktails d'entreprise à Dakar. Service traiteur clé en main.",
            'image'       => '',
            'robots'      => 'index, follow',
        ),
        'points-de-vente' => array(
            'title'       => "Points de Vente — Carrefour, Auchan, TotalEnergies | Délices de la Mer",
            'description' => "Retrouvez nos produits chez nos distributeurs : Carrefour, Auchan, Novotel, Pullman, Terrou-Bi, TotalEnergies, Shell, TER et plus. Plus de 10 points de vente à Dakar et régions.",
            'image'       => '',
            'robots'      => 'index, follow',
        ),
        'a-propos' => array(
            'title'       => "À Propos — Notre Histoire depuis 2016 | Délices de la Mer",
            'description' => "Les Délices de la Mer, fondée en 2016 à Dakar. Plus de 40 employés passionnés, 10+ partenaires distributeurs. Découvrez notre histoire, nos valeurs et notre équipe.",
            'image'       => '',
            'robots'      => 'index, follow',
        ),
        'contact' => array(
            'title'       => "Contact — WhatsApp, Téléphone, Email | Délices de la Mer",
            'description' => "Contactez Les Délices de la Mer par WhatsApp, téléphone ou email. Adresse à Dakar, Sénégal. Horaires d'ouverture et carte de localisation.",
            'image'       => '',
            'robots'      => 'index, follow',
        ),
        'temoignages' => array(
            'title'       => "Témoignages Clients — Avis & Retours | Délices de la Mer",
            'description' => "Découvrez les témoignages de nos clients satisfaits. Particuliers, hôtels, entreprises et distributeurs partagent leur expérience avec Les Délices de la Mer.",
            'image'       => '',
            'robots'      => 'index, follow',
        ),
        'galerie' => array(
            'title'       => "Galerie — Photos de nos Produits | Délices de la Mer",
            'description' => "Explorez notre galerie de photos : snacks artisanaux, produits fumés, événements et coulisses de fabrication. Les Délices de la Mer en images.",
            'image'       => '',
            'robots'      => 'index, follow',
        ),
        'promotions' => array(
            'title'       => "Promotions — Offres Spéciales & Réductions | Délices de la Mer",
            'description' => "Profitez de nos promotions sur une sélection de snacks et produits de la mer. Réductions automatiques au panier, livraison rapide à Dakar.",
            'image'       => '',
            'robots'      => 'index, follow',
        ),
    );
}

function dm_seo_get_pages()
{
    $data = get_option('dm_seo_pages', array());
    $defaults = dm_seo_default_pages();
    if (!is_array($data)) {
        $data = array();
    }
    // Merge: defaults serve as base, saved data overrides
    foreach ($defaults as $slug => $default) {
        if (!isset($data[$slug])) {
            $data[$slug] = $default;
        } else {
            foreach ($default as $key => $val) {
                if (!isset($data[$slug][$key]) || $data[$slug][$key] === '') {
                    $data[$slug][$key] = $val;
                }
            }
        }
    }
    return $data;
}

/* -------------------------------------------------------------------------- */
/* Helpers                                                                      */
/* -------------------------------------------------------------------------- */

/**
 * Detect the current page slug for SEO purposes.
 */
function dm_seo_current_slug()
{
    if (is_front_page() || is_home()) {
        return 'home';
    }
    if (function_exists('is_woocommerce') && is_woocommerce()) {
        return 'shop';
    }
    if (function_exists('is_product') && is_product()) {
        return 'product';
    }
    if (function_exists('is_shop') && is_shop()) {
        return 'shop';
    }
    if (is_page()) {
        return get_post_field('post_name', get_queried_object_id());
    }
    if (is_singular('post')) {
        return 'post';
    }
    if (is_category() || is_tag() || is_archive()) {
        return 'archive';
    }
    return 'home';
}

/**
 * Get SEO title for the current page.
 */
function dm_get_seo_title()
{
    $slug = dm_seo_current_slug();
    $pages = dm_seo_get_pages();

    // WooCommerce product: use product name
    if ($slug === 'product' && function_exists('wc_get_product')) {
        $product = wc_get_product(get_queried_object_id());
        if ($product) {
            return $product->get_name() . " — Les Délices de la Mer";
        }
    }

    if (isset($pages[$slug]['title']) && !empty($pages[$slug]['title'])) {
        return $pages[$slug]['title'];
    }

    // Fallback to WordPress title
    if (is_page() || is_singular()) {
        return get_the_title() . " — Les Délices de la Mer";
    }

    return get_bloginfo('name') . " — " . get_bloginfo('description');
}

/**
 * Get SEO description for the current page.
 */
function dm_get_seo_description()
{
    $slug = dm_seo_current_slug();
    $pages = dm_seo_get_pages();

    // WooCommerce product: use short description
    if ($slug === 'product' && function_exists('wc_get_product')) {
        $product = wc_get_product(get_queried_object_id());
        if ($product) {
            $short = $product->get_short_description();
            if ($short) {
                return wp_strip_all_tags($short);
            }
            return $product->get_name() . " — produit artisanal de Les Délices de la Mer. Commandez en ligne avec livraison à Dakar.";
        }
    }

    if (isset($pages[$slug]['description']) && !empty($pages[$slug]['description'])) {
        return $pages[$slug]['description'];
    }

    // Fallback: post excerpt
    if (is_singular() && has_excerpt()) {
        return wp_strip_all_tags(get_the_excerpt());
    }

    return "Les Délices de la Mer — snacks artisanaux et produits de la mer à Dakar. Qualité premium depuis 2016.";
}

/**
 * Get SEO image (OG image) for the current page.
 */
function dm_get_seo_image()
{
    $slug = dm_seo_current_slug();
    $pages = dm_seo_get_pages();

    if (isset($pages[$slug]['image']) && !empty($pages[$slug]['image'])) {
        return $pages[$slug]['image'];
    }

    // WooCommerce product: use product image
    if ($slug === 'product' && function_exists('wc_get_product')) {
        $product = wc_get_product(get_queried_object_id());
        if ($product) {
            $img_id = $product->get_image_id();
            if ($img_id) {
                return wp_get_attachment_image_url($img_id, 'full');
            }
        }
    }

    // Post featured image
    if (is_singular() && has_post_thumbnail()) {
        return get_the_post_thumbnail_url(get_queried_object_ID(), 'full');
    }

    // Default OG image from settings
    $default = get_option('dm_seo_default_image', '');
    if (!empty($default)) {
        return $default;
    }

    // Fallback: banner image
    if (function_exists('dm_get_banner_image')) {
        return dm_get_banner_image();
    }

    return '';
}

/**
 * Get OG type for the current page.
 */
function dm_get_seo_og_type()
{
    $slug = dm_seo_current_slug();
    if ($slug === 'product') {
        return 'product';
    }
    if ($slug === 'post') {
        return 'article';
    }
    return 'website';
}

/**
 * Get robots directive for the current page.
 */
function dm_get_seo_robots()
{
    $slug = dm_seo_current_slug();
    $pages = dm_seo_get_pages();
    if (isset($pages[$slug]['robots']) && !empty($pages[$slug]['robots'])) {
        return $pages[$slug]['robots'];
    }
    return get_option('dm_seo_robots_global', 'index, follow');
}

/* -------------------------------------------------------------------------- */
/* Output: Meta tags via wp_head                                               */
/* -------------------------------------------------------------------------- */

add_action('wp_head', 'dm_seo_output_meta', 1);

function dm_seo_output_meta()
{
    $title       = dm_get_seo_title();
    $desc        = dm_get_seo_description();
    $image       = dm_get_seo_image();
    $og_type     = dm_get_seo_og_type();
    $robots      = dm_get_seo_robots();
    $url         = esc_url(home_url($_SERVER['REQUEST_URI'] ?? '/'));
    $site_name   = get_option('dm_seo_site_name', get_bloginfo('name'));
    $twitter     = get_option('dm_seo_twitter_handle', '');
    $locale      = get_locale();

    // Convert locale to OG format (fr_FR -> fr_FR)
    $og_locale = $locale;

    echo "\n<!-- ===== SEO by Délices de la Mer Theme ===== -->\n";

    // Meta description
    echo '<meta name="description" content="' . esc_attr($desc) . '" />' . "\n";

    // Robots
    echo '<meta name="robots" content="' . esc_attr($robots) . '" />' . "\n";

    // Canonical
    echo '<link rel="canonical" href="' . esc_url($url) . '" />' . "\n";

    // Open Graph
    echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($desc) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($url) . '" />' . "\n";
    echo '<meta property="og:type" content="' . esc_attr($og_type) . '" />' . "\n";
    echo '<meta property="og:locale" content="' . esc_attr($og_locale) . '" />' . "\n";

    if (!empty($image)) {
        echo '<meta property="og:image" content="' . esc_url($image) . '" />' . "\n";
        echo '<meta property="og:image:width" content="1200" />' . "\n";
        echo '<meta property="og:image:height" content="630" />' . "\n";
    }

    // Twitter Card
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($desc) . '" />' . "\n";
    if (!empty($image)) {
        echo '<meta name="twitter:image" content="' . esc_url($image) . '" />' . "\n";
    }
    if (!empty($twitter)) {
        echo '<meta name="twitter:site" content="' . esc_attr($twitter) . '" />' . "\n";
    }

    // Theme color
    echo '<meta name="theme-color" content="#0a1929" media="(prefers-color-scheme: dark)" />' . "\n";
    echo '<meta name="theme-color" content="#f8f5f0" media="(prefers-color-scheme: light)" />' . "\n";

    echo "<!-- ===== End SEO ===== -->\n\n";
}

/* -------------------------------------------------------------------------- */
/* Output: Schema.org JSON-LD via wp_head                                      */
/* -------------------------------------------------------------------------- */

add_action('wp_head', 'dm_seo_output_schema', 2);

function dm_seo_output_schema()
{
    $slug = dm_seo_current_slug();

    // Organization schema on all pages
    dm_seo_organization_schema();

    // LocalBusiness / FoodEstablishment schema on all pages
    dm_seo_local_business_schema();

    // Product schema on WooCommerce product pages
    if ($slug === 'product' && function_exists('wc_get_product')) {
        dm_seo_product_schema();
    }

    // BreadcrumbList schema
    dm_seo_breadcrumb_schema();
}

function dm_seo_organization_schema()
{
    $name  = get_option('dm_seo_business_name', get_bloginfo('name'));
    $url   = home_url('/');
    $logo  = get_option('dm_seo_business_logo', '');
    if (empty($logo)) {
        $logo = get_site_icon_url(512);
    }

    $socials = array();
    if (function_exists('dm_get_social_networks')) {
        foreach (dm_get_social_networks() as $s) {
            if (!empty($s['url'])) {
                $socials[] = $s['url'];
            }
        }
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => $name,
        'url'      => $url,
    );

    if (!empty($logo)) {
        $schema['logo'] = array(
            '@type' => 'ImageObject',
            'url'   => $logo,
        );
    }

    if (!empty($socials)) {
        $schema['sameAs'] = $socials;
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
}

function dm_seo_local_business_schema()
{
    $name        = get_option('dm_seo_business_name', get_bloginfo('name'));
    $url         = home_url('/');
    $logo        = get_option('dm_seo_business_logo', '');
    if (empty($logo)) {
        $logo = get_site_icon_url(512);
    }
    $type        = get_option('dm_seo_business_type', 'FoodEstablishment');
    $price_range = get_option('dm_seo_business_price_range', '$$');
    $cuisine     = get_option('dm_seo_business_cuisine', 'Sénégalaise');
    $image       = dm_get_seo_image();

    // Contact info
    $phone   = function_exists('dm_get_phone_tel') ? dm_get_phone_tel() : '';
    $email   = function_exists('dm_get_email') ? dm_get_email() : '';
    $address = function_exists('dm_get_address') ? dm_get_address() : 'Dakar, Sénégal';

    // Hours
    $hours = array();
    if (function_exists('dm_get_contact_hours')) {
        foreach (dm_get_contact_hours() as $h) {
            if (empty($h['closed']) || $h['closed'] === '0') {
                $hours[] = $h['day'] . ' ' . $h['hours'];
            }
        }
    }

    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => $type,
        'name'        => $name,
        'url'         => $url,
        'priceRange'  => $price_range,
        'servesCuisine' => $cuisine,
        'address'     => array(
            '@type'           => 'PostalAddress',
            'streetAddress'   => $address,
            'addressLocality' => 'Dakar',
            'addressCountry'  => 'SN',
        ),
    );

    if (!empty($phone)) {
        $schema['telephone'] = $phone;
    }
    if (!empty($email)) {
        $schema['email'] = $email;
    }
    if (!empty($logo)) {
        $schema['logo'] = $logo;
        $schema['image'] = $image ?: $logo;
    } elseif (!empty($image)) {
        $schema['image'] = $image;
    }
    if (!empty($hours)) {
        $schema['openingHours'] = $hours;
    }

    // Geo coordinates
    $lat = function_exists('dm_get_contact_map_lat') ? dm_get_contact_map_lat() : '';
    $lng = function_exists('dm_get_contact_map_lng') ? dm_get_contact_map_lng() : '';
    if ($lat && $lng) {
        $schema['geo'] = array(
            '@type'     => 'GeoCoordinates',
            'latitude'  => $lat,
            'longitude' => $lng,
        );
    }

    // Social links
    $socials = array();
    if (function_exists('dm_get_social_networks')) {
        foreach (dm_get_social_networks() as $s) {
            if (!empty($s['url'])) {
                $socials[] = $s['url'];
            }
        }
    }
    if (!empty($socials)) {
        $schema['sameAs'] = $socials;
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
}

function dm_seo_product_schema()
{
    $product_id = get_queried_object_id();
    $product = wc_get_product($product_id);
    if (!$product) {
        return;
    }

    $name    = $product->get_name();
    $desc    = wp_strip_all_tags($product->get_short_description() ?: $product->get_description());
    $price   = $product->get_price();
    $currency = get_woocommerce_currency();
    $img_id  = $product->get_image_id();
    $image   = $img_id ? wp_get_attachment_image_url($img_id, 'full') : '';
    $available = $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock';

    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'Product',
        'name'        => $name,
        'description' => $desc,
        'brand'       => array(
            '@type' => 'Brand',
            'name'  => get_option('dm_seo_business_name', 'Les Délices de la Mer'),
        ),
        'offers'      => array(
            '@type'         => 'Offer',
            'price'         => $price,
            'priceCurrency' => $currency,
            'availability'  => $available,
            'url'           => get_permalink($product_id),
        ),
    );

    if (!empty($image)) {
        $schema['image'] = $image;
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
}

function dm_seo_breadcrumb_schema()
{
    $slug = dm_seo_current_slug();
    $pages = dm_seo_get_pages();

    $items = array(
        array(
            '@type'    => 'ListItem',
            'position' => 1,
            'name'     => 'Accueil',
            'item'     => home_url('/'),
        ),
    );

    if ($slug !== 'home') {
        $page_names = array(
            'shop'           => 'Catalogue',
            'services'       => 'Services',
            'points-de-vente' => 'Points de Vente',
            'a-propos'       => 'À Propos',
            'contact'        => 'Contact',
            'temoignages'    => 'Témoignages',
            'galerie'        => 'Galerie',
            'promotions'     => 'Promotions',
        );

        $name = $page_names[$slug] ?? (is_singular() ? get_the_title() : 'Page');

        if ($slug === 'product' && function_exists('wc_get_product')) {
            $product = wc_get_product(get_queried_object_id());
            if ($product) {
                $name = $product->get_name();
            }
            $items[] = array(
                '@type'    => 'ListItem',
                'position' => 2,
                'name'     => 'Catalogue',
                'item'     => home_url('/shop'),
            );
            $items[] = array(
                '@type'    => 'ListItem',
                'position' => 3,
                'name'     => $name,
                'item'     => get_permalink(),
            );
        } else {
            $items[] = array(
                '@type'    => 'ListItem',
                'position' => 2,
                'name'     => $name,
                'item'     => home_url('/' . ($slug === 'home' ? '' : $slug)),
            );
        }
    }

    $schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items,
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
}

/* -------------------------------------------------------------------------- */
/* Register settings                                                           */
/* -------------------------------------------------------------------------- */

add_action('admin_init', function () {
    register_setting('dm_seo_group', 'dm_seo_site_name', array(
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => get_bloginfo('name'),
    ));
    register_setting('dm_seo_group', 'dm_seo_default_image', array(
        'type'              => 'string',
        'sanitize_callback' => 'esc_url_raw',
        'default'           => '',
    ));
    register_setting('dm_seo_group', 'dm_seo_twitter_handle', array(
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
    ));
    register_setting('dm_seo_group', 'dm_seo_business_name', array(
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => 'Les Délices de la Mer',
    ));
    register_setting('dm_seo_group', 'dm_seo_business_logo', array(
        'type'              => 'string',
        'sanitize_callback' => 'esc_url_raw',
        'default'           => '',
    ));
    register_setting('dm_seo_group', 'dm_seo_business_type', array(
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => 'FoodEstablishment',
    ));
    register_setting('dm_seo_group', 'dm_seo_business_price_range', array(
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '$$',
    ));
    register_setting('dm_seo_group', 'dm_seo_business_cuisine', array(
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => 'Sénégalaise',
    ));
    register_setting('dm_seo_group', 'dm_seo_robots_global', array(
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => 'index, follow',
    ));
    register_setting('dm_seo_group', 'dm_seo_pages', array(
        'type'              => 'array',
        'sanitize_callback' => 'dm_seo_sanitize_pages',
        'default'           => array(),
    ));
});

function dm_seo_sanitize_pages($input)
{
    $clean = array();
    if (!is_array($input)) return $clean;

    foreach ($input as $slug => $data) {
        if (!is_array($data)) continue;
        $slug = sanitize_key($slug);
        $clean[$slug] = array(
            'title'       => sanitize_text_field($data['title'] ?? ''),
            'description' => sanitize_textarea_field($data['description'] ?? ''),
            'image'       => esc_url_raw($data['image'] ?? ''),
            'robots'      => sanitize_text_field($data['robots'] ?? 'index, follow'),
        );
    }
    return $clean;
}

/* -------------------------------------------------------------------------- */
/* Enqueue media uploader on SEO admin page                                    */
/* -------------------------------------------------------------------------- */

add_action('admin_enqueue_scripts', function ($hook) {
    if (isset($_GET['page']) && $_GET['page'] === 'dm-seo') {
        wp_enqueue_media();
        wp_enqueue_script('jquery');
    }
});

/* -------------------------------------------------------------------------- */
/* Admin page HTML                                                             */
/* -------------------------------------------------------------------------- */

add_action('admin_menu', function () {
    add_submenu_page(
        'dm-content',
        'SEO',
        'SEO',
        'manage_options',
        'dm-seo',
        'dm_seo_page_html'
    );
}, 60);

function dm_seo_page_html()
{
    if (!current_user_can('manage_options')) return;

    $pages = dm_seo_get_pages();
    $site_name     = get_option('dm_seo_site_name', get_bloginfo('name'));
    $default_img   = get_option('dm_seo_default_image', '');
    $twitter       = get_option('dm_seo_twitter_handle', '');
    $biz_name      = get_option('dm_seo_business_name', 'Les Délices de la Mer');
    $biz_logo      = get_option('dm_seo_business_logo', '');
    $biz_type      = get_option('dm_seo_business_type', 'FoodEstablishment');
    $biz_price     = get_option('dm_seo_business_price_range', '$$');
    $biz_cuisine   = get_option('dm_seo_business_cuisine', 'Sénégalaise');
    $robots_global = get_option('dm_seo_robots_global', 'index, follow');

    $page_labels = array(
        'home'            => 'Accueil',
        'shop'            => 'Catalogue (Boutique)',
        'services'        => 'Nos Services',
        'points-de-vente' => 'Points de Vente',
        'a-propos'        => 'À Propos',
        'contact'         => 'Contact',
        'temoignages'     => 'Témoignages',
        'galerie'         => 'Galerie',
        'promotions'      => 'Promotions',
    );

    $biz_types = array(
        'FoodEstablishment' => 'Établissement alimentaire',
        'Restaurant'        => 'Restaurant',
        'Bakery'            => 'Boulangerie',
        'BarOrPub'          => 'Bar / Pub',
        'CafeOrCoffeeShop'  => 'Café',
        'Caterer'           => 'Traiteur',
        'GroceryStore'      => 'Épicerie',
        'Store'             => 'Boutique',
    );
    ?>
    <div class="wrap">
        <h1>SEO — Référencement</h1>
        <p>Gérez le référencement de votre site : meta descriptions, Open Graph (partage social), données structurées Schema.org. Les balises sont automatiquement injectées dans le <code>&lt;head&gt;</code> de chaque page.</p>

        <form method="post" action="options.php">
            <?php settings_fields('dm_seo_group'); ?>

            <!-- ===== Paramètres globaux ===== -->
            <h2>Paramètres globaux</h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="dm_seo_site_name">Nom du site (OG)</label></th>
                    <td><input type="text" id="dm_seo_site_name" name="dm_seo_site_name" value="<?php echo esc_attr($site_name); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="dm_seo_default_image">Image OG par défaut</label></th>
                    <td>
                        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                            <input type="text" id="dm_seo_default_image" name="dm_seo_default_image" value="<?php echo esc_attr($default_img); ?>" placeholder="URL image (1200x630 min)" class="regular-text dm-seo-img-input" style="width:400px;" />
                            <button type="button" class="button dm-seo-upload">Choisir une image</button>
                            <?php if (!empty($default_img)) : ?>
                                <img src="<?php echo esc_url($default_img); ?>" alt="" style="max-width:120px;max-height:60px;object-fit:cover;border-radius:4px;" />
                            <?php endif; ?>
                        </div>
                        <p class="description">Image affichée lors du partage sur Facebook, WhatsApp, LinkedIn. Min 1200x630px.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="dm_seo_twitter_handle">Twitter Handle</label></th>
                    <td><input type="text" id="dm_seo_twitter_handle" name="dm_seo_twitter_handle" value="<?php echo esc_attr($twitter); ?>" placeholder="@votrecompte" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="dm_seo_robots_global">Directive robots globale</label></th>
                    <td><input type="text" id="dm_seo_robots_global" name="dm_seo_robots_global" value="<?php echo esc_attr($robots_global); ?>" class="regular-text" />
                        <p class="description">Par défaut : <code>index, follow</code>. Utilisez <code>noindex, nofollow</code> pour cacher le site des moteurs de recherche.</p>
                    </td>
                </tr>
            </table>

            <hr style="margin:1.5rem 0;" />

            <!-- ===== Schema.org Business ===== -->
            <h2>Données structurées — Entreprise (Schema.org)</h2>
            <p class="description">Ces informations alimentent le <strong>Knowledge Graph</strong> Google et les <strong>rich snippets</strong> dans les résultats de recherche.</p>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="dm_seo_business_name">Nom officiel de l'entreprise</label></th>
                    <td><input type="text" id="dm_seo_business_name" name="dm_seo_business_name" value="<?php echo esc_attr($biz_name); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="dm_seo_business_type">Type d'établissement</label></th>
                    <td>
                        <select id="dm_seo_business_type" name="dm_seo_business_type">
                            <?php foreach ($biz_types as $val => $label) : ?>
                                <option value="<?php echo esc_attr($val); ?>" <?php selected($biz_type, $val); ?>><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="dm_seo_business_logo">Logo (Schema.org)</label></th>
                    <td>
                        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                            <input type="text" id="dm_seo_business_logo" name="dm_seo_business_logo" value="<?php echo esc_attr($biz_logo); ?>" placeholder="URL du logo" class="regular-text dm-seo-logo-input" style="width:400px;" />
                            <button type="button" class="button dm-seo-logo-upload">Choisir une image</button>
                            <?php if (!empty($biz_logo)) : ?>
                                <img src="<?php echo esc_url($biz_logo); ?>" alt="" style="max-width:80px;max-height:60px;object-fit:contain;" />
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="dm_seo_business_price_range">Fourchette de prix</label></th>
                    <td><input type="text" id="dm_seo_business_price_range" name="dm_seo_business_price_range" value="<?php echo esc_attr($biz_price); ?>" class="small-text" />
                        <p class="description">Ex: <code>$</code> (bon marché), <code>$$</code> (modéré), <code>$$$</code> (cher)</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="dm_seo_business_cuisine">Type de cuisine</label></th>
                    <td><input type="text" id="dm_seo_business_cuisine" name="dm_seo_business_cuisine" value="<?php echo esc_attr($biz_cuisine); ?>" class="regular-text" /></td>
                </tr>
            </table>

            <hr style="margin:1.5rem 0;" />

            <!-- ===== SEO par page ===== -->
            <h2>SEO par page</h2>
            <p class="description">Définissez un titre SEO, une meta description et une image de partage pour chaque page. Les champs vides utilisent les valeurs par défaut.</p>

            <?php foreach ($pages as $slug => $data) :
                $label = $page_labels[$slug] ?? $slug;
                $title = $data['title'] ?? '';
                $desc  = $data['description'] ?? '';
                $img   = $data['image'] ?? '';
                $rob   = $data['robots'] ?? 'index, follow';
            ?>
            <div style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin:12px 0;">
                <h3 style="margin-top:0;"><?php echo esc_html($label); ?> <code>(/<?php echo esc_html($slug === 'home' ? '' : $slug); ?>)</code></h3>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label>Titre SEO</label></th>
                        <td><input type="text" name="dm_seo_pages[<?php echo esc_attr($slug); ?>][title]" value="<?php echo esc_attr($title); ?>" class="regular-text" style="width:100%;max-width:700px;" />
                            <p class="description">50-60 caractères recommandés.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Meta description</label></th>
                        <td><textarea name="dm_seo_pages[<?php echo esc_attr($slug); ?>][description]" rows="2" style="width:100%;max-width:700px;"><?php echo esc_textarea($desc); ?></textarea>
                            <p class="description">150-160 caractères recommandés.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Image de partage (OG)</label></th>
                        <td>
                            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                                <input type="text" name="dm_seo_pages[<?php echo esc_attr($slug); ?>][image]" value="<?php echo esc_attr($img); ?>" placeholder="URL image (laisser vide = image par défaut)" class="regular-text dm-seo-page-img-input" style="width:400px;" />
                                <button type="button" class="button dm-seo-page-upload">Image</button>
                                <?php if (!empty($img)) : ?>
                                    <img src="<?php echo esc_url($img); ?>" alt="" style="max-width:80px;max-height:50px;object-fit:cover;border-radius:4px;" />
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Robots</label></th>
                        <td><input type="text" name="dm_seo_pages[<?php echo esc_attr($slug); ?>][robots]" value="<?php echo esc_attr($rob); ?>" class="regular-text" style="width:250px;" /></td>
                    </tr>
                </table>
            </div>
            <?php endforeach; ?>

            <?php submit_button('Enregistrer les paramètres SEO'); ?>
        </form>

        <script>
        jQuery(function($) {
            // Media uploader for default OG image
            var frame;
            $('.dm-seo-upload').on('click', function(e) {
                e.preventDefault();
                var input = $(this).siblings('.dm-seo-img-input');
                if (frame) { frame.open(); return; }
                frame = wp.media({ title: 'Choisir une image OG', button: { text: 'Utiliser' }, multiple: false });
                frame.on('select', function() {
                    var att = frame.state().get('selection').first().toJSON();
                    input.val(att.url);
                    frame.close();
                });
                frame.open();
            });

            // Media uploader for business logo
            var logoFrame;
            $('.dm-seo-logo-upload').on('click', function(e) {
                e.preventDefault();
                var input = $(this).siblings('.dm-seo-logo-input');
                if (logoFrame) { logoFrame.open(); return; }
                logoFrame = wp.media({ title: 'Choisir un logo', button: { text: 'Utiliser' }, multiple: false });
                logoFrame.on('select', function() {
                    var att = logoFrame.state().get('selection').first().toJSON();
                    input.val(att.url);
                    logoFrame.close();
                });
                logoFrame.open();
            });

            // Media uploader for per-page images
            $('.dm-seo-page-upload').on('click', function(e) {
                e.preventDefault();
                var input = $(this).siblings('.dm-seo-page-img-input');
                var frame2 = wp.media({ title: 'Choisir une image', button: { text: 'Utiliser' }, multiple: false });
                frame2.on('select', function() {
                    var att = frame2.state().get('selection').first().toJSON();
                    input.val(att.url);
                    frame2.close();
                });
                frame2.open();
            });
        });
        </script>
    </div>
    <?php
}
