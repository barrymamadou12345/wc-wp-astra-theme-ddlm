<?php
/**
 * Seeder WooCommerce — Délices de la Mer
 * Crée les produits du prototype (snacks, beignets, produits fumés).
 *
 * Pour exécuter : décommenter la ligne add_action ci-dessous,
 * visiter le site une fois, puis re-commenter.
 * Vérification par titre : ne crée que les produits manquants.
 */

if (!defined('ABSPATH')) exit;

add_action('init', 'dm_seed_woocommerce_products', 30);

function dm_seed_woocommerce_products()
{
    if (!class_exists('WooCommerce')) return;
    if (get_option('dm_wc_products_seeded') === 'yes') return;

    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $cat_beignets   = dm_ensure_product_cat('Beignets', 'beignets');
    $cat_snacks     = dm_ensure_product_cat('Snacks', 'snacks');
    $cat_fumes      = dm_ensure_product_cat('Produits Fumés', 'produits-fumes');
    $cat_promo      = dm_ensure_product_cat('Promotions', 'promotions');
    $cat_packs      = dm_ensure_product_cat('Packs Famille', 'packs-famille');

    $products = array(
        array(
            'name'        => 'Beignets de Poisson (10 pièces)',
            'description' => "Beignets croustillants au poisson frais, faits maison avec une pâte légère et dorée. Épices sénégalaises, friture parfaite. Servis chauds.",
            'short_desc'  => "Beignets dorés au poisson frais, recette artisanale.",
            'price'       => 2500,
            'sale_price'  => 2000,
            'categories'  => array($cat_beignets, $cat_promo),
            'image'       => 'https://images.unsplash.com/photo-1626808642875-0aa545482dfb?w=600&q=80',
            'featured'    => true,
            'stock'       => 80,
            'weight'      => '0.5',
        ),
        array(
            'name'        => 'Beignets de Calamar (8 pièces)',
            'description' => "Calamars frais enrobés d'une pâte croustillante, frits à la perfection. Servis avec sauce piquante maison.",
            'short_desc'  => "Calamars frits croustillants, sauce piquante incluse.",
            'price'       => 3000,
            'sale_price'  => '',
            'categories'  => array($cat_beignets),
            'image'       => 'https://images.unsplash.com/photo-1559737558-2f5a35f4523b?w=600&q=80',
            'featured'    => true,
            'stock'       => 50,
            'weight'      => '0.4',
        ),
        array(
            'name'        => 'Beignets Mixtes (12 pièces)',
            'description' => "Assortiment de beignets: poisson, calamar et crevettes. Parfait pour découvrir toutes nos saveurs. Idéal pour partager.",
            'short_desc'  => "Assortiment 12 beignets: poisson, calamar, crevettes.",
            'price'       => 3500,
            'sale_price'  => 2800,
            'categories'  => array($cat_beignets, $cat_promo),
            'image'       => 'https://images.unsplash.com/photo-1606755962773-d324e0a13086?w=600&q=80',
            'featured'    => true,
            'stock'       => 60,
            'weight'      => '0.6',
        ),
        array(
            'name'        => 'Poisson Fumé (1kg)',
            'description' => "Poisson fumé au bois naturel, méthode traditionnelle sénégalaise. Viande tendre, goût fumé intense. Prêt à consommer ou à cuisiner.",
            'short_desc'  => "Poisson fumé au bois, méthode traditionnelle.",
            'price'       => 5000,
            'sale_price'  => '',
            'categories'  => array($cat_fumes),
            'image'       => 'https://images.unsplash.com/photo-1535140728325-a4d3707eee85?w=600&q=80',
            'featured'    => true,
            'stock'       => 40,
            'weight'      => '1.0',
        ),
        array(
            'name'        => 'Crevettes Fumées (500g)',
            'description' => "Crevettes fraîches fumées au bois de palme. Saveur intense et parfumée. Parfaites pour apéritif ou cuisson.",
            'short_desc'  => "Crevettes fumées au bois de palme, 500g.",
            'price'       => 4500,
            'sale_price'  => 3800,
            'categories'  => array($cat_fumes, $cat_promo),
            'image'       => 'https://images.unsplash.com/photo-1565680018434-b513d5e6fd47?w=600&q=80',
            'featured'    => true,
            'stock'       => 35,
            'weight'      => '0.5',
        ),
        array(
            'name'        => 'Chips de Poisson (sachet 200g)',
            'description' => "Chips croustillantes de poisson séché et épicé. Snack léger et savoureux, parfait pour l'apéritif. Épices locales.",
            'short_desc'  => "Chips de poisson séché épicé, snack croustillant.",
            'price'       => 1500,
            'sale_price'  => '',
            'categories'  => array($cat_snacks),
            'image'       => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
            'featured'    => false,
            'stock'       => 100,
            'weight'      => '0.2',
        ),
        array(
            'name'        => 'Snacks Épicés Mixte (300g)',
            'description' => "Mélange de snacks épicés: poisson séché, crevettes séchées, arachides épicées. Parfait pour l'apéritif sénégalais.",
            'short_desc'  => "Mélange snacks épicés: poisson, crevettes, arachides.",
            'price'       => 2000,
            'sale_price'  => '',
            'categories'  => array($cat_snacks),
            'image'       => 'https://images.unsplash.com/photo-1576506542790-51244b486a6b?w=600&q=80',
            'featured'    => false,
            'stock'       => 90,
            'weight'      => '0.3',
        ),
        array(
            'name'        => 'Pack Famille — 20 Beignets + Sauce',
            'description' => "20 beignets mixtes (poisson, calamar, crevettes) + 2 sauces piquantes maison. Idéal pour 6-8 personnes. Bon prix de famille.",
            'short_desc'  => "20 beignets mixtes + 2 sauces, pour 6-8 personnes.",
            'price'       => 6000,
            'sale_price'  => 5000,
            'categories'  => array($cat_packs, $cat_promo),
            'image'       => 'https://images.unsplash.com/photo-1606755962773-d324e0a13086?w=600&q=80',
            'featured'    => true,
            'stock'       => 25,
            'weight'      => '1.2',
        ),
        array(
            'name'        => 'Filet de Poisson Séché (300g)',
            'description' => "Filets de poisson séché et épicé, prêts à consommer. Texture moelleuse, saveur intense. Snack riche en protéines.",
            'short_desc'  => "Filets de poisson séché épicé, prêts à consommer.",
            'price'       => 2500,
            'sale_price'  => '',
            'categories'  => array($cat_snacks, $cat_fumes),
            'image'       => 'https://images.unsplash.com/photo-1611171644391-67cb3e8d9b8a?w=600&q=80',
            'featured'    => false,
            'stock'       => 70,
            'weight'      => '0.3',
        ),
        array(
            'name'        => 'Maquereau Fumé (2 pièces)',
            'description' => "Maquereaux entiers fumés au bois naturel. Chair fondante, peau dorée. Parfaits pour grillades ou salades.",
            'short_desc'  => "2 maquereaux fumés au bois, chair fondante.",
            'price'       => 3500,
            'sale_price'  => 3000,
            'categories'  => array($cat_fumes, $cat_promo),
            'image'       => 'https://images.unsplash.com/photo-1490818387583-1baba5e638af?w=600&q=80',
            'featured'    => false,
            'stock'       => 45,
            'weight'      => '0.6',
        ),
    );

    foreach ($products as $p) {
        $q = new WP_Query(array(
            'post_type'      => 'product',
            'post_status'    => 'any',
            'title'          => $p['name'],
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ));
        $existing_id = $q->have_posts() ? $q->posts[0] : 0;

        if ($existing_id) {
            if (!has_post_thumbnail($existing_id) && !empty($p['image'])) {
                dm_download_and_set_image($p['image'], $existing_id, $p['name']);
            }
            continue;
        }

        $product = new WC_Product_Simple();
        $product->set_name($p['name']);
        $product->set_status('publish');
        $product->set_catalog_visibility('visible');
        $product->set_description($p['description']);
        $product->set_short_description($p['short_desc']);
        $product->set_regular_price($p['price']);
        if ($p['sale_price']) {
            $product->set_sale_price($p['sale_price']);
        }
        $product->set_manage_stock(true);
        $product->set_stock_quantity($p['stock']);
        $product->set_stock_status('instock');
        $product->set_weight($p['weight']);
        $product->set_featured($p['featured']);
        $product->set_reviews_allowed(true);
        $product->set_category_ids($p['categories']);

        $product_id = $product->save();

        if ($product_id && !empty($p['image'])) {
            dm_download_and_set_image($p['image'], $product_id, $p['name']);
        }
    }

    update_option('dm_wc_products_seeded', 'yes');
}

function dm_download_and_set_image($url, $post_id, $name)
{
    $tmp = download_url($url);
    if (is_wp_error($tmp)) return false;
    $file_array = array(
        'name'     => sanitize_title($name) . '.jpg',
        'tmp_name' => $tmp,
    );
    $attach_id = media_handle_sideload($file_array, $post_id);
    if (!is_wp_error($attach_id)) {
        set_post_thumbnail($post_id, $attach_id);
        @unlink($tmp);
        return true;
    }
    @unlink($tmp);
    return false;
}

if (!function_exists('dm_ensure_product_cat')) {
    function dm_ensure_product_cat($name, $slug)
    {
        $term = get_term_by('slug', $slug, 'product_cat');
        if ($term && !is_wp_error($term)) return $term->term_id;
        $result = wp_insert_term($name, 'product_cat', array('slug' => $slug));
        if (!is_wp_error($result)) return $result['term_id'];
        return 0;
    }
}
