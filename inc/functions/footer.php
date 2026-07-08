<?php
/**
 * Footer custom — remplace le footer Astra.
 *
 * Inclut : marque + réseaux sociaux (dynamiques), liens navigation, liens informations,
 * coordonnées de contact (dynamiques via helpers), bas de page.
 * Adapté du prototype Delices de la Mer (Footer.jsx).
 */

if (! defined('ABSPATH')) {
    exit;
}

function dm_custom_footer()
{
    $home = home_url('/');

    $logo_path = DM_THEME_DIR . '/assets/images/logo/logo1.png';
    $logo_uri  = DM_THEME_URI . '/assets/images/logo/logo1.png';
    $has_logo  = file_exists($logo_path);
?>
    <footer class="dm-footer">
        <!-- Wave divider top — reproduit du prototype Footer.jsx -->
        <div class="dm-footer-wave">
            <svg viewBox="0 0 1200 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,40 C300,80 600,0 900,40 C1050,60 1150,30 1200,40 L1200,0 L0,0 Z" fill="var(--cream)"></path>
            </svg>
        </div>

        <div class="dm-footer-inner">
            <div class="dm-footer-grid">
                <!-- Marque -->
                <div>
                    <a href="<?php echo esc_url($home); ?>" class="dm-footer-brand-logo">
                        <?php if ($has_logo) : ?>
                            <img src="<?php echo esc_url($logo_uri); ?>" alt="Délices de la Mer" class="dm-footer-brand-img" />
                        <?php else : ?>
                            <span class="dm-logo-fallback">DM</span>
                        <?php endif; ?>
                        <span>
                            <span class="dm-footer-brand-title" style="display:block;">Délices de la Mer</span>
                            <span class="dm-footer-brand-sub" style="display:block;">Une symbiose de saveurs</span>
                        </span>
                    </a>
                    <p class="dm-footer-brand-text">
                        Snacks croustillants, beignets dorés et produits fumés d'exception. Depuis 2016, nous fabriquons chaque jour des produits qui enchantent les palais avec des ingrédients frais et des recettes authentiques.
                    </p>
                    <div class="dm-footer-socials">
                        <?php foreach (dm_get_social_networks() as $social) :
                            $icon = $social['icon'];
                            $is_svg = (strpos($icon, '<svg') !== false);
                        ?>
                            <a href="<?php echo esc_url($social['url']); ?>" target="_blank" rel="noreferrer" class="dm-footer-social" aria-label="<?php echo esc_attr($social['name']); ?>">
                                <?php if ($is_svg) : ?>
                                    <?php echo $icon; // phpcs:ignore ?>
                                <?php else : ?>
                                    <?php echo dm_get_social_icon_svg($icon); // phpcs:ignore ?>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Navigation -->
                <div>
                    <h4 class="dm-footer-col-title">Navigation</h4>
                    <ul class="dm-footer-links">
                        <?php
                        $footer_nav = array(
                            array('Accueil', '/'),
                            array('Catalogue', '/shop'),
                            array('Services', '/services'),
                            array('Points de Vente', '/points-de-vente'),
                            array('À Propos', '/a-propos'),
                        );
                        foreach ($footer_nav as $item) : ?>
                            <li>
                                <a href="<?php echo esc_url(home_url($item[1])); ?>" class="dm-footer-link">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                    <?php echo esc_html($item[0]); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Informations -->
                <div>
                    <h4 class="dm-footer-col-title">Informations</h4>
                    <ul class="dm-footer-links">
                        <?php
                        $footer_info = array(
                            array('Témoignages', '/temoignages'),
                            array('Galerie', '/galerie'),
                            array('Suivi de commande', '/my-account/orders'),
                            array('Espace Client', '/my-account'),
                            array('Contact', '/contact'),
                        );
                        foreach ($footer_info as $item) : ?>
                            <li>
                                <a href="<?php echo esc_url(home_url($item[1])); ?>" class="dm-footer-link">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                    <?php echo esc_html($item[0]); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="dm-footer-col-title">Contact</h4>
                    <ul class="dm-footer-contact">
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <span><?php echo dm_get_address_html(); ?></span>
                        </li>
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            <a href="tel:<?php echo esc_attr(dm_get_phone_tel()); ?>"><?php echo esc_html(dm_get_phone()); ?></a>
                        </li>
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <a href="mailto:<?php echo esc_attr(dm_get_email()); ?>"><?php echo esc_html(dm_get_email()); ?></a>
                        </li>
                    </ul>
                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="dm-footer-wa">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
                        </svg>
                        <span>
                            <span class="dm-footer-wa-label" style="display:block;">Commandez sur</span>
                            <span class="dm-footer-wa-value" style="display:block;">WhatsApp</span>
                        </span>
                    </a>
                </div>
            </div>

            <!-- Bas de page -->
            <div class="dm-footer-bottom">
                <p class="dm-footer-copy">
                    © <?php echo esc_html(date('Y')); ?> Délices de la Mer. Tous droits réservés.
                </p>
            </div>
        </div>
    </footer>
<?php
}
