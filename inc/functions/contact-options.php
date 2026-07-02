<?php
/**
 * Options de contact centralisées — WhatsApp, téléphone, email, réseaux sociaux.
 *
 * Page admin "Coordonnées" sous le menu "Délices Content".
 * Helpers disponibles globalement :
 *   dm_get_whatsapp_number()  — numéro WA sans + ni espaces (ex: 221775630223)
 *   dm_get_phone()            — téléphone affichage (ex: +221 77 563 02 23)
 *   dm_get_phone_tel()        — téléphone pour tel: (sans espaces)
 *   dm_get_email()            — email de contact
 *   dm_wa_link($message)      — URL wa.me complète avec message optionnel
 *   dm_get_social_networks()  — tableau des réseaux sociaux (name, url, icon)
 *   dm_get_address()          — adresse postale
 *   dm_get_address_html()     — adresse postale formatée HTML
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Helper : numéro WhatsApp au format international sans + ni espaces.
 */
function dm_get_whatsapp_number()
{
    return get_option('dm_whatsapp_number', '221775630223');
}

/**
 * Helper : numéro de téléphone au format international avec +.
 */
function dm_get_phone()
{
    return get_option('dm_phone', '+221 77 563 02 23');
}

/**
 * Helper : numéro de téléphone au format tel: (sans espaces, avec +).
 */
function dm_get_phone_tel()
{
    $phone = dm_get_phone();
    return preg_replace('/\s+/', '', $phone);
}

/**
 * Helper : adresse email de contact.
 */
function dm_get_email()
{
    return get_option('dm_email', 'contact@delicesdelamer.com');
}

/**
 * Helper : adresse postale de contact.
 */
function dm_get_address()
{
    return get_option('dm_address', 'Dakar, Sénégal');
}

/**
 * Helper : adresse postale formatée pour affichage HTML (retours à la ligne).
 */
function dm_get_address_html()
{
    $addr = dm_get_address();
    $parts = array_map('trim', explode(',', $addr));
    if (count($parts) >= 2) {
        return esc_html($parts[0]) . '<br />' . esc_html(implode(', ', array_slice($parts, 1)));
    }
    return esc_html($addr);
}

/**
 * Helper : image de fond du hero Contact.
 * Retourne l'image personnalisée si définie, sinon une image par défaut depuis le web.
 */
function dm_get_contact_hero_image()
{
    $custom = get_option('dm_contact_hero_image', '');
    if (!empty($custom)) {
        return $custom;
    }
    if (function_exists('dm_get_banner_image')) {
        return dm_get_banner_image();
    }
    return 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=1920&q=80';
}

/**
 * Helper : latitude pour la carte (OpenStreetMap).
 */
function dm_get_contact_map_lat()
{
    return get_option('dm_contact_map_lat', '14.6928');
}

/**
 * Helper : longitude pour la carte (OpenStreetMap).
 */
function dm_get_contact_map_lng()
{
    return get_option('dm_contact_map_lng', '-17.4467');
}

/**
 * Helper : zoom de la carte.
 */
function dm_get_contact_map_zoom()
{
    return get_option('dm_contact_map_zoom', '14');
}

/**
 * Helper : horaires d'ouverture (tableau dynamique).
 */
function dm_get_contact_hours()
{
    $hours = get_option('dm_contact_hours', null);
    if ($hours === null) {
        $hours = array(
            array('day' => 'Lundi - Vendredi', 'hours' => '08h00 - 18h00', 'closed' => '0'),
            array('day' => 'Samedi', 'hours' => '09h00 - 15h00', 'closed' => '0'),
            array('day' => 'Dimanche', 'hours' => 'Fermé', 'closed' => '1'),
        );
        update_option('dm_contact_hours', $hours);
    }
    return is_array($hours) ? $hours : array();
}

/**
 * Sanitize horaires.
 */
function dm_sanitize_contact_hours($input)
{
    $clean = array();
    if (!is_array($input)) return $clean;
    foreach ($input as $row) {
        if (!is_array($row)) continue;
        $item = array();
        $item['day']    = sanitize_text_field($row['day'] ?? '');
        $item['hours']  = sanitize_text_field($row['hours'] ?? '');
        $item['closed'] = isset($row['closed']) ? '1' : '0';
        if ($item['day'] !== '') $clean[] = $item;
    }
    return $clean;
}

/**
 * Helper : lien wa.me préformaté avec message optionnel.
 */
function dm_wa_link($message = '')
{
    $url = 'https://wa.me/' . dm_get_whatsapp_number();
    if ($message) {
        $url .= '?text=' . urlencode($message);
    }
    return $url;
}

/**
 * Réseaux sociaux par défaut (4 réseaux).
 */
function dm_default_social_networks()
{
    return array(
        array(
            'name' => 'Facebook',
            'url'  => 'https://facebook.com',
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
        ),
        array(
            'name' => 'Instagram',
            'url'  => 'https://instagram.com',
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>',
        ),
        array(
            'name' => 'YouTube',
            'url'  => 'https://youtube.com',
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.42a2.78 2.78 0 0 0-1.94 2C1 8.18 1 12 1 12s0 3.82.46 5.58a2.78 2.78 0 0 0 1.94 2C5.12 20 12 20 12 20s6.88 0 8.6-.42a2.78 2.78 0 0 0 1.94-2C23 15.82 23 12 23 12s0-3.82-.46-5.58z"></path><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"></polygon></svg>',
        ),
        array(
            'name' => 'TikTok',
            'url'  => 'https://tiktok.com',
            'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"></path></svg>',
        ),
    );
}

/**
 * Helper : récupère les réseaux sociaux depuis la base, avec auto-population.
 */
function dm_get_social_networks()
{
    $networks = get_option('dm_social_networks', array());
    if (empty($networks)) {
        $networks = dm_default_social_networks();
        update_option('dm_social_networks', $networks);
    }
    return $networks;
}

/**
 * Auto-populer les réseaux sociaux par défaut au chargement si l'option n'existe pas.
 */
add_action('init', function () {
    if (get_option('dm_social_networks', null) === null) {
        update_option('dm_social_networks', dm_default_social_networks());
    }
});

/**
 * Sanitize callback pour les réseaux sociaux (tableau de tableaux).
 */
function dm_sanitize_social_networks($input)
{
    $clean = array();
    if (is_array($input)) {
        foreach ($input as $item) {
            $name = isset($item['name']) ? sanitize_text_field($item['name']) : '';
            $url  = isset($item['url']) ? esc_url_raw($item['url']) : '';
            $icon = isset($item['icon']) ? $item['icon'] : '';
            if ($name && $url) {
                $clean[] = array(
                    'name' => $name,
                    'url'  => $url,
                    'icon' => $icon,
                );
            }
        }
    }
    return $clean;
}

/**
 * Enregistrer les settings.
 */
add_action('admin_init', function () {
    register_setting('dm_options_group', 'dm_whatsapp_number', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '221775630223',
    ));
    register_setting('dm_options_group', 'dm_phone', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '+221 77 563 02 23',
    ));
    register_setting('dm_options_group', 'dm_email', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_email',
        'default' => 'contact@delicesdelamer.com',
    ));
    register_setting('dm_options_group', 'dm_social_networks', array(
        'type' => 'array',
        'sanitize_callback' => 'dm_sanitize_social_networks',
        'default' => array(),
    ));
    register_setting('dm_options_group', 'dm_address', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => 'Dakar, Sénégal',
    ));
    register_setting('dm_options_group', 'dm_contact_hero_image', array(
        'type' => 'string',
        'sanitize_callback' => 'esc_url_raw',
        'default' => '',
    ));
    register_setting('dm_options_group', 'dm_contact_map_lat', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '14.6928',
    ));
    register_setting('dm_options_group', 'dm_contact_map_lng', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '-17.4467',
    ));
    register_setting('dm_options_group', 'dm_contact_map_zoom', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '14',
    ));
    register_setting('dm_options_group', 'dm_contact_hours', array(
        'type' => 'array',
        'sanitize_callback' => 'dm_sanitize_contact_hours',
        'default' => array(),
    ));
});

/**
 * Enqueue les scripts media uploader sur la page Coordonnées.
 */
add_action('admin_enqueue_scripts', function ($hook) {
    if (isset($_GET['page']) && $_GET['page'] === 'dm-contacts') {
        wp_enqueue_media();
        wp_enqueue_script('jquery');
    }
});

/**
 * Menu principal "Délices Content" + sous-menu "Coordonnées".
 */
add_action('admin_menu', function () {
    add_menu_page(
        'Délices Content',
        'Délices Content',
        'manage_options',
        'dm-content',
        'dm_content_dashboard_html',
        'dashicons-food',
        26
    );

    add_submenu_page(
        'dm-content',
        'Coordonnées de contact',
        'Coordonnées',
        'manage_options',
        'dm-contacts',
        'dm_contacts_page_html'
    );
}, 10);

/**
 * Page dashboard "Délices Content" — liens vers les sous-pages.
 */
function dm_content_dashboard_html()
{
    if (!current_user_can('manage_options')) return;
    $pages = array(
        array('slug' => 'dm-home-sections', 'title' => "Sections d'accueil", 'desc' => "Hero, Why Us, How It Works, Services, Stats, Témoignages, Partenaires", 'icon' => 'dashicons-admin-home'),
        array('slug' => 'dm-contacts', 'title' => 'Coordonnées', 'desc' => "Téléphone, email, adresse, WhatsApp, réseaux sociaux, hero, horaires, carte", 'icon' => 'dashicons-location-alt'),
        array('slug' => 'dm-stores', 'title' => 'Points de Vente', 'desc' => "Distributeurs, hôtels, stations-service, transport — cartes avec image, type, zone", 'icon' => 'dashicons-store'),
        array('slug' => 'dm-promotions', 'title' => 'Promotions', 'desc' => "Créer et gérer les promotions, produits en promo, dates d'échéance, contenu détaillé", 'icon' => 'dashicons-megaphone'),
    );
    ?>
    <div class="wrap">
        <h1>Délices Content</h1>
        <p>Gérez le contenu dynamique de votre site depuis les sous-pages ci-dessous.</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;margin-top:20px;">
            <?php foreach ($pages as $p) : ?>
                <a href="<?php echo esc_url(admin_url('admin.php?page=' . $p['slug'])); ?>" style="display:block;padding:20px;background:#fff;border:1px solid #e0e0e0;border-radius:8px;text-decoration:none;color:inherit;transition:box-shadow 0.2s;">
                    <span class="dashicons <?php echo esc_attr($p['icon']); ?>" style="font-size:32px;width:32px;height:32px;color:#ff6b00;margin-bottom:10px;display:block;"></span>
                    <h3 style="margin:0 0 5px;font-size:16px;"><?php echo esc_html($p['title']); ?></h3>
                    <p style="margin:0;color:#666;font-size:13px;"><?php echo esc_html($p['desc']); ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * HTML de la page d'options — avec onglets.
 */
function dm_contacts_page_html()
{
    if (! current_user_can('manage_options')) {
        return;
    }

    $socials  = dm_get_social_networks();
    $hours    = dm_get_contact_hours();
    $hero_img = get_option('dm_contact_hero_image', '');
    $tabs     = array('coordonnees', 'hero', 'horaires', 'carte');
    $active   = isset($_GET['tab']) && in_array($_GET['tab'], $tabs, true) ? $_GET['tab'] : 'coordonnees';
    ?>
    <div class="wrap">
        <h1>Délices de la Mer — Coordonnées & Contact</h1>
        <p>Gérez les coordonnées, l'image du hero, les horaires et la carte de la page Contact.</p>

        <h2 class="nav-tab-wrapper">
            <a href="<?php echo esc_url(admin_url('admin.php?page=dm-contacts&tab=coordonnees')); ?>" class="nav-tab<?php echo $active === 'coordonnees' ? ' nav-tab-active' : ''; ?>">Coordonnées</a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=dm-contacts&tab=hero')); ?>" class="nav-tab<?php echo $active === 'hero' ? ' nav-tab-active' : ''; ?>">Hero Contact</a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=dm-contacts&tab=horaires')); ?>" class="nav-tab<?php echo $active === 'horaires' ? ' nav-tab-active' : ''; ?>">Horaires</a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=dm-contacts&tab=carte')); ?>" class="nav-tab<?php echo $active === 'carte' ? ' nav-tab-active' : ''; ?>">Carte</a>
        </h2>

        <?php if ($active === 'coordonnees') : ?>
        <!-- TAB: Coordonnées + Réseaux sociaux -->
        <form method="post" action="options.php">
            <?php settings_fields('dm_options_group'); ?>

            <h2 class="title">Coordonnées de contact</h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="dm_whatsapp_number">Numéro WhatsApp</label></th>
                    <td>
                        <input type="text" id="dm_whatsapp_number" name="dm_whatsapp_number"
                               value="<?php echo esc_attr(get_option('dm_whatsapp_number', '221775630223')); ?>"
                               class="regular-text" placeholder="221775630223" />
                        <p class="description">Format international sans <code>+</code> ni espaces (ex: <code>221775630223</code>). Utilisé pour les liens <code>wa.me</code>.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="dm_phone">Téléphone (affichage)</label></th>
                    <td>
                        <input type="text" id="dm_phone" name="dm_phone"
                               value="<?php echo esc_attr(get_option('dm_phone', '+221 77 563 02 23')); ?>"
                               class="regular-text" placeholder="+221 77 563 02 23" />
                        <p class="description">Format affiché sur le site.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="dm_email">Email de contact</label></th>
                    <td>
                        <input type="email" id="dm_email" name="dm_email"
                               value="<?php echo esc_attr(get_option('dm_email', 'contact@delicesdelamer.com')); ?>"
                               class="regular-text" placeholder="contact@delicesdelamer.com" />
                        <p class="description">Adresse email utilisée dans le footer et la page contact.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="dm_address">Adresse postale</label></th>
                    <td>
                        <input type="text" id="dm_address" name="dm_address"
                               value="<?php echo esc_attr(get_option('dm_address', 'Dakar, Sénégal')); ?>"
                               class="regular-text" placeholder="Dakar, Sénégal" />
                        <p class="description">Adresse affichée dans le footer et la page contact.</p>
                    </td>
                </tr>
            </table>

            <h2 class="title">Réseaux sociaux</h2>
            <p class="description">Ajoutez, modifiez ou supprimez les réseaux sociaux affichés dans le footer.</p>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">Réseaux</th>
                    <td>
                        <div id="dm-social-repeater">
                            <?php foreach ($socials as $i => $social) : ?>
                            <div class="dm-social-row" style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px;">
                                <input type="text" name="dm_social_networks[<?php echo esc_attr($i); ?>][name]" value="<?php echo esc_attr($social['name']); ?>" placeholder="Nom (ex: Facebook)" class="regular-text" style="width:180px;" />
                                <input type="url" name="dm_social_networks[<?php echo esc_attr($i); ?>][url]" value="<?php echo esc_attr($social['url']); ?>" placeholder="Lien" class="regular-text" style="width:300px;" />
                                <input type="text" name="dm_social_networks[<?php echo esc_attr($i); ?>][icon]" value="<?php echo esc_attr($social['icon']); ?>" placeholder="URL icône ou code SVG" class="regular-text dm-social-icon-input" style="width:300px;" />
                                <button type="button" class="button dm-social-upload">Icône</button>
                                <button type="button" class="button button-link-delete dm-social-remove">Supprimer</button>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <p><button type="button" class="button button-secondary" id="dm-social-add">+ Ajouter un réseau</button></p>
                        <p class="description">L'icône peut être une URL d'image (bouton « Icône ») ou du code SVG.</p>
                    </td>
                </tr>
            </table>

            <?php submit_button('Enregistrer les coordonnées'); ?>
        </form>
        <?php endif; ?>

        <?php if ($active === 'hero') : ?>
        <!-- TAB: Hero Contact -->
        <form method="post" action="options.php">
            <?php settings_fields('dm_options_group'); ?>

            <h2 class="title">Image du Hero Contact</h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="dm_contact_hero_image">Image de fond</label></th>
                    <td>
                        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:10px;">
                            <input type="text" id="dm_contact_hero_image" name="dm_contact_hero_image"
                                   value="<?php echo esc_attr($hero_img); ?>"
                                   placeholder="URL de l'image (laisser vide pour image par défaut)"
                                   class="regular-text dm-contact-hero-input" style="width:400px;" />
                            <button type="button" class="button dm-contact-hero-upload">Choisir une image</button>
                        </div>
                        <div class="dm-contact-hero-preview" style="margin-top:10px;">
                            <?php $preview_url = !empty($hero_img) ? $hero_img : dm_get_contact_hero_image(); ?>
                            <img src="<?php echo esc_url($preview_url); ?>" alt="" style="max-width:320px;max-height:140px;object-fit:cover;border-radius:6px;border:1px solid #ddd;" />
                        </div>
                        <p class="description">Image affichée en haut de la page Contact. Si vous laissez vide, une image par défaut depuis le web est utilisée.</p>
                    </td>
                </tr>
            </table>

            <?php submit_button('Enregistrer l\'image'); ?>
        </form>
        <?php endif; ?>

        <?php if ($active === 'horaires') : ?>
        <!-- TAB: Horaires -->
        <form method="post" action="options.php">
            <?php settings_fields('dm_options_group'); ?>

            <h2 class="title">Horaires d'ouverture</h2>
            <p class="description">Horaires affichés sur la page Contact.</p>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">Horaires</th>
                    <td>
                        <div id="dm-hours-repeater">
                            <?php foreach ($hours as $i => $h) : ?>
                            <div class="dm-hours-row" style="display:flex;gap:8px;align-items:center;margin-bottom:10px;flex-wrap:wrap;">
                                <input type="text" name="dm_contact_hours[<?php echo esc_attr($i); ?>][day]" value="<?php echo esc_attr($h['day']); ?>" placeholder="Jour (ex: Lundi - Vendredi)" class="regular-text" style="width:250px;" />
                                <input type="text" name="dm_contact_hours[<?php echo esc_attr($i); ?>][hours]" value="<?php echo esc_attr($h['hours']); ?>" placeholder="Horaires (ex: 08h00 - 18h00)" class="regular-text" style="width:200px;" />
                                <label style="display:flex;align-items:center;gap:4px;font-size:13px;">
                                    <input type="checkbox" name="dm_contact_hours[<?php echo esc_attr($i); ?>][closed]" value="1"<?php echo ($h['closed'] ?? '0') === '1' ? ' checked' : ''; ?> /> Fermé
                                </label>
                                <button type="button" class="button button-link-delete dm-hours-remove">Supprimer</button>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <p><button type="button" class="button button-secondary" id="dm-hours-add">+ Ajouter un horaire</button></p>
                    </td>
                </tr>
            </table>

            <?php submit_button('Enregistrer les horaires'); ?>
        </form>
        <?php endif; ?>

        <?php if ($active === 'carte') : ?>
        <!-- TAB: Carte -->
        <form method="post" action="options.php">
            <?php settings_fields('dm_options_group'); ?>

            <h2 class="title">Carte de localisation</h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="dm_contact_map_lat">Latitude</label></th>
                    <td>
                        <input type="text" id="dm_contact_map_lat" name="dm_contact_map_lat"
                               value="<?php echo esc_attr(dm_get_contact_map_lat()); ?>"
                               class="regular-text" placeholder="14.6928" style="width:200px;" />
                        <p class="description">Coordonnée latitude (ex: <code>14.6928</code> pour Dakar).</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="dm_contact_map_lng">Longitude</label></th>
                    <td>
                        <input type="text" id="dm_contact_map_lng" name="dm_contact_map_lng"
                               value="<?php echo esc_attr(dm_get_contact_map_lng()); ?>"
                               class="regular-text" placeholder="-17.4467" style="width:200px;" />
                        <p class="description">Coordonnée longitude (ex: <code>-17.4467</code> pour Dakar).</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="dm_contact_map_zoom">Zoom</label></th>
                    <td>
                        <input type="number" id="dm_contact_map_zoom" name="dm_contact_map_zoom"
                               value="<?php echo esc_attr(dm_get_contact_map_zoom()); ?>"
                               class="regular-text" placeholder="14" style="width:100px;" min="1" max="19" />
                        <p class="description">Niveau de zoom (1 = monde, 19 = bâtiment).</p>
                    </td>
                </tr>
            </table>

            <?php submit_button('Enregistrer la carte'); ?>
        </form>
        <?php endif; ?>

        <script>
        jQuery(function($) {
            // ---- Réseaux sociaux ----
            var rowIndex = <?php echo count($socials); ?>;
            function createSocialRow() {
                var html = '<div class="dm-social-row" style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px;">' +
                    '<input type="text" name="dm_social_networks[' + rowIndex + '][name]" value="" placeholder="Nom (ex: Facebook)" class="regular-text" style="width:180px;" />' +
                    '<input type="url" name="dm_social_networks[' + rowIndex + '][url]" value="" placeholder="Lien" class="regular-text" style="width:300px;" />' +
                    '<input type="text" name="dm_social_networks[' + rowIndex + '][icon]" value="" placeholder="URL icône ou code SVG" class="regular-text dm-social-icon-input" style="width:300px;" />' +
                    '<button type="button" class="button dm-social-upload">Icône</button>' +
                    '<button type="button" class="button button-link-delete dm-social-remove">Supprimer</button>' +
                '</div>';
                rowIndex++;
                return html;
            }
            $('#dm-social-add').on('click', function() {
                $('#dm-social-repeater').append(createSocialRow());
            });
            $('#dm-social-repeater').on('click', '.dm-social-remove', function() {
                $(this).closest('.dm-social-row').remove();
            });
            $('#dm-social-repeater').on('click', '.dm-social-upload', function(e) {
                e.preventDefault();
                var $input = $(this).siblings('.dm-social-icon-input');
                var frame = wp.media({
                    title: 'Choisir une icône',
                    button: { text: 'Utiliser comme icône' },
                    multiple: false
                });
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $input.val(attachment.url);
                });
                frame.open();
            });

            // ---- Horaires ----
            var hoursIdx = <?php echo max(array_keys($hours)) + 1; ?>;
            $('#dm-hours-add').on('click', function() {
                var html = '<div class="dm-hours-row" style="display:flex;gap:8px;align-items:center;margin-bottom:10px;flex-wrap:wrap;">' +
                    '<input type="text" name="dm_contact_hours[' + hoursIdx + '][day]" value="" placeholder="Jour (ex: Lundi - Vendredi)" class="regular-text" style="width:250px;" />' +
                    '<input type="text" name="dm_contact_hours[' + hoursIdx + '][hours]" value="" placeholder="Horaires (ex: 08h00 - 18h00)" class="regular-text" style="width:200px;" />' +
                    '<label style="display:flex;align-items:center;gap:4px;font-size:13px;"><input type="checkbox" name="dm_contact_hours[' + hoursIdx + '][closed]" value="1" /> Fermé</label>' +
                    '<button type="button" class="button button-link-delete dm-hours-remove">Supprimer</button>' +
                '</div>';
                hoursIdx++;
                $('#dm-hours-repeater').append(html);
            });
            $('#dm-hours-repeater').on('click', '.dm-hours-remove', function() {
                $(this).closest('.dm-hours-row').remove();
            });

            // ---- Hero image uploader ----
            $('.dm-contact-hero-upload').on('click', function(e) {
                e.preventDefault();
                var $input = $('.dm-contact-hero-input');
                var $preview = $('.dm-contact-hero-preview img');
                var frame = wp.media({
                    title: "Choisir l'image du Hero Contact",
                    button: { text: 'Utiliser cette image' },
                    multiple: false
                });
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $input.val(attachment.url);
                    if ($preview.length) {
                        $preview.attr('src', attachment.url);
                    } else {
                        $('.dm-contact-hero-preview').html('<img src="' + attachment.url + '" alt="" style="max-width:320px;max-height:140px;object-fit:cover;border-radius:6px;border:1px solid #ddd;" />');
                    }
                });
                frame.open();
            });
        });
        </script>
    </div>
    <?php
}

/* -------------------------------------------------------------------------- */
/* Traitement du formulaire de contact (admin-post.php)                        */
/* -------------------------------------------------------------------------- */

add_action('admin_post_dm_contact_form', 'dm_handle_contact_form');
add_action('admin_post_nopriv_dm_contact_form', 'dm_handle_contact_form');

function dm_handle_contact_form()
{
    if (!wp_verify_nonce($_POST['dm_contact_nonce'] ?? '', 'dm_contact_form')) {
        wp_die('Erreur de sécurité. Veuillez réessayer.');
    }

    $name    = sanitize_text_field($_POST['dm_name'] ?? '');
    $email   = sanitize_email($_POST['dm_email'] ?? '');
    $phone   = sanitize_text_field($_POST['dm_phone'] ?? '');
    $subject = sanitize_text_field($_POST['dm_subject'] ?? '');
    $message = sanitize_textarea_field($_POST['dm_message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        wp_safe_redirect(add_query_arg('contact', 'error', wp_get_referer()));
        exit;
    }

    // Sauvegarder le message en base
    $messages = get_option('dm_contact_messages', array());
    $messages[] = array(
        'name'    => $name,
        'email'   => $email,
        'phone'   => $phone,
        'subject' => $subject,
        'message' => $message,
        'date'    => current_time('mysql'),
        'ip'      => $_SERVER['REMOTE_ADDR'] ?? '',
    );
    // Garder uniquement les 50 derniers messages
    if (count($messages) > 50) {
        $messages = array_slice($messages, -50);
    }
    update_option('dm_contact_messages', $messages);

    // Envoyer un email HTML à l'admin
    $to       = dm_get_email();
    $subj     = !empty($subject) ? $subject : 'Nouveau message depuis le site';
    $date_str = current_time('mysql');
    $ip_str   = $_SERVER['REMOTE_ADDR'] ?? '';

    // Générer le body HTML via le template
    $body_html = dm_build_contact_email_html($name, $email, $phone, $subject, $message, $date_str, $ip_str);

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    );

    wp_mail($to, $subj, $body_html, $headers);

    // Rediriger avec succès
    wp_safe_redirect(add_query_arg('contact', 'success', wp_get_referer()));
    exit;
}

/* -------------------------------------------------------------------------- */
/* Helpers pour les emails HTML                                                */
/* -------------------------------------------------------------------------- */

/**
 * Génère le HTML complet du mail de contact en utilisant les templates.
 *
 * @param string $name    Nom de l'expéditeur
 * @param string $email   Email de l'expéditeur
 * @param string $phone   Téléphone (peut être vide)
 * @param string $subject Objet du message
 * @param string $message Contenu du message
 * @param string $date    Date/heure
 * @param string $ip      Adresse IP
 * @return string HTML complet du mail
 */
function dm_build_contact_email_html($name, $email, $phone, $subject, $message, $date, $ip)
{
    // Variables pour le template de contenu
    $vars = compact('name', 'email', 'phone', 'subject', 'message', 'date', 'ip');

    // Capturer le contenu spécifique (email-contact.php)
    ob_start();
    extract($vars);
    include DM_THEME_DIR . '/inc/emails/email-contact.php';
    $body_html = ob_get_clean();

    // Variables communes pour le layout (email-template.php)
    $title     = 'Nouveau message de contact';
    $logo_url  = home_url('/wp-content/themes/astra-delices-de-la-mer/assets/images/logo/logo1.png');
    $site_name = 'Les Délices de la Mer';
    $phone_admin = dm_get_phone();
    $email_admin = dm_get_email();
    $address     = dm_get_address();
    $socials     = dm_get_social_networks();

    ob_start();
    include DM_THEME_DIR . '/inc/emails/email-template.php';
    return ob_get_clean();
}
