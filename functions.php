<?php
/**
 * Astra Délices de la Mer — Thème enfant
 *
 * Point d'entrée principal du thème.
 * Toutes les fonctionnalités sont organisées dans inc/functions/ (1 fichier = 1 domaine).
 * Les CPTs sont dans inc/cpt/, les seeders dans inc/seeders/.
 * Les templates de pages sont dans pages/.
 *
 * - Aucune dépendance Tailwind (CSS classique modulaire, chargé conditionnellement).
 * - Header & footer custom injectés via les hooks Astra (astra_header / astra_footer).
 * - WooCommerce : habillage visuel uniquement, logique intacte.
 */

if (! defined('ABSPATH')) {
    exit;
}

define('DM_THEME_DIR', get_stylesheet_directory());
define('DM_THEME_URI', get_stylesheet_directory_uri());

// Fonctionnalités du thème (ordre de chargement important)
require_once DM_THEME_DIR . '/inc/functions/setup.php';
require_once DM_THEME_DIR . '/inc/functions/contact-options.php';
require_once DM_THEME_DIR . '/inc/functions/enqueue.php';
require_once DM_THEME_DIR . '/inc/functions/astra-overrides.php';
require_once DM_THEME_DIR . '/inc/functions/wc-translations.php';
require_once DM_THEME_DIR . '/inc/functions/wc-price-format.php';
require_once DM_THEME_DIR . '/inc/functions/wc-shop-filters.php';
require_once DM_THEME_DIR . '/inc/functions/wc-account.php';
require_once DM_THEME_DIR . '/inc/functions/header.php';
require_once DM_THEME_DIR . '/inc/functions/footer.php';
require_once DM_THEME_DIR . '/inc/functions/page-banner.php';
require_once DM_THEME_DIR . '/inc/functions/wc-overlap-cards.php';
require_once DM_THEME_DIR . '/inc/functions/wc-order-method.php';
require_once DM_THEME_DIR . '/inc/functions/floating-whatsapp.php';
require_once DM_THEME_DIR . '/inc/functions/auto-pages.php';
require_once DM_THEME_DIR . '/inc/functions/admin-repeaters.php';
require_once DM_THEME_DIR . '/inc/functions/repeaters/stores.php';
require_once DM_THEME_DIR . '/inc/functions/repeaters/services.php';
require_once DM_THEME_DIR . '/inc/functions/repeaters/about.php';
require_once DM_THEME_DIR . '/inc/functions/repeaters/gallery.php';
require_once DM_THEME_DIR . '/inc/functions/repeaters/promotions.php';
require_once DM_THEME_DIR . '/inc/functions/seo.php';

// Custom Post Types (à activer selon les besoins)
// require_once DM_THEME_DIR . '/inc/cpt/cpt-testimonials.php';
// require_once DM_THEME_DIR . '/inc/cpt/cpt-stores.php';

// Seeders (à supprimer après déploiement)
require_once DM_THEME_DIR . '/inc/seeders/seed-wc-products.php';
require_once DM_THEME_DIR . '/inc/seeders/seed-banners.php';
require_once DM_THEME_DIR . '/inc/seeders/seed-content.php';
require_once DM_THEME_DIR . '/inc/seeders/seed-promotions.php';
