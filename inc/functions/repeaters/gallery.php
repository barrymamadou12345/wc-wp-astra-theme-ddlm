<?php
/**
 * Repeater: Galerie
 * Page: /galerie
 *
 * Champs par item galerie :
 *   - type        : 'image' ou 'video'
 *   - url         : URL de l'image ou de la vidéo (YouTube/Vimeo/MP4)
 *   - thumbnail   : URL de la miniature (pour les vidéos)
 *   - title       : Titre
 *   - description : Description (max 3 lignes en affichage)
 *   - category    : Catégorie pour le filtrage dynamique (case-insensitive)
 *   - date_added  : Date d'ajout (Y-m-d H:i:s) pour filtrage par année
 *   - likes       : Nombre de likes (int) — lecture seule côté admin
 *   - dislikes    : Nombre de dislikes (int) — lecture seule côté admin
 *   - hearts      : Nombre de hearts (int) — lecture seule côté admin
 *
 * Les items sont stockés du plus ancien au plus récent.
 * L'affichage se fait du plus récent au plus ancien (array_reverse).
 */

if (! defined('ABSPATH')) {
    exit;
}

/* -------------------------------------------------------------------------- */
/* Getter                                                                       */
/* -------------------------------------------------------------------------- */
function dm_get_gallery_items()
{
    $data = get_option('dm_gallery_items', array());
    if (!is_array($data)) {
        return array();
    }
    return $data;
}

function dm_get_gallery_items_reversed()
{
    return array_reverse(dm_get_gallery_items(), true);
}

/* -------------------------------------------------------------------------- */
/* Réactions (likes / dislikes / hearts)                                        */
/* Stockées dans une option SÉPARÉE et NON enregistrée via register_setting.    */
/* Raison : dm_gallery_items est nettoyée par dm_sanitize_gallery_items() à      */
/* chaque update_option (le hook sanitize_option_* est ajouté par               */
/* register_setting et s'exécute aussi pendant admin-ajax via admin_init),      */
/* ce qui réinitialiserait systématiquement les compteurs. En isolant les       */
/* réactions, les incréments persistent réellement pour tous les visiteurs.     */
/* -------------------------------------------------------------------------- */
function dm_get_gallery_reactions()
{
    $r = get_option('dm_gallery_reactions', array());
    return is_array($r) ? $r : array();
}

function dm_get_item_reactions($index)
{
    $all = dm_get_gallery_reactions();
    $r = (isset($all[$index]) && is_array($all[$index])) ? $all[$index] : array();
    return array(
        'likes'    => intval($r['likes'] ?? 0),
        'dislikes' => intval($r['dislikes'] ?? 0),
        'hearts'   => intval($r['hearts'] ?? 0),
    );
}

function dm_get_gallery_categories()
{
    $items = dm_get_gallery_items();
    $cats = array();
    foreach ($items as $item) {
        $cat = $item['category'] ?? '';
        if ($cat) {
            $cat_lower = mb_strtolower($cat);
            $found = false;
            foreach ($cats as $existing) {
                if (mb_strtolower($existing) === $cat_lower) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $cats[] = $cat;
            }
        }
    }
    return $cats;
}

function dm_get_gallery_years()
{
    $items = dm_get_gallery_items();
    $years = array();
    foreach ($items as $item) {
        $date = $item['date_added'] ?? '';
        if ($date) {
            $year = substr($date, 0, 4);
            if ($year && !in_array($year, $years)) {
                $years[] = $year;
            }
        }
    }
    rsort($years);
    return $years;
}

/* -------------------------------------------------------------------------- */
/* Normalisation des catégories (case-insensitive)                              */
/* -------------------------------------------------------------------------- */
function dm_normalize_category($cat)
{
    $cat = trim($cat);
    if ($cat === '') return '';
    // Check if category already exists (case-insensitive)
    $existing = dm_get_gallery_categories();
    foreach ($existing as $ex) {
        if (mb_strtolower($ex) === mb_strtolower($cat)) {
            return $ex; // Return existing version to keep consistency
        }
    }
    // New category: capitalize first letter of each word
    return mb_convert_case($cat, MB_CASE_TITLE, 'UTF-8');
}

/* -------------------------------------------------------------------------- */
/* Sanitizer                                                                    */
/* -------------------------------------------------------------------------- */
function dm_sanitize_gallery_items($input)
{
    $clean = array();
    if (!is_array($input)) return $clean;

    foreach ($input as $row) {
        if (!is_array($row)) continue;
        $item = array();
        $has_data = false;

        $type = isset($row['type']) ? sanitize_text_field($row['type']) : 'image';
        $item['type'] = in_array($type, array('image', 'video')) ? $type : 'image';

        $url = isset($row['url']) ? esc_url_raw($row['url']) : '';
        $item['url'] = $url;
        if ($url !== '') $has_data = true;

        $thumb = isset($row['thumbnail']) ? esc_url_raw($row['thumbnail']) : '';
        $item['thumbnail'] = $thumb;

        $title = isset($row['title']) ? sanitize_text_field($row['title']) : '';
        $item['title'] = $title;
        if ($title !== '') $has_data = true;

        $desc = isset($row['description']) ? sanitize_textarea_field($row['description']) : '';
        $item['description'] = $desc;

        $cat = isset($row['category']) ? sanitize_text_field($row['category']) : '';
        $item['category'] = dm_normalize_category($cat);

        // date_added : conserver la valeur existante ou définir maintenant
        $existing_items_for_date = get_option('dm_gallery_items', array());
        $existing_date = '';
        $existing_likes = 0;
        $existing_dislikes = 0;
        $existing_hearts = 0;
        if (is_array($existing_items_for_date)) {
            foreach ($existing_items_for_date as $ei) {
                if (is_array($ei) && ($ei['url'] ?? '') === $url && $url !== '') {
                    $existing_date = $ei['date_added'] ?? '';
                    $existing_likes = intval($ei['likes'] ?? 0);
                    $existing_dislikes = intval($ei['dislikes'] ?? 0);
                    $existing_hearts = intval($ei['hearts'] ?? 0);
                    break;
                }
            }
        }
        $item['date_added'] = $existing_date !== '' ? $existing_date : current_time('mysql');
        $item['likes'] = $existing_likes;
        $item['dislikes'] = $existing_dislikes;
        $item['hearts'] = $existing_hearts;

        if ($has_data) $clean[] = $item;
    }
    return $clean;
}

/* -------------------------------------------------------------------------- */
/* Register settings                                                            */
/* -------------------------------------------------------------------------- */
add_action('admin_init', function () {
    register_setting('dm_gallery_group', 'dm_gallery_items', array(
        'type'              => 'array',
        'sanitize_callback' => 'dm_sanitize_gallery_items',
        'default'           => array(),
    ));
});

/* -------------------------------------------------------------------------- */
/* Enqueue media uploader sur la page admin galerie                             */
/* -------------------------------------------------------------------------- */
add_action('admin_enqueue_scripts', function ($hook) {
    if (isset($_GET['page']) && $_GET['page'] === 'dm-gallery') {
        wp_enqueue_media();
        wp_enqueue_script('jquery');
    }
});

/* -------------------------------------------------------------------------- */
/* Sous-menu "Galerie" sous Délices Content                                     */
/* -------------------------------------------------------------------------- */
add_action('admin_menu', function () {
    add_submenu_page(
        'dm-content',
        'Galerie',
        'Galerie',
        'manage_options',
        'dm-gallery',
        'dm_gallery_page_html'
    );
}, 25);

/* -------------------------------------------------------------------------- */
/* Page HTML — repeater dynamique avec add/remove + media uploader               */
/* -------------------------------------------------------------------------- */
function dm_gallery_page_html()
{
    if (!current_user_can('manage_options')) {
        return;
    }
    $items = dm_get_gallery_items();
    if (!is_array($items)) {
        $items = array();
    }
    $existing_cats = dm_get_gallery_categories();
    ?>
    <div class="wrap">
        <h1>Galerie</h1>
        <p>Gérez les images et vidéos affichées sur la page <strong>Galerie</strong> (<code>/galerie</code>). Les éléments les plus récents s'affichent en premier. Chaque élément peut être une image ou une vidéo (YouTube, Vimeo, ou MP4 direct).</p>
        <p><strong>Note :</strong> Les likes, dislikes et cœurs sont gérés par les visiteurs et ne peuvent pas être modifiés depuis l'admin. Les catégories ne sont pas sensibles à la casse (majuscules/minuscules).</p>

        <!-- Datalist pour l'autocomplétion des catégories -->
        <datalist id="dm-gal-categories">
            <?php foreach ($existing_cats as $cat) : ?>
                <option value="<?php echo esc_attr($cat); ?>">
            <?php endforeach; ?>
        </datalist>

        <form method="post" action="options.php">
            <?php settings_fields('dm_gallery_group'); ?>

            <div id="dm-gallery-repeater">
                <?php foreach ($items as $i => $item) : ?>
                <div class="dm-gal-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin-bottom:16px;">
                    <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:8px;flex-wrap:wrap;">
                        <select name="dm_gallery_items[<?php echo esc_attr($i); ?>][type]" class="dm-gal-type" style="width:120px;">
                            <option value="image"<?php selected($item['type'] ?? 'image', 'image'); ?>>Image</option>
                            <option value="video"<?php selected($item['type'] ?? 'image', 'video'); ?>>Vidéo</option>
                        </select>
                        <input type="text" name="dm_gallery_items[<?php echo esc_attr($i); ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" placeholder="Titre" class="regular-text" style="width:250px;" />
                        <input type="text" name="dm_gallery_items[<?php echo esc_attr($i); ?>][category]" value="<?php echo esc_attr($item['category'] ?? ''); ?>" placeholder="Catégorie (ex: Présentation)" class="regular-text dm-gal-cat-input" list="dm-gal-categories" style="width:180px;" />
                    </div>
                    <div style="margin-bottom:8px;">
                        <textarea name="dm_gallery_items[<?php echo esc_attr($i); ?>][description]" rows="2" placeholder="Description (max 3 lignes en affichage)" style="width:100%;max-width:700px;"><?php echo esc_textarea($item['description'] ?? ''); ?></textarea>
                    </div>
                    <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;flex-wrap:wrap;">
                        <input type="text" name="dm_gallery_items[<?php echo esc_attr($i); ?>][url]" value="<?php echo esc_attr($item['url'] ?? ''); ?>" placeholder="URL de l'image ou vidéo (YouTube/Vimeo/MP4)" class="regular-text dm-gal-url-input" style="width:400px;" />
                        <button type="button" class="button dm-gal-upload">Choisir un média</button>
                    </div>
                    <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;flex-wrap:wrap;">
                        <input type="text" name="dm_gallery_items[<?php echo esc_attr($i); ?>][thumbnail]" value="<?php echo esc_attr($item['thumbnail'] ?? ''); ?>" placeholder="URL miniature (optionnel pour vidéos)" class="regular-text dm-gal-thumb-input" style="width:350px;" />
                        <button type="button" class="button dm-gal-thumb-upload">Miniature</button>
                    </div>
                    <!-- Preview -->
                    <div class="dm-gal-preview" style="margin-bottom:8px;">
                        <?php $preview_url = ($item['type'] ?? 'image') === 'image' ? ($item['url'] ?? '') : ($item['thumbnail'] ?? ''); ?>
                        <?php if (!empty($preview_url)) : ?>
                            <img src="<?php echo esc_url($preview_url); ?>" alt="" style="max-width:200px;max-height:120px;object-fit:cover;border-radius:6px;border:1px solid #ddd;" />
                        <?php endif; ?>
                    </div>
                    <!-- Date + Stats (read-only) -->
                    <?php $admin_rx = dm_get_item_reactions($i); ?>
                    <div style="display:flex;gap:1rem;align-items:center;margin-bottom:8px;font-size:0.85rem;color:#666;flex-wrap:wrap;">
                        <span>📅 Ajouté le : <strong><?php echo esc_html($item['date_added'] ?? '—'); ?></strong></span>
                        <span>👍 Likes : <strong><?php echo esc_html($admin_rx['likes']); ?></strong></span>
                        <span>👎 Dislikes : <strong><?php echo esc_html($admin_rx['dislikes']); ?></strong></span>
                        <span>❤️ Cœurs : <strong><?php echo esc_html($admin_rx['hearts']); ?></strong></span>
                    </div>
                    <div style="margin-top:8px;">
                        <button type="button" class="button button-link-delete dm-gal-remove">Supprimer cet élément</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <p>
                <button type="button" class="button button-secondary" id="dm-gal-add">+ Ajouter un élément</button>
            </p>
            <?php submit_button('Enregistrer la galerie'); ?>
        </form>

        <script>
        jQuery(function($) {
            var galIndex = <?php echo empty($items) ? 0 : max(array_keys($items)) + 1; ?>;

            function createRow() {
                var html = '<div class="dm-gal-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin-bottom:16px;">' +
                    '<div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:8px;flex-wrap:wrap;">' +
                        '<select name="dm_gallery_items[' + galIndex + '][type]" class="dm-gal-type" style="width:120px;">' +
                            '<option value="image">Image</option>' +
                            '<option value="video">Vidéo</option>' +
                        '</select>' +
                        '<input type="text" name="dm_gallery_items[' + galIndex + '][title]" value="" placeholder="Titre" class="regular-text" style="width:250px;" />' +
                        '<input type="text" name="dm_gallery_items[' + galIndex + '][category]" value="" placeholder="Catégorie (ex: Présentation)" class="regular-text dm-gal-cat-input" list="dm-gal-categories" style="width:180px;" />' +
                    '</div>' +
                    '<div style="margin-bottom:8px;">' +
                        '<textarea name="dm_gallery_items[' + galIndex + '][description]" rows="2" placeholder="Description (max 3 lignes en affichage)" style="width:100%;max-width:700px;"></textarea>' +
                    '</div>' +
                    '<div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;flex-wrap:wrap;">' +
                        '<input type="text" name="dm_gallery_items[' + galIndex + '][url]" value="" placeholder="URL de l\'image ou vidéo (YouTube/Vimeo/MP4)" class="regular-text dm-gal-url-input" style="width:400px;" />' +
                        '<button type="button" class="button dm-gal-upload">Choisir un média</button>' +
                    '</div>' +
                    '<div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;flex-wrap:wrap;">' +
                        '<input type="text" name="dm_gallery_items[' + galIndex + '][thumbnail]" value="" placeholder="URL miniature (optionnel pour vidéos)" class="regular-text dm-gal-thumb-input" style="width:350px;" />' +
                        '<button type="button" class="button dm-gal-thumb-upload">Miniature</button>' +
                    '</div>' +
                    '<div class="dm-gal-preview" style="margin-bottom:8px;"></div>' +
                    '<div style="display:flex;gap:1rem;align-items:center;margin-bottom:8px;font-size:0.85rem;color:#666;flex-wrap:wrap;">' +
                        '<span>📅 Ajouté le : <strong>maintenant</strong></span>' +
                        '<span>👍 Likes : <strong>0</strong></span>' +
                        '<span>👎 Dislikes : <strong>0</strong></span>' +
                        '<span>❤️ Cœurs : <strong>0</strong></span>' +
                    '</div>' +
                    '<div style="margin-top:8px;"><button type="button" class="button button-link-delete dm-gal-remove">Supprimer cet élément</button></div>' +
                '</div>';
                galIndex++;
                return html;
            }

            $('#dm-gal-add').on('click', function() {
                $('#dm-gallery-repeater').append(createRow());
            });

            $('#dm-gallery-repeater').on('click', '.dm-gal-remove', function() {
                $(this).closest('.dm-gal-row').remove();
            });

            // Media uploader pour l'URL principale
            $('#dm-gallery-repeater').on('click', '.dm-gal-upload', function(e) {
                e.preventDefault();
                var $input = $(this).siblings('.dm-gal-url-input');
                var $type = $(this).closest('.dm-gal-row').find('.dm-gal-type');
                var frame = wp.media({
                    title: 'Choisir un média',
                    button: { text: 'Utiliser ce média' },
                    multiple: false
                });
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $input.val(attachment.url);
                    // Auto-detect type
                    if (attachment.type === 'video') {
                        $type.val('video');
                    } else {
                        $type.val('image');
                    }
                    // Update preview
                    updatePreview($(this).closest('.dm-gal-row'));
                });
                frame.open();
            });

            // Media uploader pour la miniature
            $('#dm-gallery-repeater').on('click', '.dm-gal-thumb-upload', function(e) {
                e.preventDefault();
                var $input = $(this).siblings('.dm-gal-thumb-input');
                var frame = wp.media({
                    title: 'Choisir une miniature',
                    button: { text: 'Utiliser comme miniature' },
                    multiple: false
                });
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $input.val(attachment.url);
                    updatePreview($input.closest('.dm-gal-row'));
                });
                frame.open();
            });

            // Update preview on type or URL change
            function updatePreview($row) {
                var type = $row.find('.dm-gal-type').val();
                var url = $row.find('.dm-gal-url-input').val();
                var thumb = $row.find('.dm-gal-thumb-input').val();
                var previewUrl = type === 'image' ? url : thumb;
                var $preview = $row.find('.dm-gal-preview');
                if (previewUrl) {
                    $preview.html('<img src="' + previewUrl + '" alt="" style="max-width:200px;max-height:120px;object-fit:cover;border-radius:6px;border:1px solid #ddd;" />');
                } else {
                    $preview.empty();
                }
            }

            $('#dm-gallery-repeater').on('change', '.dm-gal-type', function() {
                updatePreview($(this).closest('.dm-gal-row'));
            });
            $('#dm-gallery-repeater').on('input', '.dm-gal-url-input, .dm-gal-thumb-input', function() {
                updatePreview($(this).closest('.dm-gal-row'));
            });
        });
        </script>
    </div>
    <?php
}

/* -------------------------------------------------------------------------- */
/* Seeder : pré-remplir la galerie avec les images/vidéos par défaut             */
/* -------------------------------------------------------------------------- */
add_action('init', function () {
    if (get_option('dm_gallery_seeded') === 'yes') return;
    if (is_admin() && current_user_can('manage_options')) {
        // Only seed from admin or front-end init
    }
    dm_seed_default_gallery();
}, 20);

function dm_seed_default_gallery()
{
    $existing = get_option('dm_gallery_items', array());
    if (!is_array($existing)) $existing = array();
    if (get_option('dm_gallery_seeded') === 'yes' && !empty($existing)) return;

    $theme_uri = get_stylesheet_directory_uri();
    $now = current_time('mysql');
    $base_date = '2025-01-15 10:00:00';

    $defaults = array();

    // Images from gallery folder
    $gallery_images = array(
        array('akkara.jpeg', 'Akkara', 'Plat traditionnel à base de poisson épicé, une spécialité de la maison.', 'Cuisine Demo'),
        array('nems-poulet.jpeg', 'Nems Poulet', 'Nems croustillants au poulet, servis avec sauce sucrée-salée.', 'Vos Meilleurs Produits'),
        array('pain-chinois.jpeg', 'Pain Chinois', 'Pain chinois moelleux, parfait pour accompagner nos plats.', 'Présentation'),
        array('poulet-grille.jpeg', 'Poulet Grillé', 'Poulet grillé mariné aux épices, tendre et savoureux.', 'Cuisine Demo'),
        array('rissoles.jpeg', 'Rissoles', 'Rissoles dorées et croustillantes, garniture généreuse.', 'Beignets'),
        array('samoussa.jpeg', 'Samoussa', 'Samoussas faits maison, feuille croustillante et garniture épicée.', 'Beignets'),
        array('samoussa1.jpeg', 'Samoussa Spécial', 'Samoussas spéciaux avec garniture au poisson.', 'Beignets'),
    );

    foreach ($gallery_images as $idx => $img) {
        $defaults[] = array(
            'type'        => 'image',
            'url'         => $theme_uri . '/assets/images/gallery/' . $img[0],
            'thumbnail'   => '',
            'title'       => $img[1],
            'description' => $img[2],
            'category'    => $img[3],
            'date_added'  => date('Y-m-d H:i:s', strtotime($base_date . ' +' . ($idx * 3) . ' days')),
            'likes'       => 0,
            'dislikes'    => 0,
            'hearts'      => 0,
        );
    }

    // Images from banners folder
    $banner_images = array(
        array('banner-catalog.png', 'Catalogue', 'Découvrez notre catalogue complet de produits.', 'Présentation'),
        array('banner-general.png', 'Bannière', 'Bannière principale du site.', 'Présentation'),
        array('banner.png', 'Bannière Accueil', 'Image de bannière d\'accueil.', 'Présentation'),
    );

    foreach ($banner_images as $idx => $img) {
        $defaults[] = array(
            'type'        => 'image',
            'url'         => $theme_uri . '/assets/images/banners/' . $img[0],
            'thumbnail'   => '',
            'title'       => $img[1],
            'description' => $img[2],
            'category'    => $img[3],
            'date_added'  => date('Y-m-d H:i:s', strtotime($base_date . ' +' . ($idx * 2 + 21) . ' days')),
            'likes'       => 0,
            'dislikes'    => 0,
            'hearts'      => 0,
        );
    }

    // Videos from gallery folder
    $videos = array(
        array('video1.mp4', 'Démonstration Cuisine', 'Découvrez nos chefs en action dans cette démonstration de cuisine.', 'Cuisine Demo'),
        array('video2.mp4', 'Préparation Beignets', 'Suivez la préparation de nos fameux beignets croustillants.', 'Beignets'),
        array('video3.mp4', 'Présentation Produits', 'Présentation de nos meilleurs produits de la mer.', 'Présentation'),
    );

    foreach ($videos as $idx => $vid) {
        $defaults[] = array(
            'type'        => 'video',
            'url'         => $theme_uri . '/assets/images/gallery/' . $vid[0],
            'thumbnail'   => '',
            'title'       => $vid[1],
            'description' => $vid[2],
            'category'    => $vid[3],
            'date_added'  => date('Y-m-d H:i:s', strtotime($base_date . ' +' . ($idx * 5 + 30) . ' days')),
            'likes'       => 0,
            'dislikes'    => 0,
            'hearts'      => 0,
        );
    }

    // Merge: only add items whose URL doesn't already exist
    $existing_urls = array();
    foreach ($existing as $ei) {
        if (is_array($ei) && !empty($ei['url'])) {
            $existing_urls[] = $ei['url'];
        }
    }

    $added = false;
    foreach ($defaults as $d) {
        if (!in_array($d['url'], $existing_urls)) {
            $existing[] = $d;
            $added = true;
        }
    }

    if ($added) {
        update_option('dm_gallery_items', $existing);
    }
    update_option('dm_gallery_seeded', 'yes');
}

/* -------------------------------------------------------------------------- */
/* AJAX : Like / Dislike / Heart                                                */
/* -------------------------------------------------------------------------- */
add_action('wp_ajax_dm_gallery_react', 'dm_gallery_react');
add_action('wp_ajax_nopriv_dm_gallery_react', 'dm_gallery_react');

function dm_gallery_react()
{
    if (!check_ajax_referer('dm-gallery-nonce', 'nonce', false)) {
        wp_send_json_error('Invalid nonce');
    }

    $index = isset($_POST['index']) ? intval($_POST['index']) : -1;
    $reaction = isset($_POST['reaction']) ? sanitize_text_field($_POST['reaction']) : '';

    if ($index < 0 || !in_array($reaction, array('likes', 'dislikes', 'hearts'))) {
        wp_send_json_error('Invalid params');
    }

    // Vérifier que l'item existe réellement
    $items = get_option('dm_gallery_items', array());
    if (!is_array($items) || !isset($items[$index])) {
        wp_send_json_error('Item not found');
    }

    // Compteurs dans une option SÉPARÉE (non sanitizée) : les incréments persistent
    $reactions = get_option('dm_gallery_reactions', array());
    if (!is_array($reactions)) $reactions = array();
    if (!isset($reactions[$index]) || !is_array($reactions[$index])) {
        $reactions[$index] = array('likes' => 0, 'dislikes' => 0, 'hearts' => 0);
    }

    // Cookie : empêcher les réactions multiples (une seule active par utilisateur/item)
    $cookie_key = 'dm_gal_react_' . $index;
    $previous = isset($_COOKIE[$cookie_key]) ? sanitize_text_field($_COOKIE[$cookie_key]) : '';

    if ($previous === $reaction) {
        // Même réaction → on retire (toggle off)
        $reactions[$index][$reaction] = max(0, intval($reactions[$index][$reaction] ?? 0) - 1);
        setcookie($cookie_key, '', time() - 3600, '/');
        $_COOKIE[$cookie_key] = '';
        $previous = '';
    } else {
        // Retirer l'ancienne réaction si différente
        if ($previous && in_array($previous, array('likes', 'dislikes', 'hearts'))) {
            $reactions[$index][$previous] = max(0, intval($reactions[$index][$previous] ?? 0) - 1);
        }
        // Ajouter la nouvelle réaction
        $reactions[$index][$reaction] = intval($reactions[$index][$reaction] ?? 0) + 1;
        setcookie($cookie_key, $reaction, time() + 30 * DAY_IN_SECONDS, '/');
        $_COOKIE[$cookie_key] = $reaction;
        $previous = $reaction;
    }

    update_option('dm_gallery_reactions', $reactions, false);

    wp_send_json_success(array(
        'likes'    => intval($reactions[$index]['likes'] ?? 0),
        'dislikes' => intval($reactions[$index]['dislikes'] ?? 0),
        'hearts'   => intval($reactions[$index]['hearts'] ?? 0),
        'active'   => $previous,
    ));
}

/* -------------------------------------------------------------------------- */
/* Helper : formater les grands nombres (1k, 1M)                                */
/* -------------------------------------------------------------------------- */
function dm_format_count($n)
{
    $n = intval($n);
    if ($n >= 1000000) {
        return round($n / 1000000, 1) . 'M';
    }
    if ($n >= 1000) {
        return round($n / 1000, 1) . 'k';
    }
    return (string) $n;
}
