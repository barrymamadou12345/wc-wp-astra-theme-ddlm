<?php
/**
 * Enqueue des styles — modulaire + conditionnel.
 *
 * Styles globaux (toutes les pages) : base, header, footer
 * Styles conditionnels : home, page-banner, pages personnalisées, WooCommerce
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue des styles : modulaire + conditionnel.
 */
function dm_enqueue_styles()
{
    // Police Montserrat (unique pour toute l'application)
    wp_enqueue_style(
        'dm-google-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap',
        array(),
        null
    );

    // style.css du thème enfant (requis par WordPress, contient l'en-tête du thème).
    wp_enqueue_style(
        'dm-style',
        DM_THEME_URI . '/style.css',
        array(),
        dm_asset_ver('style.css')
    );

    // --- Styles globaux (toutes les pages) -------------------------------
    wp_enqueue_style('dm-base', DM_THEME_URI . '/assets/css/base.css', array('dm-style'), dm_asset_ver('assets/css/base.css'));
    wp_enqueue_style('dm-header', DM_THEME_URI . '/assets/css/header.css', array('dm-base'), dm_asset_ver('assets/css/header.css'));
    wp_enqueue_style('dm-footer', DM_THEME_URI . '/assets/css/footer.css', array('dm-base'), dm_asset_ver('assets/css/footer.css'));

    // --- Page d'accueil --------------------------------------------------
    if (is_front_page()) {
        wp_enqueue_style('dm-home', DM_THEME_URI . '/assets/css/home.css', array('dm-base'), dm_asset_ver('assets/css/home.css'));
        wp_enqueue_style('dm-promotions', DM_THEME_URI . '/assets/css/promotions.css', array('dm-base'), dm_asset_ver('assets/css/promotions.css'));
        wp_enqueue_script('dm-promotions', DM_THEME_URI . '/assets/js/promotions.js', array(), dm_asset_ver('assets/js/promotions.js'), true);
    }

    // --- Bannière d'en-tête (toutes les pages sauf l'accueil) ------------
    if (!is_front_page()) {
        wp_enqueue_style('dm-page-banner', DM_THEME_URI . '/assets/css/page-banner.css', array('dm-base'), dm_asset_ver('assets/css/page-banner.css'));
    }

    // --- Page Services ---------------------------------------------------
    if (is_page_template('pages/page-services.php') || is_page('services')) {
        wp_enqueue_style('dm-page-services', DM_THEME_URI . '/assets/css/page-services.css', array('dm-base'), dm_asset_ver('assets/css/page-services.css'));
    }

    // --- Page Points de Vente --------------------------------------------
    if (is_page_template('pages/page-stores.php') || is_page('points-de-vente')) {
        wp_enqueue_style('dm-page-stores', DM_THEME_URI . '/assets/css/page-stores.css', array('dm-base'), dm_asset_ver('assets/css/page-stores.css'));
    }

    // --- Page À Propos ---------------------------------------------------
    if (is_page_template('pages/page-about.php') || is_page('a-propos')) {
        wp_enqueue_style('dm-page-about', DM_THEME_URI . '/assets/css/page-about.css', array('dm-base'), dm_asset_ver('assets/css/page-about.css'));
    }

    // --- Page Témoignages ------------------------------------------------
    if (is_page_template('pages/page-testimonials.php') || is_page('temoignages')) {
        wp_enqueue_style('dm-page-testimonials', DM_THEME_URI . '/assets/css/page-testimonials.css', array('dm-base'), dm_asset_ver('assets/css/page-testimonials.css'));
    }

    // --- Page Contact ----------------------------------------------------
    if (is_page_template('pages/page-contact.php') || is_page('contact')) {
        wp_enqueue_style('dm-page-contact', DM_THEME_URI . '/assets/css/page-contact.css', array('dm-base'), dm_asset_ver('assets/css/page-contact.css'));
    }

    // --- Page Galerie ----------------------------------------------------
    if (is_page_template('pages/page-gallery.php') || is_page('galerie')) {
        wp_enqueue_style('dm-gallery', DM_THEME_URI . '/assets/css/gallery.css', array('dm-base'), dm_asset_ver('assets/css/gallery.css'));
        wp_enqueue_script('dm-gallery', DM_THEME_URI . '/assets/js/gallery.js', array(), dm_asset_ver('assets/js/gallery.js'), true);
    }

    // --- Page Promotions -------------------------------------------------
    if (is_page_template('pages/page-promotions.php') || is_page('promotions')) {
        wp_enqueue_style('dm-promotions-page', DM_THEME_URI . '/assets/css/promotions.css', array('dm-base'), dm_asset_ver('assets/css/promotions.css'));
        wp_enqueue_script('dm-promotions-page', DM_THEME_URI . '/assets/js/promotions.js', array(), dm_asset_ver('assets/js/promotions.js'), true);

        // WooCommerce add-to-cart scripts for AJAX add to cart on promo page
        if (class_exists('WooCommerce')) {
            wp_enqueue_script('wc-add-to-cart');
            wp_enqueue_script('woocommerce');
            wp_enqueue_script('wc-cart-fragments');
        }
    }

    // --- WooCommerce : design system modulaire (voir dm_enqueue_woocommerce_styles) ---
}
add_action('wp_enqueue_scripts', 'dm_enqueue_styles', 20);

/**
 * Design system WooCommerce — feuilles de style modulaires (habillage visuel uniquement).
 */
function dm_enqueue_woocommerce_styles()
{
    if (! class_exists('WooCommerce')) {
        return;
    }

    $uri = DM_THEME_URI . '/assets/css/woocommerce/';
    $rel = 'assets/css/woocommerce/';

    $wc_blocks_dep = class_exists('WooCommerce') ? array('wc-blocks-style') : array();
    $wc_cart_dep   = class_exists('WooCommerce') ? array('wc-blocks-style', 'wc-blocks-style-cart') : array();

    // --- Globales (tout le site) -----------------------------------------
    wp_enqueue_style('dm-wc-base', $uri . 'wc-base.css', array_merge(array('dm-base'), $wc_blocks_dep), dm_asset_ver($rel . 'wc-base.css'));
    wp_enqueue_style('dm-wc-buttons', $uri . 'wc-buttons.css', array('dm-wc-base'), dm_asset_ver($rel . 'wc-buttons.css'));
    wp_enqueue_style('dm-wc-product-card', $uri . 'wc-product-card.css', array('dm-wc-base'), dm_asset_ver($rel . 'wc-product-card.css'));

    // --- Conditionnelles -------------------------------------------------
    if (dm_is_woocommerce_page()) {
        wp_enqueue_style('dm-wc-badges', $uri . 'wc-badges.css', array('dm-wc-product-card'), dm_asset_ver($rel . 'wc-badges.css'));
    }

    // --- Barre de filtres du Catalogue (boutique + catégories) -----------
    if ((function_exists('is_shop') && is_shop()) || (function_exists('is_product_category') && is_product_category()) || (function_exists('is_product_tag') && is_product_tag()) || is_search()) {
        wp_enqueue_style('dm-wc-shop-filters', $uri . 'wc-shop-filters.css', array('dm-wc-product-card'), dm_asset_ver($rel . 'wc-shop-filters.css'));
    }

    if (function_exists('is_product') && is_product()) {
        wp_enqueue_style('dm-wc-single-product', $uri . 'wc-single-product.css', array('dm-wc-base'), dm_asset_ver($rel . 'wc-single-product.css'));
        wp_enqueue_style('dm-wc-reviews', $uri . 'wc-reviews.css', array('dm-wc-single-product'), dm_asset_ver($rel . 'wc-reviews.css'));
    }

    if (function_exists('is_cart') && is_cart()) {
        wp_enqueue_style('dm-wc-cart', $uri . 'wc-cart.css', array_merge(array('dm-wc-product-card'), $wc_cart_dep), dm_asset_ver($rel . 'wc-cart.css'));
        wp_enqueue_style('dm-wc-order-method', $uri . 'wc-order-method.css', array('dm-wc-cart'), dm_asset_ver($rel . 'wc-order-method.css'));
    }

    if (function_exists('is_checkout') && is_checkout()) {
        wp_enqueue_style('dm-wc-checkout', $uri . 'wc-checkout.css', array('dm-wc-base'), dm_asset_ver($rel . 'wc-checkout.css'));

        $chk_uri = $uri . 'checkout-style/';
        $chk_rel = $rel . 'checkout-style/';
        $chk_dep = array('dm-wc-checkout', 'wc-blocks-style');

        $chk_files = array(
            'dm-wc-checkout-form'     => 'checkout-form.css',
            'dm-wc-order-received'    => 'order-received.css',
        );
        foreach ($chk_files as $handle => $file) {
            wp_enqueue_style($handle, $chk_uri . $file, $chk_dep, dm_asset_ver($chk_rel . $file));
            $chk_dep[] = $handle;
        }
        wp_enqueue_style('dm-wc-order-method-checkout', $uri . 'wc-order-method.css', array('dm-wc-checkout'), dm_asset_ver($rel . 'wc-order-method.css'));
    }

    if (function_exists('is_account_page') && is_account_page()) {
        $acc_uri = $uri . 'account-style/';
        $acc_rel = $rel . 'account-style/';
        $acc_dep = array('dm-wc-base');

        $acc_files = array(
            'dm-wc-account-layout'    => 'account-layout.css',
            'dm-wc-account-sidebar'   => 'account-sidebar.css',
            'dm-wc-account-dashboard' => 'account-dashboard.css',
            'dm-wc-account-orders'    => 'account-orders.css',
            'dm-wc-account-forms'     => 'account-forms.css',
            'dm-wc-account-addresses' => 'account-addresses.css',
        );
        foreach ($acc_files as $handle => $file) {
            wp_enqueue_style($handle, $acc_uri . $file, $acc_dep, dm_asset_ver($acc_rel . $file));
            $acc_dep[] = $handle;
        }
    }
}
add_action('wp_enqueue_scripts', 'dm_enqueue_woocommerce_styles', 99);

/**
 * Détecte une page WooCommerce (boutique, catégorie, produit, panier, commande, compte).
 */
function dm_is_woocommerce_page()
{
    if (! class_exists('WooCommerce')) {
        return false;
    }
    return (function_exists('is_woocommerce') && is_woocommerce())
        || (function_exists('is_shop') && is_shop())
        || (function_exists('is_product_taxonomy') && is_product_taxonomy())
        || (function_exists('is_cart') && is_cart())
        || (function_exists('is_checkout') && is_checkout())
        || (function_exists('is_account_page') && is_account_page());
}
