<?php
/**
 * Auto-création des pages WordPress avec assignation de template.
 *
 * Crée automatiquement les pages au chargement si elles n'existent pas,
 * et assigne le template personnalisé correspondant depuis le dossier pages/.
 *
 * Templates (dossier pages/) :
 *   - page-services.php     → slug: services
 *   - page-stores.php       → slug: points-de-vente
 *   - page-about.php        → slug: a-propos
 *   - page-testimonials.php → slug: temoignages
 *   - page-contact.php      → slug: contact
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Auto-création de toutes les pages personnalisées.
 */
function dm_ensure_custom_pages()
{
    $pages = array(
        array('slug' => 'services',         'title' => 'Services',          'template' => 'pages/page-services.php'),
        array('slug' => 'points-de-vente',  'title' => 'Points de Vente',   'template' => 'pages/page-stores.php'),
        array('slug' => 'a-propos',         'title' => 'À Propos',          'template' => 'pages/page-about.php'),
        array('slug' => 'temoignages',      'title' => 'Témoignages',       'template' => 'pages/page-testimonials.php'),
        array('slug' => 'contact',          'title' => 'Contact',           'template' => 'pages/page-contact.php'),
        array('slug' => 'galerie',          'title' => 'Galerie',            'template' => 'pages/page-gallery.php'),
        array('slug' => 'promotions',       'title' => 'Promotions',         'template' => 'pages/page-promotions.php'),
    );
    foreach ($pages as $p) {
        $page = get_page_by_path($p['slug']);
        if (! $page) {
            $page_id = wp_insert_post(array(
                'post_title'   => $p['title'],
                'post_name'    => $p['slug'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '',
            ));
            if ($page_id && ! is_wp_error($page_id)) {
                update_post_meta($page_id, '_wp_page_template', $p['template']);
            }
        } else {
            $current_template = get_post_meta($page->ID, '_wp_page_template', true);
            if ($current_template !== $p['template']) {
                update_post_meta($page->ID, '_wp_page_template', $p['template']);
            }
        }
    }
}
add_action('init', 'dm_ensure_custom_pages');
