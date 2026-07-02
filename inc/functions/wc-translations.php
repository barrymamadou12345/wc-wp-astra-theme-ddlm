<?php
/**
 * Traductions WooCommerce — personnalisation des textes standards.
 *
 * 1. Filtre gettext pour les chaînes PHP WooCommerce
 * 2. Filtre the_title pour traduire les titres de pages WC
 * 3. Filtre astra_breadcrumb_trail pour remplacer "Shop" → "Catalogue"
 * 4. Hook officiel WC pour le titre des cross-sells
 * 5. Script JS pour les blocs WC (React) — textes rendus côté client
 */

if (! defined('ABSPATH')) {
    exit;
}

// 1. Filtre gettext pour les chaînes PHP WooCommerce
add_filter('gettext', 'dm_translate_woocommerce_strings', 20, 3);
function dm_translate_woocommerce_strings($translated, $text, $domain)
{
    if ($domain !== 'woocommerce') {
        return $translated;
    }

    $translations = array(
        // Navigation & pages
        'Shop'                       => 'Catalogue',
        'Return to shop'             => 'Retour au catalogue',
        'Browse products'            => 'Voir le catalogue',
        'Read more'                  => 'Voir le produit',
        'Cart'                       => 'Panier',
        'Checkout'                   => 'Commander',
        'My account'                 => 'Mon compte',

        // Boutons & actions
        'Add to cart'                => 'Ajouter au panier',
        'Add to basket'              => 'Ajouter au panier',
        'View cart'                  => 'Voir le panier',
        'View Cart'                  => 'Voir le panier',
        'Proceed to checkout'        => 'Passer la commande',
        'Place order'                => 'Valider la commande',
        'Update cart'                => 'Mettre à jour le panier',
        'Apply coupon'               => 'Appliquer le code',
        'Continue shopping'          => 'Continuer mes achats',
        'Select options'             => 'Choisir les options',

        // Produit
        'Description'                => 'Description',
        'Additional information'     => 'Informations complémentaires',
        'Reviews'                    => 'Avis',
        'Related products'           => 'Produits liés',
        'You may also like…'         => 'Vous pourriez aussi aimer…',
        'You may be interested in…'  => 'Vous pourriez aussi aimer…',

        // Panier
        'Cart totals'                => 'Totaux du panier',
        'Subtotal'                   => 'Sous-total',
        'Total'                      => 'Total',
        'Shipping'                   => 'Livraison',
        'Calculate shipping'         => 'Calculer la livraison',
        'Update shipping'            => 'Mettre à jour la livraison',
        'Coupon code'                => 'Code promo',
        'Coupon:'                    => 'Code promo :',
        'Remove this item'           => 'Retirer cet article',

        // Checkout
        'Billing details'            => 'Coordonnées de facturation',
        'Shipping details'           => 'Adresse de livraison',
        'Your order'                 => 'Votre commande',
        'Place order'                => 'Valider la commande',
        'Create an account?'         => 'Créer un compte ?',
        'Ship to a different address?' => 'Livrer à une adresse différente ?',
        'Order notes'                => 'Notes de commande',

        // Compte
        'Login'                      => 'Connexion',
        'Register'                   => 'Inscription',
        'Logout'                     => 'Déconnexion',
        'Dashboard'                  => 'Tableau de bord',
        'Orders'                     => 'Commandes',
        'Downloads'                  => 'Téléchargements',
        'Addresses'                  => 'Adresses',
        'Account details'            => 'Détails du compte',
        'Edit account'               => 'Modifier le compte',
        'Save changes'               => 'Enregistrer les modifications',

        // Messages
        'Your cart is currently empty.' => 'Votre panier est actuellement vide.',
        'No products were found matching your selection.' => 'Aucun produit ne correspond à votre recherche.',
        'No products were found matching your selection' => 'Aucun produit ne correspond à votre recherche',
        'Nothing found' => 'Aucun résultat trouvé',
        'Product'                    => 'Produit',
        'Price'                      => 'Prix',
        'Quantity'                   => 'Quantité',
        'Subtotal'                   => 'Sous-total',

        // Stock
        'In stock'                   => 'En stock',
        'Out of stock'               => 'Rupture de stock',
        'Available on backorder'     => 'Disponible en précommande',

        // Tri boutique
        'Default sorting'            => 'Tri par défaut',
        'Sort by popularity'         => 'Trier par popularité',
        'Sort by average rating'     => 'Trier par note moyenne',
        'Sort by latest'             => 'Trier par nouveauté',
        'Sort by price: low to high' => 'Trier par prix croissant',
        'Sort by price: high to low' => 'Trier par prix décroissant',
        'Showing all'                => 'Affichage de tous',
        'results'                    => 'résultats',
    );

    if (isset($translations[$text])) {
        return $translations[$text];
    }

    return $translated;
}

// 1b. Traduire les titres de pages WC "Cart" → "Panier", "Shop" → "Catalogue"
add_filter('the_title', 'dm_translate_wc_page_titles', 10, 2);
function dm_translate_wc_page_titles($title, $id)
{
    if (function_exists('is_cart') && is_cart() && $id === get_queried_object_id()) {
        return 'Panier';
    }
    if (function_exists('is_shop') && is_shop() && $id === get_queried_object_id()) {
        return 'Catalogue';
    }
    if (function_exists('is_checkout') && is_checkout() && $id === get_queried_object_id()) {
        return 'Commander';
    }
    if (function_exists('is_account_page') && is_account_page() && $id === get_queried_object_id()) {
        return 'Mon compte';
    }
    return $title;
}

// 1c. Filtrer le fil d'Ariane d'Astra pour remplacer "Shop" → "Catalogue"
add_filter('astra_breadcrumb_trail', 'dm_translate_astra_breadcrumb', 10, 1);
function dm_translate_astra_breadcrumb($breadcrumb)
{
    $replacements = array(
        '>Shop<'              => '>Catalogue<',
        '>Cart<'              => '>Panier<',
        '>Checkout<'          => '>Commander<',
        '>My Account<'        => '>Mon compte<',
    );
    return str_replace(array_keys($replacements), array_values($replacements), $breadcrumb);
}

// 1d. Hook officiel WC pour le titre des cross-sells
add_filter('woocommerce_product_cross_sells_products_heading', 'dm_cross_sells_heading');
function dm_cross_sells_heading($heading)
{
    return __('Vous pourriez aussi aimer…', 'astra-delices-de-la-mer');
}

// 1e. Traduire le titre "Related products"
add_filter('woocommerce_product_related_products_heading', 'dm_related_products_heading');
function dm_related_products_heading($heading)
{
    return __('Produits liés', 'astra-delices-de-la-mer');
}

// 1f. Traduire le titre "Upsells"
add_filter('woocommerce_product_upsells_products_heading', 'dm_upsells_heading');
function dm_upsells_heading($heading)
{
    return __('Vous aimerez peut-être…', 'astra-delices-de-la-mer');
}

// 2. Script JS pour les blocs WC (React) — les textes rendus côté client
add_action('wp_footer', 'dm_wc_blocks_translate_script');
function dm_wc_blocks_translate_script()
{
    if (! (function_exists('is_cart') && is_cart()) && ! (function_exists('is_checkout') && is_checkout()) && ! (function_exists('is_shop') && is_shop()) && ! (function_exists('is_account_page') && is_account_page()) && ! (function_exists('is_product') && is_product()) && ! is_search()) {
        return;
    }
    ?>
    <script>
    (function() {
        var replacements = {
            'Your cart is currently empty!': 'Votre panier est actuellement vide !',
            'Your cart is currently empty.': 'Votre panier est actuellement vide.',
            'No products were found matching your selection.': 'Aucun produit ne correspond à votre recherche.',
            'No products were found matching your selection': 'Aucun produit ne correspond à votre recherche',
            'New in store': 'Nouveautés en boutique',
            'You may be interested in…': 'Vous pourriez aussi aimer…',
            'You may be interested in...': 'Vous pourriez aussi aimer…',
            'You may also like…': 'Vous pourriez aussi aimer…',
            'You may also like...': 'Vous pourriez aussi aimer…',
            'Related products': 'Produits liés',
            'Cart': 'Panier',
            'Product on sale': 'Produit en promotion',
            'Sale': 'Promo',
            'Add to cart': 'Ajouter au panier',
            'View cart': 'Voir le panier',
            'Proceed to checkout': 'Passer la commande',
            'Place order': 'Valider la commande',
            'Update cart': 'Mettre à jour le panier',
            'Apply coupon': 'Appliquer le code',
            'Continue shopping': 'Continuer mes achats',
            'Subtotal': 'Sous-total',
            'Total': 'Total',
            'Shipping': 'Livraison',
            'In stock': 'En stock',
            'Out of stock': 'Rupture de stock',
            'Description': 'Description',
            'Additional information': 'Informations complémentaires',
            'Reviews': 'Avis',
            'Select options': 'Choisir les options',
            'Default sorting': 'Tri par défaut',
            'Sort by popularity': 'Trier par popularité',
            'Sort by average rating': 'Trier par note moyenne',
            'Sort by latest': 'Trier par nouveauté',
            'Sort by price: low to high': 'Trier par prix croissant',
            'Sort by price: high to low': 'Trier par prix décroissant',
            'Billing details': 'Coordonnées de facturation',
            'Shipping details': 'Adresse de livraison',
            'Your order': 'Votre commande',
            'Login': 'Connexion',
            'Register': 'Inscription',
            'Dashboard': 'Tableau de bord',
            'Orders': 'Commandes',
            'Downloads': 'Téléchargements',
            'Addresses': 'Adresses',
            'Account details': 'Détails du compte',
            'Logout': 'Déconnexion',
        };
        function replaceText(node) {
            if (node.nodeType === 3) {
                var key = node.textContent.trim();
                if (replacements[key]) {
                    node.textContent = replacements[key];
                }
            } else if (node.nodeType === 1 && node.childNodes && !['SCRIPT','STYLE'].includes(node.tagName)) {
                node.childNodes.forEach(replaceText);
            }
        }
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(m) {
                m.addedNodes.forEach(function(node) {
                    replaceText(node);
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            replaceText(document.body);
            observer.observe(document.body, { childList: true, subtree: true });
        });
    })();
    </script>
    <?php
}

