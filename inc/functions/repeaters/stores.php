<?php
/**
 * Repeater: Points de Vente
 * Page: /points-de-vente
 *
 * Champs par store :
 *   - name    : Nom de l'enseigne
 *   - type    : Type (Grande distribution, Hôtel, Station-service, Transport, Entreprise)
 *   - zone    : Zone géographique (Dakar, Dakar & Régions)
 *   - address : Adresse / localisation
 *   - rayon   : Rayon / service proposé
 *   - phone   : Téléphone (optionnel)
 *   - image   : URL de l'image (photo du point de vente)
 */

if (! defined('ABSPATH')) {
    exit;
}

/* -------------------------------------------------------------------------- */
/* Données par défaut (issues du prototype StoreLocator.jsx)                   */
/* -------------------------------------------------------------------------- */
function dm_default_stores()
{
    return array(
        array(
            'name'    => 'Carrefour',
            'type'    => 'Grande distribution',
            'zone'    => 'Dakar',
            'address' => 'Centre-ville, Dakar',
            'rayon'   => 'Rayon surgelés & boulangerie',
            'phone'   => '',
            'image'   => 'https://images.unsplash.com/photo-1534723452862-4c874018d66d?w=600&h=400&fit=crop',
        ),
        array(
            'name'    => 'Auchan',
            'type'    => 'Grande distribution',
            'zone'    => 'Dakar',
            'address' => 'Plusieurs points à Dakar',
            'rayon'   => 'Rayon surgelés & snacking',
            'phone'   => '',
            'image'   => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=600&h=400&fit=crop',
        ),
        array(
            'name'    => 'Novotel',
            'type'    => 'Hôtel',
            'zone'    => 'Dakar',
            'address' => 'Novotel Dakar',
            'rayon'   => 'Restaurant & room service',
            'phone'   => '',
            'image'   => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&h=400&fit=crop',
        ),
        array(
            'name'    => 'Pullman',
            'type'    => 'Hôtel',
            'zone'    => 'Dakar',
            'address' => 'Pullman Dakar Teranga',
            'rayon'   => 'Restaurant & banquets',
            'phone'   => '',
            'image'   => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=600&h=400&fit=crop',
        ),
        array(
            'name'    => 'Terrou-Bi',
            'type'    => 'Hôtel',
            'zone'    => 'Dakar',
            'address' => 'Hôtel Terrou-Bi, Corniche',
            'rayon'   => 'Restaurant & plage',
            'phone'   => '',
            'image'   => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=600&h=400&fit=crop',
        ),
        array(
            'name'    => 'TotalEnergies',
            'type'    => 'Station-service',
            'zone'    => 'Dakar & Régions',
            'address' => 'Stations TotalEnergies',
            'rayon'   => 'Boutique station',
            'phone'   => '',
            'image'   => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=600&h=400&fit=crop',
        ),
        array(
            'name'    => 'Shell',
            'type'    => 'Station-service',
            'zone'    => 'Dakar & Régions',
            'address' => 'Stations Shell',
            'rayon'   => 'Boutique station',
            'phone'   => '',
            'image'   => 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?w=600&h=400&fit=crop',
        ),
        array(
            'name'    => 'Seter',
            'type'    => 'Transport',
            'zone'    => 'Dakar',
            'address' => 'Gares TER',
            'rayon'   => 'Points de vente en gare',
            'phone'   => '',
            'image'   => 'https://images.unsplash.com/photo-1570125909232-eb263c4e96cb?w=600&h=400&fit=crop',
        ),
        array(
            'name'    => "Sen'Eau",
            'type'    => 'Entreprise',
            'zone'    => 'Dakar',
            'address' => "Bureaux Sen'Eau",
            'rayon'   => "Cantine d'entreprise",
            'phone'   => '',
            'image'   => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=600&h=400&fit=crop',
        ),
        array(
            'name'    => 'EDK',
            'type'    => 'Grande distribution',
            'zone'    => 'Dakar',
            'address' => 'Groupe EDK, Dakar',
            'rayon'   => 'Rayon snacking',
            'phone'   => '',
            'image'   => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&h=400&fit=crop',
        ),
    );
}

/* -------------------------------------------------------------------------- */
/* Getter — récupère les stores depuis wp_options avec fallback démo           */
/* -------------------------------------------------------------------------- */
function dm_get_stores()
{
    $stores = get_option('dm_stores', null);
    if ($stores === null || !is_array($stores) || empty($stores)) {
        $defaults = dm_default_stores();
        update_option('dm_stores', $defaults);
        return $defaults;
    }
    return $stores;
}

/* -------------------------------------------------------------------------- */
/* Sanitize                                                                    */
/* -------------------------------------------------------------------------- */
function dm_sanitize_stores($input)
{
    $clean = array();
    if (!is_array($input)) {
        return $clean;
    }
    $fields = array('name', 'type', 'zone', 'address', 'rayon', 'phone', 'image');
    foreach ($input as $row) {
        if (!is_array($row)) {
            continue;
        }
        $item = array();
        $has_data = false;
        foreach ($fields as $key) {
            $val = isset($row[$key]) ? $row[$key] : '';
            if ($key === 'image' || $key === 'phone') {
                $val = sanitize_text_field($val);
            } else {
                $val = sanitize_text_field($val);
            }
            $item[$key] = $val;
            if ($val !== '') {
                $has_data = true;
            }
        }
        if ($has_data) {
            $clean[] = $item;
        }
    }
    return $clean;
}

/* -------------------------------------------------------------------------- */
/* Register setting                                                            */
/* -------------------------------------------------------------------------- */
add_action('admin_init', function () {
    register_setting('dm_stores_group', 'dm_stores', array(
        'type'              => 'array',
        'sanitize_callback' => 'dm_sanitize_stores',
        'default'           => array(),
    ));
    register_setting('dm_stores_group', 'dm_stores_hero_image', array(
        'type'              => 'string',
        'sanitize_callback' => 'esc_url_raw',
        'default'           => '',
    ));
});

/* ========================================================================== */
/* REPEATER : Étapes "Devenir Distributeur"                                    */
/* Pattern : steps-resto de Kante Volaille (num, title, desc + image option)   */
/* ========================================================================== */

function dm_default_partner_steps()
{
    return array(
        array(
            'num'   => '01',
            'title' => 'Contactez-nous',
            'desc'  => "Envoyez-nous votre demande via WhatsApp ou par téléphone. Nous étudierons votre profil et votre zone d'activité.",
            'image' => '',
        ),
        array(
            'num'   => '02',
            'title' => 'Étude de votre dossier',
            'desc'  => "Nous analysons votre enseigne, votre emplacement et votre capacité de distribution pour garantir un partenariat gagnant-gagnant.",
            'image' => '',
        ),
        array(
            'num'   => '03',
            'title' => 'Signature du contrat',
            'desc'  => "Après validation, nous établissons un contrat de partenariat définissant les conditions, les délais de livraison et les engagements mutuels.",
            'image' => '',
        ),
        array(
            'num'   => '04',
            'title' => 'Livraison & lancement',
            'desc'  => "Nous livrons vos premiers produits et vous accompagnons dans le lancement : mise en rayon, supports de vente et formation de vos équipes.",
            'image' => '',
        ),
    );
}

function dm_get_partner_steps()
{
    $data = get_option('dm_partner_steps', null);
    if ($data === null || !is_array($data) || empty($data)) {
        $defaults = dm_default_partner_steps();
        update_option('dm_partner_steps', $defaults);
        return $defaults;
    }
    return $data;
}

function dm_sanitize_partner_steps($input)
{
    $clean = array();
    if (!is_array($input)) return $clean;
    $fields = array('num', 'title', 'desc', 'image');
    foreach ($input as $row) {
        if (!is_array($row)) continue;
        $item = array();
        $has_data = false;
        foreach ($fields as $key) {
            $val = isset($row[$key]) ? $row[$key] : '';
            $val = sanitize_text_field($val);
            $item[$key] = $val;
            if ($val !== '') $has_data = true;
        }
        if ($has_data) $clean[] = $item;
    }
    return $clean;
}

/* ========================================================================== */
/* REPEATER : Avantages Distributeur                                           */
/* ========================================================================== */

function dm_default_partner_advantages()
{
    return array(
        array(
            'title' => 'Produits de qualité',
            'desc'  => "Accédez à notre gamme complète de produits de la mer, frais et surgelés, issus d'une sélection rigoureuse.",
            'image' => '',
        ),
        array(
            'title' => 'Livraison rapide',
            'desc'  => "Notre logistique assure des livraisons régulières et fiables dans toute la zone de Dakar et ses régions.",
            'image' => '',
        ),
        array(
            'title' => 'Accompagnement dédié',
            'desc'  => "Un chargé de compte vous accompagne pour optimiser vos ventes et développer votre rayon produits de la mer.",
            'image' => '',
        ),
        array(
            'title' => 'Marges attractives',
            'desc'  => "Bénéficiez de conditions tarifaires compétitives et de remises sur volume pour maximiser votre rentabilité.",
            'image' => '',
        ),
    );
}

function dm_get_partner_advantages()
{
    $data = get_option('dm_partner_advantages', null);
    if ($data === null || !is_array($data) || empty($data)) {
        $defaults = dm_default_partner_advantages();
        update_option('dm_partner_advantages', $defaults);
        return $defaults;
    }
    return $data;
}

function dm_sanitize_partner_advantages($input)
{
    $clean = array();
    if (!is_array($input)) return $clean;
    $fields = array('title', 'desc', 'image');
    foreach ($input as $row) {
        if (!is_array($row)) continue;
        $item = array();
        $has_data = false;
        foreach ($fields as $key) {
            $val = isset($row[$key]) ? $row[$key] : '';
            $val = sanitize_text_field($val);
            $item[$key] = $val;
            if ($val !== '') $has_data = true;
        }
        if ($has_data) $clean[] = $item;
    }
    return $clean;
}

/* -------------------------------------------------------------------------- */
/* Register settings                                                           */
/* -------------------------------------------------------------------------- */
add_action('admin_init', function () {
    register_setting('dm_stores_group', 'dm_stores', array(
        'type'              => 'array',
        'sanitize_callback' => 'dm_sanitize_stores',
        'default'           => array(),
    ));
    register_setting('dm_partner_steps_group', 'dm_partner_steps', array(
        'type'              => 'array',
        'sanitize_callback' => 'dm_sanitize_partner_steps',
        'default'           => array(),
    ));
    register_setting('dm_partner_advantages_group', 'dm_partner_advantages', array(
        'type'              => 'array',
        'sanitize_callback' => 'dm_sanitize_partner_advantages',
        'default'           => array(),
    ));
});

/* -------------------------------------------------------------------------- */
/* Enqueue media uploader sur la page admin stores                             */
/* -------------------------------------------------------------------------- */
add_action('admin_enqueue_scripts', function ($hook) {
    if (isset($_GET['page']) && $_GET['page'] === 'dm-stores') {
        wp_enqueue_media();
        wp_enqueue_script('jquery');
    }
});

/* -------------------------------------------------------------------------- */
/* Sous-menu "Points de Vente" sous Délices Content                            */
/* -------------------------------------------------------------------------- */
add_action('admin_menu', function () {
    add_submenu_page(
        'dm-content',
        'Points de Vente',
        'Points de Vente',
        'manage_options',
        'dm-stores',
        'dm_stores_page_html'
    );
}, 20);

/* -------------------------------------------------------------------------- */
/* Page HTML — repeater dynamique avec add/remove + media uploader              */
/* -------------------------------------------------------------------------- */
function dm_stores_page_html()
{
    if (!current_user_can('manage_options')) {
        return;
    }
    $stores = dm_get_stores();
    if (!is_array($stores)) {
        $stores = array();
    }
    $partner_steps = dm_get_partner_steps();
    $partner_advantages = dm_get_partner_advantages();
    $types = array(
        'Grande distribution' => 'Grande distribution',
        'Hôtel'               => 'Hôtel',
        'Station-service'     => 'Station-service',
        'Transport'           => 'Transport',
        'Entreprise'          => 'Entreprise',
    );
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'stores';
    ?>
    <div class="wrap">
        <h1>Points de Vente</h1>
        <p>Gérez les points de vente, les étapes pour devenir distributeur et les avantages partenaires affichés sur la page <strong>Points de Vente</strong> (<code>/points-de-vente</code>).</p>

        <h2 class="nav-tab-wrapper">
            <a href="<?php echo esc_url(admin_url('admin.php?page=dm-stores&tab=stores')); ?>" class="nav-tab<?php echo $active_tab === 'stores' ? ' nav-tab-active' : ''; ?>">Points de vente</a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=dm-stores&tab=steps')); ?>" class="nav-tab<?php echo $active_tab === 'steps' ? ' nav-tab-active' : ''; ?>">Étapes distributeur</a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=dm-stores&tab=advantages')); ?>" class="nav-tab<?php echo $active_tab === 'advantages' ? ' nav-tab-active' : ''; ?>">Avantages partenaires</a>
        </h2>

        <?php if ($active_tab === 'stores') : ?>
        <!-- TAB: STORES -->
        <form method="post" action="options.php">
            <?php settings_fields('dm_stores_group'); ?>

            <!-- Hero image option -->
            <h3>Image de bannière (Hero)</h3>
            <p class="description">Image de fond affichée en pleine largeur en haut de la page Points de Vente. Si vide, l'image par défaut est utilisée.</p>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="dm_stores_hero_image">Image du Hero</label></th>
                    <td>
                        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                            <input type="text" id="dm_stores_hero_image" name="dm_stores_hero_image" value="<?php echo esc_attr(get_option('dm_stores_hero_image', '')); ?>" placeholder="URL de l'image (laisser vide pour l'image par défaut)" class="regular-text dm-hero-img-input" style="width:400px;" />
                            <button type="button" class="button dm-hero-upload">Choisir une image</button>
                            <?php $hero_img_val = get_option('dm_stores_hero_image', ''); ?>
                            <?php if (!empty($hero_img_val)) : ?>
                                <img src="<?php echo esc_url($hero_img_val); ?>" alt="" style="max-width:120px;max-height:60px;object-fit:cover;border-radius:4px;" />
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            </table>
            <hr style="margin:1.5rem 0;" />

            <h3>Points de vente</h3>
            <div id="dm-stores-repeater">
                <?php foreach ($stores as $i => $store) : ?>
                <div class="dm-store-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin-bottom:12px;">
                    <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:8px;flex-wrap:wrap;">
                        <input type="text" name="dm_stores[<?php echo esc_attr($i); ?>][name]" value="<?php echo esc_attr($store['name'] ?? ''); ?>" placeholder="Nom de l'enseigne" class="regular-text" style="width:200px;" />
                        <select name="dm_stores[<?php echo esc_attr($i); ?>][type]" style="width:180px;">
                            <?php foreach ($types as $val => $label) : ?>
                                <option value="<?php echo esc_attr($val); ?>"<?php selected($store['type'] ?? '', $val); ?>><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="dm_stores[<?php echo esc_attr($i); ?>][zone]" value="<?php echo esc_attr($store['zone'] ?? ''); ?>" placeholder="Zone (ex: Dakar)" class="regular-text" style="width:160px;" />
                    </div>
                    <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:8px;flex-wrap:wrap;">
                        <input type="text" name="dm_stores[<?php echo esc_attr($i); ?>][address]" value="<?php echo esc_attr($store['address'] ?? ''); ?>" placeholder="Adresse" class="regular-text" style="width:250px;" />
                        <input type="text" name="dm_stores[<?php echo esc_attr($i); ?>][rayon]" value="<?php echo esc_attr($store['rayon'] ?? ''); ?>" placeholder="Rayon / service (ex: Rayon surgelés)" class="regular-text" style="width:250px;" />
                        <input type="text" name="dm_stores[<?php echo esc_attr($i); ?>][phone]" value="<?php echo esc_attr($store['phone'] ?? ''); ?>" placeholder="Téléphone (optionnel)" class="regular-text" style="width:160px;" />
                    </div>
                    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                        <input type="text" name="dm_stores[<?php echo esc_attr($i); ?>][image]" value="<?php echo esc_attr($store['image'] ?? ''); ?>" placeholder="URL de l'image" class="regular-text dm-store-img-input" style="width:350px;" />
                        <button type="button" class="button dm-store-upload">Image</button>
                        <?php if (!empty($store['image'])) : ?>
                            <img src="<?php echo esc_url($store['image']); ?>" alt="" style="max-width:80px;max-height:60px;object-fit:cover;border-radius:4px;" />
                        <?php endif; ?>
                        <button type="button" class="button button-link-delete dm-store-remove" style="margin-left:auto;">Supprimer</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <p>
                <button type="button" class="button button-secondary" id="dm-store-add">+ Ajouter un point de vente</button>
            </p>
            <?php submit_button('Enregistrer les points de vente'); ?>
        </form>

        <script>
        jQuery(function($) {
            var storeIndex = <?php echo max(array_keys($stores)) + 1; ?>;
            var typesHtml = '<?php foreach ($types as $val => $label) : ?><option value="<?php echo esc_attr($val); ?>"><?php echo esc_html($label); ?></option><?php endforeach; ?>';

            function createRow() {
                var html = '<div class="dm-store-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin-bottom:12px;">' +
                    '<div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:8px;flex-wrap:wrap;">' +
                        '<input type="text" name="dm_stores[' + storeIndex + '][name]" value="" placeholder="Nom de l\'enseigne" class="regular-text" style="width:200px;" />' +
                        '<select name="dm_stores[' + storeIndex + '][type]" style="width:180px;">' + typesHtml + '</select>' +
                        '<input type="text" name="dm_stores[' + storeIndex + '][zone]" value="" placeholder="Zone (ex: Dakar)" class="regular-text" style="width:160px;" />' +
                    '</div>' +
                    '<div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:8px;flex-wrap:wrap;">' +
                        '<input type="text" name="dm_stores[' + storeIndex + '][address]" value="" placeholder="Adresse" class="regular-text" style="width:250px;" />' +
                        '<input type="text" name="dm_stores[' + storeIndex + '][rayon]" value="" placeholder="Rayon / service" class="regular-text" style="width:250px;" />' +
                        '<input type="text" name="dm_stores[' + storeIndex + '][phone]" value="" placeholder="Téléphone (optionnel)" class="regular-text" style="width:160px;" />' +
                    '</div>' +
                    '<div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">' +
                        '<input type="text" name="dm_stores[' + storeIndex + '][image]" value="" placeholder="URL de l\'image" class="regular-text dm-store-img-input" style="width:350px;" />' +
                        '<button type="button" class="button dm-store-upload">Image</button>' +
                        '<button type="button" class="button button-link-delete dm-store-remove" style="margin-left:auto;">Supprimer</button>' +
                    '</div>' +
                '</div>';
                storeIndex++;
                return html;
            }

            $('#dm-store-add').on('click', function() {
                $('#dm-stores-repeater').append(createRow());
            });

            $('#dm-stores-repeater').on('click', '.dm-store-remove', function() {
                $(this).closest('.dm-store-row').remove();
            });

            $('#dm-stores-repeater').on('click', '.dm-store-upload', function(e) {
                e.preventDefault();
                var $input = $(this).siblings('.dm-store-img-input');
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
            $('.dm-hero-upload').on('click', function(e) {
                e.preventDefault();
                var $input = $(this).siblings('.dm-hero-img-input');
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

        <?php elseif ($active_tab === 'steps') : ?>
        <!-- TAB: STEPS -->
        <form method="post" action="options.php">
            <?php settings_fields('dm_partner_steps_group'); ?>
            <p>Les étapes sont affichées dans la section « Comment devenir distributeur » sur la page Points de Vente. Chaque étape comporte un numéro (01, 02, 03...), un titre, une description et une image optionnelle (à la place du numéro).</p>
            <div id="dm-steps-repeater">
                <?php foreach ($partner_steps as $i => $step) : ?>
                <div class="dm-step-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin-bottom:12px;">
                    <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:8px;flex-wrap:wrap;">
                        <input type="text" name="dm_partner_steps[<?php echo esc_attr($i); ?>][num]" value="<?php echo esc_attr($step['num'] ?? ''); ?>" placeholder="01" class="regular-text" style="width:60px;" />
                        <input type="text" name="dm_partner_steps[<?php echo esc_attr($i); ?>][title]" value="<?php echo esc_attr($step['title'] ?? ''); ?>" placeholder="Titre de l'étape" class="regular-text" style="width:250px;" />
                    </div>
                    <div style="margin-bottom:8px;">
                        <textarea name="dm_partner_steps[<?php echo esc_attr($i); ?>][desc]" rows="2" placeholder="Description de l'étape" style="width:100%;max-width:600px;"><?php echo esc_textarea($step['desc'] ?? ''); ?></textarea>
                    </div>
                    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                        <input type="text" name="dm_partner_steps[<?php echo esc_attr($i); ?>][image]" value="<?php echo esc_attr($step['image'] ?? ''); ?>" placeholder="URL image (optionnel, remplace le numéro)" class="regular-text dm-step-img-input" style="width:350px;" />
                        <button type="button" class="button dm-step-upload">Image</button>
                        <?php if (!empty($step['image'])) : ?>
                            <img src="<?php echo esc_url($step['image']); ?>" alt="" style="max-width:60px;max-height:60px;object-fit:cover;border-radius:4px;" />
                        <?php endif; ?>
                        <button type="button" class="button button-link-delete dm-step-remove" style="margin-left:auto;">Supprimer</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <p>
                <button type="button" class="button button-secondary" id="dm-step-add">+ Ajouter une étape</button>
            </p>
            <?php submit_button('Enregistrer les étapes'); ?>
        </form>

        <script>
        jQuery(function($) {
            var stepIndex = <?php echo max(array_keys($partner_steps)) + 1; ?>;
            $('#dm-step-add').on('click', function() {
                var html = '<div class="dm-step-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin-bottom:12px;">' +
                    '<div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:8px;flex-wrap:wrap;">' +
                        '<input type="text" name="dm_partner_steps[' + stepIndex + '][num]" value="" placeholder="01" class="regular-text" style="width:60px;" />' +
                        '<input type="text" name="dm_partner_steps[' + stepIndex + '][title]" value="" placeholder="Titre de l\'étape" class="regular-text" style="width:250px;" />' +
                    '</div>' +
                    '<div style="margin-bottom:8px;">' +
                        '<textarea name="dm_partner_steps[' + stepIndex + '][desc]" rows="2" placeholder="Description de l\'étape" style="width:100%;max-width:600px;"></textarea>' +
                    '</div>' +
                    '<div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">' +
                        '<input type="text" name="dm_partner_steps[' + stepIndex + '][image]" value="" placeholder="URL image (optionnel)" class="regular-text dm-step-img-input" style="width:350px;" />' +
                        '<button type="button" class="button dm-step-upload">Image</button>' +
                        '<button type="button" class="button button-link-delete dm-step-remove" style="margin-left:auto;">Supprimer</button>' +
                    '</div>' +
                '</div>';
                stepIndex++;
                $('#dm-steps-repeater').append(html);
            });
            $('#dm-steps-repeater').on('click', '.dm-step-remove', function() {
                $(this).closest('.dm-step-row').remove();
            });
            $('#dm-steps-repeater').on('click', '.dm-step-upload', function(e) {
                e.preventDefault();
                var $input = $(this).siblings('.dm-step-img-input');
                var frame = wp.media({ title: 'Choisir une image', button: { text: 'Utiliser comme image' }, multiple: false });
                frame.on('select', function() {
                    $input.val(frame.state().get('selection').first().toJSON().url);
                });
                frame.open();
            });
        });
        </script>

        <?php elseif ($active_tab === 'advantages') : ?>
        <!-- TAB: ADVANTAGES -->
        <form method="post" action="options.php">
            <?php settings_fields('dm_partner_advantages_group'); ?>
            <p>Les avantages sont affichés dans la section « Pourquoi nous choisir » sur la page Points de Vente. Chaque avantage comporte un titre, une description et une image optionnelle.</p>
            <div id="dm-adv-repeater">
                <?php foreach ($partner_advantages as $i => $adv) : ?>
                <div class="dm-adv-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin-bottom:12px;">
                    <div style="margin-bottom:8px;">
                        <input type="text" name="dm_partner_advantages[<?php echo esc_attr($i); ?>][title]" value="<?php echo esc_attr($adv['title'] ?? ''); ?>" placeholder="Titre de l'avantage" class="regular-text" style="width:300px;" />
                    </div>
                    <div style="margin-bottom:8px;">
                        <textarea name="dm_partner_advantages[<?php echo esc_attr($i); ?>][desc]" rows="2" placeholder="Description de l'avantage" style="width:100%;max-width:600px;"><?php echo esc_textarea($adv['desc'] ?? ''); ?></textarea>
                    </div>
                    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                        <input type="text" name="dm_partner_advantages[<?php echo esc_attr($i); ?>][image]" value="<?php echo esc_attr($adv['image'] ?? ''); ?>" placeholder="URL image (optionnel)" class="regular-text dm-adv-img-input" style="width:350px;" />
                        <button type="button" class="button dm-adv-upload">Image</button>
                        <?php if (!empty($adv['image'])) : ?>
                            <img src="<?php echo esc_url($adv['image']); ?>" alt="" style="max-width:60px;max-height:60px;object-fit:cover;border-radius:4px;" />
                        <?php endif; ?>
                        <button type="button" class="button button-link-delete dm-adv-remove" style="margin-left:auto;">Supprimer</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <p>
                <button type="button" class="button button-secondary" id="dm-adv-add">+ Ajouter un avantage</button>
            </p>
            <?php submit_button('Enregistrer les avantages'); ?>
        </form>

        <script>
        jQuery(function($) {
            var advIndex = <?php echo max(array_keys($partner_advantages)) + 1; ?>;
            $('#dm-adv-add').on('click', function() {
                var html = '<div class="dm-adv-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin-bottom:12px;">' +
                    '<div style="margin-bottom:8px;">' +
                        '<input type="text" name="dm_partner_advantages[' + advIndex + '][title]" value="" placeholder="Titre de l\'avantage" class="regular-text" style="width:300px;" />' +
                    '</div>' +
                    '<div style="margin-bottom:8px;">' +
                        '<textarea name="dm_partner_advantages[' + advIndex + '][desc]" rows="2" placeholder="Description de l\'avantage" style="width:100%;max-width:600px;"></textarea>' +
                    '</div>' +
                    '<div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">' +
                        '<input type="text" name="dm_partner_advantages[' + advIndex + '][image]" value="" placeholder="URL image (optionnel)" class="regular-text dm-adv-img-input" style="width:350px;" />' +
                        '<button type="button" class="button dm-adv-upload">Image</button>' +
                        '<button type="button" class="button button-link-delete dm-adv-remove" style="margin-left:auto;">Supprimer</button>' +
                    '</div>' +
                '</div>';
                advIndex++;
                $('#dm-adv-repeater').append(html);
            });
            $('#dm-adv-repeater').on('click', '.dm-adv-remove', function() {
                $(this).closest('.dm-adv-row').remove();
            });
            $('#dm-adv-repeater').on('click', '.dm-adv-upload', function(e) {
                e.preventDefault();
                var $input = $(this).siblings('.dm-adv-img-input');
                var frame = wp.media({ title: 'Choisir une image', button: { text: 'Utiliser comme image' }, multiple: false });
                frame.on('select', function() {
                    $input.val(frame.state().get('selection').first().toJSON().url);
                });
                frame.open();
            });
        });
        </script>

        <?php endif; ?>
    </div>
    <?php
}
