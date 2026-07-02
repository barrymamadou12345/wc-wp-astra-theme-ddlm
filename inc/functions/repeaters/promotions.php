<?php
/**
 * Promotions — repeater functions, admin page, auto-deactivation.
 *
 * Structure d'une promotion :
 *   - id          : identifiant unique (string)
 *   - title       : titre
 *   - description : description courte
 *   - badge       : texte du badge (ex: "PROMO")
 *   - image       : URL image
 *   - video       : URL vidéo (mp4/youtube/vimeo)
 *   - percentage  : pourcentage de réduction (ex: "20")
 *   - end_date    : date/heure de fin (format Y-m-d H:i) ou vide
 *   - is_active   : 1 ou 0
 *   - products    : array d'IDs de produits WooCommerce
 *   - content     : array de blocs de contenu (repeaters)
 *       chaque bloc : type (title/text/list), title, text, list_items (array)
 */

if (! defined('ABSPATH')) {
    exit;
}

/* -------------------------------------------------------------------------- */
/* Defaults                                                                     */
/* -------------------------------------------------------------------------- */
function dm_default_promotions()
{
    return array();
}

/* Defaults for common sections (editable in admin) */
function dm_default_promo_how_it_works()
{
    return array(
        array('icon' => 'cart', 'title' => 'Sélectionnez vos produits', 'desc' => "Parcourez la liste des produits en promotion et ajoutez-les à votre panier en un clic."),
        array('icon' => 'check', 'title' => 'La réduction s\'applique', 'desc' => "Le pourcentage de réduction est automatiquement déduit du prix dans votre panier."),
        array('icon' => 'truck', 'title' => 'Livraison rapide', 'desc' => "Recevez votre commande en 24-48h à Dakar, directement chez vous ou au bureau."),
        array('icon' => 'coffee', 'title' => 'Dégustez et profitez', 'desc' => "Savourez nos spécialités artisanales à prix réduit. Une qualité irréprochable, un prix imbattable."),
    );
}

function dm_default_promo_benefits()
{
    return array(
        array('icon' => 'fish', 'title' => 'Produits frais', 'desc' => "Tous nos produits sont préparés quotidiennement dans nos ateliers."),
        array('icon' => 'shield', 'title' => 'Qualité certifiée', 'desc' => "Respect strict des normes HACCP et traçabilité complète."),
        array('icon' => 'truck', 'title' => 'Livraison 24-48h', 'desc' => "Service de livraison fiable et ponctuel sur tout Dakar."),
        array('icon' => 'star', 'title' => 'Recettes artisanales', 'desc' => "Des recettes traditionnelles sénégalaises transmises depuis 2016."),
    );
}

function dm_default_promo_faq()
{
    return array(
        array('q' => 'Comment profiter de la promotion ?', 'a' => "C'est très simple ! Ajoutez les produits en promotion à votre panier. La réduction s'applique automatiquement au moment du paiement. Aucun code promo n'est nécessaire."),
        array('q' => 'La promotion est-elle valable sur tous les produits ?', 'a' => "Non, la promotion s'applique uniquement sur les produits sélectionnés et affichés sur cette page. Cherchez le badge de réduction sur chaque produit."),
        array('q' => 'Puis-je cumuler plusieurs offres ?', 'a' => "Les promotions ne sont pas cumulables entre elles. Vous profitez automatiquement de la meilleure offre applicable à votre commande."),
        array('q' => 'Quand la promotion expire-t-elle ?', 'a' => "Chaque promotion a une date de fin indiquée sur cette page. Profitez-en avant la date d'expiration !"),
        array('q' => 'La livraison est-elle incluse dans la promotion ?', 'a' => "La promotion s'applique sur le prix des produits. Les frais de livraison restent standards selon votre zone de livraison à Dakar."),
        array('q' => 'Quels modes de paiement sont acceptés ?', 'a' => "Nous acceptons Orange Money, Wave, et le paiement en espèces à la livraison. Choisissez ce qui vous convient le plus !"),
    );
}

/* Liste des icônes disponibles pour les sections communes */
function dm_promo_icon_options()
{
    return array(
        'cart'     => 'Panier',
        'check'    => 'Coche',
        'truck'    => 'Camion / Livraison',
        'coffee'   => 'Café',
        'fish'     => 'Poisson',
        'shield'   => 'Bouclier / Qualité',
        'star'     => 'Étoile',
        'clock'    => 'Horloge',
        'flame'    => 'Flamme',
        'leaf'     => 'Feuille',
        'heart'    => 'Cœur',
        'utensils' => 'Couverts',
        'package'  => 'Colis',
    );
}

/* Render une ligne "carte" (how it works / benefits) dans l'admin */
function dm_render_promo_card_admin_row($option_key, $index, $row, $icon_options)
{
    $icon  = isset($row['icon']) ? $row['icon'] : 'star';
    $title = isset($row['title']) ? $row['title'] : '';
    $desc  = isset($row['desc']) ? $row['desc'] : '';
    ?>
    <div class="dm-common-row">
        <div class="dm-common-row-grid">
            <label class="dm-common-field dm-common-field-icon">
                <span>Icône</span>
                <select name="<?php echo esc_attr($option_key); ?>[<?php echo esc_attr($index); ?>][icon]">
                    <?php foreach ($icon_options as $val => $label) : ?>
                        <option value="<?php echo esc_attr($val); ?>" <?php selected($icon, $val); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="dm-common-field">
                <span>Titre</span>
                <input type="text" name="<?php echo esc_attr($option_key); ?>[<?php echo esc_attr($index); ?>][title]" value="<?php echo esc_attr($title); ?>" class="regular-text" />
            </label>
            <button type="button" class="button dm-common-remove" title="Supprimer">&times;</button>
        </div>
        <label class="dm-common-field">
            <span>Description</span>
            <textarea name="<?php echo esc_attr($option_key); ?>[<?php echo esc_attr($index); ?>][desc]" rows="2" class="large-text"><?php echo esc_textarea($desc); ?></textarea>
        </label>
    </div>
    <?php
}

/* Render une ligne "FAQ" dans l'admin */
function dm_render_promo_faq_admin_row($option_key, $index, $row)
{
    $q = isset($row['q']) ? $row['q'] : '';
    $a = isset($row['a']) ? $row['a'] : '';
    ?>
    <div class="dm-common-row">
        <div class="dm-common-row-grid">
            <label class="dm-common-field">
                <span>Question</span>
                <input type="text" name="<?php echo esc_attr($option_key); ?>[<?php echo esc_attr($index); ?>][q]" value="<?php echo esc_attr($q); ?>" class="regular-text" />
            </label>
            <button type="button" class="button dm-common-remove" title="Supprimer">&times;</button>
        </div>
        <label class="dm-common-field">
            <span>Réponse</span>
            <textarea name="<?php echo esc_attr($option_key); ?>[<?php echo esc_attr($index); ?>][a]" rows="2" class="large-text"><?php echo esc_textarea($a); ?></textarea>
        </label>
    </div>
    <?php
}

/* Getters for common sections */
function dm_get_promo_how_it_works()
{
    $data = get_option('dm_promo_how_it_works', array());
    if (!is_array($data) || empty($data)) {
        $defaults = dm_default_promo_how_it_works();
        update_option('dm_promo_how_it_works', $defaults);
        return $defaults;
    }
    return $data;
}

function dm_get_promo_benefits()
{
    $data = get_option('dm_promo_benefits', array());
    if (!is_array($data) || empty($data)) {
        $defaults = dm_default_promo_benefits();
        update_option('dm_promo_benefits', $defaults);
        return $defaults;
    }
    return $data;
}

function dm_get_promo_faq()
{
    $data = get_option('dm_promo_faq', array());
    if (!is_array($data) || empty($data)) {
        $defaults = dm_default_promo_faq();
        update_option('dm_promo_faq', $defaults);
        return $defaults;
    }
    return $data;
}

/* -------------------------------------------------------------------------- */
/* Getter — récupère toutes les promotions                                      */
/* -------------------------------------------------------------------------- */
function dm_get_promotions()
{
    $data = get_option('dm_promotions', array());
    if (!is_array($data)) {
        $data = array();
    }
    return $data;
}

/* -------------------------------------------------------------------------- */
/* Getter — récupère uniquement les promotions actives                          */
/* -------------------------------------------------------------------------- */
function dm_get_active_promotions()
{
    $promos = dm_get_promotions();
    $active = array();
    foreach ($promos as $promo) {
        if (!empty($promo['is_active']) && $promo['is_active'] == 1) {
            $active[] = $promo;
        }
    }
    return $active;
}

/* -------------------------------------------------------------------------- */
/* Getter — récupère la première promotion active (pour homepage)               */
/* -------------------------------------------------------------------------- */
function dm_get_current_promotion()
{
    $active = dm_get_active_promotions();
    return !empty($active) ? $active[0] : null;
}

/* -------------------------------------------------------------------------- */
/* Auto-désactivation — vérifie les dates d'échéance au chargement              */
/* -------------------------------------------------------------------------- */
function dm_check_promotion_expirations()
{
    $promos = dm_get_promotions();
    $changed = false;
    $now = current_time('timestamp');

    foreach ($promos as &$promo) {
        if (!empty($promo['is_active']) && $promo['is_active'] == 1 && !empty($promo['end_date'])) {
            $end_ts = strtotime($promo['end_date']);
            if ($end_ts !== false && $end_ts < $now) {
                $promo['is_active'] = 0;
                $changed = true;
            }
        }
    }
    unset($promo);

    if ($changed) {
        update_option('dm_promotions', $promos);
    }
}
add_action('wp', 'dm_check_promotion_expirations', 5);

/* -------------------------------------------------------------------------- */
/* Application RÉELLE des prix promo sur les produits WooCommerce               */
/* -------------------------------------------------------------------------- */
/**
 * Pour chaque produit d'une promotion active (non expirée) avec un pourcentage,
 * applique réellement le tarif promo (sale_price) = tarif régulier × (1 - pct/100).
 * Les produits retirés / promotions désactivées ou expirées sont restaurés.
 * Ainsi la réduction est cohérente partout : boutique, fiche produit, panier, paiement.
 */
function dm_sync_promo_prices()
{
    if (!class_exists('WooCommerce') || !function_exists('wc_get_product')) {
        return;
    }
    // Évite toute récursion (set_sale_price ne touche pas l'option, mais par sécurité)
    static $running = false;
    if ($running) {
        return;
    }
    $running = true;

    $promos = dm_get_promotions();
    if (!is_array($promos)) {
        $promos = array();
    }
    $now = current_time('timestamp');

    // Construire la map product_id => meilleur pourcentage (parmi promos actives non expirées)
    $map = array();
    foreach ($promos as $promo) {
        if (empty($promo['is_active'])) {
            continue;
        }
        if (!empty($promo['end_date'])) {
            $ts = strtotime($promo['end_date']);
            if ($ts !== false && $ts <= $now) {
                continue;
            }
        }
        $pct = isset($promo['percentage']) ? floatval($promo['percentage']) : 0;
        if ($pct <= 0 || $pct >= 100) {
            continue;
        }
        $prods = isset($promo['products']) && is_array($promo['products']) ? $promo['products'] : array();
        foreach ($prods as $pid) {
            $pid = intval($pid);
            if ($pid <= 0) {
                continue;
            }
            if (!isset($map[$pid]) || $pct > $map[$pid]) {
                $map[$pid] = $pct;
            }
        }
    }

    $managed = get_option('dm_promo_managed_products', array());
    if (!is_array($managed)) {
        $managed = array();
    }

    $new_managed = array();

    // Appliquer / mettre à jour les prix
    foreach ($map as $pid => $pct) {
        $product = wc_get_product($pid);
        if (!$product) {
            continue;
        }

        // Tarif régulier de référence
        $regular = $product->get_regular_price();
        if ($regular === '' || $regular === null) {
            $regular = $product->get_price();
        }
        $regular_f = floatval($regular);
        if ($regular_f <= 0) {
            continue;
        }

        // Sauvegarder l'ancien tarif promo une seule fois (pour restauration ultérieure)
        if (get_post_meta($pid, '_dm_promo_managed', true) === '') {
            update_post_meta($pid, '_dm_promo_prev_sale', (string) $product->get_sale_price());
        }

        $sale = round($regular_f * (1 - $pct / 100), 2);

        $product->set_regular_price((string) $regular_f);
        $product->set_sale_price((string) $sale);
        $product->save();

        update_post_meta($pid, '_dm_promo_managed', '1');
        update_post_meta($pid, '_dm_promo_pct', $pct);

        $new_managed[] = $pid;
    }

    // Restaurer les produits qui ne sont plus en promo
    foreach ($managed as $pid) {
        $pid = intval($pid);
        if (in_array($pid, $new_managed, true)) {
            continue;
        }
        $product = wc_get_product($pid);
        if (!$product) {
            delete_post_meta($pid, '_dm_promo_managed');
            delete_post_meta($pid, '_dm_promo_prev_sale');
            delete_post_meta($pid, '_dm_promo_pct');
            continue;
        }
        $prev_sale = get_post_meta($pid, '_dm_promo_prev_sale', true);
        $product->set_sale_price($prev_sale !== '' ? (string) $prev_sale : '');
        $product->save();

        delete_post_meta($pid, '_dm_promo_managed');
        delete_post_meta($pid, '_dm_promo_prev_sale');
        delete_post_meta($pid, '_dm_promo_pct');
    }

    update_option('dm_promo_managed_products', array_values(array_unique($new_managed)));

    $running = false;
}
// Resynchronise dès que les promotions changent (sauvegarde admin, expiration auto, seeder)
add_action('update_option_dm_promotions', 'dm_sync_promo_prices', 20, 0);
add_action('add_option_dm_promotions', 'dm_sync_promo_prices', 20, 0);

/* -------------------------------------------------------------------------- */
/* Badge de réduction dynamique (-X%) partout où le produit est en promo        */
/* -------------------------------------------------------------------------- */
add_filter('woocommerce_sale_flash', function ($html, $post, $product) {
    if (!$product instanceof WC_Product) {
        return $html;
    }
    $regular = (float) $product->get_regular_price();
    $sale    = (float) $product->get_sale_price();
    if ($regular > 0 && $sale > 0 && $sale < $regular) {
        $pct = (int) round(($regular - $sale) / $regular * 100);
        if ($pct > 0) {
            return '<span class="onsale dm-onsale">-' . esc_html($pct) . '%</span>';
        }
    }
    return $html;
}, 10, 3);

/**
 * Retourne le texte du badge de réduction pour un produit en promo.
 * Calcule le pourcentage à partir des prix WC réels (regular / sale).
 */
function dm_get_promo_badge_text($product_id, $fallback_percentage = '')
{
    $product = wc_get_product($product_id);
    if (!$product) {
        return $fallback_percentage ? '-' . $fallback_percentage . '%' : '';
    }
    $regular = (float) $product->get_regular_price();
    $sale    = (float) $product->get_sale_price();
    if ($regular > 0 && $sale > 0 && $sale < $regular) {
        $pct = (int) round(($regular - $sale) / $regular * 100);
        if ($pct > 0) {
            return '-' . $pct . '%';
        }
    }
    return $fallback_percentage ? '-' . $fallback_percentage . '%' : '';
}

/* -------------------------------------------------------------------------- */
/* Sanitize                                                                     */
/* -------------------------------------------------------------------------- */
function dm_sanitize_promotions($input)
{
    $clean = array();
    if (!is_array($input)) {
        return $clean;
    }

    foreach ($input as $row) {
        if (!is_array($row)) {
            continue;
        }
        $has_data = false;
        $item = array();

        $item['id']          = isset($row['id']) ? sanitize_text_field($row['id']) : '';
        $item['title']       = isset($row['title']) ? sanitize_text_field($row['title']) : '';
        $item['description'] = isset($row['description']) ? sanitize_textarea_field($row['description']) : '';
        $item['badge']       = isset($row['badge']) ? sanitize_text_field($row['badge']) : '';
        $item['image']       = isset($row['image']) ? esc_url_raw($row['image']) : '';
        $item['video']       = isset($row['video']) ? esc_url_raw($row['video']) : '';
        $item['percentage']  = isset($row['percentage']) ? sanitize_text_field($row['percentage']) : '';
        $item['end_date']    = isset($row['end_date']) ? sanitize_text_field($row['end_date']) : '';
        $item['is_active']   = isset($row['is_active']) ? 1 : 0;

        // Products (array of IDs)
        $item['products'] = array();
        if (isset($row['products']) && is_array($row['products'])) {
            foreach ($row['products'] as $pid) {
                $pid = intval($pid);
                if ($pid > 0) {
                    $item['products'][] = $pid;
                }
            }
        }

        // Content blocks (repeaters)
        $item['content'] = array();
        if (isset($row['content']) && is_array($row['content'])) {
            foreach ($row['content'] as $block) {
                if (!is_array($block)) {
                    continue;
                }
                $b = array(
                    'type'       => isset($block['type']) ? sanitize_text_field($block['type']) : 'text',
                    'title'      => isset($block['title']) ? sanitize_text_field($block['title']) : '',
                    'text'       => isset($block['text']) ? sanitize_textarea_field($block['text']) : '',
                    'list_items' => array(),
                );
                if (isset($block['list_items'])) {
                    $items = is_array($block['list_items'])
                        ? $block['list_items']
                        : preg_split('/\r\n|\r|\n/', (string) $block['list_items']);
                    foreach ($items as $li) {
                        $li = sanitize_text_field($li);
                        if ($li !== '') {
                            $b['list_items'][] = $li;
                        }
                    }
                }
                if ($b['title'] !== '' || $b['text'] !== '' || !empty($b['list_items'])) {
                    $item['content'][] = $b;
                }
            }
        }

        if ($item['title'] !== '' || $item['description'] !== '' || !empty($item['products'])) {
            $has_data = true;
        }
        if ($has_data || !empty($item['id'])) {
            if (empty($item['id'])) {
                $item['id'] = 'promo_' . substr(md5(uniqid(mt_rand(), true)), 0, 10);
            }
            $clean[] = $item;
        }
    }

    return $clean;
}

/* -------------------------------------------------------------------------- */
/* AJAX — recherche de produits WooCommerce pour Select2                        */
/* -------------------------------------------------------------------------- */
add_action('wp_ajax_dm_search_products', function () {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }
    $term = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '';
    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 20,
        's'              => $term,
    );
    $query = new WP_Query($args);
    $results = array();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $results[] = array(
                'id'   => get_the_ID(),
                'text' => get_the_title(),
            );
        }
        wp_reset_postdata();
    }
    wp_send_json(array('results' => $results));
});

/* -------------------------------------------------------------------------- */
/* Sanitize — sections communes                                                 */
/* -------------------------------------------------------------------------- */
function dm_sanitize_promo_cards($input)
{
    $clean = array();
    if (!is_array($input)) {
        return $clean;
    }
    foreach ($input as $row) {
        if (!is_array($row)) {
            continue;
        }
        $icon  = isset($row['icon']) ? sanitize_text_field($row['icon']) : 'star';
        $title = isset($row['title']) ? sanitize_text_field($row['title']) : '';
        $desc  = isset($row['desc']) ? sanitize_textarea_field($row['desc']) : '';
        if ($title !== '' || $desc !== '') {
            $clean[] = array('icon' => $icon, 'title' => $title, 'desc' => $desc);
        }
    }
    return $clean;
}

function dm_sanitize_promo_faq($input)
{
    $clean = array();
    if (!is_array($input)) {
        return $clean;
    }
    foreach ($input as $row) {
        if (!is_array($row)) {
            continue;
        }
        $q = isset($row['q']) ? sanitize_text_field($row['q']) : '';
        $a = isset($row['a']) ? sanitize_textarea_field($row['a']) : '';
        if ($q !== '' || $a !== '') {
            $clean[] = array('q' => $q, 'a' => $a);
        }
    }
    return $clean;
}

/* -------------------------------------------------------------------------- */
/* Register settings                                                            */
/* -------------------------------------------------------------------------- */
add_action('admin_init', function () {
    register_setting('dm_promotions_group', 'dm_promotions', array(
        'type'              => 'array',
        'sanitize_callback' => 'dm_sanitize_promotions',
        'default'           => array(),
    ));
    register_setting('dm_promotions_group', 'dm_promo_how_it_works', array(
        'type'              => 'array',
        'sanitize_callback' => 'dm_sanitize_promo_cards',
        'default'           => array(),
    ));
    register_setting('dm_promotions_group', 'dm_promo_benefits', array(
        'type'              => 'array',
        'sanitize_callback' => 'dm_sanitize_promo_cards',
        'default'           => array(),
    ));
    register_setting('dm_promotions_group', 'dm_promo_faq', array(
        'type'              => 'array',
        'sanitize_callback' => 'dm_sanitize_promo_faq',
        'default'           => array(),
    ));
});

/* -------------------------------------------------------------------------- */
/* Enqueue admin scripts (Select2 + media uploader)                             */
/* -------------------------------------------------------------------------- */
add_action('admin_enqueue_scripts', function ($hook) {
    if (isset($_GET['page']) && $_GET['page'] === 'dm-promotions') {
        wp_enqueue_media();
        wp_enqueue_script('jquery');

        // Admin JS for promotions
        wp_enqueue_script('dm-promotions-admin', DM_THEME_URI . '/assets/js/promotions-admin.js', array('jquery'), dm_asset_ver('assets/js/promotions-admin.js'), true);
        wp_localize_script('dm-promotions-admin', 'dmPromoAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('dm-promo-admin'),
        ));
    }
});

/* -------------------------------------------------------------------------- */
/* Sous-menu "Promotions" sous Délices Content                                  */
/* -------------------------------------------------------------------------- */
add_action('admin_menu', function () {
    add_submenu_page(
        'dm-content',
        'Promotions',
        'Promotions',
        'manage_options',
        'dm-promotions',
        'dm_promotions_page_html'
    );
}, 30);

/* -------------------------------------------------------------------------- */
/* Page HTML — repeater dynamique promotions                                    */
/* -------------------------------------------------------------------------- */
function dm_promotions_page_html()
{
    if (!current_user_can('manage_options')) {
        return;
    }
    $promos = dm_get_promotions();
    if (!is_array($promos)) {
        $promos = array();
    }
    $how_it_works = dm_get_promo_how_it_works();
    $benefits     = dm_get_promo_benefits();
    $faq          = dm_get_promo_faq();
    $icon_options = dm_promo_icon_options();
    ?>
    <div class="wrap dm-promo-admin-wrap">
        <h1>Délices de la Mer — Promotions</h1>
        <p>Créez et gérez vos promotions. Les promotions actives s'affichent automatiquement sur la page d'accueil et la page <code>/promotions</code>. À la date d'échéance, la promotion se désactive automatiquement.</p>

        <style>
        .dm-promo-admin-wrap { max-width: 1200px; }
        .dm-promo-row {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 24px;
            margin-bottom: 24px;
            position: relative;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .dm-promo-row-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }
        .dm-promo-row-title {
            margin: 0;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .dm-promo-status-badge {
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .dm-promo-status-active { background: #46b450; color: #fff; }
        .dm-promo-status-inactive { background: #bbb; color: #fff; }
        .dm-promo-remove { color: #a00 !important; border-color: #a00 !important; }
        .dm-promo-remove:hover { background: #a00 !important; color: #fff !important; }

        .dm-promo-fields-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        .dm-promo-field { display: flex; flex-direction: column; gap: 6px; }
        .dm-promo-field-full { grid-column: 1 / -1; }
        .dm-promo-field-label {
            font-weight: 600;
            font-size: 13px;
            color: #1d2327;
        }
        .dm-promo-field-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
        }
        .dm-promo-field-input:focus {
            border-color: #ff6b00;
            box-shadow: 0 0 0 1px #ff6b00;
            outline: none;
        }
        .dm-promo-field-textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            resize: vertical;
        }
        .dm-promo-field-textarea:focus {
            border-color: #ff6b00;
            box-shadow: 0 0 0 1px #ff6b00;
            outline: none;
        }
        .dm-promo-field-small { max-width: 120px; }
        .dm-promo-field-with-suffix {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .dm-promo-field-suffix {
            font-size: 18px;
            font-weight: 700;
            color: #ff6b00;
        }
        .dm-promo-field-hint {
            font-size: 12px;
            color: #666;
            margin: 2px 0 0;
        }
        .dm-promo-media-row {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        .dm-promo-media-row .dm-promo-field-input { flex: 1; min-width: 200px; }
        .dm-promo-img-preview {
            max-width: 120px;
            max-height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #ddd;
        }
        .dm-promo-checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            cursor: pointer;
        }

        /* Section produits en promotion (en bas de la carte) */
        .dm-promo-products-section {
            margin-top: 28px;
            padding-top: 24px;
            border-top: 2px solid #f0f0f0;
        }
        .dm-promo-products-heading {
            margin: 0 0 4px;
            font-size: 15px;
            font-weight: 700;
            color: #024276;
        }
        .dm-promo-products-hint {
            font-size: 12px;
            color: #666;
            margin: 0 0 16px;
            line-height: 1.5;
        }

        /* Chips des produits sélectionnés */
        .dm-promo-selected-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
            min-height: 32px;
            padding: 10px;
            background: #fafafa;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
        }
        .dm-promo-selected-list:empty::after {
            content: "Aucun produit sélectionné";
            color: #aaa;
            font-size: 13px;
            font-style: italic;
        }
        .dm-promo-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff5ed;
            border: 1px solid #ff6b00;
            color: #cc5500;
            border-radius: 20px;
            padding: 4px 8px 4px 12px;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.4;
            transition: background 0.15s, border-color 0.15s;
        }
        .dm-promo-chip:hover {
            background: #ffe8d6;
            border-color: #e55a00;
        }
        .dm-promo-chip-remove {
            background: none;
            border: none;
            color: #cc5500;
            font-size: 18px;
            line-height: 1;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s, color 0.15s;
            padding: 0;
        }
        .dm-promo-chip-remove:hover {
            color: #fff;
            background: #cc5500;
        }

        /* Barre de recherche + dropdown custom */
        .dm-promo-search-wrapper {
            position: relative;
        }
        .dm-promo-search-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
        }
        .dm-promo-search-input:focus {
            border-color: #ff6b00;
            box-shadow: 0 0 0 1px #ff6b00;
            outline: none;
        }
        .dm-promo-search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            margin-top: 4px;
            background: #f7f7f7;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            z-index: 999999;
            max-height: 300px;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        .dm-promo-search-item {
            padding: 10px 14px;
            font-size: 13px;
            color: #1d2327;
            border-bottom: 1px solid #e5e5e5;
            cursor: pointer;
            transition: background 0.1s;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }
        .dm-promo-search-item:last-child {
            border-bottom: none;
        }
        .dm-promo-search-item:hover {
            background: #ff6b00;
            color: #fff;
        }
        .dm-promo-search-item.is-selected {
            background: #f0f6fc;
            color: #2271b1;
            font-weight: 600;
        }
        .dm-promo-search-item.is-selected::before {
            content: "✓ ";
            font-weight: 700;
        }
        .dm-promo-search-item.is-selected:hover {
            background: #ff6b00;
            color: #fff;
        }
        .dm-promo-search-status {
            padding: 14px;
            font-size: 13px;
            color: #666;
            text-align: center;
        }

        /* Content blocks */
        .dm-promo-content-section {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 2px solid #f0f0f0;
        }
        .dm-promo-content-heading {
            margin: 0 0 6px;
            font-size: 14px;
            font-weight: 700;
        }
        .dm-promo-content-hint {
            font-size: 12px;
            color: #666;
            margin: 0 0 12px;
        }
        .dm-promo-content-block {
            background: #fafafa;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
        }
        .dm-promo-content-block-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .dm-promo-content-block input[type="text"],
        .dm-promo-content-block textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        .dm-promo-content-block input[type="text"]:focus,
        .dm-promo-content-block textarea:focus {
            border-color: #ff6b00;
            box-shadow: 0 0 0 1px #ff6b00;
            outline: none;
        }
        .dm-promo-content-add { margin-top: 10px; }

        /* Sections communes */
        .dm-common-sep { margin: 40px 0 24px; border: none; border-top: 2px solid #e0e0e0; }
        .dm-common-heading { font-size: 20px; margin-bottom: 4px; }
        .dm-common-section {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px 24px;
            margin: 16px 0 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .dm-common-section h3 {
            margin: 0 0 16px;
            font-size: 15px;
            color: #024276;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 10px;
        }
        .dm-common-row {
            background: #fafafa;
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            padding: 14px 16px;
            margin-bottom: 12px;
        }
        .dm-common-row-grid {
            display: flex;
            gap: 12px;
            align-items: flex-end;
            margin-bottom: 10px;
        }
        .dm-common-field { display: flex; flex-direction: column; flex: 1; min-width: 0; }
        .dm-common-field > span {
            font-size: 12px;
            font-weight: 600;
            color: #555;
            margin-bottom: 4px;
        }
        .dm-common-field input[type="text"],
        .dm-common-field select,
        .dm-common-field textarea {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
        .dm-common-field-icon { flex: 0 0 200px; }
        .dm-common-remove {
            flex: 0 0 auto;
            color: #a00 !important;
            border-color: #a00 !important;
            font-size: 18px !important;
            line-height: 1 !important;
            height: 32px;
            padding: 0 12px !important;
        }
        .dm-common-add { margin-top: 6px; }

        @media (max-width: 782px) {
            .dm-promo-fields-grid { grid-template-columns: 1fr; }
            .dm-common-row-grid { flex-direction: column; align-items: stretch; }
            .dm-common-field-icon { flex: 1; }
        }
        </style>

        <form method="post" action="options.php">
            <?php settings_fields('dm_promotions_group'); ?>

            <div id="dm-promo-repeater">
                <?php if (!empty($promos)) : ?>
                    <?php foreach ($promos as $i => $promo) : ?>
                        <?php dm_render_promo_row($i, $promo); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <button type="button" class="button button-primary button-large" id="dm-promo-add">
                + Ajouter une promotion
            </button>

            <hr class="dm-common-sep" />

            <h2 class="dm-common-heading">Sections communes de la page promotions</h2>
            <p class="description">Ces sections s'affichent sur la page <code>/promotions</code> sous les produits. Elles sont indépendantes des promotions individuelles.</p>

            <!-- Comment ça marche -->
            <div class="dm-common-section" data-option="dm_promo_how_it_works" data-type="card">
                <h3>Comment ça marche</h3>
                <div class="dm-common-list">
                    <?php foreach ($how_it_works as $i => $row) : ?>
                        <?php dm_render_promo_card_admin_row('dm_promo_how_it_works', $i, $row, $icon_options); ?>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button dm-common-add">+ Ajouter une étape</button>
            </div>

            <!-- Pourquoi en profiter -->
            <div class="dm-common-section" data-option="dm_promo_benefits" data-type="card">
                <h3>Pourquoi en profiter (avantages)</h3>
                <div class="dm-common-list">
                    <?php foreach ($benefits as $i => $row) : ?>
                        <?php dm_render_promo_card_admin_row('dm_promo_benefits', $i, $row, $icon_options); ?>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button dm-common-add">+ Ajouter un avantage</button>
            </div>

            <!-- FAQ -->
            <div class="dm-common-section" data-option="dm_promo_faq" data-type="faq">
                <h3>FAQ — Questions fréquentes</h3>
                <div class="dm-common-list">
                    <?php foreach ($faq as $i => $row) : ?>
                        <?php dm_render_promo_faq_admin_row('dm_promo_faq', $i, $row); ?>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button dm-common-add">+ Ajouter une question</button>
            </div>

            <!-- Templates pour lignes communes (clonés par JS) -->
            <script type="text/html" id="dm-common-card-template">
                <?php dm_render_promo_card_admin_row('__OPTION__', '__INDEX__', array('icon' => 'star', 'title' => '', 'desc' => ''), $icon_options); ?>
            </script>
            <script type="text/html" id="dm-common-faq-template">
                <?php dm_render_promo_faq_admin_row('__OPTION__', '__INDEX__', array('q' => '', 'a' => '')); ?>
            </script>

            <p class="submit" style="margin-top: 20px;">
                <button type="submit" class="button button-primary button-large">Enregistrer les promotions</button>
            </p>
        </form>
    </div>

    <!-- Template pour nouvelle promotion (cloné par JS) -->
    <script type="text/html" id="dm-promo-template">
        <?php dm_render_promo_row('__INDEX__', array(
            'id'          => '',
            'title'       => '',
            'description' => '',
            'badge'       => '',
            'image'       => '',
            'video'       => '',
            'percentage'  => '',
            'end_date'    => '',
            'is_active'   => 1,
            'products'    => array(),
            'content'     => array(),
        )); ?>
    </script>
    <?php
}

/* -------------------------------------------------------------------------- */
/* Render une ligne de promotion (repeater)                                     */
/* -------------------------------------------------------------------------- */
function dm_render_promo_row($index, $promo)
{
    $id          = isset($promo['id']) ? $promo['id'] : '';
    $title       = isset($promo['title']) ? $promo['title'] : '';
    $description = isset($promo['description']) ? $promo['description'] : '';
    $badge       = isset($promo['badge']) ? $promo['badge'] : '';
    $image       = isset($promo['image']) ? $promo['image'] : '';
    $video       = isset($promo['video']) ? $promo['video'] : '';
    $percentage  = isset($promo['percentage']) ? $promo['percentage'] : '';
    $end_date    = isset($promo['end_date']) ? $promo['end_date'] : '';
    $is_active   = isset($promo['is_active']) ? $promo['is_active'] : 0;
    $products    = isset($promo['products']) && is_array($promo['products']) ? $promo['products'] : array();
    $content     = isset($promo['content']) && is_array($promo['content']) ? $promo['content'] : array();

    // Récupérer les noms des produits pour Select2 préselectionnés
    $selected_products = array();
    foreach ($products as $pid) {
        $post = get_post($pid);
        if ($post) {
            $selected_products[] = array('id' => $pid, 'text' => $post->post_title);
        }
    }
    ?>
    <div class="dm-promo-row" data-index="<?php echo esc_attr($index); ?>">

        <!-- En-tête de la promotion -->
        <div class="dm-promo-row-header">
            <h3 class="dm-promo-row-title">
                <span class="dm-promo-title-display"><?php echo esc_html($title ? $title : 'Nouvelle promotion'); ?></span>
                <?php if ($is_active) : ?>
                    <span class="dm-promo-status-badge dm-promo-status-active">Active</span>
                <?php else : ?>
                    <span class="dm-promo-status-badge dm-promo-status-inactive">Inactive</span>
                <?php endif; ?>
            </h3>
            <button type="button" class="button dm-promo-remove">Supprimer</button>
        </div>

        <input type="hidden" name="dm_promotions[<?php echo esc_attr($index); ?>][id]" value="<?php echo esc_attr($id); ?>" class="dm-promo-id" />

        <div class="dm-promo-fields-grid">
            <div class="dm-promo-field">
                <label class="dm-promo-field-label">Titre</label>
                <input type="text" name="dm_promotions[<?php echo esc_attr($index); ?>][title]" value="<?php echo esc_attr($title); ?>" class="dm-promo-field-input dm-promo-title-input" placeholder="Ex: Promotion de fin d'année" />
            </div>
            <div class="dm-promo-field">
                <label class="dm-promo-field-label">Badge</label>
                <input type="text" name="dm_promotions[<?php echo esc_attr($index); ?>][badge]" value="<?php echo esc_attr($badge); ?>" class="dm-promo-field-input" placeholder="Ex: PROMO, SOLDES, -20%" />
            </div>
            <div class="dm-promo-field">
                <label class="dm-promo-field-label">Pourcentage de réduction</label>
                <div class="dm-promo-field-with-suffix">
                    <input type="text" name="dm_promotions[<?php echo esc_attr($index); ?>][percentage]" value="<?php echo esc_attr($percentage); ?>" class="dm-promo-field-input dm-promo-field-small" placeholder="Ex: 20" />
                    <span class="dm-promo-field-suffix">%</span>
                </div>
            </div>
            <div class="dm-promo-field">
                <label class="dm-promo-field-label">Date / heure de fin</label>
                <input type="text" name="dm_promotions[<?php echo esc_attr($index); ?>][end_date]" value="<?php echo esc_attr($end_date); ?>" class="dm-promo-field-input dm-promo-datepicker" placeholder="AAAA-MM-JJ HH:MM (ex: 2025-12-31 23:59)" />
                <p class="dm-promo-field-hint">Laisser vide pour une promotion sans expiration. Désactivation auto à cette date.</p>
            </div>
            <div class="dm-promo-field dm-promo-field-full">
                <label class="dm-promo-field-label">Description</label>
                <textarea name="dm_promotions[<?php echo esc_attr($index); ?>][description]" rows="2" class="dm-promo-field-textarea" placeholder="Description courte affichée sur la carte d'accueil"><?php echo esc_textarea($description); ?></textarea>
            </div>
            <div class="dm-promo-field dm-promo-field-full">
                <label class="dm-promo-field-label">Image de la promotion</label>
                <div class="dm-promo-media-row">
                    <input type="text" name="dm_promotions[<?php echo esc_attr($index); ?>][image]" value="<?php echo esc_attr($image); ?>" class="dm-promo-field-input dm-promo-img-input" placeholder="URL de l'image" />
                    <button type="button" class="button dm-promo-img-upload">Choisir une image</button>
                    <?php if (!empty($image)) : ?>
                        <img src="<?php echo esc_url($image); ?>" alt="" class="dm-promo-img-preview" />
                    <?php else : ?>
                        <img src="" alt="" class="dm-promo-img-preview" style="display:none;" />
                    <?php endif; ?>
                </div>
            </div>
            <div class="dm-promo-field dm-promo-field-full">
                <label class="dm-promo-field-label">Vidéo (URL ou fichier local)</label>
                <div class="dm-promo-media-row">
                    <input type="text" name="dm_promotions[<?php echo esc_attr($index); ?>][video]" value="<?php echo esc_attr($video); ?>" class="dm-promo-field-input dm-promo-video-input" placeholder="URL YouTube, Vimeo ou MP4 direct" />
                    <button type="button" class="button dm-promo-video-upload">Choisir une vidéo</button>
                </div>
                <p class="dm-promo-field-hint">Collez un lien YouTube/Vimeo OU sélectionnez un fichier vidéo depuis votre bibliothèque média.</p>
            </div>
            <div class="dm-promo-field dm-promo-field-full">
                <label class="dm-promo-field-label">Produits en promotion</label>
                <p class="dm-promo-products-hint">Recherchez et sélectionnez les produits WooCommerce. Le pourcentage de réduction sera automatiquement appliqué aux prix.</p>

                <!-- Produits déjà sélectionnés -->
                <div class="dm-promo-selected-list" data-index="<?php echo esc_attr($index); ?>">
                    <?php foreach ($selected_products as $sp) : ?>
                        <span class="dm-promo-chip" data-pid="<?php echo esc_attr($sp['id']); ?>">
                            <?php echo esc_html($sp['text']); ?>
                            <button type="button" class="dm-promo-chip-remove">&times;</button>
                        </span>
                        <input type="hidden" name="dm_promotions[<?php echo esc_attr($index); ?>][products][]" value="<?php echo esc_attr($sp['id']); ?>" class="dm-promo-product-hidden" data-pid="<?php echo esc_attr($sp['id']); ?>">
                    <?php endforeach; ?>
                </div>

                <!-- Barre de recherche + dropdown custom -->
                <div class="dm-promo-search-wrapper">
                    <input type="text" class="dm-promo-search-input" placeholder="Rechercher un produit…" autocomplete="off">
                    <div class="dm-promo-search-dropdown" style="display:none;"></div>
                </div>
            </div>
            <div class="dm-promo-field dm-promo-field-full">
                <label class="dm-promo-field-label">Statut</label>
                <label class="dm-promo-checkbox-label">
                    <input type="checkbox" name="dm_promotions[<?php echo esc_attr($index); ?>][is_active]" value="1" <?php checked($is_active, 1); ?> />
                    Promotion active (visible sur le site)
                </label>
            </div>
        </div>

        <!-- Blocs de contenu (repeaters) -->
        <div class="dm-promo-content-section">
            <h4 class="dm-promo-content-heading">Blocs de contenu (page de détail)</h4>
            <p class="dm-promo-content-hint">Ajoutez des titres, textes et listes pour enrichir la page de promotion.</p>

            <div class="dm-promo-content-list">
                <?php if (!empty($content)) : ?>
                    <?php foreach ($content as $ci => $block) : ?>
                        <?php dm_render_content_block($index, $ci, $block); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <button type="button" class="button dm-promo-content-add">+ Ajouter un bloc</button>
        </div>
    </div>
    <?php
}

/* -------------------------------------------------------------------------- */
/* Render un bloc de contenu                                                    */
/* -------------------------------------------------------------------------- */
function dm_render_content_block($promo_index, $block_index, $block = array())
{
    $type       = isset($block['type']) ? $block['type'] : 'text';
    $title      = isset($block['title']) ? $block['title'] : '';
    $text       = isset($block['text']) ? $block['text'] : '';
    $list_items = isset($block['list_items']) && is_array($block['list_items']) ? $block['list_items'] : array();
    ?>
    <div class="dm-promo-content-block">
        <div class="dm-promo-content-block-header">
            <select name="dm_promotions[<?php echo esc_attr($promo_index); ?>][content][<?php echo esc_attr($block_index); ?>][type]" class="dm-content-type">
                <option value="title" <?php selected($type, 'title'); ?>>Titre</option>
                <option value="text" <?php selected($type, 'text'); ?>>Texte</option>
                <option value="list" <?php selected($type, 'list'); ?>>Liste à puces</option>
            </select>
            <button type="button" class="button dm-content-remove" style="color:#a00;border-color:#a00;">Retirer</button>
        </div>
        <p>
            <input type="text" name="dm_promotions[<?php echo esc_attr($promo_index); ?>][content][<?php echo esc_attr($block_index); ?>][title]" value="<?php echo esc_attr($title); ?>" class="regular-text" placeholder="Titre du bloc" />
        </p>
        <p class="dm-content-text-field">
            <textarea name="dm_promotions[<?php echo esc_attr($promo_index); ?>][content][<?php echo esc_attr($block_index); ?>][text]" rows="3" class="large-text" placeholder="Texte du bloc"><?php echo esc_textarea($text); ?></textarea>
        </p>
        <div class="dm-content-list-field" style="<?php echo $type === 'list' ? '' : 'display:none;'; ?>">
            <p class="description">Ajoutez un élément par ligne :</p>
            <textarea name="dm_promotions[<?php echo esc_attr($promo_index); ?>][content][<?php echo esc_attr($block_index); ?>][list_items]" rows="4" class="large-text" placeholder="Un élément par ligne"><?php echo esc_textarea(implode("\n", $list_items)); ?></textarea>
        </div>
    </div>
    <?php
}
