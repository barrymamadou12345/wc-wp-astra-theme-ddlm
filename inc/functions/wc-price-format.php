<?php
/**
 * Formatage des prix WooCommerce — devise CFA à droite du montant.
 *
 * Centralise le formatage pour toutes les pages :
 * - Cartes produits (boutique, accueil, liés, upsells)
 * - Fiche produit
 * - Panier, commande, compte
 *
 * Exemple : "CFA 2.000" → "2.000 CFA"
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * 1. Position du symbole : à droite (après le montant).
 */
add_filter('woocommerce_currency_symbol', 'dm_currency_symbol_cfa', 10, 2);
function dm_currency_symbol_cfa($symbol, $currency)
{
    if ($currency === 'XOF') {
        return 'CFA';
    }
    return $symbol;
}

/**
 * 2. Forcer le format du prix : montant + espace + symbole.
 *    WooCommerce utilise le format défini dans les options WP.
 *    On force le format "right" avec un espace.
 */
add_filter('woocommerce_price_format', 'dm_price_format_cfa', 10, 3);
function dm_price_format_cfa($format, $currency_pos, $currency_symbol = '')
{
    // Toujours : montant + espace + symbole
    return '%2$s&nbsp;%1$s';
}

/**
 * 3. Forcer la position du symbole à "right" (après le montant).
 *    Ce filtre garantit que même si l'option WC est en "left",
 *    le symbole CFA s'affiche à droite.
 */
add_filter('option_woocommerce_currency_pos', 'dm_force_currency_pos_right');
function dm_force_currency_pos_right($value)
{
    return 'right_space';
}

/**
 * 4. Séparateur de milliers : point (format français).
 *    Exemple : 2000 → 2.000
 */
add_filter('woocommerce_price_thousand_separator', 'dm_price_thousand_sep');
function dm_price_thousand_sep($sep)
{
    return '.';
}

/**
 * 5. Séparateur décimal : virgule (format français).
 */
add_filter('woocommerce_price_decimal_separator', 'dm_price_decimal_sep');
function dm_price_decimal_sep($sep)
{
    return ',';
}

/**
 * 6. Nombre de décimales : 0 (montants entiers en CFA).
 */
add_filter('woocommerce_price_num_decimals', 'dm_price_num_decimals');
function dm_price_num_decimals($num)
{
    return 0;
}

/**
 * 7. Script JS pour reformater les prix rendus côté client (blocs WC React).
 *    Les blocs Gutenberg WooCommerce rendent les prix en JS et ne passent
 *    pas toujours par les filtres PHP. On corrige côté client.
 */
add_action('wp_footer', 'dm_wc_price_format_script');
function dm_wc_price_format_script()
{
    if (! (function_exists('is_woocommerce') && is_woocommerce()) && ! (function_exists('is_shop') && is_shop()) && ! (function_exists('is_product') && is_product()) && ! (function_exists('is_cart') && is_cart()) && ! (function_exists('is_checkout') && is_checkout()) && ! (function_exists('is_account_page') && is_account_page()) && ! is_front_page()) {
        return;
    }
    ?>
    <script>
    (function() {
        // Regex : "CFA 2.000" ou "CFA2.000" ou "CFA 2,000" → "2.000 CFA"
        // Capture le montant (chiffres + séparateurs) après "CFA"
        var pattern = /CFA\s?([\d.,]+)/g;

        function formatPrices(node) {
            if (node.nodeType === 3) {
                var text = node.textContent;
                if (text.indexOf('CFA') !== -1) {
                    var newText = text.replace(pattern, '$1 CFA');
                    if (newText !== text) {
                        node.textContent = newText;
                    }
                }
            } else if (node.nodeType === 1 && node.childNodes && !['SCRIPT', 'STYLE'].includes(node.tagName)) {
                node.childNodes.forEach(formatPrices);
            }
        }

        function init() {
            formatPrices(document.body);
        }

        document.addEventListener('DOMContentLoaded', init);

        // Observer pour les blocs WC qui se re-rendent
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(m) {
                m.addedNodes.forEach(function(node) {
                    formatPrices(node);
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            observer.observe(document.body, { childList: true, subtree: true });
        });
    })();
    </script>
    <?php
}
