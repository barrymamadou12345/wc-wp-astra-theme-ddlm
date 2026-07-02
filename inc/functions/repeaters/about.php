<?php
/**
 * Repeater: À Propos
 * Page: /a-propos
 *
 * Trois repeaters :
 *   1. dm_about_values  — Nos valeurs (icon, title, desc)
 *   2. dm_about_stats   — Chiffres clés (value, label)
 *   3. dm_about_timeline — Chronologie/historique (year, title, desc, media)
 *
 * Option :
 *   dm_about_hero_image — Image de fond du hero
 *   dm_about_story      — Texte de l'histoire (textarea)
 *   dm_about_slogan     — Slogan mis en avant
 */

if (! defined('ABSPATH')) {
    exit;
}

/* -------------------------------------------------------------------------- */
/* Données par défaut (issues du prototype About.jsx)                           */
/* -------------------------------------------------------------------------- */

function dm_default_about_values()
{
    return array(
        array(
            'icon'  => 'award',
            'title' => 'Qualité Premium',
            'desc'  => "Des ingrédients soigneusement sélectionnés et des recettes élaborées pour garantir l'excellence.",
        ),
        array(
            'icon'  => 'heart',
            'title' => 'Fraîcheur Absolue',
            'desc'  => "Nos produits sont préparés quotidiennement dans nos ateliers pour une fraîcheur incomparable.",
        ),
        array(
            'icon'  => 'users',
            'title' => 'Esprit d\'Équipe',
            'desc'  => "Plus de 40 collaborateurs passionnés au service de notre vision culinaire.",
        ),
    );
}

function dm_default_about_stats()
{
    return array(
        array('value' => '2016', 'label' => 'Année de création'),
        array('value' => '40+',  'label' => 'Employés passionnés'),
        array('value' => '10+',  'label' => 'Partenaires distributeurs'),
        array('value' => '1000+', 'label' => 'Clients satisfaits'),
    );
}

function dm_default_about_timeline()
{
    return array(
        array(
            'year'  => '2016',
            'title' => 'Création de l\'entreprise',
            'desc'  => "Les Délices de la Mer voit le jour à Dakar avec une ambition claire : réinventer le snack sénégalais.",
            'media' => '',
        ),
        array(
            'year'  => '2017',
            'title' => 'Partenariats hôteliers',
            'desc'  => "Signature des premiers contrats avec les hôtels de luxe de la capitale.",
            'media' => '',
        ),
        array(
            'year'  => '2019',
            'title' => 'Grande distribution',
            'desc'  => "Entrée dans les enseignes Auchan et Carrefour, rendant nos produits accessibles au grand public.",
            'media' => '',
        ),
        array(
            'year'  => '2021',
            'title' => 'Gamme fumée',
            'desc'  => "Lancement de la gamme de produits fumés au beurre de coco, un succès immédiat.",
            'media' => '',
        ),
        array(
            'year'  => '2023',
            'title' => 'Expansion nationale',
            'desc'  => "Présence dans les stations-service TotalEnergies et Shell, ainsi que dans les gares du TER.",
            'media' => '',
        ),
        array(
            'year'  => '2024',
            'title' => 'Consolidation',
            'desc'  => "Plus de 10 partenaires distributeurs au Sénégal et une équipe de plus de 40 passionnés.",
            'media' => '',
        ),
    );
}

function dm_default_about_team()
{
    return array(
        array(
            'name'  => 'Aminata Diallo',
            'role'  => 'Directrice Générale',
            'desc'  => "Visionnaire et passionnée, Aminata dirige Les Délices de la Mer depuis sa création avec une exigence constante de qualité.",
            'photo' => 'https://i.pravatar.cc/300?img=47',
        ),
        array(
            'name'  => 'Mamadou Sow',
            'role'  => 'Chef de Production',
            'desc'  => "Garant de la qualité de nos ateliers, Mamadou supervise la fabrication quotidienne avec rigueur et passion.",
            'photo' => 'https://i.pravatar.cc/300?img=12',
        ),
        array(
            'name'  => 'Fatou Ndiaye',
            'role'  => 'Responsable Qualité',
            'desc'  => "Fatou veille au respect des normes d'hygiène et de fraîcheur sur l'ensemble de notre chaîne de production.",
            'photo' => 'https://i.pravatar.cc/300?img=45',
        ),
        array(
            'name'  => 'Ibrahima Fall',
            'role'  => 'Responsable Commercial',
            'desc'  => "Ibrahima développe nos partenariats avec les grandes enseignes et les distributeurs au Sénégal.",
            'photo' => 'https://i.pravatar.cc/300?img=13',
        ),
        array(
            'name'  => 'Awa Cissé',
            'role'  => 'Responsable Marketing',
            'desc'  => "Awa fait rayonner la marque Les Délices de la Mer à travers des campagnes créatives et engageantes.",
            'photo' => 'https://i.pravatar.cc/300?img=44',
        ),
        array(
            'name'  => 'Cheikh Diop',
            'role'  => 'Responsable Logistique',
            'desc'  => "Cheikh coordonne la distribution de nos produits vers les points de vente avec efficacité et ponctualité.",
            'photo' => 'https://i.pravatar.cc/300?img=15',
        ),
        array(
            'name'  => 'Khadija Ba',
            'role'  => 'Comptable Financière',
            'desc'  => "Khadija assure la gestion financière de l'entreprise avec précision et transparence.",
            'photo' => 'https://i.pravatar.cc/300?img=48',
        ),
        array(
            'name'  => 'Ousmane Gueye',
            'role'  => 'Chef Atelier',
            'desc'  => "Ousmane et son équipe préparent chaque jour nos snacks croustillants avec savoir-faire et dedication.",
            'photo' => 'https://i.pravatar.cc/300?img=16',
        ),
    );
}

/* -------------------------------------------------------------------------- */
/* Getters                                                                      */
/* -------------------------------------------------------------------------- */

function dm_get_about_values()
{
    $data = get_option('dm_about_values', null);
    if ($data === null || !is_array($data) || empty($data)) {
        $defaults = dm_default_about_values();
        update_option('dm_about_values', $defaults);
        return $defaults;
    }
    return $data;
}

function dm_get_about_stats()
{
    $data = get_option('dm_about_stats', null);
    if ($data === null || !is_array($data) || empty($data)) {
        $defaults = dm_default_about_stats();
        update_option('dm_about_stats', $defaults);
        return $defaults;
    }
    return $data;
}

function dm_get_about_timeline()
{
    $data = get_option('dm_about_timeline', null);
    if ($data === null || !is_array($data) || empty($data)) {
        $defaults = dm_default_about_timeline();
        update_option('dm_about_timeline', $defaults);
        return $defaults;
    }
    return $data;
}

function dm_get_about_team()
{
    $data = get_option('dm_about_team', null);
    if ($data === null || !is_array($data) || empty($data)) {
        $defaults = dm_default_about_team();
        update_option('dm_about_team', $defaults);
        return $defaults;
    }
    return $data;
}

/* -------------------------------------------------------------------------- */
/* Sanitizers                                                                   */
/* -------------------------------------------------------------------------- */

function dm_sanitize_about_values($input)
{
    $clean = array();
    if (!is_array($input)) return $clean;

    $icons = array('fish', 'flame', 'truck', 'leaf', 'star', 'check', 'heart', 'clock', 'shield', 'cart', 'clipboard', 'coffee', 'award', 'users', 'store', 'utensils', 'building', 'package');

    foreach ($input as $row) {
        if (!is_array($row)) continue;
        $item = array();
        $has_data = false;

        $icon = isset($row['icon']) ? sanitize_text_field($row['icon']) : 'fish';
        $item['icon'] = in_array($icon, $icons) ? $icon : 'fish';

        $item['title'] = sanitize_text_field($row['title'] ?? '');
        $item['desc']  = sanitize_textarea_field($row['desc'] ?? '');

        if ($item['title'] !== '' || $item['desc'] !== '') $has_data = true;
        if ($has_data) $clean[] = $item;
    }
    return $clean;
}

function dm_sanitize_about_stats($input)
{
    $clean = array();
    if (!is_array($input)) return $clean;

    foreach ($input as $row) {
        if (!is_array($row)) continue;
        $item = array();
        $item['value'] = sanitize_text_field($row['value'] ?? '');
        $item['label'] = sanitize_text_field($row['label'] ?? '');
        if ($item['value'] !== '' || $item['label'] !== '') {
            $clean[] = $item;
        }
    }
    return $clean;
}

function dm_sanitize_about_timeline($input)
{
    $clean = array();
    if (!is_array($input)) return $clean;

    foreach ($input as $row) {
        if (!is_array($row)) continue;
        $item = array();
        $has_data = false;

        $item['year']  = sanitize_text_field($row['year'] ?? '');
        $item['title'] = sanitize_text_field($row['title'] ?? '');
        $item['desc']  = sanitize_textarea_field($row['desc'] ?? '');
        $item['media'] = esc_url_raw($row['media'] ?? '');

        if ($item['year'] !== '' || $item['title'] !== '') $has_data = true;
        if ($has_data) $clean[] = $item;
    }
    return $clean;
}

function dm_sanitize_about_team($input)
{
    $clean = array();
    if (!is_array($input)) return $clean;

    foreach ($input as $row) {
        if (!is_array($row)) continue;
        $item = array();
        $has_data = false;

        $item['name']  = sanitize_text_field($row['name'] ?? '');
        $item['role']  = sanitize_text_field($row['role'] ?? '');
        $item['desc']  = sanitize_textarea_field($row['desc'] ?? '');
        $item['photo'] = esc_url_raw($row['photo'] ?? '');

        if ($item['name'] !== '' || $item['role'] !== '') $has_data = true;
        if ($has_data) $clean[] = $item;
    }
    return $clean;
}
/* -------------------------------------------------------------------------- */

add_action('admin_init', function () {
    register_setting('dm_about_group', 'dm_about_values', array(
        'type'              => 'array',
        'sanitize_callback' => 'dm_sanitize_about_values',
        'default'           => array(),
    ));
    register_setting('dm_about_group', 'dm_about_stats', array(
        'type'              => 'array',
        'sanitize_callback' => 'dm_sanitize_about_stats',
        'default'           => array(),
    ));
    register_setting('dm_about_group', 'dm_about_timeline', array(
        'type'              => 'array',
        'sanitize_callback' => 'dm_sanitize_about_timeline',
        'default'           => array(),
    ));
    register_setting('dm_about_group', 'dm_about_team', array(
        'type'              => 'array',
        'sanitize_callback' => 'dm_sanitize_about_team',
        'default'           => array(),
    ));
    register_setting('dm_about_group', 'dm_about_hero_image', array(
        'type'              => 'string',
        'sanitize_callback' => 'esc_url_raw',
        'default'           => '',
    ));
    register_setting('dm_about_group', 'dm_about_story', array(
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_textarea_field',
        'default'           => '',
    ));
    register_setting('dm_about_group', 'dm_about_slogan', array(
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
    ));
});

/* -------------------------------------------------------------------------- */
/* Enqueue media uploader                                                       */
/* -------------------------------------------------------------------------- */

add_action('admin_enqueue_scripts', function ($hook) {
    if (isset($_GET['page']) && $_GET['page'] === 'dm-about') {
        wp_enqueue_media();
        wp_enqueue_script('jquery');
    }
});

/* -------------------------------------------------------------------------- */
/* Sous-menu "À Propos" sous Délices Content                                    */
/* -------------------------------------------------------------------------- */

add_action('admin_menu', function () {
    add_submenu_page(
        'dm-content',
        'À Propos',
        'À Propos',
        'manage_options',
        'dm-about',
        'dm_about_page_html'
    );
}, 20);

/* -------------------------------------------------------------------------- */
/* Page HTML — tabs : Valeurs, Chiffres, Timeline, Hero                         */
/* -------------------------------------------------------------------------- */

function dm_about_page_html()
{
    if (!current_user_can('manage_options')) return;

    $values   = dm_get_about_values();
    $stats    = dm_get_about_stats();
    $timeline = dm_get_about_timeline();
    $team     = dm_get_about_team();
    $hero_img = get_option('dm_about_hero_image', '');
    $story    = get_option('dm_about_story', '');
    $slogan   = get_option('dm_about_slogan', '');

    $icons = array(
        'fish' => 'Poisson', 'flame' => 'Flamme', 'truck' => 'Camion',
        'leaf' => 'Feuille', 'star' => 'Étoile', 'check' => 'Check',
        'heart' => 'Cœur', 'clock' => 'Horloge', 'shield' => 'Bouclier',
        'cart' => 'Panier', 'clipboard' => 'Presse-papier', 'coffee' => 'Café',
        'award' => 'Médaille', 'users' => 'Utilisateurs', 'store' => 'Magasin',
        'utensils' => 'Couverts', 'building' => 'Bâtiment', 'package' => 'Colis',
    );

    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'values';
    ?>
    <div class="wrap">
        <h1>À Propos — Contenu dynamique</h1>
        <p>Gérez le contenu de la page <strong>À Propos</strong> (<code>/a-propos</code>) : valeurs, chiffres clés, chronologie et image de bannière.</p>

        <nav class="nav-tab-wrapper">
            <a href="?page=dm-about&tab=values" class="nav-tab<?php echo $active_tab === 'values' ? ' nav-tab-active' : ''; ?>">Valeurs</a>
            <a href="?page=dm-about&tab=stats" class="nav-tab<?php echo $active_tab === 'stats' ? ' nav-tab-active' : ''; ?>">Chiffres clés</a>
            <a href="?page=dm-about&tab=timeline" class="nav-tab<?php echo $active_tab === 'timeline' ? ' nav-tab-active' : ''; ?>">Chronologie</a>
            <a href="?page=dm-about&tab=hero" class="nav-tab<?php echo $active_tab === 'hero' ? ' nav-tab-active' : ''; ?>">Hero & Histoire</a>
            <a href="?page=dm-about&tab=team" class="nav-tab<?php echo $active_tab === 'team' ? ' nav-tab-active' : ''; ?>">Équipe</a>
        </nav>

        <form method="post" action="options.php">
            <?php
            settings_fields('dm_about_group');

            if ($active_tab === 'values') :
                $icons_html = '';
                foreach ($icons as $val => $label) {
                    $icons_html .= '<option value="' . esc_attr($val) . '">' . esc_html($label) . '</option>';
                }
            ?>
            <h3>Nos Valeurs</h3>
            <p class="description">Cartes affichées dans la section "Nos Valeurs" de la page À Propos.</p>
            <div id="dm-about-values-repeater">
                <?php foreach ($values as $i => $val) : ?>
                <div class="dm-about-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin:12px 0;">
                    <select name="dm_about_values[<?php echo esc_attr($i); ?>][icon]" style="width:180px;">
                        <?php foreach ($icons as $val2 => $label2) : ?>
                            <option value="<?php echo esc_attr($val2); ?>"<?php selected($val['icon'] ?? 'fish', $val2); ?>><?php echo esc_html($label2); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="dm_about_values[<?php echo esc_attr($i); ?>][title]" value="<?php echo esc_attr($val['title'] ?? ''); ?>" placeholder="Titre" class="regular-text" style="width:250px;margin-left:8px;" />
                    <textarea name="dm_about_values[<?php echo esc_attr($i); ?>][desc]" rows="2" placeholder="Description" style="width:100%;max-width:600px;margin-top:8px;"><?php echo esc_textarea($val['desc'] ?? ''); ?></textarea>
                    <button type="button" class="button button-link-delete dm-about-val-remove" style="margin-top:8px;">Supprimer</button>
                </div>
                <?php endforeach; ?>
            </div>
            <p><button type="button" class="button button-secondary" id="dm-about-val-add">+ Ajouter une valeur</button></p>
            <script>
            jQuery(function($) {
                var idx = <?php echo max(array_keys($values)) + 1; ?>;
                var iconsHtml = '<?php echo $icons_html; ?>';
                $('#dm-about-val-add').on('click', function() {
                    var html = '<div class="dm-about-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin:12px 0;">' +
                        '<select name="dm_about_values[' + idx + '][icon]" style="width:180px;">' + iconsHtml + '</select>' +
                        '<input type="text" name="dm_about_values[' + idx + '][title]" value="" placeholder="Titre" class="regular-text" style="width:250px;margin-left:8px;" />' +
                        '<textarea name="dm_about_values[' + idx + '][desc]" rows="2" placeholder="Description" style="width:100%;max-width:600px;margin-top:8px;"></textarea>' +
                        '<button type="button" class="button button-link-delete dm-about-val-remove" style="margin-top:8px;">Supprimer</button>' +
                    '</div>';
                    idx++;
                    $('#dm-about-values-repeater').append(html);
                });
                $('#dm-about-values-repeater').on('click', '.dm-about-val-remove', function() {
                    $(this).closest('.dm-about-row').remove();
                });
            });
            </script>

            <?php elseif ($active_tab === 'stats') : ?>
            <h3>Chiffres clés</h3>
            <p class="description">Statistiques affichées dans la section "En chiffres" de la page À Propos.</p>
            <div id="dm-about-stats-repeater">
                <?php foreach ($stats as $i => $stat) : ?>
                <div class="dm-about-stat-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:12px;margin:8px 0;display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                    <input type="text" name="dm_about_stats[<?php echo esc_attr($i); ?>][value]" value="<?php echo esc_attr($stat['value'] ?? ''); ?>" placeholder="Valeur (ex: 2016)" style="width:120px;" />
                    <input type="text" name="dm_about_stats[<?php echo esc_attr($i); ?>][label]" value="<?php echo esc_attr($stat['label'] ?? ''); ?>" placeholder="Libellé (ex: Année de création)" class="regular-text" style="width:300px;" />
                    <button type="button" class="button button-link-delete dm-about-stat-remove">Supprimer</button>
                </div>
                <?php endforeach; ?>
            </div>
            <p><button type="button" class="button button-secondary" id="dm-about-stat-add">+ Ajouter un chiffre</button></p>
            <script>
            jQuery(function($) {
                var idx = <?php echo max(array_keys($stats)) + 1; ?>;
                $('#dm-about-stat-add').on('click', function() {
                    var html = '<div class="dm-about-stat-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:12px;margin:8px 0;display:flex;gap:8px;align-items:center;flex-wrap:wrap;">' +
                        '<input type="text" name="dm_about_stats[' + idx + '][value]" value="" placeholder="Valeur (ex: 2016)" style="width:120px;" />' +
                        '<input type="text" name="dm_about_stats[' + idx + '][label]" value="" placeholder="Libellé (ex: Année de création)" class="regular-text" style="width:300px;" />' +
                        '<button type="button" class="button button-link-delete dm-about-stat-remove">Supprimer</button>' +
                    '</div>';
                    idx++;
                    $('#dm-about-stats-repeater').append(html);
                });
                $('#dm-about-stats-repeater').on('click', '.dm-about-stat-remove', function() {
                    $(this).closest('.dm-about-stat-row').remove();
                });
            });
            </script>

            <?php elseif ($active_tab === 'timeline') : ?>
            <h3>Chronologie / Historique</h3>
            <p class="description">Étapes clés affichées dans la timeline de la page À Propos. Le champ <strong>Média</strong> accepte une URL d'image ou de vidéo (YouTube, Vimeo, MP4, WebM).</p>
            <div id="dm-about-timeline-repeater">
                <?php foreach ($timeline as $i => $item) : ?>
                <div class="dm-about-tl-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin:12px 0;">
                    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin-bottom:8px;">
                        <input type="text" name="dm_about_timeline[<?php echo esc_attr($i); ?>][year]" value="<?php echo esc_attr($item['year'] ?? ''); ?>" placeholder="Année (ex: 2016)" style="width:100px;" />
                        <input type="text" name="dm_about_timeline[<?php echo esc_attr($i); ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" placeholder="Titre" class="regular-text" style="width:300px;" />
                    </div>
                    <textarea name="dm_about_timeline[<?php echo esc_attr($i); ?>][desc]" rows="2" placeholder="Description" style="width:100%;max-width:600px;margin-bottom:8px;"><?php echo esc_textarea($item['desc'] ?? ''); ?></textarea>
                    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                        <input type="text" name="dm_about_timeline[<?php echo esc_attr($i); ?>][media]" value="<?php echo esc_attr($item['media'] ?? ''); ?>" placeholder="URL média (image ou vidéo — optionnel)" class="regular-text dm-tl-media-input" style="width:400px;" />
                        <button type="button" class="button dm-tl-upload">Image</button>
                        <?php if (!empty($item['media'])) : ?>
                            <img src="<?php echo esc_url($item['media']); ?>" alt="" style="max-width:60px;max-height:40px;object-fit:cover;border-radius:4px;" />
                        <?php endif; ?>
                    </div>
                    <button type="button" class="button button-link-delete dm-about-tl-remove" style="margin-top:8px;">Supprimer</button>
                </div>
                <?php endforeach; ?>
            </div>
            <p><button type="button" class="button button-secondary" id="dm-about-tl-add">+ Ajouter une étape</button></p>
            <script>
            jQuery(function($) {
                var idx = <?php echo max(array_keys($timeline)) + 1; ?>;
                $('#dm-about-tl-add').on('click', function() {
                    var html = '<div class="dm-about-tl-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin:12px 0;">' +
                        '<div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin-bottom:8px;">' +
                            '<input type="text" name="dm_about_timeline[' + idx + '][year]" value="" placeholder="Année (ex: 2016)" style="width:100px;" />' +
                            '<input type="text" name="dm_about_timeline[' + idx + '][title]" value="" placeholder="Titre" class="regular-text" style="width:300px;" />' +
                        '</div>' +
                        '<textarea name="dm_about_timeline[' + idx + '][desc]" rows="2" placeholder="Description" style="width:100%;max-width:600px;margin-bottom:8px;"></textarea>' +
                        '<div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">' +
                            '<input type="text" name="dm_about_timeline[' + idx + '][media]" value="" placeholder="URL média (image ou vidéo — optionnel)" class="regular-text dm-tl-media-input" style="width:400px;" />' +
                            '<button type="button" class="button dm-tl-upload">Image</button>' +
                        '</div>' +
                        '<button type="button" class="button button-link-delete dm-about-tl-remove" style="margin-top:8px;">Supprimer</button>' +
                    '</div>';
                    idx++;
                    $('#dm-about-timeline-repeater').append(html);
                });
                $('#dm-about-timeline-repeater').on('click', '.dm-about-tl-remove', function() {
                    $(this).closest('.dm-about-tl-row').remove();
                });
                $('#dm-about-timeline-repeater').on('click', '.dm-tl-upload', function(e) {
                    e.preventDefault();
                    var $input = $(this).siblings('.dm-tl-media-input');
                    var frame = wp.media({
                        title: 'Choisir une image',
                        button: { text: 'Utiliser comme média' },
                        multiple: false
                    });
                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        $input.val(attachment.url);
                    });
                    frame.open();
                });
            });
            </script>

            <?php elseif ($active_tab === 'hero') : ?>
            <h3>Hero & Histoire</h3>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="dm_about_hero_image">Image du Hero</label></th>
                    <td>
                        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                            <input type="text" id="dm_about_hero_image" name="dm_about_hero_image" value="<?php echo esc_attr($hero_img); ?>" placeholder="URL de l'image (laisser vide pour fallback)" class="regular-text dm-about-hero-input" style="width:400px;" />
                            <button type="button" class="button dm-about-hero-upload">Choisir une image</button>
                            <?php if (!empty($hero_img)) : ?>
                                <img src="<?php echo esc_url($hero_img); ?>" alt="" style="max-width:120px;max-height:60px;object-fit:cover;border-radius:4px;" />
                            <?php endif; ?>
                        </div>
                        <p class="description">Image de fond pleine largeur en haut de la page. Si vide, l'image par défaut du prototype est utilisée.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="dm_about_slogan">Slogan</label></th>
                    <td>
                        <input type="text" id="dm_about_slogan" name="dm_about_slogan" value="<?php echo esc_attr($slogan); ?>" placeholder="Ex: Une symbiose de saveurs" class="regular-text" style="width:400px;" />
                        <p class="description">Slogan mis en avant dans la section histoire.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="dm_about_story">Texte de l'histoire</label></th>
                    <td>
                        <textarea id="dm_about_story" name="dm_about_story" rows="8" placeholder="Texte de présentation de l'entreprise..." style="width:100%;max-width:700px;"><?php echo esc_textarea($story); ?></textarea>
                        <p class="description">Texte principal de la section "Notre histoire". Laissez vide pour utiliser le texte par défaut.</p>
                    </td>
                </tr>
            </table>
            <script>
            jQuery(function($) {
                $('.dm-about-hero-upload').on('click', function(e) {
                    e.preventDefault();
                    var $input = $(this).siblings('.dm-about-hero-input');
                    var frame = wp.media({
                        title: 'Choisir l\\'image du Hero',
                        button: { text: 'Utiliser comme image du Hero' },
                        multiple: false
                    });
                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        $input.val(attachment.url);
                    });
                    frame.open();
                });
            });
            </script>

            <?php elseif ($active_tab === 'team') : ?>
            <h3>Membres de l'équipe</h3>
            <p class="description">Cartes affichées dans la section "Notre Équipe" de la page À Propos, juste après la chronologie. 4 cartes par ligne sur desktop.</p>
            <div id="dm-about-team-repeater">
                <?php foreach ($team as $i => $member) : ?>
                <div class="dm-about-team-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin:12px 0;">
                    <div style="display:flex;gap:12px;align-items:flex-start;flex-wrap:wrap;margin-bottom:8px;">
                        <div style="flex-shrink:0;">
                            <img src="<?php echo esc_url($member['photo'] ?? ''); ?>" alt="" class="dm-team-preview" style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid #e0e0e0;" <?php echo empty($member['photo']) ? 'style="display:none;"' : ''; ?> />
                        </div>
                        <div style="flex:1;min-width:200px;">
                            <input type="text" name="dm_about_team[<?php echo esc_attr($i); ?>][name]" value="<?php echo esc_attr($member['name'] ?? ''); ?>" placeholder="Nom et prénom" class="regular-text" style="width:100%;margin-bottom:6px;" />
                            <input type="text" name="dm_about_team[<?php echo esc_attr($i); ?>][role]" value="<?php echo esc_attr($member['role'] ?? ''); ?>" placeholder="Poste / Fonction (ex: Directrice Générale)" class="regular-text" style="width:100%;margin-bottom:6px;" />
                        </div>
                    </div>
                    <textarea name="dm_about_team[<?php echo esc_attr($i); ?>][desc]" rows="2" placeholder="Description courte" style="width:100%;max-width:600px;margin-bottom:8px;"><?php echo esc_textarea($member['desc'] ?? ''); ?></textarea>
                    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                        <input type="text" name="dm_about_team[<?php echo esc_attr($i); ?>][photo]" value="<?php echo esc_attr($member['photo'] ?? ''); ?>" placeholder="URL photo de profil" class="regular-text dm-team-photo-input" style="width:400px;" />
                        <button type="button" class="button dm-team-upload">Image</button>
                    </div>
                    <button type="button" class="button button-link-delete dm-about-team-remove" style="margin-top:8px;">Supprimer</button>
                </div>
                <?php endforeach; ?>
            </div>
            <p><button type="button" class="button button-secondary" id="dm-about-team-add">+ Ajouter un membre</button></p>
            <script>
            jQuery(function($) {
                var idx = <?php echo !empty($team) ? max(array_keys($team)) + 1 : 0; ?>;
                $('#dm-about-team-add').on('click', function() {
                    var html = '<div class="dm-about-team-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin:12px 0;">' +
                        '<div style="display:flex;gap:12px;align-items:flex-start;flex-wrap:wrap;margin-bottom:8px;">' +
                            '<div style="flex-shrink:0;"><img src="" alt="" class="dm-team-preview" style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid #e0e0e0;display:none;" /></div>' +
                            '<div style="flex:1;min-width:200px;">' +
                                '<input type="text" name="dm_about_team[' + idx + '][name]" value="" placeholder="Nom et prénom" class="regular-text" style="width:100%;margin-bottom:6px;" />' +
                                '<input type="text" name="dm_about_team[' + idx + '][role]" value="" placeholder="Poste / Fonction (ex: Directrice Générale)" class="regular-text" style="width:100%;margin-bottom:6px;" />' +
                            '</div>' +
                        '</div>' +
                        '<textarea name="dm_about_team[' + idx + '][desc]" rows="2" placeholder="Description courte" style="width:100%;max-width:600px;margin-bottom:8px;"></textarea>' +
                        '<div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">' +
                            '<input type="text" name="dm_about_team[' + idx + '][photo]" value="" placeholder="URL photo de profil" class="regular-text dm-team-photo-input" style="width:400px;" />' +
                            '<button type="button" class="button dm-team-upload">Image</button>' +
                        '</div>' +
                        '<button type="button" class="button button-link-delete dm-about-team-remove" style="margin-top:8px;">Supprimer</button>' +
                    '</div>';
                    idx++;
                    $('#dm-about-team-repeater').append(html);
                });
                $('#dm-about-team-repeater').on('click', '.dm-about-team-remove', function() {
                    $(this).closest('.dm-about-team-row').remove();
                });
                // Media uploader for team photos
                $('#dm-about-team-repeater').on('click', '.dm-team-upload', function(e) {
                    e.preventDefault();
                    var $row = $(this).closest('.dm-about-team-row');
                    var $input = $row.find('.dm-team-photo-input');
                    var $preview = $row.find('.dm-team-preview');
                    var frame = wp.media({
                        title: 'Choisir la photo de profil',
                        button: { text: 'Utiliser comme photo' },
                        multiple: false
                    });
                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        $input.val(attachment.url);
                        $preview.attr('src', attachment.url).show();
                    });
                    frame.open();
                });
                // Live preview on URL input
                $('#dm-about-team-repeater').on('input', '.dm-team-photo-input', function() {
                    var url = $(this).val();
                    var $preview = $(this).closest('.dm-about-team-row').find('.dm-team-preview');
                    if (url) {
                        $preview.attr('src', url).show();
                    } else {
                        $preview.hide();
                    }
                });
            });
            </script>
            <?php endif; ?>

            <?php submit_button('Enregistrer'); ?>
        </form>
    </div>
    <?php
}
