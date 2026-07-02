<?php
/**
 * Seeder Contenu — Délices de la Mer
 *
 * 1. Témoignages : alimente dm_testimonials avec les témoignages par défaut
 * 2. Produits WooCommerce : 11 produits (8 snacks & beignets + 3 produits fumés)
 *    avec images locales du dossier assets/images/gallery
 * 3. Partenaires : alimente dm_partners avec les logos par défaut
 *
 * Pour exécuter : visiter le site une fois (hook init).
 * Vérification par option flag + par titre : ne crée que les éléments manquants.
 */

if (!defined('ABSPATH')) exit;

function dm_seed_log($message)
{
    $log_file = get_stylesheet_directory() . '/seeder-debug.log';
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    file_put_contents($log_file, $line, FILE_APPEND | LOCK_EX);
}

/* -------------------------------------------------------------------------- */
/* 1. SEEDER TÉMOIGNAGES                                                       */
/* -------------------------------------------------------------------------- */
add_action('init', 'dm_seed_testimonials', 20);

function dm_seed_testimonials()
{
    if (get_option('dm_testimonials_seeded') === 'yes') return;

    $existing = get_option('dm_testimonials', array());
    if (is_array($existing) && !empty($existing)) {
        update_option('dm_testimonials_seeded', 'yes');
        return;
    }

    if (function_exists('dm_get_default_testimonials')) {
        $defaults = dm_get_default_testimonials();
    } else {
        $defaults = array(
            array('name' => 'Awa Diop', 'role' => 'Cliente fidèle', 'location' => 'Dakar', 'text' => 'Les beignets de poisson sont un délice ! Frais, croustillants et pleins de saveur.', 'rating' => '5', 'photo' => '', 'date' => '2025-06-15'),
            array('name' => 'Mamadou Sow', 'role' => 'Restaurant Le Dakar', 'location' => 'Dakar', 'text' => 'Partenaire depuis 3 ans, la qualité est toujours au rendez-vous.', 'rating' => '5', 'photo' => '', 'date' => '2025-05-20'),
            array('name' => 'Fatou Ndiaye', 'role' => 'Gérante épicerie', 'location' => 'Pikine', 'text' => 'Mes clients adorent leurs produits fumés. Livraison toujours ponctuelle.', 'rating' => '5', 'photo' => '', 'date' => '2025-05-10'),
            array('name' => 'Cheikh Fall', 'role' => 'Chef cuisinier', 'location' => 'Dakar', 'text' => 'J\'utilise leurs produits fumés dans ma cuisine depuis 2 ans. Le kong fumé apporte une touche unique à mes plats. Qualité constante, je recommande à tous mes confrères.', 'rating' => '5', 'photo' => '', 'date' => '2025-06-22'),
            array('name' => 'Aïssatou Ba', 'role' => 'Maman active', 'location' => 'Thiès', 'text' => 'Les akkara de Délices de la Mer sont exactement comme ceux de ma grand-mère. Mes enfants en réclament tous les week-ends ! Parfaits pour le petit-déjeuner.', 'rating' => '5', 'photo' => '', 'date' => '2025-06-18'),
            array('name' => 'Ousmane Diallo', 'role' => 'Gérant boutique', 'location' => 'Dakar', 'text' => 'Je vends leurs snacks dans ma boutique et ça se vend comme des petits pains. Les nems poulet sont les plus demandés. Excellent rapport qualité-prix.', 'rating' => '4', 'photo' => '', 'date' => '2025-06-12'),
            array('name' => 'Mariama Sy', 'role' => 'Cliente régulière', 'location' => 'Rufisque', 'text' => 'Le poulet fumé est un régal, tendre et parfumé. Je le sers à chaque repas de famille. La conservation à -18°C est très pratique pour les réserves.', 'rating' => '5', 'photo' => '', 'date' => '2025-06-08'),
            array('name' => 'Ibrahima Ndiaye', 'role' => 'Directeur restauration', 'location' => 'Saly', 'text' => 'Nous travaillons avec eux pour notre hôtel à Saly. Les fataya et pastel sont toujours un succès lors de nos cocktails. Service irréprochable et livraisons ponctuelles.', 'rating' => '5', 'photo' => '', 'date' => '2025-05-28'),
            array('name' => 'Khadija Mbaye', 'role' => 'Traiteur professionnel', 'location' => 'Dakar', 'text' => 'En tant que traiteur, la qualité des produits est essentielle. Leurs quiches et beignets crevette ont fait l\'unanimité lors du mariage que j\'ai organisé. Clients 100% satisfaits.', 'rating' => '5', 'photo' => '', 'date' => '2025-05-15'),
        );
    }

    update_option('dm_testimonials', $defaults);
    update_option('dm_testimonials_seeded', 'yes');
}

/* -------------------------------------------------------------------------- */
/* 2. SEEDER PARTENAIRES                                                       */
/* -------------------------------------------------------------------------- */
add_action('init', 'dm_seed_partners', 20);

function dm_seed_partners()
{
    if (get_option('dm_partners_seeded') === 'yes') return;

    $existing = get_option('dm_partners', array());
    if (is_array($existing) && !empty($existing)) {
        update_option('dm_partners_seeded', 'yes');
        return;
    }

    $partners = array(
        array('name' => 'Novotel', 'logo' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=200&h=200&fit=crop&crop=center'),
        array('name' => 'Carrefour', 'logo' => 'https://images.unsplash.com/photo-1534723452862-4c874018d66d?w=200&h=200&fit=crop&crop=center'),
        array('name' => 'EDK Groupe', 'logo' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=200&h=200&fit=crop&crop=center'),
        array('name' => 'TotalEnergies', 'logo' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=200&h=200&fit=crop&crop=center'),
        array('name' => 'Shell', 'logo' => 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?w=200&h=200&fit=crop&crop=center'),
        array('name' => 'Auchan', 'logo' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=200&h=200&fit=crop&crop=center'),
        array('name' => 'Pullman', 'logo' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=200&h=200&fit=crop&crop=center'),
        array('name' => 'Seter', 'logo' => 'https://images.unsplash.com/photo-1570125909232-eb263c4e96cb?w=200&h=200&fit=crop&crop=center'),
        array('name' => 'Terrou-Bi', 'logo' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=200&h=200&fit=crop&crop=center'),
        array('name' => "Sen'Eau", 'logo' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=200&h=200&fit=crop&crop=center'),
        array('name' => 'SAR', 'logo' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=200&h=200&fit=crop&crop=center'),
    );

    update_option('dm_partners', $partners);
    update_option('dm_partners_seeded', 'yes');
}

/* -------------------------------------------------------------------------- */
/* 3. SEEDER PRODUITS WOOCOMMERCE                                              */
/* -------------------------------------------------------------------------- */
add_action('init', 'dm_seed_new_products', 35);

function dm_seed_new_products()
{
    dm_seed_log('=== dm_seed_new_products started ===');

    if (!class_exists('WooCommerce')) {
        dm_seed_log('ERROR: WooCommerce not active');
        return;
    }

    $force = is_admin() && current_user_can('manage_options') && !empty($_GET['dm_seed_force']);

    if (get_option('dm_new_products_seeded') === 'yes' && !$force) {
        dm_seed_log('Already seeded and no force flag. Skipping.');
        return;
    }

    if ($force) {
        delete_option('dm_new_products_seeded');
        dm_seed_log('FORCE re-run requested by admin');
        // Clear previous log so notice counts reflect this run only
        $log_file = get_stylesheet_directory() . '/seeder-debug.log';
        file_put_contents($log_file, '');
    }

    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    dm_seed_log('Required admin files loaded');

    $theme_uri = get_stylesheet_directory_uri();
    $theme_dir = get_stylesheet_directory();
    dm_seed_log('Theme directory: ' . $theme_dir);

    // Catégories
    $cat_snacks_beignets = dm_ensure_product_cat('Snacks & Beignets', 'snacks-beignets');
    $cat_fumes           = dm_ensure_product_cat('Produits Fumés', 'produits-fumes');
    $cat_promo           = dm_ensure_product_cat('Promotions', 'promotions');

    // Tags utilisés par les produits seedés (images locales uniquement)
    $tag_petit_dej   = dm_ensure_product_tag('petit-dejeuner');
    $tag_aperitif    = dm_ensure_product_tag('aperitif');
    $tag_traditionnel = dm_ensure_product_tag('traditionnel');
    $tag_ceremonie   = dm_ensure_product_tag('ceremonie');
    $tag_farce       = dm_ensure_product_tag('farce');
    $tag_boeuf       = dm_ensure_product_tag('boeuf');
    $tag_asiatique   = dm_ensure_product_tag('asiatique');
    $tag_poulet      = dm_ensure_product_tag('poulet');
    $tag_leger       = dm_ensure_product_tag('leger');
    $tag_poisson     = dm_ensure_product_tag('poisson');
    $tag_cocktail    = dm_ensure_product_tag('cocktail');
    $tag_fume        = dm_ensure_product_tag('fume');
    $tag_surgele     = dm_ensure_product_tag('surgele');
    $tag_volaille    = dm_ensure_product_tag('volaille');

    // Produits avec images locales uniquement (production-safe)
    $products = array(

        // --- Akkara ---
        array(
            'name'        => 'Akkara — Beignets de Niébé (12 pièces)',
            'description' => "<p>Petits beignets traditionnels sénégalais à base de niébé, croustillants à l'extérieur et moelleux à l'intérieur. Parfaits pour un petit-déjeuner authentique ou un apéritif salé.</p>
<h4>Ingrédients</h4><p>Farine de niébé, eau, oignons, sel, piment.</p>
<h4>Mode d'emploi</h4><p>À consommer chaud. Réchauffer à la friteuse quelques secondes ou au four pour maintenir le croustillant.</p>",
            'short_desc'  => "Beignets traditionnels de niébé, croustillants et moelleux. À consommer chaud — réchauffer à la friteuse ou au four.",
            'price'       => '1500',
            'sale_price'  => '',
            'categories'  => array($cat_snacks_beignets),
            'tags'        => array($tag_petit_dej, $tag_aperitif, $tag_traditionnel),
            'image'       => $theme_uri . '/assets/images/gallery/akkara.jpeg',
            'image_path'  => $theme_dir . '/assets/images/gallery/akkara.jpeg',
            'featured'    => true,
            'stock'       => 100,
            'weight'      => '0.4',
            'badge'       => 'À consommer chaud',
        ),

        // --- Fataya ---
        array(
            'name'        => 'Fataya — Chaussons Frits (8 pièces)',
            'description' => "<p>Petits chaussons frits typiques du Sénégal, appréciés pour leur pâte fine et leur farce généreuse. Souvent servis lors des cérémonies.</p>
<h4>Ingrédients</h4><p>Pâte brisée (farine, beurre, eau), farce à la viande hachée ou au poisson, oignons, ail, épices.</p>
<h4>Mode d'emploi</h4><p>Se déguste chaud avec une sauce tomate pimentée. Réchauffer au four ou à la friteuse.</p>",
            'short_desc'  => "Chaussons frits sénégalais à la farce généreuse. À déguster chaud avec sauce tomate pimentée — four ou friteuse.",
            'price'       => '2500',
            'sale_price'  => '',
            'categories'  => array($cat_snacks_beignets),
            'tags'        => array($tag_ceremonie, $tag_traditionnel, $tag_farce),
            'image'       => $theme_uri . '/assets/images/gallery/samoussa.jpeg',
            'image_path'  => $theme_dir . '/assets/images/gallery/samoussa.jpeg',
            'featured'    => true,
            'stock'       => 70,
            'weight'      => '0.4',
            'badge'       => 'À consommer chaud',
        ),

        // --- Nems Bœuf ---
        array(
            'name'        => 'Nems Bœuf (10 pièces)',
            'description' => "<p>Rouleaux de printemps frits garnis d'une farce savoureuse au bœuf haché. Contraste textural marqué entre la galette craquante et le cœur tendre.</p>
<h4>Ingrédients</h4><p>Galette de riz, bœuf haché, vermicelles de riz, carottes, champignons noirs, oignons.</p>
<h4>Mode d'emploi</h4><p>Servir chaud enroulé dans une feuille de salade. Réchauffer exclusivement au four ou à la friteuse.</p>",
            'short_desc'  => "Rouleaux frits au bœuf haché, galette craquante et cœur tendre. À servir chaud enroulé dans une feuille de salade — four ou friteuse.",
            'price'       => '2800',
            'sale_price'  => '',
            'categories'  => array($cat_snacks_beignets),
            'tags'        => array($tag_boeuf, $tag_aperitif, $tag_asiatique),
            'image'       => $theme_uri . '/assets/images/gallery/samoussa1.jpeg',
            'image_path'  => $theme_dir . '/assets/images/gallery/samoussa1.jpeg',
            'featured'    => false,
            'stock'       => 80,
            'weight'      => '0.4',
            'badge'       => 'À consommer chaud',
        ),

        // --- Nems Poulet ---
        array(
            'name'        => 'Nems Poulet (10 pièces)',
            'description' => "<p>Version classique et légère du nem au poulet pour une saveur plus douce qui plaît à tous les palais.</p>
<h4>Ingrédients</h4><p>Galette de riz, blanc de poulet haché, légumes râpés, vermicelles, herbes aromatiques.</p>
<h4>Mode d'emploi</h4><p>À consommer chaud. Four ou friteuse pour garantir le côté crunchy de la galette.</p>",
            'short_desc'  => "Nems au poulet, version légère et douce. À consommer chaud — four ou friteuse pour un croustillant parfait.",
            'price'       => '2600',
            'sale_price'  => '2200',
            'categories'  => array($cat_snacks_beignets, $cat_promo),
            'tags'        => array($tag_poulet, $tag_leger, $tag_aperitif),
            'image'       => $theme_uri . '/assets/images/gallery/nems-poulet.jpeg',
            'image_path'  => $theme_dir . '/assets/images/gallery/nems-poulet.jpeg',
            'featured'    => true,
            'stock'       => 90,
            'weight'      => '0.4',
            'badge'       => 'À consommer chaud',
        ),

        // --- Pastel ---
        array(
            'name'        => 'Pastel — Beignets au Poisson (15 pièces)',
            'description' => "<p>Petits beignets semi-circulaires à base de poisson, parfaits pour les cocktails dînatoires.</p>
<h4>Ingrédients</h4><p>Farine, eau, sel, farce au poisson type thon, oignons, poivre.</p>
<h4>Mode d'emploi</h4><p>À servir chaud. Friteuse pour cuisson rapide ou four pour réchauffage homogène.</p>",
            'short_desc'  => "Beignets semi-circulaires au poisson, parfaits pour cocktails. À servir chaud — friteuse pour cuisson, four pour réchauffage.",
            'price'       => '2200',
            'sale_price'  => '',
            'categories'  => array($cat_snacks_beignets),
            'tags'        => array($tag_poisson, $tag_cocktail, $tag_aperitif),
            'image'       => $theme_uri . '/assets/images/gallery/pain-chinois.jpeg',
            'image_path'  => $theme_dir . '/assets/images/gallery/pain-chinois.jpeg',
            'featured'    => false,
            'stock'       => 75,
            'weight'      => '0.4',
            'badge'       => 'À consommer chaud',
        ),

        // --- Poulet Fumé ---
        array(
            'name'        => 'Poulet Fumé au Beurre de Coco',
            'description' => "<p>Poulet entier fumé au beurre de coco. Méthode traditionnelle de fumage qui garantit une viande tendre et parfumée, avec une peau dorée croustillante.</p>
<h4>Conservation</h4><p><strong>À conserver à -18°C.</strong> Produit surgelé.</p>
<h4>Mode d'emploi</h4><p>Décongélation au réfrigérateur 6h avant consommation. Peut être réchauffé au four à 180°C pendant 20 minutes ou consommé froid en salade.</p>",
            'short_desc'  => "Poulet entier fumé au beurre de coco, viande tendre et parfumée. ⚠️ Surgelé — À conserver à -18°C.",
            'price'       => '4500',
            'sale_price'  => '',
            'categories'  => array($cat_fumes),
            'tags'        => array($tag_fume, $tag_surgele, $tag_volaille),
            'image'       => $theme_uri . '/assets/images/gallery/poulet-grille.jpeg',
            'image_path'  => $theme_dir . '/assets/images/gallery/poulet-grille.jpeg',
            'featured'    => true,
            'stock'       => 40,
            'weight'      => '1.2',
            'badge'       => 'Surgelé',
        ),
    );

    // Cross-sells / Up-sells : associations entre produits existants
    $cross_sells_map = array(
        'Akkara'       => array('Fataya', 'Pastel'),
        'Fataya'       => array('Akkara', 'Pastel'),
        'Nems Bœuf'    => array('Nems Poulet', 'Pastel'),
        'Nems Poulet'  => array('Nems Bœuf', 'Pastel'),
        'Pastel'       => array('Akkara', 'Fataya'),
        'Poulet Fumé'  => array(),
    );

    $created_ids = array();

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
            $created_ids[$p['name']] = $existing_id;
            dm_seed_log('Product EXISTS: "' . $p['name'] . '" (ID: ' . $existing_id . ')');
            // Force re-attach image (delete old thumbnail first)
            delete_post_thumbnail($existing_id);
            dm_seed_log('Old thumbnail deleted for ID ' . $existing_id);
            $ok = dm_set_product_image($p, $existing_id);
            dm_seed_log('Image attachment result for "' . $p['name'] . '": ' . ($ok ? 'SUCCESS' : 'FAILED'));
            continue;
        }

        $product = new WC_Product_Simple();
        $product->set_name($p['name']);
        $product->set_status('publish');
        $product->set_catalog_visibility('visible');
        $product->set_description($p['description']);
        $product->set_short_description($p['short_desc']);
        $product->set_regular_price($p['price']);
        if (!empty($p['sale_price'])) {
            $product->set_sale_price($p['sale_price']);
        }
        $product->set_manage_stock(true);
        $product->set_stock_quantity($p['stock']);
        $product->set_stock_status('instock');
        $product->set_weight($p['weight']);
        $product->set_featured($p['featured']);
        $product->set_reviews_allowed(true);
        $product->set_category_ids($p['categories']);
        if (!empty($p['tags'])) {
            $product->set_tag_ids($p['tags']);
        }

        // Attributs personnalisés : Badge + Conservation
        $attributes = array();

        $badge_attr = new WC_Product_Attribute();
        $badge_attr->set_name('Badge');
        $badge_attr->set_options(array($p['badge']));
        $badge_attr->set_position(0);
        $badge_attr->set_visible(true);
        $badge_attr->set_variation(false);
        $attributes[] = $badge_attr;

        if (strpos($p['badge'], 'Surgelé') !== false) {
            $conserv_attr = new WC_Product_Attribute();
            $conserv_attr->set_name('Conservation');
            $conserv_attr->set_options(array('À conserver à -18°C'));
            $conserv_attr->set_position(1);
            $conserv_attr->set_visible(true);
            $conserv_attr->set_variation(false);
            $attributes[] = $conserv_attr;
        } else {
            $reheat_attr = new WC_Product_Attribute();
            $reheat_attr->set_name('Réchauffage');
            $reheat_attr->set_options(array('Four ou friteuse'));
            $reheat_attr->set_position(1);
            $reheat_attr->set_visible(true);
            $reheat_attr->set_variation(false);
            $attributes[] = $reheat_attr;
        }

        $product->set_attributes($attributes);

        $product_id = $product->save();
        $created_ids[$p['name']] = $product_id;
        dm_seed_log('Product CREATED: "' . $p['name'] . '" (ID: ' . $product_id . ')');

        if ($product_id) {
            $ok = dm_set_product_image($p, $product_id);
            dm_seed_log('Image attachment result for new "' . $p['name'] . '": ' . ($ok ? 'SUCCESS' : 'FAILED'));
        }
    }

    dm_seed_log('=== dm_seed_new_products finished (' . count($created_ids) . ' products processed) ===');

    // Appliquer les cross-sells
    foreach ($cross_sells_map as $product_name => $cross_names) {
        if (!isset($created_ids[$product_name])) continue;
        $cross_ids = array();
        foreach ($cross_names as $cn) {
            if (isset($created_ids[$cn])) {
                $cross_ids[] = $created_ids[$cn];
            }
        }
        if (!empty($cross_ids)) {
            $prod = wc_get_product($created_ids[$product_name]);
            if ($prod) {
                $prod->set_cross_sell_ids($cross_ids);
                $prod->save();
            }
        }
    }

    update_option('dm_new_products_seeded', 'yes');
    set_transient('dm_seeder_products_ran', 'yes', 60);
}

add_action('admin_notices', 'dm_seeder_admin_notice');
function dm_seeder_admin_notice()
{
    $ran = get_transient('dm_seeder_products_ran');
    if ($ran !== 'yes') return;

    $log_file = get_stylesheet_directory() . '/seeder-debug.log';
    $log_url  = get_stylesheet_directory_uri() . '/seeder-debug.log';
    $log_text = file_exists($log_file) ? file_get_contents($log_file) : '';

    $success_count = substr_count($log_text, 'SUCCESS');
    $failed_count  = substr_count($log_text, 'FAILED');

    echo '<div class="notice notice-info">';
    echo '<p><strong>Séeder produits exécuté.</strong> Images attachées avec succès : ' . $success_count . ' | Échecs : ' . $failed_count . '</p>';
    echo '<p>Voir le fichier log : <a href="' . esc_url($log_url) . '" target="_blank">' . esc_html($log_url) . '</a></p>';
    echo '</div>';

    delete_transient('dm_seeder_products_ran');
}

/* -------------------------------------------------------------------------- */
/* Helpers                                                                     */
/* -------------------------------------------------------------------------- */

function dm_set_product_image($product_data, $post_id)
{
    $name = $product_data['name'];

    dm_seed_log('  Attaching image for "' . $name . '" (post_id=' . $post_id . ')');
    dm_seed_log('  image_path: ' . (empty($product_data['image_path']) ? 'EMPTY' : $product_data['image_path']));
    dm_seed_log('  image_url:  ' . (empty($product_data['image_url']) ? 'EMPTY' : $product_data['image_url']));

    // Priority 1: local file (image_path)
    if (!empty($product_data['image_path'])) {
        if (file_exists($product_data['image_path'])) {
            dm_seed_log('  Local file EXISTS');
            $attach_id = dm_insert_attachment_from_file($product_data['image_path'], $post_id, $name);
            if ($attach_id) {
                set_post_thumbnail($post_id, $attach_id);
                dm_seed_log('  Local image attached: ID ' . $attach_id);
                return true;
            }
            dm_seed_log('  Local attachment failed');
        } else {
            dm_seed_log('  ERROR: Local file NOT FOUND: ' . $product_data['image_path']);
        }
    }

    // Priority 2: remote URL (image_url)
    if (!empty($product_data['image_url'])) {
        dm_seed_log('  Trying remote URL');
        $attach_id = dm_insert_attachment_from_url($product_data['image_url'], $post_id, $name);
        if ($attach_id) {
            set_post_thumbnail($post_id, $attach_id);
            dm_seed_log('  Remote image attached: ID ' . $attach_id);
            return true;
        }
        dm_seed_log('  Remote attachment failed');
    }

    dm_seed_log('  No image source available for "' . $name . '"');
    return false;
}

function dm_insert_attachment_from_file($file_path, $post_id, $name)
{
    if (!file_exists($file_path)) {
        dm_seed_log('  dm_insert_attachment_from_file: file not found: ' . $file_path);
        return false;
    }

    $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    $filename = sanitize_title($name) . '.' . $ext;

    $content = file_get_contents($file_path);
    if ($content === false) {
        dm_seed_log('  dm_insert_attachment_from_file: file_get_contents failed');
        return false;
    }
    dm_seed_log('  dm_insert_attachment_from_file: read ' . strlen($content) . ' bytes');

    // Use wp_upload_bits to properly place the file in the uploads directory
    $upload = wp_upload_bits($filename, null, $content);
    if (!empty($upload['error'])) {
        dm_seed_log('  wp_upload_bits ERROR: ' . $upload['error']);
        return false;
    }
    dm_seed_log('  wp_upload_bits OK: file=' . $upload['file'] . ' type=' . $upload['type']);

    $attachment = array(
        'post_mime_type' => $upload['type'],
        'post_title'     => sanitize_file_name($filename),
        'post_content'   => '',
        'post_status'    => 'inherit',
        'post_parent'    => $post_id,
    );

    $attach_id = wp_insert_attachment($attachment, $upload['file'], $post_id);
    if (is_wp_error($attach_id)) {
        dm_seed_log('  wp_insert_attachment ERROR: ' . $attach_id->get_error_message());
        return false;
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
    wp_update_attachment_metadata($attach_id, $attach_data);

    dm_seed_log('  Attachment created from file: ID ' . $attach_id);
    return $attach_id;
}

function dm_insert_attachment_from_url($url, $post_id, $name)
{
    dm_seed_log('  dm_insert_attachment_from_url: ' . $url);

    $tmp = download_url($url);
    if (is_wp_error($tmp)) {
        dm_seed_log('  download_url ERROR: ' . $tmp->get_error_message());
        return false;
    }
    dm_seed_log('  download_url OK: tmp=' . $tmp);

    $ext = 'jpg';
    $path = parse_url($url, PHP_URL_PATH);
    $url_ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if (in_array($url_ext, array('jpg', 'jpeg', 'png', 'gif', 'webp'))) {
        $ext = $url_ext === 'jpeg' ? 'jpg' : $url_ext;
    }

    $filename = sanitize_title($name) . '.' . $ext;

    $file_array = array(
        'name'     => $filename,
        'tmp_name' => $tmp,
    );

    $attach_id = media_handle_sideload($file_array, $post_id);
    @unlink($tmp);

    if (is_wp_error($attach_id)) {
        dm_seed_log('  media_handle_sideload ERROR: ' . $attach_id->get_error_message());
        return false;
    }
    dm_seed_log('  Attachment created from URL: ID ' . $attach_id);
    return $attach_id;
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

function dm_ensure_product_tag($name)
{
    $term = get_term_by('name', $name, 'product_tag');
    if ($term && !is_wp_error($term)) return $term->term_id;
    $result = wp_insert_term($name, 'product_tag');
    if (!is_wp_error($result)) return $result['term_id'];
    return 0;
}
