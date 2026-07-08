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
    return (float) get_option('dm_contact_map_lat', '14.6928');
}

/**
 * Helper : longitude pour la carte (OpenStreetMap).
 */
function dm_get_contact_map_lng()
{
    return (float) get_option('dm_contact_map_lng', '-17.4467');
}

/**
 * Helper : zoom de la carte.
 */
function dm_get_contact_map_zoom()
{
    return (float) get_option('dm_contact_map_zoom', '14');
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
 * Liste des icônes de réseaux sociaux disponibles pour le select admin.
 */
function dm_social_icon_options()
{
    return array(
        'facebook'  => 'Facebook',
        'instagram' => 'Instagram',
        'youtube'   => 'YouTube',
        'tiktok'    => 'TikTok',
        'twitter'   => 'Twitter / X',
        'linkedin'  => 'LinkedIn',
        'whatsapp'  => 'WhatsApp',
        'telegram'  => 'Telegram',
        'pinterest' => 'Pinterest',
        'snapchat'  => 'Snapchat',
        'github'    => 'GitHub',
        'email'     => 'Email',
    );
}

/**
 * Retourne le SVG inline d'une icône de réseau social.
 * Toutes les icônes utilisent currentColor pour hériter de la couleur du parent.
 */
function dm_get_social_icon_svg($key)
{
    $icons = array(
        'facebook' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
        'instagram' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>',
        'youtube' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.42a2.78 2.78 0 0 0-1.94 2C1 8.18 1 12 1 12s0 3.82.46 5.58a2.78 2.78 0 0 0 1.94 2C5.12 20 12 20 12 20s6.88 0 8.6-.42a2.78 2.78 0 0 0 1.94-2C23 15.82 23 12 23 12s0-3.82-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>',
        'tiktok' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/></svg>',
        'twitter' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
        'linkedin' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>',
        'whatsapp' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>',
        'telegram' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.531 6.998-3.014 3.332-1.387 4.025-1.627 4.476-1.635z"/></svg>',
        'pinterest' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.648 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.608 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/></svg>',
        'snapchat' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.206.793c.99 0 4.347.276 5.93 3.821.529 1.193.403 3.219.299 4.847l-.003.06c-.012.18-.022.345-.03.51.075.045.203.09.401.09.3-.016.659-.12.976-.301.15-.09.301-.12.4-.12.149 0 .301.046.42.12.449.301.449.961-.046 1.26-.226.121-.6.271-1.05.331-.241.029-.481.045-.676.045-.181 0-.329-.012-.435-.029.029.225.075.48.135.75.165.811.405 1.531.706 2.131.811 1.621 2.386 2.491 3.332 2.871.165.075.271.226.271.391.029.449-.375.93-1.155 1.35-.301.166-.676.241-.93.301-.121.029-.226.075-.286.105-.045.18-.075.435-.105.69-.075.526-.165 1.171-.631 1.171-.181 0-.361-.045-.57-.075-.301-.046-.676-.105-1.155-.105-.271 0-.526.015-.78.075-.481.105-.9.405-1.38.75-.691.526-1.471 1.095-2.642 1.095-.046 0-.09-.015-.121-.015-.046 0-.075.015-.121.015-1.171 0-1.951-.555-2.642-1.095-.481-.345-.9-.646-1.38-.75-.255-.061-.526-.075-.78-.075-.481 0-.855.045-1.155.105-.211.029-.391.075-.57.075-.465 0-.555-.631-.631-1.171-.029-.255-.075-.51-.105-.69-.061-.045-.166-.075-.286-.105-.255-.075-.646-.135-.93-.301-.78-.42-1.185-.93-1.155-1.35 0-.165.105-.316.271-.391.946-.405 2.521-1.275 3.332-2.871.301-.6.541-1.32.706-2.131.06-.271.105-.525.135-.75-.105.015-.255.029-.435.029-.196 0-.435-.015-.676-.045-.451-.061-.826-.211-1.05-.331-.481-.301-.481-.961-.046-1.26.121-.075.271-.12.42-.12.099 0 .249.029.4.12.317.181.676.285.976.301.196 0 .324-.045.401-.09-.015-.165-.029-.33-.046-.51l-.003-.06c-.105-1.621-.226-3.647.299-4.847C7.858 1.069 11.215.793 12.206.793z"/></svg>',
        'github' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>',
        'email' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
    );
    return $icons[$key] ?? $icons['facebook'];
}

/**
 * Réseaux sociaux par défaut (4 réseaux).
 */
function dm_default_social_networks()
{
    return array(
        array('name' => 'Facebook',  'url' => 'https://facebook.com',  'icon' => 'facebook'),
        array('name' => 'Instagram', 'url' => 'https://instagram.com', 'icon' => 'instagram'),
        array('name' => 'YouTube',   'url' => 'https://youtube.com',   'icon' => 'youtube'),
        array('name' => 'TikTok',    'url' => 'https://tiktok.com',    'icon' => 'tiktok'),
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
            $icon = isset($item['icon']) ? sanitize_text_field($item['icon']) : 'facebook';
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
        array('slug' => 'dm-home-sections', 'title' => "Sections d'accueil", 'desc' => "Hero, Why Us, How It Works, Services, Stats, Témoignages, Partenaires, Tarifs", 'icon' => 'dashicons-admin-home'),
        array('slug' => 'dm-about', 'title' => 'À Propos', 'desc' => "Valeurs, chiffres clés, chronologie, équipe, hero & histoire", 'icon' => 'dashicons-info'),
        array('slug' => 'dm-services', 'title' => 'Nos Services', 'desc' => "Sections de services, image de bannière, features", 'icon' => 'dashicons-format-aside'),
        array('slug' => 'dm-contacts', 'title' => 'Coordonnées', 'desc' => "Téléphone, email, adresse, WhatsApp, réseaux sociaux, hero, horaires, carte", 'icon' => 'dashicons-location-alt'),
        array('slug' => 'dm-stores', 'title' => 'Points de Vente', 'desc' => "Distributeurs, hôtels, stations-service, transport — cartes avec image, type, zone", 'icon' => 'dashicons-store'),
        array('slug' => 'dm-promotions', 'title' => 'Promotions', 'desc' => "Créer et gérer les promotions, produits en promo, dates d'échéance, contenu détaillé", 'icon' => 'dashicons-megaphone'),
        array('slug' => 'dm-seo', 'title' => 'SEO', 'desc' => "Référencement : meta descriptions, Open Graph, Schema.org, robots, titres SEO par page", 'icon' => 'dashicons-search'),
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
            <p class="description">Ajoutez, modifiez ou supprimez les réseaux sociaux affichés dans le footer. Choisissez l'icône dans la liste déroulante.</p>
            <?php $icon_options = dm_social_icon_options(); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">Réseaux</th>
                    <td>
                        <div id="dm-social-repeater">
                            <?php foreach ($socials as $i => $social) : ?>
                            <div class="dm-social-row" style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px;">
                                <input type="text" name="dm_social_networks[<?php echo esc_attr($i); ?>][name]" value="<?php echo esc_attr($social['name']); ?>" placeholder="Nom (ex: Facebook)" class="regular-text" style="width:180px;" />
                                <input type="url" name="dm_social_networks[<?php echo esc_attr($i); ?>][url]" value="<?php echo esc_attr($social['url']); ?>" placeholder="Lien" class="regular-text" style="width:300px;" />
                                <select name="dm_social_networks[<?php echo esc_attr($i); ?>][icon]" class="dm-social-icon-select" style="width:180px;">
                                    <?php foreach ($icon_options as $val => $label) : ?>
                                        <option value="<?php echo esc_attr($val); ?>"<?php selected($social['icon'] ?? 'facebook', $val); ?>><?php echo esc_html($label); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="button button-link-delete dm-social-remove">Supprimer</button>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <p><button type="button" class="button button-secondary" id="dm-social-add">+ Ajouter un réseau</button></p>
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
            var socialIconsHtml = '<?php foreach ($icon_options as $val => $label) : ?><option value="<?php echo esc_attr($val); ?>"><?php echo esc_html($label); ?></option><?php endforeach; ?>';
            function createSocialRow() {
                var html = '<div class="dm-social-row" style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px;">' +
                    '<input type="text" name="dm_social_networks[' + rowIndex + '][name]" value="" placeholder="Nom (ex: Facebook)" class="regular-text" style="width:180px;" />' +
                    '<input type="url" name="dm_social_networks[' + rowIndex + '][url]" value="" placeholder="Lien" class="regular-text" style="width:300px;" />' +
                    '<select name="dm_social_networks[' + rowIndex + '][icon]" class="dm-social-icon-select" style="width:180px;">' + socialIconsHtml + '</select>' +
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
