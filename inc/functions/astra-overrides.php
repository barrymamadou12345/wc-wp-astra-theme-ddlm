<?php
/**
 * Overrides Astra — mise en page, body class, swap header/footer.
 *
 * - Force le layout full-width (page-builder) sur l'accueil et les pages personnalisées.
 * - Désactive la sidebar sur les mêmes pages.
 * - Force les classes body page-template-* pour les pages dont le meta n'est pas encore assigné.
 * - Remplace le header et footer Astra par les versions custom du thème.
 */

if (! defined('ABSPATH')) {
    exit;
}

/* ------------------------------------------------------------------ */
/* MISE EN PAGE ASTRA : full width + pas de sidebar sur l'accueil       */
/* ------------------------------------------------------------------ */
function dm_force_full_width($layout)
{
    if (is_front_page()
        || is_page_template('pages/page-services.php') || is_page('services')
        || is_page_template('pages/page-stores.php') || is_page('points-de-vente')
        || is_page_template('pages/page-about.php') || is_page('a-propos')
        || is_page_template('pages/page-testimonials.php') || is_page('temoignages')
        || is_page_template('pages/page-contact.php') || is_page('contact')) {
        return 'page-builder';
    }
    return $layout;
}
add_filter('astra_get_content_layout', 'dm_force_full_width');

function dm_disable_sidebar($sidebar)
{
    if (is_front_page()
        || is_page_template('pages/page-services.php') || is_page('services')
        || is_page_template('pages/page-stores.php') || is_page('points-de-vente')
        || is_page_template('pages/page-about.php') || is_page('a-propos')
        || is_page_template('pages/page-testimonials.php') || is_page('temoignages')
        || is_page_template('pages/page-contact.php') || is_page('contact')) {
        return 'no-sidebar';
    }
    return $sidebar;
}
add_filter('astra_page_layout', 'dm_disable_sidebar');

/* ------------------------------------------------------------------ */
/* BODY CLASS — Forcer les classes page-template-* pour les nouvelles  */
/* pages même si le meta _wp_page_template n'est pas encore assigné.    */
/* ------------------------------------------------------------------ */
function dm_force_body_template_class($classes)
{
    $map = array(
        'services'         => 'page-template-page-services',
        'points-de-vente'  => 'page-template-page-stores',
        'a-propos'         => 'page-template-page-about',
        'temoignages'      => 'page-template-page-testimonials',
        'contact'          => 'page-template-page-contact',
    );
    foreach ($map as $slug => $body_class) {
        if (is_page($slug) && ! in_array($body_class, $classes)) {
            $classes[] = $body_class;
            $classes[] = 'page-template';
        }
    }
    return $classes;
}
add_filter('body_class', 'dm_force_body_template_class');

/* ------------------------------------------------------------------ */
/* REMPLACEMENT CENTRALISÉ DU HEADER ET DU FOOTER ASTRA                 */
/* ------------------------------------------------------------------ */
function dm_swap_header_footer()
{
    // --- Header ---
    remove_action('astra_header', 'astra_header_markup');
    if (class_exists('Astra_Builder_Header')) {
        remove_action('astra_header', array(Astra_Builder_Header::get_instance(), 'header_builder_markup'));
    }
    add_action('astra_header', 'dm_custom_header');

    // --- Footer ---
    remove_action('astra_footer', 'astra_footer_markup');
    if (class_exists('Astra_Builder_Footer')) {
        remove_action('astra_footer', array(Astra_Builder_Footer::get_instance(), 'footer_markup'), 10);
    }
    add_action('astra_footer', 'dm_custom_footer');
}
add_action('template_redirect', 'dm_swap_header_footer');
