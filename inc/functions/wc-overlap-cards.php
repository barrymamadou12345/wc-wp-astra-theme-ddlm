<?php
/**
 * Cartes flottantes qui chevauchent la bannière sur les pages WooCommerce.
 *
 * Affiche une carte blanche avec border-left orange, icône, titre,
 * description et lien d'action, qui chevauche le banner de 3rem.
 *
 * Pages concernées :
 * - Checkout (pas order-received) → carte "sécurité"
 * - Order received → carte "merci"
 * - Account pages → carte "bienvenue"
 */

if (! defined('ABSPATH')) {
    exit;
}

add_action('astra_header_after', 'dm_wc_overlap_card', 6);
function dm_wc_overlap_card()
{
    if (! class_exists('WooCommerce')) {
        return;
    }

    // Checkout (pas order-received)
    if (function_exists('is_checkout') && is_checkout() && ! is_wc_endpoint_url('order-received')) {
        $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart');

        // Données par défaut de la carte overlap checkout
        $card_data = array(
            'title' => __('Finalisez votre commande en toute sécurité', 'astra-delices-de-la-mer'),
            'desc'  => __('Vos informations sont protégées. Remplissez le formulaire et choisissez votre mode de paiement.', 'astra-delices-de-la-mer'),
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
        );

        // Permettre à wc-order-method.php de modifier le contenu
        $card_data = apply_filters('dm_wc_overlap_card_checkout', $card_data);
        ?>
        <div class="dm-wc-overlap-card">
            <div class="dm-wc-overlap-card__inner">
                <div class="dm-wc-overlap-card__icon">
                    <?php echo $card_data['icon']; // phpcs:ignore ?>
                </div>
                <div class="dm-wc-overlap-card__body">
                    <h3 class="dm-wc-overlap-card__title"><?php echo esc_html($card_data['title']); ?></h3>
                    <p class="dm-wc-overlap-card__desc"><?php echo esc_html($card_data['desc']); ?></p>
                    <a href="<?php echo esc_url($cart_url); ?>" class="dm-wc-overlap-card__link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"/>
                            <polyline points="12 19 5 12 12 5"/>
                        </svg>
                        <?php esc_html_e('Retour au panier', 'astra-delices-de-la-mer'); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
        return;
    }

    // Order received (thank you page)
    if (function_exists('is_checkout') && is_checkout() && is_wc_endpoint_url('order-received')) {
        $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop');
        ?>
        <div class="dm-wc-overlap-card dm-wc-overlap-card--success">
            <div class="dm-wc-overlap-card__inner">
                <div class="dm-wc-overlap-card__icon dm-wc-overlap-card__icon--success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <div class="dm-wc-overlap-card__body">
                    <h3 class="dm-wc-overlap-card__title"><?php esc_html_e('Merci pour votre commande !', 'astra-delices-de-la-mer'); ?></h3>
                    <p class="dm-wc-overlap-card__desc"><?php esc_html_e('Votre commande a bien été reçue. Vous recevrez une confirmation par email avec les détails.', 'astra-delices-de-la-mer'); ?></p>
                    <a href="<?php echo esc_url($shop_url); ?>" class="dm-wc-overlap-card__link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                        <?php esc_html_e('Continuer mes achats', 'astra-delices-de-la-mer'); ?>
                    </a>
                </div>
                <a href="<?php echo esc_url($shop_url); ?>" class="dm-wc-overlap-card__cta">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    <?php esc_html_e('Faire une commande', 'astra-delices-de-la-mer'); ?>
                </a>
            </div>
        </div>
        <?php
        return;
    }

    // Account pages
    if (function_exists('is_account_page') && is_account_page() && is_user_logged_in()) {
        $user         = wp_get_current_user();
        $first_name   = $user->first_name;
        $display_name = $user->display_name;
        $greet_name   = ! empty($first_name) ? $first_name : $display_name;
        $logout_url   = function_exists('wc_get_account_endpoint_url') ? wc_get_account_endpoint_url('customer-logout') : wp_logout_url();
        ?>
        <div class="dm-wc-overlap-card">
            <div class="dm-wc-overlap-card__inner">
                <div class="dm-wc-overlap-card__avatar">
                    <?php echo esc_html(strtoupper(substr($greet_name, 0, 1))); ?>
                </div>
                <div class="dm-wc-overlap-card__body">
                    <h3 class="dm-wc-overlap-card__title"><?php echo esc_html(sprintf(__('Bonjour, %s', 'astra-delices-de-la-mer'), $greet_name)); ?></h3>
                    <p class="dm-wc-overlap-card__desc"><?php esc_html_e('Bienvenue dans votre espace client. Gérez vos commandes, vos adresses et vos informations personnelles.', 'astra-delices-de-la-mer'); ?></p>
                    <a href="<?php echo esc_url($logout_url); ?>" class="dm-wc-overlap-card__link dm-wc-overlap-card__link--logout">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        <?php esc_html_e('Déconnexion', 'astra-delices-de-la-mer'); ?>
                    </a>
                </div>
                <?php
                $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop');
                ?>
                <a href="<?php echo esc_url($shop_url); ?>" class="dm-wc-overlap-card__cta">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    <?php esc_html_e('Faire une commande', 'astra-delices-de-la-mer'); ?>
                </a>
            </div>
        </div>
        <?php
        return;
    }
}
