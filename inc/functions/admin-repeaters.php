<?php
/**
 * Admin Repeaters — Sections de contenu dynamique via Settings API.
 *
 * Permet de gérer le contenu dynamique des sections de la page d'accueil
 * et des pages personnalisées depuis l'admin WordPress.
 *
 * Options gérées :
 *   - dm_hero_label, dm_hero_title, dm_hero_desc, dm_hero_image
 *   - dm_hero_stat_1v/1l, dm_hero_stat_2v/2l, dm_hero_stat_3v/3l
 *   - dm_why_us       (4 items: icon, title, desc)
 *   - dm_how_steps    (4 items: num, title, desc)
 *   - dm_services     (3 items: icon, title, desc, link)
 *   - dm_stats        (4 items: value, label) — non-duplicatable
 *   - dm_stats_images (4 URLs)
 *   - dm_testimonials (items: name, role, text, rating)
 *   - dm_partners     (array of names)
 *   - dm_snacks_menu  (items: name, price)
 *
 *   Section header string options (label, title, subtitle):
 *   - dm_why_label, dm_why_title, dm_why_subtitle
 *   - dm_how_label, dm_how_title, dm_how_subtitle
 *   - dm_services_label, dm_services_title, dm_services_subtitle
 *   - dm_stats_label, dm_stats_title, dm_stats_intro
 *   - dm_testi_label, dm_testi_title, dm_testi_subtitle
 *   - dm_partners_label, dm_partners_title
 */

if (! defined('ABSPATH')) {
    exit;
}

/* -------------------------------------------------------------------------- */
/* Default getter functions — shared with front-page.php                       */
/* -------------------------------------------------------------------------- */
function dm_get_default_why_us()
{
    return array(
        array('icon' => 'shield', 'title' => 'Qualité certifiée', 'desc' => 'Respect strict des normes HACCP et traçabilité complète de nos ingrédients.'),
        array('icon' => 'clock', 'title' => 'Fraîcheur quotidienne', 'desc' => 'Produits préparés chaque jour dans nos ateliers pour une fraîcheur incomparable.'),
        array('icon' => 'truck', 'title' => 'Livraison rapide', 'desc' => 'Livraison en 24-48h à Dakar, avec un service fiable et ponctuel.'),
        array('icon' => 'leaf', 'title' => 'Ingrédients naturels', 'desc' => 'Des recettes sans additifs, avec des ingrédients soigneusement sélectionnés.'),
    );
}

function dm_get_default_how_steps()
{
    return array(
        array('num' => '01', 'icon' => 'cart', 'title' => 'Commandez en ligne', 'desc' => 'Parcourez notre catalogue et ajoutez vos snacks préférés au panier en quelques clics.'),
        array('num' => '02', 'icon' => 'clipboard', 'title' => 'Choisissez le paiement', 'desc' => 'Payez facilement via Orange Money, Wave ou en espèces à la livraison.'),
        array('num' => '03', 'icon' => 'truck', 'title' => 'Livraison rapide', 'desc' => 'Recevez votre commande en 24-48h à Dakar, directement chez vous ou au bureau.'),
        array('num' => '04', 'icon' => 'coffee', 'title' => 'Régalez-vous !', 'desc' => 'Dégustez des snacks croustillants préparés avec des ingrédients frais et de qualité.'),
    );
}

function dm_get_default_services()
{
    return array(
        array('icon' => 'utensils', 'title' => 'Restauration Événementielle', 'desc' => 'Cocktails, mariages, séminaires — nous sublimons vos événements avec nos plateaux de snacks raffinés.', 'link' => '/services', 'image' => 'https://media.base44.com/images/public/6a335bc52ea23c09416a685d/d0f743324_generated_99b5c623.png'),
        array('icon' => 'building', 'title' => 'Gestion de Cantines', 'desc' => 'Un service B2B clé en main pour les entreprises qui souhaitent offrir une restauration de qualité.', 'link' => '/services', 'image' => 'https://media.base44.com/images/public/6a335bc52ea23c09416a685d/90a8e37e2_generated_e0fb4722.png'),
        array('icon' => 'package', 'title' => 'Produits Traiteur & Fumés', 'desc' => 'Poulet fumé, Kong fumé, Pigeon fumé — des saveurs authentiques prêtes à déguster.', 'link' => '/services', 'image' => 'https://media.base44.com/images/public/6a335bc52ea23c09416a685d/6b081792e_generated_e1eee356.png'),
    );
}

function dm_get_default_stats()
{
    return array(
        array('value' => '8+', 'label' => "Années d'expérience"),
        array('value' => '40+', 'label' => 'Employés passionnés'),
        array('value' => '10+', 'label' => 'Partenaires distributeurs'),
        array('value' => '1000+', 'label' => 'Clients satisfaits'),
    );
}

function dm_get_default_stats_images()
{
    return array(
        'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?w=400&h=500&fit=crop',
        'https://images.unsplash.com/photo-1556911220-bff31c812dba?w=400&h=400&fit=crop',
        'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=400&fit=crop',
        'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400&h=500&fit=crop',
    );
}

function dm_get_default_partners()
{
    return array(
        array('name' => 'Novotel', 'logo' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=200&h=200&fit=crop&crop=center'),
        array('name' => 'Carrefour', 'logo' => 'https://images.unsplash.com/photo-1534723452862-4c874018d66d?w=200&h=200&fit=crop&crop=center'),
        array('name' => 'Auchan', 'logo' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=200&h=200&fit=crop&crop=center'),
        array('name' => 'TotalEnergies', 'logo' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=200&h=200&fit=crop&crop=center'),
        array('name' => 'Shell', 'logo' => 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?w=200&h=200&fit=crop&crop=center'),
        array('name' => 'Pullman', 'logo' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=200&h=200&fit=crop&crop=center'),
        array('name' => 'Seter', 'logo' => 'https://images.unsplash.com/photo-1570125909232-eb263c4e96cb?w=200&h=200&fit=crop&crop=center'),
        array('name' => 'Terrou-Bi', 'logo' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=200&h=200&fit=crop&crop=center'),
        array('name' => "Sen'Eau", 'logo' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=200&h=200&fit=crop&crop=center'),
        array('name' => 'EDK', 'logo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=200&fit=crop&crop=center'),
    );
}

function dm_get_default_snacks_menu()
{
    return array(
        array('name' => 'Nems bœuf x10', 'price' => '2 250 Fr'),
        array('name' => 'Nems crevettes x10', 'price' => '2 500 Fr'),
        array('name' => 'Nems bourek x10', 'price' => '2 500 Fr'),
        array('name' => 'Beignets crevette x10', 'price' => '2 750 Fr'),
        array('name' => 'Pastels x10', 'price' => '2 000 Fr'),
        array('name' => 'Fataya x10', 'price' => '2 000 Fr'),
        array('name' => 'Samoussa x10', 'price' => '2 500 Fr'),
        array('name' => 'Pain chinois x10', 'price' => '2 500 Fr'),
        array('name' => 'Rissoles x10', 'price' => '2 500 Fr'),
        array('name' => 'Quiches x10', 'price' => '2 250 Fr'),
        array('name' => 'Pizza x10', 'price' => '2 250 Fr'),
        array('name' => 'Akkaras paquet 250g', 'price' => '1 500 Fr'),
        array('name' => 'Tacos x1', 'price' => '350 Fr'),
        array('name' => 'Beignets saucisses x1', 'price' => '350 Fr'),
        array('name' => 'Apéros salés plateau x50', 'price' => '15 000 Fr'),
    );
}

/* -------------------------------------------------------------------------- */
/* Enregistrement des settings                                                 */
/* -------------------------------------------------------------------------- */
add_action('admin_init', function () {
    // Hero fields are individual strings
    $hero_string_fields = array('dm_hero_label', 'dm_hero_title', 'dm_hero_desc', 'dm_hero_image', 'dm_hero_stat_1v', 'dm_hero_stat_1l', 'dm_hero_stat_2v', 'dm_hero_stat_2l', 'dm_hero_stat_3v', 'dm_hero_stat_3l');
    foreach ($hero_string_fields as $field) {
        register_setting('dm_hero', $field, array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        ));
    }

    // Section header string fields
    $section_header_fields = array(
        'dm_why'      => array('dm_why_label', 'dm_why_title', 'dm_why_subtitle'),
        'dm_how'      => array('dm_how_label', 'dm_how_title', 'dm_how_subtitle'),
        'dm_services' => array('dm_services_label', 'dm_services_title', 'dm_services_subtitle'),
        'dm_stats'    => array('dm_stats_label', 'dm_stats_title', 'dm_stats_intro'),
        'dm_testi'    => array('dm_testi_label', 'dm_testi_title', 'dm_testi_subtitle'),
        'dm_partners' => array('dm_partners_label', 'dm_partners_title'),
    );
    foreach ($section_header_fields as $group => $fields) {
        foreach ($fields as $field) {
            $is_title = (strpos($field, '_title') !== false);
            register_setting($group, $field, array(
                'type'              => 'string',
                'sanitize_callback' => $is_title ? 'wp_kses_post' : 'sanitize_text_field',
                'default'           => '',
            ));
        }
    }

    // Repeater fields are arrays
    $repeater_groups = array(
        'dm_why'      => array('dm_why_us'),
        'dm_how'      => array('dm_how_steps'),
        'dm_services' => array('dm_services'),
        'dm_stats'    => array('dm_stats', 'dm_stats_images'),
        'dm_testi'    => array('dm_testimonials'),
        'dm_partners' => array('dm_partners'),
        'dm_tarifs'   => array('dm_snacks_menu'),
    );
    foreach ($repeater_groups as $group => $fields) {
        foreach ($fields as $field) {
            register_setting($group, $field, array(
                'type'              => 'array',
                'sanitize_callback' => 'dm_sanitize_repeater',
                'default'           => array(),
            ));
        }
    }
});

function dm_sanitize_repeater($input)
{
    if (!is_array($input)) {
        return sanitize_text_field($input);
    }
    $home = home_url();
    foreach ($input as $k => $v) {
        if (is_array($v)) {
            foreach ($v as $kk => $vv) {
                $val = sanitize_text_field($vv);
                // Strip home URL prefix from link fields to keep relative paths
                if ($kk === 'link' && strpos($val, $home) === 0) {
                    $val = substr($val, strlen($home));
                    $val = '/' . ltrim($val, '/');
                }
                $input[$k][$kk] = $val;
            }
        } else {
            $input[$k] = sanitize_text_field($v);
        }
    }
    return $input;
}

/* -------------------------------------------------------------------------- */
/* Helper: Default testimonials + getter                                       */
/* -------------------------------------------------------------------------- */
function dm_get_default_testimonials()
{
    return array(
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

function dm_get_testimonials()
{
    $testimonials = get_option('dm_testimonials', array());
    if (!is_array($testimonials) || empty($testimonials)) {
        return dm_get_default_testimonials();
    }
    return $testimonials;
}

/* -------------------------------------------------------------------------- */
/* Sous-menu "Sections d'accueil"                                              */
/* -------------------------------------------------------------------------- */
add_action('admin_enqueue_scripts', function ($hook) {
    if (isset($_GET['page']) && $_GET['page'] === 'dm-home-sections') {
        wp_enqueue_media();
        wp_enqueue_script('jquery');
    }
});

add_action('admin_menu', function () {
    add_submenu_page(
        'dm-content',
        'Sections d\'accueil',
        'Sections d\'accueil',
        'manage_options',
        'dm-home-sections',
        'dm_home_sections_page_html'
    );
}, 20);

/* -------------------------------------------------------------------------- */
/* Page HTML — tabs : Hero, Why Us, How, Services, Stats, Testimonials, Partners */
/* -------------------------------------------------------------------------- */
function dm_home_sections_page_html()
{
    if (! current_user_can('manage_options')) {
        return;
    }
    $tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'hero';
    $tabs = array(
        'hero'         => 'Hero',
        'why'          => 'Pourquoi nous',
        'how'          => 'Comment ça marche',
        'services'     => 'Services',
        'stats'        => 'Notre impact',
        'testimonials' => 'Témoignages',
        'partners'     => 'Partenaires',
        'tarifs'       => 'Nos tarifs',
    );
    ?>
    <div class="wrap">
        <h1>Délices de la Mer — Sections d'accueil</h1>
        <h2 class="nav-tab-wrapper">
            <?php foreach ($tabs as $key => $label) : ?>
                <a href="?page=dm-home-sections&tab=<?php echo esc_attr($key); ?>" class="nav-tab<?php echo $tab === $key ? ' nav-tab-active' : ''; ?>"><?php echo esc_html($label); ?></a>
            <?php endforeach; ?>
        </h2>

        <form method="post" action="options.php">
            <?php
            switch ($tab) {
                case 'hero':
                    dm_render_hero_tab();
                    break;
                case 'why':
                    dm_render_repeater_tab('dm_why_us', 'dm_why', array(
                        array('name' => 'icon', 'label' => 'Icône', 'type' => 'select', 'options' => array('fish' => 'Poisson', 'flame' => 'Flamme', 'leaf' => 'Feuille', 'truck' => 'Camion', 'star' => 'Étoile', 'check' => 'Check', 'heart' => 'Cœur', 'clock' => 'Horloge', 'shield' => 'Bouclier', 'cart' => 'Panier', 'clipboard' => 'Presse-papier', 'coffee' => 'Café', 'utensils' => 'Couverts', 'building' => 'Bâtiment', 'package' => 'Colis', 'award' => 'Médaille', 'users' => 'Utilisateurs', 'store' => 'Magasin')),
                        array('name' => 'title', 'label' => 'Titre', 'type' => 'text'),
                        array('name' => 'desc', 'label' => 'Description', 'type' => 'textarea'),
                    ), 4, dm_get_default_why_us(), array(
                        'dm_why_label'    => 'Label (sur-titre)',
                        'dm_why_title'    => 'Titre principal',
                        'dm_why_subtitle' => 'Sous-titre',
                    ), array(
                        'dm_why_label'    => 'Pourquoi nous choisir',
                        'dm_why_title'    => "L'Excellence à chaque bouchée",
                        'dm_why_subtitle' => 'Ce qui fait de Délices de la Mer le choix préféré des Sénégalais pour leurs snacks et apéros.',
                    ));
                    break;
                case 'how':
                    dm_render_repeater_tab('dm_how_steps', 'dm_how', array(
                        array('name' => 'num', 'label' => 'Numéro', 'type' => 'text'),
                        array('name' => 'icon', 'label' => 'Icône', 'type' => 'select', 'options' => array('cart' => 'Panier', 'clipboard' => 'Presse-papier', 'truck' => 'Camion', 'coffee' => 'Café', 'fish' => 'Poisson', 'flame' => 'Flamme', 'leaf' => 'Feuille', 'star' => 'Étoile', 'check' => 'Check', 'heart' => 'Cœur', 'clock' => 'Horloge', 'shield' => 'Bouclier', 'utensils' => 'Couverts', 'building' => 'Bâtiment', 'package' => 'Colis')),
                        array('name' => 'title', 'label' => 'Titre', 'type' => 'text'),
                        array('name' => 'desc', 'label' => 'Description', 'type' => 'textarea'),
                    ), 4, dm_get_default_how_steps(), array(
                        'dm_how_label'    => 'Label (sur-titre)',
                        'dm_how_title'    => 'Titre principal',
                        'dm_how_subtitle' => 'Sous-titre',
                    ), array(
                        'dm_how_label'    => 'Simple & rapide',
                        'dm_how_title'    => 'Comment ça marche ?',
                        'dm_how_subtitle' => "Commander vos snacks préférés n'a jamais été aussi simple.",
                    ));
                    break;
                case 'services':
                    dm_render_repeater_tab('dm_services', 'dm_services', array(
                        array('name' => 'icon', 'label' => 'Icône', 'type' => 'select', 'options' => array('fish' => 'Poisson', 'flame' => 'Flamme', 'leaf' => 'Feuille', 'truck' => 'Camion', 'star' => 'Étoile', 'check' => 'Check', 'heart' => 'Cœur', 'clock' => 'Horloge', 'shield' => 'Bouclier', 'cart' => 'Panier', 'clipboard' => 'Presse-papier', 'coffee' => 'Café', 'utensils' => 'Couverts', 'building' => 'Bâtiment', 'package' => 'Colis')),
                        array('name' => 'title', 'label' => 'Titre', 'type' => 'text'),
                        array('name' => 'desc', 'label' => 'Description', 'type' => 'textarea'),
                        array('name' => 'link', 'label' => 'Lien (chemin relatif, ex: /services)', 'type' => 'text'),
                        array('name' => 'image', 'label' => 'Image (URL)', 'type' => 'text'),
                    ), 3, dm_get_default_services(), array(
                        'dm_services_label'    => 'Label (sur-titre)',
                        'dm_services_title'    => 'Titre principal',
                        'dm_services_subtitle' => 'Sous-titre',
                    ), array(
                        'dm_services_label'    => 'Ce que nous faisons',
                        'dm_services_title'    => 'Nos 3 Pôles de Service',
                        'dm_services_subtitle' => 'De l\'événementiel à la vente de produits, nous couvrons tous vos besoins en restauration de qualité.',
                    ));
                    break;
                case 'stats':
                    dm_render_stats_tab();
                    break;
                case 'testimonials':
                    dm_render_testimonials_tab();
                    break;
                case 'partners':
                    dm_render_partners_tab();
                    break;
                case 'tarifs':
                    dm_render_tarifs_tab();
                    break;
            }
            ?>
        </form>
    </div>
    <?php
}

/* -------------------------------------------------------------------------- */
/* Tab: Hero                                                                   */
/* -------------------------------------------------------------------------- */
function dm_render_hero_tab()
{
    settings_fields('dm_hero');
    $defaults = array(
        'dm_hero_label'   => 'Fabrication artisanale depuis 2016',
        'dm_hero_title'   => "Une symbiose\nde saveurs",
        'dm_hero_desc'    => "Snacks croustillants, beignets dorés et produits fumés d'exception. Une restauration saine et de qualité, du Sénégal à votre assiette.",
        'dm_hero_image'   => 'https://media.base44.com/images/public/6a335bc52ea23c09416a685d/eb6f541af_generated_08510815.png',
        'dm_hero_stat_1v' => '2016',
        'dm_hero_stat_1l' => 'Depuis',
        'dm_hero_stat_2v' => '40+',
        'dm_hero_stat_2l' => 'Employés',
        'dm_hero_stat_3v' => '10+',
        'dm_hero_stat_3l' => 'Partenaires',
    );
    $fields = array(
        'dm_hero_label'   => 'Label (sur-titre)',
        'dm_hero_title'   => 'Titre principal',
        'dm_hero_desc'    => 'Description',
        'dm_hero_image'   => 'URL Image de fond (vide = gradient navy)',
        'dm_hero_stat_1v' => 'Stat 1 — Valeur',
        'dm_hero_stat_1l' => 'Stat 1 — Label',
        'dm_hero_stat_2v' => 'Stat 2 — Valeur',
        'dm_hero_stat_2l' => 'Stat 2 — Label',
        'dm_hero_stat_3v' => 'Stat 3 — Valeur',
        'dm_hero_stat_3l' => 'Stat 3 — Label',
    );
    ?>
    <table class="form-table">
        <?php foreach ($fields as $key => $label) :
            $val = get_option($key, $defaults[$key] ?? '');
            $is_area = ($key === 'dm_hero_desc');
            $is_image = ($key === 'dm_hero_image');
        ?>
        <tr>
            <th scope="row"><label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label></th>
            <td>
                <?php if ($is_area) : ?>
                    <textarea name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" rows="3" class="large-text"><?php echo esc_textarea($val); ?></textarea>
                <?php elseif ($is_image) : ?>
                    <input type="text" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($val); ?>" class="regular-text dm-hero-image-input" placeholder="https://..." />
                    <button type="button" class="button dm-hero-image-upload">Choisir une image</button>
                    <?php if ($val) : ?>
                        <p><img src="<?php echo esc_url($val); ?>" alt="" style="max-width:200px;border-radius:8px;margin-top:8px;" /></p>
                    <?php endif; ?>
                <?php else : ?>
                    <input type="text" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($val); ?>" class="regular-text" />
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php submit_button('Enregistrer le Hero'); ?>
    <script>
    jQuery(function($) {
        $('.dm-hero-image-upload').on('click', function(e) {
            e.preventDefault();
            var $input = $(this).siblings('.dm-hero-image-input');
            var frame = wp.media({
                title: 'Choisir l\\'image de fond du Hero',
                button: { text: 'Utiliser comme image de fond' },
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
    <?php
}

/* -------------------------------------------------------------------------- */
/* Tab: Repeater générique                                                     */
/* -------------------------------------------------------------------------- */
function dm_render_repeater_tab($option_name, $group, $fields, $max_items, $defaults = array(), $header_fields = array(), $header_defaults = array())
{
    settings_fields($group);
    $items = get_option($option_name, array());
    // Use defaults if option is empty or not set
    if (empty($items) || !is_array($items)) {
        $items = $defaults;
    }
    // Pad to max_items
    while (count($items) < $max_items) {
        $items[] = array();
    }
    $items = array_slice($items, 0, $max_items);
    ?>
    <?php if (!empty($header_fields)) : ?>
    <h3>En-tête de section</h3>
    <table class="form-table">
        <?php foreach ($header_fields as $hkey => $hlabel) :
            $hdef = $header_defaults[$hkey] ?? '';
            $hval = get_option($hkey, $hdef);
        ?>
        <tr>
            <th scope="row"><label for="<?php echo esc_attr($hkey); ?>"><?php echo esc_html($hlabel); ?></label></th>
            <td>
                <input type="text" name="<?php echo esc_attr($hkey); ?>" id="<?php echo esc_attr($hkey); ?>" value="<?php echo esc_attr($hval); ?>" class="regular-text" />
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

    <h3>Items</h3>
    <table class="form-table">
        <?php foreach ($items as $i => $item) : ?>
        <tr>
            <th scope="row">Item <?php echo esc_html($i + 1); ?></th>
            <td>
                <?php foreach ($fields as $field) :
                    $val = $item[$field['name']] ?? '';
                    $is_image = ($field['name'] === 'image');
                ?>
                <p>
                    <label><strong><?php echo esc_html($field['label']); ?></strong></label><br />
                    <?php if ($field['type'] === 'textarea') : ?>
                        <textarea name="<?php echo esc_attr($option_name); ?>[<?php echo esc_attr($i); ?>][<?php echo esc_attr($field['name']); ?>]" rows="2" class="large-text"><?php echo esc_textarea($val); ?></textarea>
                    <?php elseif ($field['type'] === 'select') : ?>
                        <select name="<?php echo esc_attr($option_name); ?>[<?php echo esc_attr($i); ?>][<?php echo esc_attr($field['name']); ?>]">
                            <?php foreach ($field['options'] as $opt_val => $opt_label) : ?>
                                <option value="<?php echo esc_attr($opt_val); ?>"<?php selected($val, $opt_val); ?>><?php echo esc_html($opt_label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php elseif ($field['type'] === 'number') : ?>
                        <input type="number" min="1" max="5" name="<?php echo esc_attr($option_name); ?>[<?php echo esc_attr($i); ?>][<?php echo esc_attr($field['name']); ?>]" value="<?php echo esc_attr($val); ?>" class="small-text" />
                    <?php else : ?>
                        <input type="text" name="<?php echo esc_attr($option_name); ?>[<?php echo esc_attr($i); ?>][<?php echo esc_attr($field['name']); ?>]" value="<?php echo esc_attr($val); ?>" class="regular-text<?php echo $is_image ? ' dm-repeater-image-input' : ''; ?>" />
                        <?php if ($is_image) : ?>
                            <button type="button" class="button dm-repeater-image-upload">Choisir une image</button>
                            <?php if ($val) : ?>
                                <br><img src="<?php echo esc_url($val); ?>" alt="" style="max-width:120px;border-radius:6px;margin-top:6px;" />
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </p>
                <?php endforeach; ?>
                <hr style="margin:1rem 0;border:none;border-top:1px solid #e0e0e0;" />
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php submit_button('Enregistrer'); ?>
    <script>
    jQuery(function($) {
        $('.dm-repeater-image-upload').on('click', function(e) {
            e.preventDefault();
            var $input = $(this).siblings('.dm-repeater-image-input');
            var frame = wp.media({
                title: 'Choisir une image',
                button: { text: 'Utiliser cette image' },
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
    <?php
}

/* -------------------------------------------------------------------------- */
/* Tab: Stats (non-duplicatable — 4 stats + 4 images)                          */
/* -------------------------------------------------------------------------- */
function dm_render_stats_tab()
{
    settings_fields('dm_stats');
    $stats  = get_option('dm_stats', array());
    $images = get_option('dm_stats_images', array());
    // Use defaults if empty
    if (empty($stats) || !is_array($stats)) { $stats = dm_get_default_stats(); }
    if (empty($images) || !is_array($images)) { $images = dm_get_default_stats_images(); }
    while (count($stats) < 4) { $stats[] = array(); }
    while (count($images) < 4) { $images[] = ''; }
    $stats  = array_slice($stats, 0, 4);
    $images = array_slice($images, 0, 4);
    ?>
    <h3>En-tête de section</h3>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="dm_stats_label">Label (sur-titre)</label></th>
            <td><input type="text" name="dm_stats_label" id="dm_stats_label" value="<?php echo esc_attr(get_option('dm_stats_label', 'Notre impact')); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="dm_stats_title">Titre principal</label></th>
            <td><input type="text" name="dm_stats_title" id="dm_stats_title" value="<?php echo esc_attr(get_option('dm_stats_title', 'Une entreprise sénégalaise qui grandit avec vous')); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="dm_stats_intro">Texte d'introduction</label></th>
            <td><textarea name="dm_stats_intro" id="dm_stats_intro" rows="2" class="large-text"><?php echo esc_textarea(get_option('dm_stats_intro', 'Depuis 2016, nous accompagnons les familles sénégalaises, les hôtels de luxe et les grandes enseignes avec des produits de qualité. Notre croissance est le reflet de la confiance que vous nous accordez.')); ?></textarea></td>
        </tr>
    </table>

    <h3>Statistiques (4 fixes — non duplicables)</h3>
    <table class="form-table">
        <?php foreach ($stats as $i => $stat) : ?>
        <tr>
            <th scope="row">Stat <?php echo esc_html($i + 1); ?></th>
            <td>
                <p>
                    <input type="text" name="dm_stats[<?php echo esc_attr($i); ?>][value]" value="<?php echo esc_attr($stat['value'] ?? ''); ?>" placeholder="Valeur (ex: 2016)" class="regular-text" />
                </p>
                <p>
                    <input type="text" name="dm_stats[<?php echo esc_attr($i); ?>][label]" value="<?php echo esc_attr($stat['label'] ?? ''); ?>" placeholder="Label (ex: Année de création)" class="regular-text" />
                </p>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h3>Galerie (4 images)</h3>
    <table class="form-table">
        <?php foreach ($images as $i => $img) : ?>
        <tr>
            <th scope="row">Image <?php echo esc_html($i + 1); ?></th>
            <td>
                <input type="text" name="dm_stats_images[<?php echo esc_attr($i); ?>]" value="<?php echo esc_attr($img); ?>" placeholder="URL de l'image" class="regular-text dm-stats-image-input" />
                <button type="button" class="button dm-stats-image-upload">Choisir une image</button>
                <?php if ($img) : ?>
                    <br><img src="<?php echo esc_url($img); ?>" alt="" style="max-width:120px;border-radius:6px;margin-top:6px;" />
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php submit_button('Enregistrer les stats'); ?>
    <script>
    jQuery(function($) {
        $('.dm-stats-image-upload').on('click', function(e) {
            e.preventDefault();
            var $input = $(this).siblings('.dm-stats-image-input');
            var frame = wp.media({
                title: 'Choisir une image',
                button: { text: 'Utiliser cette image' },
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
    <?php
}

/* -------------------------------------------------------------------------- */
/* Tab: Testimonials (dynamic repeater with photo)                              */
/* -------------------------------------------------------------------------- */
function dm_render_testimonials_tab()
{
    settings_fields('dm_testi');
    $testimonials = dm_get_testimonials();
    if (!is_array($testimonials)) $testimonials = array();
    while (count($testimonials) < 2) { $testimonials[] = array('name' => '', 'role' => '', 'text' => '', 'rating' => '5', 'photo' => '', 'location' => '', 'date' => ''); }
    ?>
    <h3>En-tête de section</h3>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="dm_testi_label">Label (sur-titre)</label></th>
            <td><input type="text" name="dm_testi_label" id="dm_testi_label" value="<?php echo esc_attr(get_option('dm_testi_label', 'Témoignages')); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="dm_testi_title">Titre principal</label></th>
            <td><input type="text" name="dm_testi_title" id="dm_testi_title" value="<?php echo esc_attr(get_option('dm_testi_title', 'Ce que disent nos clients')); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="dm_testi_subtitle">Sous-titre</label></th>
            <td><input type="text" name="dm_testi_subtitle" id="dm_testi_subtitle" value="<?php echo esc_attr(get_option('dm_testi_subtitle', 'La satisfaction de nos clients est notre plus grande fierté.')); ?>" class="regular-text" /></td>
        </tr>
    </table>

    <p class="description">Ajoutez, modifiez ou supprimez les témoignages. Chaque témoignage peut inclure une photo de profil (URL).</p>
    <div id="dm-testimonials-repeater">
        <?php foreach ($testimonials as $i => $tm) : ?>
        <div class="dm-testimonial-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin-bottom:12px;">
            <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:8px;flex-wrap:wrap;">
                <input type="text" name="dm_testimonials[<?php echo esc_attr($i); ?>][name]" value="<?php echo esc_attr($tm['name'] ?? ''); ?>" placeholder="Nom" class="regular-text" style="width:180px;" />
                <input type="text" name="dm_testimonials[<?php echo esc_attr($i); ?>][role]" value="<?php echo esc_attr($tm['role'] ?? ''); ?>" placeholder="Rôle / Métier" class="regular-text" style="width:180px;" />
                <input type="text" name="dm_testimonials[<?php echo esc_attr($i); ?>][location]" value="<?php echo esc_attr($tm['location'] ?? ''); ?>" placeholder="Localisation (ex: Dakar)" class="regular-text" style="width:140px;" />
                <input type="number" min="1" max="5" name="dm_testimonials[<?php echo esc_attr($i); ?>][rating]" value="<?php echo esc_attr($tm['rating'] ?? '5'); ?>" placeholder="Note" class="small-text" />
                <input type="date" name="dm_testimonials[<?php echo esc_attr($i); ?>][date]" value="<?php echo esc_attr($tm['date'] ?? ''); ?>" placeholder="Date" style="width:140px;" />
            </div>
            <p>
                <textarea name="dm_testimonials[<?php echo esc_attr($i); ?>][text]" rows="3" class="large-text" placeholder="Texte du témoignage"><?php echo esc_textarea($tm['text'] ?? ''); ?></textarea>
            </p>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                <input type="text" name="dm_testimonials[<?php echo esc_attr($i); ?>][photo]" value="<?php echo esc_attr($tm['photo'] ?? ''); ?>" placeholder="URL de la photo de profil" class="regular-text dm-tm-photo-input" style="width:350px;" />
                <button type="button" class="button dm-tm-upload">Photo</button>
                <button type="button" class="button button-link-delete dm-tm-remove">Supprimer</button>
            </div>
            <?php if (!empty($tm['photo'])) : ?>
                <div style="margin-top:8px;"><img src="<?php echo esc_url($tm['photo']); ?>" alt="" style="max-width:80px;border-radius:50%;" /></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <p>
        <button type="button" class="button button-secondary" id="dm-tm-add">+ Ajouter un témoignage</button>
    </p>
    <?php submit_button('Enregistrer les témoignages'); ?>

    <script>
    jQuery(function($) {
        var tmIndex = <?php echo max(array_keys($testimonials)) + 1; ?>;

        function createRow() {
            var html = '<div class="dm-testimonial-row" style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:15px;margin-bottom:12px;">' +
                '<div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:8px;flex-wrap:wrap;">' +
                    '<input type="text" name="dm_testimonials[' + tmIndex + '][name]" value="" placeholder="Nom" class="regular-text" style="width:180px;" />' +
                    '<input type="text" name="dm_testimonials[' + tmIndex + '][role]" value="" placeholder="Rôle / Métier" class="regular-text" style="width:180px;" />' +
                    '<input type="text" name="dm_testimonials[' + tmIndex + '][location]" value="" placeholder="Localisation (ex: Dakar)" class="regular-text" style="width:140px;" />' +
                    '<input type="number" min="1" max="5" name="dm_testimonials[' + tmIndex + '][rating]" value="5" placeholder="Note" class="small-text" />' +
                    '<input type="date" name="dm_testimonials[' + tmIndex + '][date]" value="" placeholder="Date" style="width:140px;" />' +
                '</div>' +
                '<p><textarea name="dm_testimonials[' + tmIndex + '][text]" rows="3" class="large-text" placeholder="Texte du témoignage"></textarea></p>' +
                '<div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">' +
                    '<input type="text" name="dm_testimonials[' + tmIndex + '][photo]" value="" placeholder="URL de la photo de profil" class="regular-text dm-tm-photo-input" style="width:350px;" />' +
                    '<button type="button" class="button dm-tm-upload">Photo</button>' +
                    '<button type="button" class="button button-link-delete dm-tm-remove">Supprimer</button>' +
                '</div>' +
            '</div>';
            tmIndex++;
            return html;
        }

        $('#dm-tm-add').on('click', function() {
            $('#dm-testimonials-repeater').append(createRow());
        });

        $('#dm-testimonials-repeater').on('click', '.dm-tm-remove', function() {
            $(this).closest('.dm-testimonial-row').remove();
        });

        $('#dm-testimonials-repeater').on('click', '.dm-tm-upload', function(e) {
            e.preventDefault();
            var $input = $(this).siblings('.dm-tm-photo-input');
            var frame = wp.media({
                title: 'Choisir une photo',
                button: { text: 'Utiliser comme photo' },
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
    <?php
}

/* -------------------------------------------------------------------------- */
/* Tab: Partners                                                               */
/* -------------------------------------------------------------------------- */
function dm_render_partners_tab()
{
    settings_fields('dm_partners');
    $partners = get_option('dm_partners', array());
    // Use defaults if empty
    if (empty($partners) || !is_array($partners)) {
        $partners = dm_get_default_partners();
    }
    // Normalize to array format
    $normalized = array();
    foreach ($partners as $p) {
        if (is_array($p)) {
            $normalized[] = $p;
        } else {
            $normalized[] = array('name' => $p, 'logo' => '');
        }
    }
    // Ensure at least 2 slots
    while (count($normalized) < 2) { $normalized[] = array('name' => '', 'logo' => ''); }
    ?>
    <h3>En-tête de section</h3>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="dm_partners_label">Label (sur-titre)</label></th>
            <td><input type="text" name="dm_partners_label" id="dm_partners_label" value="<?php echo esc_attr(get_option('dm_partners_label', 'Ils nous font confiance')); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="dm_partners_title">Titre principal</label></th>
            <td><input type="text" name="dm_partners_title" id="dm_partners_title" value="<?php echo esc_attr(get_option('dm_partners_title', 'Retrouvez-nous chez nos partenaires')); ?>" class="regular-text" /></td>
        </tr>
    </table>

    <p class="description">Ajoutez, modifiez ou supprimez les partenaires. Cliquez sur « Ajouter » pour en ajouter un nouveau.</p>
    <div id="dm-partners-repeater">
        <?php foreach ($normalized as $i => $partner) :
            $p_name = $partner['name'] ?? '';
            $p_logo = $partner['logo'] ?? '';
        ?>
        <div class="dm-partner-row" style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px;flex-wrap:wrap;">
            <input type="text" name="dm_partners[<?php echo esc_attr($i); ?>][name]" value="<?php echo esc_attr($p_name); ?>" placeholder="Nom du partenaire" class="regular-text" style="width:200px;" />
            <input type="text" name="dm_partners[<?php echo esc_attr($i); ?>][logo]" value="<?php echo esc_attr($p_logo); ?>" placeholder="URL du logo" class="regular-text dm-partner-logo-input" style="width:300px;" />
            <button type="button" class="button dm-partner-upload">Logo</button>
            <button type="button" class="button button-link-delete dm-partner-remove">Supprimer</button>
            <?php if ($p_logo) : ?>
                <div style="width:100%;margin-top:4px;"><img src="<?php echo esc_url($p_logo); ?>" alt="" style="max-width:80px;border-radius:6px;" /></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <p>
        <button type="button" class="button button-secondary" id="dm-partner-add">+ Ajouter un partenaire</button>
    </p>
    <?php submit_button('Enregistrer les partenaires'); ?>

    <script>
    jQuery(function($) {
        var rowIndex = <?php echo max(array_keys($normalized)) + 1; ?>;

        function createRow() {
            var html = '<div class="dm-partner-row" style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px;flex-wrap:wrap;">' +
                '<input type="text" name="dm_partners[' + rowIndex + '][name]" value="" placeholder="Nom du partenaire" class="regular-text" style="width:200px;" />' +
                '<input type="text" name="dm_partners[' + rowIndex + '][logo]" value="" placeholder="URL du logo" class="regular-text dm-partner-logo-input" style="width:300px;" />' +
                '<button type="button" class="button dm-partner-upload">Logo</button>' +
                '<button type="button" class="button button-link-delete dm-partner-remove">Supprimer</button>' +
            '</div>';
            rowIndex++;
            return html;
        }

        $('#dm-partner-add').on('click', function() {
            $('#dm-partners-repeater').append(createRow());
        });

        $('#dm-partners-repeater').on('click', '.dm-partner-remove', function() {
            $(this).closest('.dm-partner-row').remove();
        });

        $('#dm-partners-repeater').on('click', '.dm-partner-upload', function(e) {
            e.preventDefault();
            var $input = $(this).siblings('.dm-partner-logo-input');
            var frame = wp.media({
                title: 'Choisir un logo',
                button: { text: 'Utiliser comme logo' },
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
    <?php
}

/* -------------------------------------------------------------------------- */
/* Tab: Tarifs (dynamic repeater for snack menu items)                         */
/* -------------------------------------------------------------------------- */
function dm_render_tarifs_tab()
{
    settings_fields('dm_tarifs');
    $items = get_option('dm_snacks_menu', array());
    if (!is_array($items) || empty($items)) {
        $items = dm_get_default_snacks_menu();
    }
    while (count($items) < 2) { $items[] = array('name' => '', 'price' => ''); }
    $total = count($items);
    $left_count = (int) ceil($total / 2);
    ?>
    <p class="description">Ajoutez, modifiez ou supprimez les items du menu. L'affichage est automatiquement équilibré gauche/droite (<?php echo $total; ?> items → <?php echo $left_count; ?> gauche, <?php echo $total - $left_count; ?> droite).</p>
    <div id="dm-tarifs-repeater">
        <?php foreach ($items as $i => $item) : ?>
        <div class="dm-tarif-row" style="display:flex;gap:8px;align-items:center;margin-bottom:10px;flex-wrap:wrap;">
            <input type="text" name="dm_snacks_menu[<?php echo esc_attr($i); ?>][name]" value="<?php echo esc_attr($item['name'] ?? ''); ?>" placeholder="Nom du produit" class="regular-text" style="width:300px;" />
            <input type="text" name="dm_snacks_menu[<?php echo esc_attr($i); ?>][price]" value="<?php echo esc_attr($item['price'] ?? ''); ?>" placeholder="Prix (ex: 2 500 Fr)" class="regular-text" style="width:180px;" />
            <button type="button" class="button button-link-delete dm-tarif-remove">Supprimer</button>
        </div>
        <?php endforeach; ?>
    </div>
    <p>
        <button type="button" class="button button-secondary" id="dm-tarif-add">+ Ajouter un item</button>
    </p>
    <?php submit_button('Enregistrer les tarifs'); ?>
    <script>
    jQuery(function($) {
        var tarifIndex = <?php echo max(array_keys($items)) + 1; ?>;

        $('#dm-tarif-add').on('click', function() {
            var html = '<div class="dm-tarif-row" style="display:flex;gap:8px;align-items:center;margin-bottom:10px;flex-wrap:wrap;">' +
                '<input type="text" name="dm_snacks_menu[' + tarifIndex + '][name]" value="" placeholder="Nom du produit" class="regular-text" style="width:300px;" />' +
                '<input type="text" name="dm_snacks_menu[' + tarifIndex + '][price]" value="" placeholder="Prix (ex: 2 500 Fr)" class="regular-text" style="width:180px;" />' +
                '<button type="button" class="button button-link-delete dm-tarif-remove">Supprimer</button>' +
            '</div>';
            tarifIndex++;
            $('#dm-tarifs-repeater').append(html);
        });

        $('#dm-tarifs-repeater').on('click', '.dm-tarif-remove', function() {
            $(this).closest('.dm-tarif-row').remove();
        });
    });
    </script>
    <?php
}
