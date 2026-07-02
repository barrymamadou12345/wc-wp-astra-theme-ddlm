<?php
/**
 * Bannière d'en-tête de page — titre dynamique + breadcrumb.
 *
 * Affichée sur toutes les pages sauf l'accueil et les pages personnalisées
 * qui gèrent leur propre hero.
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Retourne l'URL de la bannière adaptée à la page courante.
 * Les URLs sont stockées en base de données par le seeder seed-banners.php.
 *
 * @return string URL de l'image, ou chaîne vide si aucune n'est configurée.
 */
function dm_get_banner_image()
{
    if (is_front_page()) {
        return get_option('dm_hero_image', '');
    }

    if (function_exists('is_shop') && is_shop()) {
        return get_option('dm_banner_catalog', '');
    }

    return get_option('dm_banner_general', '');
}

/**
 * Désactiver le titre Astra par défaut sur toutes les pages sauf l'accueil.
 */
add_filter('astra_the_title_enabled', 'dm_disable_default_page_title');
function dm_disable_default_page_title($enabled)
{
    if (!is_front_page()) {
        return false;
    }
    return $enabled;
}

/**
 * Génère le titre dynamique de la page courante selon le contexte.
 */
function dm_get_page_banner_title()
{
    if (function_exists('is_shop') && is_shop()) {
        return __('Catalogue', 'astra-delices-de-la-mer');
    }
    if (function_exists('is_product') && is_product()) {
        return get_the_title();
    }
    if (function_exists('is_cart') && is_cart()) {
        return __('Panier', 'astra-delices-de-la-mer');
    }
    if (function_exists('is_checkout') && is_checkout()) {
        return __('Commander', 'astra-delices-de-la-mer');
    }
    if (function_exists('is_account_page') && is_account_page()) {
        return __('Mon compte', 'astra-delices-de-la-mer');
    }
    if (is_singular()) {
        return get_the_title();
    }
    if (is_archive()) {
        return wp_strip_all_tags(get_the_archive_title());
    }
    if (is_search()) {
        return sprintf(__('Résultats pour : %s', 'astra-delices-de-la-mer'), get_search_query());
    }
    if (is_404()) {
        return __('Page introuvable', 'astra-delices-de-la-mer');
    }
    return get_the_title();
}

/**
 * Génère une description courte selon le contexte de page.
 */
function dm_get_page_banner_description()
{
    if (function_exists('is_shop') && is_shop()) {
        return __('Snacks croustillants, beignets dorés et produits fumés d\'exception. Choisissez, commandez, savourez.', 'astra-delices-de-la-mer');
    }
    if (function_exists('is_product') && is_product()) {
        $short = get_the_excerpt();
        if ($short) {
            return wp_strip_all_tags($short);
        }
        return __('Découvrez ce produit d\'exception, préparé avec soin pour vous offrir le meilleur de la mer.', 'astra-delices-de-la-mer');
    }
    if (function_exists('is_cart') && is_cart()) {
        return __('Vérifiez vos articles avant de passer commande.', 'astra-delices-de-la-mer');
    }
    if (function_exists('is_checkout') && is_checkout()) {
        return __('Finalisez votre commande en toute sécurité.', 'astra-delices-de-la-mer');
    }
    if (function_exists('is_account_page') && is_account_page()) {
        return __('Gérez votre compte, vos commandes et vos informations.', 'astra-delices-de-la-mer');
    }
    if (function_exists('is_product_category') && is_product_category()) {
        return __('Parcourez notre sélection de produits dans cette catégorie.', 'astra-delices-de-la-mer');
    }
    if (is_archive()) {
        return __('Parcourez notre collection d\'articles.', 'astra-delices-de-la-mer');
    }
    if (is_search()) {
        return __('Voici les résultats correspondant à votre recherche.', 'astra-delices-de-la-mer');
    }
    if (is_404()) {
        return __('La page que vous recherchez n\'existe pas ou a été déplacée.', 'astra-delices-de-la-mer');
    }
    return '';
}

/**
 * Génère un breadcrumb simple en fallback si Astra breadcrumb n'est pas actif.
 */
function dm_get_page_banner_breadcrumb()
{
    $home = home_url('/');
    $items = array();
    $items[] = '<a href="' . esc_url($home) . '">Accueil</a>';

    if (function_exists('is_shop') && is_shop()) {
        $items[] = '<span class="current">Catalogue</span>';
    } elseif (function_exists('is_product') && is_product()) {
        $items[] = '<a href="' . esc_url(home_url('/shop')) . '">Catalogue</a>';
        if (function_exists('wc_get_product_terms')) {
            $terms = wc_get_product_terms(get_the_ID(), 'product_cat', array('number' => 1));
            if (!empty($terms) && !is_wp_error($terms)) {
                $term = reset($terms);
                $items[] = '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
            }
        }
        $items[] = '<span class="current">' . esc_html(get_the_title()) . '</span>';
    } elseif (function_exists('is_product_category') && is_product_category()) {
        $items[] = '<a href="' . esc_url(home_url('/shop')) . '">Catalogue</a>';
        $items[] = '<span class="current">' . esc_html(single_term_title('', false)) . '</span>';
    } elseif (function_exists('is_cart') && is_cart()) {
        $items[] = '<span class="current">Panier</span>';
    } elseif (function_exists('is_checkout') && is_checkout()) {
        $items[] = '<a href="' . esc_url(wc_get_cart_url()) . '">Panier</a>';
        $items[] = '<span class="current">Commander</span>';
    } elseif (function_exists('is_account_page') && is_account_page()) {
        $items[] = '<span class="current">Mon compte</span>';
    } elseif (is_singular()) {
        if (is_page()) {
            $items[] = '<span class="current">' . esc_html(get_the_title()) . '</span>';
        } elseif (is_single()) {
            $categories = get_the_category();
            if (!empty($categories)) {
                $items[] = '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
            }
            $items[] = '<span class="current">' . esc_html(get_the_title()) . '</span>';
        }
    } elseif (is_archive()) {
        $items[] = '<span class="current">' . esc_html(wp_strip_all_tags(get_the_archive_title())) . '</span>';
    } elseif (is_search()) {
        $items[] = '<span class="current">Recherche</span>';
    } elseif (is_404()) {
        $items[] = '<span class="current">404</span>';
    }

    $html = '<nav class="dm-page-banner-breadcrumb" aria-label="Fil d\'Ariane">';
    $html .= implode('<span class="sep"></span>', $items);
    $html .= '</nav>';
    return $html;
}

/**
 * Affiche la bannière d'en-tête de page avec titre + breadcrumb.
 * Hookée sur astra_header_after pour s'afficher après la nav, avant le contenu.
 */
add_action('astra_header_after', 'dm_page_banner', 5);
function dm_page_banner()
{
    if (is_front_page()) {
        return;
    }

    if (is_page_template('pages/page-stores.php')) {
        return;
    }

    if (is_page_template('pages/page-services.php')) {
        return;
    }

    if (is_page_template('pages/page-testimonials.php')) {
        return;
    }

    if (is_page_template('pages/page-about.php')) {
        return;
    }

    if (is_page_template('pages/page-contact.php')) {
        return;
    }

    $title = dm_get_page_banner_title();

    $breadcrumb = '';
    if (function_exists('astra_get_breadcrumb')) {
        $breadcrumb = astra_get_breadcrumb(false);
    }
    if (empty(trim($breadcrumb))) {
        $breadcrumb = dm_get_page_banner_breadcrumb();
    } else {
        $breadcrumb = '<nav class="dm-page-banner-breadcrumb" aria-label="Fil d\'Ariane">' . $breadcrumb . '</nav>';
    }

    // Bannière centralisée depuis la base de données (seeder seed-banners.php)
    $banner_img = function_exists('dm_get_banner_image') ? dm_get_banner_image() : '';
?>
    <div class="dm-page-banner<?php echo empty($banner_img) ? ' dm-page-banner--solid' : ''; ?>"<?php echo !empty($banner_img) ? ' style="background-image:url(' . esc_url($banner_img) . ');"' : ''; ?>>
        <div class="dm-page-banner-overlay"></div>
        <div class="dm-page-banner-inner">
            <?php echo $breadcrumb; // phpcs:ignore ?>
            <h1 class="dm-page-banner-title"><?php echo esc_html($title); ?></h1>
            <?php
            $description = dm_get_page_banner_description();
            if (!empty($description)) :
            ?>
                <p class="dm-page-banner-description"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
            <div class="dm-page-banner-divider"></div>

        </div>
    </div>
<?php
}
