<?php
/**
 * Repeater: Nos Services
 * Page: /services
 *
 * Champs par section service :
 *   - icon        : Icône (utensils, building, package, fish, flame, truck, leaf, star, check, heart, clock, shield, cart, clipboard, coffee, award, users, store)
 *   - title       : Titre du service
 *   - subtitle    : Sous-titre / accroche
 *   - description : Description du service
 *   - image       : URL de l'image
 *   - features    : Array de features (texte) — sub-repeater
 *
 * Optionnel :
 *   - dm_services_hero_image : Image de fond du hero
 */

if (! defined('ABSPATH')) {
    exit;
}

/* -------------------------------------------------------------------------- */
/* Données par défaut (issues du prototype Services.jsx)                        */
/* -------------------------------------------------------------------------- */
function dm_default_services_sections()
{
    return array(
        array(
            'icon'        => 'utensils',
            'title'       => 'Restauration Événementielle',
            'subtitle'    => 'Sublimez vos événements',
            'description' => "Mariages, séminaires, cocktails d'entreprise, baptêmes — nous créons des expériences culinaires mémorables. Notre équipe conçoit des plateaux de snacks raffinés et des menus personnalisés, adaptés à la taille et au style de votre événement.",
            'image'       => 'https://media.base44.com/images/public/6a335bc52ea23c09416a685d/d0f743324_generated_99b5c623.png',
            'features'    => array(
                "Plateaux de snacks sur mesure (akkara, fataya, nems, pastels...)",
                "Capacité d'accueil de 50 à 2000+ convives",
                "Service traiteur clé en main avec personnel dédié",
                "Menus adaptés aux préférences alimentaires",
                "Livraison et mise en place incluses à Dakar",
            ),
        ),
        array(
            'icon'        => 'building',
            'title'       => 'Gestion de Cantines & Cafétérias',
            'subtitle'    => 'Un service B2B premium',
            'description' => "Nous accompagnons les entreprises, écoles et institutions dans la gestion quotidienne de leur restauration collective. Un partenariat de confiance pour offrir à vos équipes une alimentation saine, variée et de qualité.",
            'image'       => 'https://media.base44.com/images/public/6a335bc52ea23c09416a685d/90a8e37e2_generated_e0fb4722.png',
            'features'    => array(
                "Gestion complète de la cantine (personnel, logistique, menu)",
                "Menus rotatifs hebdomadaires équilibrés",
                "Respect strict des normes d'hygiène HACCP",
                "Rapports mensuels et suivi de satisfaction",
                "Flexibilité selon le nombre de couverts",
            ),
        ),
        array(
            'icon'        => 'package',
            'title'       => 'Produits Traiteur & Fumés',
            'subtitle'    => 'Des saveurs authentiques',
            'description' => "Notre gamme de produits fumés — poulet, kong, pigeon — est préparée selon des techniques artisanales au beurre de coco. Idéaux pour la conservation longue durée à -18°C, ils sont prisés par les particuliers comme les professionnels.",
            'image'       => 'https://media.base44.com/images/public/6a335bc52ea23c09416a685d/6b081792e_generated_e1eee356.png',
            'features'    => array(
                "Fumage artisanal au beurre de coco",
                "Conservation longue durée à -18°C",
                "Poulet fumé, Kong fumé, Pigeon fumé",
                "Vente au détail et en gros",
                "Disponible chez nos partenaires distributeurs",
            ),
        ),
    );
}

/* -------------------------------------------------------------------------- */
/* Getter                                                                       */
/* -------------------------------------------------------------------------- */
function dm_get_services_sections()
{
    $data = get_option('dm_services_sections', null);
    if ($data === null || !is_array($data) || empty($data)) {
        $defaults = dm_default_services_sections();
        update_option('dm_services_sections', $defaults);
        return $defaults;
    }
    return $data;
}

/* -------------------------------------------------------------------------- */
/* Sanitizer                                                                    */
/* -------------------------------------------------------------------------- */
function dm_sanitize_services_sections($input)
{
    $clean = array();
    if (!is_array($input)) return $clean;

    $icons = array('utensils', 'building', 'package', 'fish', 'flame', 'truck', 'leaf', 'star', 'check', 'heart', 'clock', 'shield', 'cart', 'clipboard', 'coffee', 'award', 'users', 'store');

    foreach ($input as $row) {
        if (!is_array($row)) continue;
        $item = array();
        $has_data = false;

        // Icon
        $icon = isset($row['icon']) ? sanitize_text_field($row['icon']) : 'fish';
        $item['icon'] = in_array($icon, $icons) ? $icon : 'fish';

        // Text fields
        $text_fields = array('title', 'subtitle', 'description', 'image');
        foreach ($text_fields as $key) {
            $val = isset($row[$key]) ? $row[$key] : '';
            if ($key === 'description') {
                $val = sanitize_textarea_field($val);
            } elseif ($key === 'image') {
                $val = esc_url_raw($val);
            } else {
                $val = sanitize_text_field($val);
            }
            $item[$key] = $val;
            if ($val !== '') $has_data = true;
        }

        // Features (sub-array)
        $features = array();
        if (isset($row['features']) && is_array($row['features'])) {
            foreach ($row['features'] as $f) {
                $fval = sanitize_text_field($f);
                if ($fval !== '') {
                    $features[] = $fval;
                }
            }
        }
        $item['features'] = $features;
        if (!empty($features)) $has_data = true;

        if ($has_data) $clean[] = $item;
    }
    return $clean;
}

/* -------------------------------------------------------------------------- */
/* Register settings                                                            */
/* -------------------------------------------------------------------------- */
add_action('admin_init', function () {
    register_setting('dm_services_group', 'dm_services_sections', array(
        'type'              => 'array',
        'sanitize_callback' => 'dm_sanitize_services_sections',
        'default'           => array(),
    ));
    register_setting('dm_services_group', 'dm_services_hero_image', array(
        'type'              => 'string',
        'sanitize_callback' => 'esc_url_raw',
        'default'           => '',
    ));
});

/* -------------------------------------------------------------------------- */
/* Enqueue media uploader sur la page admin services                            */
/* -------------------------------------------------------------------------- */
add_action('admin_enqueue_scripts', function ($hook) {
    if (isset($_GET['page']) && $_GET['page'] === 'dm-services') {
        wp_enqueue_media();
        wp_enqueue_script('jquery');
    }
});

/* -------------------------------------------------------------------------- */
/* Sous-menu "Nos Services" sous Délices Content                                */
/* -------------------------------------------------------------------------- */
add_action('admin_menu', function () {
    add_submenu_page(
        'dm-content',
        'Nos Services',
        'Nos Services',
        'manage_options',
        'dm-services',
        'dm_services_page_html'
    );
}, 20);

/* -------------------------------------------------------------------------- */
/* Page HTML — repeater dynamique avec add/remove + media uploader              */
/* -------------------------------------------------------------------------- */
function dm_services_page_html()
{
    if (!current_user_can('manage_options')) {
        return;
    }
    $sections = dm_get_services_sections();
    if (!is_array($sections)) {
        $sections = array();
    }
    $icons = array(
        'utensils'   => 'Couverts (Restauration)',
        'building'   => 'Bâtiment (Cantines)',
        'package'    => 'Colis (Traiteur)',
        'fish'       => 'Poisson',
        'flame'      => 'Flamme (Fumage)',
        'truck'      => 'Camion (Distribution)',
        'leaf'       => 'Feuille (Bio)',
        'star'       => 'Étoile',
        'check'      => 'Check',
        'heart'      => 'Cœur',
        'clock'      => 'Horloge',
        'shield'     => 'Bouclier',
        'cart'       => 'Panier',
        'clipboard'  => 'Presse-papier',
        'coffee'     => 'Café',
        'award'      => 'Médaille',
        'users'      => 'Utilisateurs',
        'store'      => 'Magasin',
    );
    ?>
    <div class="wrap">
        <h1>Nos Services</h1>
        <p>Gérez les sections de services affichées sur la page <strong>Nos Services</strong> (<code>/services</code>). Chaque section comporte une icône, un titre, un sous-titre, une description, une image, une liste d'avantages (features) et un bouton de contact WhatsApp automatique.</p>

        <form method="post" action="options.php">
            <?php settings_fields('dm_services_group'); ?>

            <!-- Hero image option -->
            <h3>Image de bannière (Hero)</h3>
            <p class="description">Image de fond affichée en pleine largeur en haut de la page Nos Services. Si vide, un dégradé navy est utilisé.</p>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="dm_services_hero_image">Image du Hero</label></th>
                    <td>
                        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                            <input type="text" id="dm_services_hero_image" name="dm_services_hero_image" value="<?php echo esc_attr(get_option('dm_services_hero_image', '')); ?>" placeholder="URL de l'image (laisser vide pour le dégradé par défaut)" class="regular-text dm-svc-hero-img-input" style="width:400px;" />
                            <button type="button" class="button dm-svc-hero-upload">Choisir une image</button>
                            <?php $hero_img_val = get_option('dm_services_hero_image', ''); ?>
                            <?php if (!empty($hero_img_val)) : ?>
                                <img src="<?php echo esc_url($hero_img_val); ?>" alt="" style="max-width:120px;max-height:60px;object-fit:cover;border-radius:4px;" />
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            </table>
            <hr style="margin:1.5rem 0;" />

            <h3>Sections de services</h3>
            <div id="dm-services-repeater">
                <?php foreach ($sections as $i => $section) : ?>
                <div class="dm-svc-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin-bottom:16px;">
                    <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:8px;flex-wrap:wrap;">
                        <select name="dm_services_sections[<?php echo esc_attr($i); ?>][icon]" style="width:200px;">
                            <?php foreach ($icons as $val => $label) : ?>
                                <option value="<?php echo esc_attr($val); ?>"<?php selected($section['icon'] ?? 'fish', $val); ?>><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="dm_services_sections[<?php echo esc_attr($i); ?>][title]" value="<?php echo esc_attr($section['title'] ?? ''); ?>" placeholder="Titre du service" class="regular-text" style="width:300px;" />
                        <input type="text" name="dm_services_sections[<?php echo esc_attr($i); ?>][subtitle]" value="<?php echo esc_attr($section['subtitle'] ?? ''); ?>" placeholder="Sous-titre / accroche" class="regular-text" style="width:250px;" />
                    </div>
                    <div style="margin-bottom:8px;">
                        <textarea name="dm_services_sections[<?php echo esc_attr($i); ?>][description]" rows="3" placeholder="Description du service" style="width:100%;max-width:700px;"><?php echo esc_textarea($section['description'] ?? ''); ?></textarea>
                    </div>
                    <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;flex-wrap:wrap;">
                        <input type="text" name="dm_services_sections[<?php echo esc_attr($i); ?>][image]" value="<?php echo esc_attr($section['image'] ?? ''); ?>" placeholder="URL de l'image" class="regular-text dm-svc-img-input" style="width:350px;" />
                        <button type="button" class="button dm-svc-upload">Image</button>
                        <?php if (!empty($section['image'])) : ?>
                            <img src="<?php echo esc_url($section['image']); ?>" alt="" style="max-width:80px;max-height:60px;object-fit:cover;border-radius:4px;" />
                        <?php endif; ?>
                    </div>
                    <!-- Features sub-repeater -->
                    <div class="dm-svc-features" style="margin-bottom:8px;">
                        <strong>Avantages / Features :</strong>
                        <?php $features = $section['features'] ?? array(); ?>
                        <?php if (empty($features)) $features = array(''); ?>
                        <?php foreach ($features as $fi => $feature) : ?>
                        <div class="dm-svc-feature-row" style="display:flex;gap:8px;align-items:center;margin-top:6px;">
                            <input type="text" name="dm_services_sections[<?php echo esc_attr($i); ?>][features][]" value="<?php echo esc_attr($feature); ?>" placeholder="Avantage (ex: Service traiteur clé en main)" class="regular-text dm-svc-feature-input" style="width:500px;" />
                            <button type="button" class="button button-link-delete dm-svc-feature-remove">Retirer</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <p style="margin-top:4px;">
                        <button type="button" class="button button-secondary dm-svc-feature-add">+ Ajouter un avantage</button>
                    </p>
                    <div style="margin-top:8px;">
                        <button type="button" class="button button-link-delete dm-svc-remove">Supprimer cette section</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <p>
                <button type="button" class="button button-secondary" id="dm-svc-add">+ Ajouter une section de service</button>
            </p>
            <?php submit_button('Enregistrer les services'); ?>
        </form>

        <script>
        jQuery(function($) {
            var svcIndex = <?php echo max(array_keys($sections)) + 1; ?>;
            var iconsHtml = '<?php foreach ($icons as $val => $label) : ?><option value="<?php echo esc_attr($val); ?>"><?php echo esc_html($label); ?></option><?php endforeach; ?>';

            function createRow() {
                var html = '<div class="dm-svc-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin-bottom:16px;">' +
                    '<div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:8px;flex-wrap:wrap;">' +
                        '<select name="dm_services_sections[' + svcIndex + '][icon]" style="width:200px;">' + iconsHtml + '</select>' +
                        '<input type="text" name="dm_services_sections[' + svcIndex + '][title]" value="" placeholder="Titre du service" class="regular-text" style="width:300px;" />' +
                        '<input type="text" name="dm_services_sections[' + svcIndex + '][subtitle]" value="" placeholder="Sous-titre / accroche" class="regular-text" style="width:250px;" />' +
                    '</div>' +
                    '<div style="margin-bottom:8px;">' +
                        '<textarea name="dm_services_sections[' + svcIndex + '][description]" rows="3" placeholder="Description du service" style="width:100%;max-width:700px;"></textarea>' +
                    '</div>' +
                    '<div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;flex-wrap:wrap;">' +
                        '<input type="text" name="dm_services_sections[' + svcIndex + '][image]" value="" placeholder="URL de l\'image" class="regular-text dm-svc-img-input" style="width:350px;" />' +
                        '<button type="button" class="button dm-svc-upload">Image</button>' +
                    '</div>' +
                    '<div class="dm-svc-features" style="margin-bottom:8px;">' +
                        '<strong>Avantages / Features :</strong>' +
                        '<div class="dm-svc-feature-row" style="display:flex;gap:8px;align-items:center;margin-top:6px;">' +
                            '<input type="text" name="dm_services_sections[' + svcIndex + '][features][]" value="" placeholder="Avantage (ex: Service traiteur clé en main)" class="regular-text dm-svc-feature-input" style="width:500px;" />' +
                            '<button type="button" class="button button-link-delete dm-svc-feature-remove">Retirer</button>' +
                        '</div>' +
                    '</div>' +
                    '<p style="margin-top:4px;"><button type="button" class="button button-secondary dm-svc-feature-add">+ Ajouter un avantage</button></p>' +
                    '<div style="margin-top:8px;"><button type="button" class="button button-link-delete dm-svc-remove">Supprimer cette section</button></div>' +
                '</div>';
                svcIndex++;
                return html;
            }

            $('#dm-svc-add').on('click', function() {
                $('#dm-services-repeater').append(createRow());
            });

            $('#dm-services-repeater').on('click', '.dm-svc-remove', function() {
                $(this).closest('.dm-svc-row').remove();
            });

            // Feature add/remove
            $('#dm-services-repeater').on('click', '.dm-svc-feature-add', function() {
                var $container = $(this).closest('.dm-svc-row').find('.dm-svc-features');
                var rowIndex = $(this).closest('.dm-svc-row').index();
                var html = '<div class="dm-svc-feature-row" style="display:flex;gap:8px;align-items:center;margin-top:6px;">' +
                    '<input type="text" name="dm_services_sections[' + rowIndex + '][features][]" value="" placeholder="Avantage (ex: Service traiteur clé en main)" class="regular-text dm-svc-feature-input" style="width:500px;" />' +
                    '<button type="button" class="button button-link-delete dm-svc-feature-remove">Retirer</button>' +
                '</div>';
                $container.append(html);
            });

            $('#dm-services-repeater').on('click', '.dm-svc-feature-remove', function() {
                $(this).closest('.dm-svc-feature-row').remove();
            });

            // Image uploader
            $('#dm-services-repeater').on('click', '.dm-svc-upload', function(e) {
                e.preventDefault();
                var $input = $(this).siblings('.dm-svc-img-input');
                var frame = wp.media({
                    title: 'Choisir une image',
                    button: { text: 'Utiliser comme image' },
                    multiple: false
                });
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $input.val(attachment.url);
                });
                frame.open();
            });

            // Hero image uploader
            $('.dm-svc-hero-upload').on('click', function(e) {
                e.preventDefault();
                var $input = $(this).siblings('.dm-svc-hero-img-input');
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
    </div>
    <?php
}
