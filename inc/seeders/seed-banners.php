<?php
/**
 * Seeder Bannières — Délices de la Mer
 *
 * Stocke en base de données les images de bannière par défaut :
 *   - Accueil     : banner.png
 *   - Catalogue   : banner-catalog.png
 *   - Toutes pages : banner-general.png
 *
 * Utilisation : dm_get_banner_image() retourne l'URL selon le contexte.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Seeder : enregistre les bannières par défaut en base de données.
 * S'exécute une seule fois ; utiliser ?dm_seed_banners_force=1 en admin pour forcer.
 */
add_action('init', 'dm_seed_banners', 15);
function dm_seed_banners()
{
    if (get_option('dm_banners_seeded') === 'yes') {
        return;
    }

    $theme_uri = get_stylesheet_directory_uri();

    $banners = array(
        'dm_hero_image'     => $theme_uri . '/assets/images/banners/banner.png',
        'dm_banner_catalog' => $theme_uri . '/assets/images/banners/banner-catalog.png',
        'dm_banner_general' => $theme_uri . '/assets/images/banners/banner-general.png',
    );

    foreach ($banners as $key => $url) {
        update_option($key, $url);
    }

    update_option('dm_banners_seeded', 'yes');
}

/**
 * Helper admin : lien de forçage du re-run du seeder de bannières.
 * Ajouter ?dm_seed_banners_force=1 dans l'admin pour réinitialiser.
 */
add_action('admin_init', 'dm_seed_banners_force');
function dm_seed_banners_force()
{
    if (is_admin() && current_user_can('manage_options') && !empty($_GET['dm_seed_banners_force'])) {
        delete_option('dm_banners_seeded');
        dm_seed_banners();
        wp_redirect(remove_query_arg('dm_seed_banners_force'));
        exit;
    }
}
