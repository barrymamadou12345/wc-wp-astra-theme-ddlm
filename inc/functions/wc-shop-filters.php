<?php
/**
 * Barre de filtres du Catalogue — chevauche le banner.
 *
 * Affiche une barre moderne avec :
 * - Recherche par mot-clé
 * - Filtres par catégorie (récupérés dynamiquement de WooCommerce)
 * - Tri WooCommerce par défaut
 *
 * Les filtres utilisent les paramètres URL standards de WooCommerce
 * (product_cat, s, orderby) pour une compatibilité native.
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * 1. Injecter la barre de filtres avant la boucle produits sur la boutique.
 */
add_action('woocommerce_before_shop_loop', 'dm_shop_filter_bar', 10);
function dm_shop_filter_bar()
{
    // Ne pas afficher sur les pages produit unique
    if (function_exists('is_product') && is_product()) {
        return;
    }

    // Afficher sur la boutique, les catégories, les tags, et la recherche
    $show_filters = (function_exists('is_shop') && is_shop())
        || (function_exists('is_product_category') && is_product_category())
        || (function_exists('is_product_tag') && is_product_tag());

    // Sur la recherche, afficher seulement si c'est une recherche de produits
    if (is_search() && isset($_GET['post_type']) && $_GET['post_type'] === 'product') {
        $show_filters = true;
    }

    if (!$show_filters) {
        return;
    }

    // Récupérer les catégories de produits
    $categories = get_terms(array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ));

    // Catégorie active
    $active_cat = '';
    if (is_product_category() && function_exists('get_queried_object')) {
        $current = get_queried_object();
        if ($current && isset($current->slug)) {
            $active_cat = $current->slug;
        }
    }
    if (isset($_GET['product_cat']) && !empty($_GET['product_cat'])) {
        $active_cat = sanitize_title($_GET['product_cat']);
    }

    // Terme de recherche actif
    $search_term = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    if (empty($search_term) && is_search()) {
        $search_term = get_search_query();
    }

    // Tri actif
    $current_orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : '';
    if (empty($current_orderby) && function_exists('wc_get_default_catalog_orderby')) {
        $current_orderby = wc_get_default_catalog_orderby();
    }

    $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop');
    ?>
    <div class="dm-shop-filters">
        <div class="dm-shop-filters-inner">
            <!-- Ligne 1 : Recherche + Tri -->
            <div class="dm-shop-filter-row-top">
                <div class="dm-shop-filter-search">
                    <form role="search" method="get" class="dm-shop-search-form" action="<?php echo esc_url($shop_url); ?>">
                        <svg class="dm-shop-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="search" name="s" value="<?php echo esc_attr($search_term); ?>" placeholder="Rechercher un produit..." class="dm-shop-search-input" />
                        <input type="hidden" name="post_type" value="product" />
                        <?php if ($active_cat): ?>
                            <input type="hidden" name="product_cat" value="<?php echo esc_attr($active_cat); ?>" />
                        <?php endif; ?>
                    </form>
                </div>

                <div class="dm-shop-filter-sort">
                    <?php
                    $sort_options = array(
                        ''              => 'Tri par défaut',
                        'popularity'    => 'Popularité',
                        'rating'        => 'Note moyenne',
                        'date'          => 'Nouveauté',
                        'price'         => 'Prix croissant',
                        'price-desc'    => 'Prix décroissant',
                    );
                    ?>
                    <select class="dm-shop-sort-select" onchange="dmShopSortChange(this.value)">
                        <?php foreach ($sort_options as $value => $label): ?>
                            <option value="<?php echo esc_attr($value); ?>"<?php selected($current_orderby, $value); ?>><?php echo esc_html($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <svg class="dm-shop-sort-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
            </div>

            <!-- Ligne 2 : Filtres par catégorie -->
            <?php if (!is_wp_error($categories) && !empty($categories)): ?>
            <div class="dm-shop-filter-row-categories">
                <div class="dm-shop-filter-categories">
                    <a href="<?php echo esc_url($shop_url); ?>" class="dm-shop-cat-pill<?php echo empty($active_cat) ? ' is-active' : ''; ?>">
                        Tous
                    </a>
                    <?php foreach ($categories as $cat):
                        $cat_url = get_term_link($cat, 'product_cat');
                        if (is_wp_error($cat_url)) {
                            $cat_url = add_query_arg('product_cat', $cat->slug, $shop_url);
                        }
                        $is_active = ($active_cat === $cat->slug);
                    ?>
                        <a href="<?php echo esc_url($cat_url); ?>" class="dm-shop-cat-pill<?php echo $is_active ? ' is-active' : ''; ?>" data-cat="<?php echo esc_attr($cat->slug); ?>">
                            <?php echo esc_html($cat->name); ?>
                            <span class="dm-shop-cat-count"><?php echo esc_html($cat->count); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
    function dmShopSortChange(value) {
        var url = new URL(window.location.href);
        if (value) {
            url.searchParams.set('orderby', value);
        } else {
            url.searchParams.delete('orderby');
        }
        window.location.href = url.toString();
    }
    </script>
    <?php
}

/**
 * 2. Masquer le tri et le compteur de résultats natifs de WooCommerce
 *    car ils sont intégrés dans notre barre de filtres.
 */
add_filter('woocommerce_show_page_title', '__return_false');
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

/**
 * 3. Retirer le fil d'Ariane natif WC (déjà dans le banner).
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

/**
 * 4. Filtrer les produits par catégorie quand le paramètre product_cat est présent
 *    sur la page boutique (WC gère déjà cela nativement via la query var).
 *    On s'assure juste que la query var est bien prise en compte.
 */
add_filter('woocommerce_product_query_meta_query', 'dm_shop_filter_by_cat', 10, 2);
function dm_shop_filter_by_cat($meta_query, $query)
{
    // WC gère déjà le filtrage par product_cat via la taxonomy query
    // Cette fonction est un placeholder pour des filtres supplémentaires futurs
    return $meta_query;
}

/**
 * 5. Personnaliser le message "aucun produit trouvé" avec un design moderne.
 */
remove_action('woocommerce_no_products_found', 'wc_no_products_found');
add_action('woocommerce_no_products_found', 'dm_no_products_found');
function dm_no_products_found()
{
    $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop');
    ?>
    <div class="dm-no-products">
        <div class="dm-no-products-icon">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </div>
        <h2>Aucun produit trouvé</h2>
        <p>Aucun produit ne correspond à votre recherche. Essayez avec d'autres mots-clés ou explorez notre catalogue complet.</p>
        <a href="<?php echo esc_url($shop_url); ?>" class="dm-no-products-link">
            Voir tout le catalogue
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </a>
    </div>
    <style>
    .dm-no-products {
        max-width: 32rem;
        margin: 3rem auto;
        text-align: center;
        padding: 3rem 2rem;
        background: #fff;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: 0 4px 20px rgba(27, 107, 177, 0.06);
    }
    .dm-no-products-icon {
        color: var(--orange);
        opacity: 0.35;
        margin-bottom: 1.5rem;
    }
    .dm-no-products h2 {
        font-family: var(--font-heading);
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: 1rem;
    }
    .dm-no-products p {
        font-family: var(--font-heading);
        font-size: 0.95rem;
        line-height: 1.6;
        color: var(--muted-foreground);
        margin-bottom: 2rem;
    }
    .dm-no-products-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-full);
        background: var(--navy-light);
        color: #fff;
        font-family: var(--font-heading);
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.25s ease;
    }
    .dm-no-products-link:hover {
        background: var(--navy);
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(27, 107, 177, 0.28);
    }
    </style>
    <?php
}
