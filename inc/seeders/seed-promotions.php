<?php
/**
 * Seeder Promotions — Délices de la Mer
 *
 * Crée une promotion par défaut avec produits, contenu détaillé, image et vidéo.
 * Utilise les assets locaux du thème (gallery + banners).
 *
 * Pour exécuter : visiter le site une fois (auto au init).
 * Vérification par option 'dm_promotions_seeded' : ne seed qu'une fois.
 */

if (!defined('ABSPATH')) exit;

add_action('init', 'dm_seed_promotions', 40);

function dm_seed_promotions()
{
    if (!class_exists('WooCommerce')) return;

    // Re-seed une seule fois par version. Incrémenter pour forcer un nouveau seed.
    $seed_version = '2';
    if (get_option('dm_promotions_seed_version') === $seed_version) return;

    // Récupérer les IDs des produits en promo par titre
    $promo_products = array();
    $product_titles = array(
        'Beignets de Poisson (10 pièces)',
        'Beignets Mixtes (12 pièces)',
        'Crevettes Fumées (500g)',
        'Pack Famille — 20 Beignets + Sauce',
        'Maquereau Fumé (2 pièces)',
    );

    foreach ($product_titles as $title) {
        $q = new WP_Query(array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'title'          => $title,
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ));
        if ($q->have_posts()) {
            $promo_products[] = $q->posts[0];
        }
    }

    // Si aucun produit trouvé, on ne seed pas
    if (empty($promo_products)) return;

    // URLs des assets locaux
    $theme_uri = get_stylesheet_directory_uri();
    $promo_image = $theme_uri . '/assets/images/gallery/samoussa.jpeg';
    $promo_video = $theme_uri . '/assets/images/gallery/video1.mp4';

    // Date de fin : 30 jours à partir de maintenant
    $end_date = date('Y-m-d H:i', strtotime('+30 days'));

    $promotions = array(
        array(
            'id'          => 'promo_summer_2026',
            'title'       => "Festival des Saveurs — Spécial Apéro",
            'description' => "Profitez de -20% sur une sélection de nos meilleurs beignets, snacks et produits fumés. Une occasion unique de découvrir ou redécouvrir nos spécialités artisanales à prix réduit !",
            'badge'       => 'PROMO -20%',
            'image'       => $promo_image,
            'video'       => $promo_video,
            'percentage'  => '20',
            'end_date'    => $end_date,
            'is_active'   => 1,
            'products'    => $promo_products,
            'content'     => array(
                array(
                    'type'  => 'title',
                    'title' => 'Une offre qui donne faim',
                ),
                array(
                    'type'  => 'text',
                    'title' => 'Des saveurs authentiques à prix doux',
                    'text'  => "Chez Délices de la Mer, nous mettons un point d'honneur à vous offrir des produits frais et de qualité. Cette promotion est l'occasion parfaite de goûter à nos spécialités : beignets croustillants au poisson, crevettes fumées au bois de palme, et notre fameux Pack Famille pour partager entre amis.",
                ),
                array(
                    'type'  => 'list',
                    'title' => 'Pourquoi ne pas en profiter ?',
                    'list_items' => array(
                        'Beignets artisanaux faits maison chaque jour',
                        'Poisson et fruits de mer frais de la côte sénégalaise',
                        'Recettes traditionnelles transmises depuis 2016',
                        'Livraison rapide 24-48h à Dakar',
                        'Qualité certifiée — normes HACCP respectées',
                        'Paiement flexible : Orange Money, Wave, espèces',
                    ),
                ),
                array(
                    'type'  => 'text',
                    'title' => 'Comment profiter de la promotion ?',
                    'text'  => "C'est simple ! Ajoutez les produits en promotion à votre panier, la réduction de 20% s'applique automatiquement. Profitez-en, cette offre est limitée dans le temps !",
                ),
            ),
        ),
    );

    update_option('dm_promotions', $promotions);

    // Seed common sections with defaults
    if (get_option('dm_promo_how_it_works') === false) {
        update_option('dm_promo_how_it_works', dm_default_promo_how_it_works());
    }
    if (get_option('dm_promo_benefits') === false) {
        update_option('dm_promo_benefits', dm_default_promo_benefits());
    }
    if (get_option('dm_promo_faq') === false) {
        update_option('dm_promo_faq', dm_default_promo_faq());
    }

    update_option('dm_promotions_seeded', 'yes');
    update_option('dm_promotions_seed_version', $seed_version);
}
