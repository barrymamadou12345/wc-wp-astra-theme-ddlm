<?php
/**
 * Commande par Mail ou WhatsApp depuis le panier.
 *
 * 1. Boutons "Commander par mail" / "Commander par WhatsApp" sur la page panier
 *    (injectés dans la sidebar à droite, sous le bouton "Passer la commande")
 * 2. Détection du mode sur la page checkout (query param dm_order_method)
 *    - Modification du bouton place_order (texte, icône, couleur)
 *    - Carte overlap modifiée pour afficher le mode choisi
 * 3. Sauvegarde du mode dans les meta de la commande (classic + Store API)
 * 4. Envoi d'emails personnalisés (admin + client) après validation
 * 5. Bouton WhatsApp sur la page thank you si méthode = whatsapp
 */

if (! defined('ABSPATH')) {
    exit;
}

/* ========================================================================
   1. GESTION DE LA SESSION — détecter le mode depuis l'URL
   ======================================================================== */

/**
 * Récupère le mode de commande depuis l'URL ou la session WC.
 *
 * @return string|false 'mail', 'whatsapp', ou false
 */
function dm_get_order_method()
{
    // D'abord depuis l'URL
    if (isset($_GET['dm_order_method'])) {
        $method = sanitize_key($_GET['dm_order_method']);
        if (in_array($method, array('mail', 'whatsapp'), true)) {
            dm_save_order_method_session($method);
            return $method;
        }
    }

    // Sinon depuis la session WC
    if (function_exists('WC') && WC()->session) {
        $stored = WC()->session->get('dm_order_method');
        if (in_array($stored, array('mail', 'whatsapp'), true)) {
            return $stored;
        }
    }

    return false;
}

/**
 * Sauvegarde le mode dans la session WC ( Initialise la session si besoin).
 */
function dm_save_order_method_session($method)
{
    if (! function_exists('WC') || ! WC()->session) {
        return;
    }
    if (! WC()->session->has_session()) {
        WC()->session->set_customer_session_cookie(true);
    }
    WC()->session->set('dm_order_method', $method);
}

/**
 * Détecte le mode  tôt dans le chargement (template_redirect)
 * pour que la session soit disponible partout (overlap card, checkout, etc.).
 */
add_action('template_redirect', 'dm_detect_order_method_early');
function dm_detect_order_method_early()
{
    // Si on est sur la page checkout sans le paramètre dm_order_method,
    // nettoyer la session pour éviter qu'un mode précédent persiste
    if (function_exists('is_checkout') && is_checkout() && ! is_wc_endpoint_url('order-received')) {
        if (! isset($_GET['dm_order_method'])) {
            if (function_exists('WC') && WC()->session) {
                WC()->session->__unset('dm_order_method');
            }
            return;
        }
    }

    if (! isset($_GET['dm_order_method'])) {
        return;
    }
    $method = sanitize_key($_GET['dm_order_method']);
    if (in_array($method, array('mail', 'whatsapp'), true)) {
        dm_save_order_method_session($method);
    }
}

/* ========================================================================
   2. BOUTONS SUR LA PAGE PANIER (sidebar à droite)
   ======================================================================== */

/**
 * Hook classic — si le panier utilise le shortcode [woocommerce_cart].
 */
add_action('woocommerce_proceed_to_checkout', 'dm_cart_order_method_buttons_classic', 20);
function dm_cart_order_method_buttons_classic()
{
    $checkout_url = wc_get_checkout_url();
    $mail_url     = add_query_arg('dm_order_method', 'mail', $checkout_url);
    $wa_url       = add_query_arg('dm_order_method', 'whatsapp', $checkout_url);
    ?>
    <div class="dm-cart-order-methods">
        <a href="<?php echo esc_url($mail_url); ?>" class="dm-cart-order-method-btn dm-cart-order-method-btn--mail">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            Commander par mail
        </a>
        <a href="<?php echo esc_url($wa_url); ?>" class="dm-cart-order-method-btn dm-cart-order-method-btn--wa">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            Commander par WhatsApp
        </a>
    </div>
    <?php
}

/**
 * JS pour les paniers basés sur les blocs WC (React).
 * Injecte les boutons DANS la sidebar (.wc-block-cart__sidebar), sous le bouton checkout.
 */
add_action('wp_footer', 'dm_cart_order_method_js');
function dm_cart_order_method_js()
{
    if (! (function_exists('is_cart') && is_cart())) {
        return;
    }

    $checkout_url = wc_get_checkout_url();
    $mail_url     = add_query_arg('dm_order_method', 'mail', $checkout_url);
    $wa_url       = add_query_arg('dm_order_method', 'whatsapp', $checkout_url);
    ?>
    <script>
    (function() {
        var mailUrl = '<?php echo esc_js($mail_url); ?>';
        var waUrl   = '<?php echo esc_js($wa_url); ?>';

        var mailSvg = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>';
        var waSvg = '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>';

        function injectButtons() {
            if (document.querySelector('.dm-cart-order-methods')) return;

            // 1. Panier en blocs WC : injecter dans la sidebar
            var sidebar = document.querySelector('.wc-block-cart__sidebar');
            if (sidebar) {
                var container = sidebar.querySelector('.wc-block-components-sidebar') || sidebar;
                if (container && !container.querySelector('.dm-cart-order-methods')) {
                    var div = document.createElement('div');
                    div.className = 'dm-cart-order-methods';

                    var mailBtn = document.createElement('a');
                    mailBtn.href = mailUrl;
                    mailBtn.className = 'dm-cart-order-method-btn dm-cart-order-method-btn--mail';
                    mailBtn.innerHTML = mailSvg + ' Commander par mail';

                    var waBtn = document.createElement('a');
                    waBtn.href = waUrl;
                    waBtn.className = 'dm-cart-order-method-btn dm-cart-order-method-btn--wa';
                    waBtn.innerHTML = waSvg + ' Commander par WhatsApp';

                    div.appendChild(mailBtn);
                    div.appendChild(waBtn);
                    container.appendChild(div);
                    return;
                }
            }

            // 2. Panier classic : le hook PHP gère  , ne rien faire
        }

        var attempts = 0;
        var interval = setInterval(function() {
            injectButtons();
            attempts++;
            if (document.querySelector('.dm-cart-order-methods') || attempts > 40) {
                clearInterval(interval);
            }
        }, 250);

        var observer = new MutationObserver(function() { injectButtons(); });
        document.addEventListener('DOMContentLoaded', function() {
            injectButtons();
            observer.observe(document.body, { childList: true, subtree: true });
        });
    })();
    </script>
    <?php
}

/* ========================================================================
   3. UI CHECKOUT — bannière + bouton modifié (PHP + JS)
   ======================================================================== */

// Modifier le texte du bouton "Valider la commande" côté PHP (fiable, pas de JS)
add_filter('woocommerce_order_button_text', 'dm_order_button_text', 10, 1);
function dm_order_button_text($text)
{
    if (! (function_exists('is_checkout') && is_checkout())) {
        return $text;
    }
    if (is_wc_endpoint_url('order-received')) {
        return $text;
    }

    $method = dm_get_order_method();
    if ($method === 'mail') {
        return 'Valider par mail';
    }
    if ($method === 'whatsapp') {
        return 'Valider par WhatsApp';
    }
    return $text;
}

// Ajouter la classe CSS sur le bouton place_order côté PHP
add_filter('woocommerce_order_button_html', 'dm_order_button_html', 10, 1);
function dm_order_button_html($html)
{
    if (! (function_exists('is_checkout') && is_checkout())) {
        return $html;
    }
    if (is_wc_endpoint_url('order-received')) {
        return $html;
    }

    $method = dm_get_order_method();
    if (! $method) {
        return $html;
    }

    // Ajouter la classe dm-place-order--mail ou dm-place-order--whatsapp
    $class = 'dm-place-order--' . esc_attr($method);
    $html = str_replace('class="button alt', 'class="button alt ' . $class, $html);

    return $html;
}

add_action('wp_footer', 'dm_checkout_order_method_js');
function dm_checkout_order_method_js()
{
    if (! (function_exists('is_checkout') && is_checkout())) {
        return;
    }
    if (is_wc_endpoint_url('order-received')) {
        return;
    }

    $method = dm_get_order_method();
    if (! $method) {
        return;
    }

    $labels = array(
        'mail'     => array(
            'btn'  => 'Valider par mail',
            'svg'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:6px;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>',
        ),
        'whatsapp' => array(
            'btn'  => 'Valider par WhatsApp',
            'svg'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="vertical-align:middle;margin-right:6px;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>',
        ),
    );

    $cfg = $labels[$method];
    ?>
    <script>
    (function() {
        var method = '<?php echo esc_js($method); ?>';
        var btnLabel = '<?php echo esc_js($cfg['btn']); ?>';
        var btnSvg = '<?php echo str_replace("'", "\'", $cfg['svg']); // phpcs:ignore ?>';
        var iconAdded = false;

        // === Ajouter l'icône SVG au bouton (classic + blocks) ===
        function addIconToButton(btn) {
            if (!btn || btn.getAttribute('data-dm-icon')) return;

            // Pour les <button> : injecter l'icône avant le texte
            if (btn.tagName === 'BUTTON') {
                btn.innerHTML = btnSvg + ' ' + btnLabel;
            }
            // Pour les <input> : rien à faire (le texte est déjà via PHP)
            btn.setAttribute('data-dm-icon', '1');
        }

        function findAndAddIcon() {
            // Classic checkout : #place_order
            var btn = document.getElementById('place_order');
            if (btn) {
                addIconToButton(btn);
                return;
            }

            // Blocks checkout
            var selectors = [
                '.wc-block-components-checkout-place-order-button',
                '.wc-block-components-checkout__place-order-button',
                '.wp-block-woocommerce-checkout button[type="submit"]',
                '.wc-block-checkout__main button[type="submit"]'
            ];
            for (var i = 0; i < selectors.length; i++) {
                btn = document.querySelector(selectors[i]);
                if (btn) {
                    addIconToButton(btn);
                    return;
                }
            }
        }

        // Réessayer en continu (WooCommerce AJAX refresh peut remplacer le bouton)
        var iconInterval = setInterval(function() {
            findAndAddIcon();
        }, 300);

        // Observer les mutations pour réinjecter l'icône après AJAX refresh
        var iconObserver = new MutationObserver(function() {
            findAndAddIcon();
        });
        document.addEventListener('DOMContentLoaded', function() {
            findAndAddIcon();
            iconObserver.observe(document.body, { childList: true, subtree: true });
        });

        // === LOADING SPINNER sur le bouton place order ===
        var spinnerSvg = '<svg class="dm-btn-spinner" width="18" height="18" viewBox="0 0 24 24" style="animation:dm-spin 0.8s linear infinite;vertical-align:middle;margin-right:6px;"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="31.4 31.4" stroke-linecap="round" stroke-dashoffset="10"></circle></svg>';

        function addLoadingOnSubmit(btn) {
            if (!btn || btn.getAttribute('data-dm-loading')) return;
            btn.setAttribute('data-dm-loading', '1');

            btn.addEventListener('click', function() {
                var form = btn.closest('form');
                if (form && form.checkValidity && !form.checkValidity()) {
                    return;
                }

                var originalHTML = btn.innerHTML;
                if (btn.tagName === 'INPUT') {
                    var originalValue = btn.value;
                    btn.value = '';
                }

                var loadingText = method === 'whatsapp' ? 'Envoi en cours...' : 'Validation en cours...';
                if (btn.tagName === 'INPUT') {
                    btn.value = loadingText;
                } else {
                    btn.innerHTML = spinnerSvg + loadingText;
                }

                // Désactiver APRÈS la soumission (setTimeout 0)
                setTimeout(function() {
                    btn.setAttribute('disabled', 'disabled');
                    btn.style.opacity = '0.75';
                    btn.style.pointerEvents = 'none';
                }, 0);

                // Réactiver après 15s si pas de redirection (sécurité)
                setTimeout(function() {
                    if (btn && document.body.contains(btn)) {
                        if (btn.tagName === 'INPUT') {
                            btn.value = originalValue;
                        } else {
                            btn.innerHTML = originalHTML;
                        }
                        btn.removeAttribute('disabled');
                        btn.style.opacity = '';
                        btn.style.pointerEvents = '';
                    }
                }, 15000);
            }, true);
        }

        function attachLoadingToButton() {
            var btn = document.getElementById('place_order');
            if (btn) {
                addLoadingOnSubmit(btn);
                return;
            }

            var selectors = [
                '.wc-block-components-checkout-place-order-button',
                '.wc-block-components-checkout__place-order-button',
                '.wp-block-woocommerce-checkout button[type="submit"]',
                '.wc-block-checkout__main button[type="submit"]'
            ];
            for (var i = 0; i < selectors.length; i++) {
                btn = document.querySelector(selectors[i]);
                if (btn) {
                    addLoadingOnSubmit(btn);
                    return;
                }
            }
        }

        var loadingAttempts = 0;
        var loadingInterval = setInterval(function() {
            attachLoadingToButton();
            loadingAttempts++;
            if (loadingAttempts > 60) {
                clearInterval(loadingInterval);
            }
        }, 300);

        var loadingObserver = new MutationObserver(function() {
            attachLoadingToButton();
        });
        document.addEventListener('DOMContentLoaded', function() {
            attachLoadingToButton();
            loadingObserver.observe(document.body, { childList: true, subtree: true });
        });
    })();
    </script>
    <?php
}

/* ========================================================================
   4. CARTE OVERLAY — modifier le texte selon le mode
   ======================================================================== */

add_filter('dm_wc_overlap_card_checkout', 'dm_overlap_card_order_method', 10, 1);
function dm_overlap_card_order_method($data)
{
    $method = dm_get_order_method();
    if (! $method) {
        return $data;
    }

    if ($method === 'mail') {
        $data['title'] = 'Finalisez votre commande par mail';
        $data['desc']  = 'Remplissez vos informations de livraison et cliquez sur "Valider par mail". Nous reviendrons vers vous sous 24h.';
        $data['icon']  = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>';
    } elseif ($method === 'whatsapp') {
        $data['title'] = 'Finalisez votre commande par WhatsApp';
        $data['desc']  = 'Remplissez vos informations de livraison et cliquez sur "Valider par WhatsApp". Votre commande sera envoyée directement sur WhatsApp.';
        $data['icon']  = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>';
    }

    return $data;
}

/* ========================================================================
   5. SAUVEGARDE DU MODE DANS LES META DE LA COMMANDE
   ======================================================================== */

// Classic checkout
add_action('woocommerce_checkout_create_order', 'dm_save_order_method_meta_classic', 10, 2);
function dm_save_order_method_meta_classic($order, $data)
{
    $method = isset($_POST['dm_order_method']) ? sanitize_key($_POST['dm_order_method']) : '';
    if (in_array($method, array('mail', 'whatsapp'), true)) {
        $order->update_meta_data('_dm_order_method', $method);
    } else {
        if (function_exists('WC') && WC()->session) {
            $stored = WC()->session->get('dm_order_method');
            if (in_array($stored, array('mail', 'whatsapp'), true)) {
                $order->update_meta_data('_dm_order_method', $stored);
            }
        }
    }
}

// Blocks checkout (Store API)
add_action('woocommerce_store_api_checkout_update_order_meta', 'dm_save_order_method_meta_blocks', 10, 1);
function dm_save_order_method_meta_blocks($order)
{
    if (function_exists('WC') && WC()->session) {
        $stored = WC()->session->get('dm_order_method');
        if (in_array($stored, array('mail', 'whatsapp'), true)) {
            $order->update_meta_data('_dm_order_method', $stored);
        }
    }
}

/* ========================================================================
   6. ENVOI D'EMAILS PERSONNALISÉS APRÈS VALIDATION
   ======================================================================== */

// Classic checkout — woocommerce_thankyou hook
add_action('woocommerce_thankyou', 'dm_send_order_method_emails', 10, 1);

// Blocks checkout — Store API hook (fires after order is processed)
add_action('woocommerce_store_api_checkout_order_processed', 'dm_send_order_method_emails_by_order', 10, 1);
function dm_send_order_method_emails_by_order($order)
{
    if (! $order) {
        return;
    }
    dm_send_order_method_emails($order->get_id());
}

// Blocks checkout — template_redirect fallback for thank you page
// (au cas où woocommerce_thankyou ne se déclenche pas avec les blocs)
add_action('template_redirect', 'dm_send_order_method_emails_thankyou_fallback');
function dm_send_order_method_emails_thankyou_fallback()
{
    if (! (function_exists('is_checkout') && is_checkout()) ) {
        return;
    }
    if (! is_wc_endpoint_url('order-received')) {
        return;
    }

    $order_id = absint(get_query_var('order-received'));
    if (! $order_id) {
        // Essayer depuis l'URL
        $order_id = isset($_GET['order-received']) ? absint($_GET['order-received']) : 0;
    }
    if (! $order_id) {
        return;
    }

    dm_send_order_method_emails($order_id);
}

function dm_send_order_method_emails($order_id)
{
    if (! $order_id) {
        return;
    }

    if (get_post_meta($order_id, '_dm_emails_sent', true)) {
        return;
    }

    $order = wc_get_order($order_id);
    if (! $order) {
        return;
    }

    // Récupérer le mode depuis les meta OU la session (fallback)
    $method = $order->get_meta('_dm_order_method');
    if (! in_array($method, array('mail', 'whatsapp'), true)) {
        if (function_exists('WC') && WC()->session) {
            $method = WC()->session->get('dm_order_method');
        }
    }

    if (! in_array($method, array('mail', 'whatsapp'), true)) {
        return;
    }

    // Sauvegarder le meta si pas déjà fait (fallback session)
    if (! $order->get_meta('_dm_order_method')) {
        $order->update_meta_data('_dm_order_method', $method);
        $order->save();
    }

    // Marquer comme envoyé
    update_post_meta($order_id, '_dm_emails_sent', '1');

    // Envoyer les emails
    dm_send_order_email_admin($order, $method);
    dm_send_order_email_customer($order, $method);

    // Nettoyer la session
    if (function_exists('WC') && WC()->session) {
        WC()->session->__unset('dm_order_method');
    }
}

/**
 * Récupère l'URL publique de l'image d'un produit.
 */
function dm_get_product_image_url($item)
{
    $product = $item->get_product();
    if (! $product) {
        return '';
    }
    $image_id = $product->get_image_id();
    if (! $image_id) {
        return '';
    }
    $url = wp_get_attachment_image_url($image_id, 'thumbnail');
    if (! $url) {
        return '';
    }
    // Forcer l'URL absolue avec le scheme
    return set_url_scheme($url, 'https');
}

/**
 * Récupère le chemin physique de l'image d'un produit sur le serveur.
 * Nécessaire pour les pièces jointes inline (CID) dans les emails.
 */
function dm_get_product_image_path($item)
{
    $product = $item->get_product();
    if (! $product) {
        return '';
    }
    $image_id = $product->get_image_id();
    if (! $image_id) {
        return '';
    }
    $path = get_attached_file($image_id);
    if (! $path || ! file_exists($path)) {
        // Fallback: utiliser le répertoire d'upload + URL relative
        $src = wp_get_attachment_image_src($image_id, 'thumbnail');
        if ($src) {
            $upload_dir = wp_upload_dir();
            $relative   = str_replace($upload_dir['baseurl'], '', $src[0]);
            $path       = $upload_dir['basedir'] . $relative;
            if (! file_exists($path)) {
                return '';
            }
        }
    }
    return $path;
}

/**
 * Variables globales pour les images inline (CID) dans les emails.
 * Remplies avant wp_mail(), consommées par le hook phpmailer_init.
 */
$GLOBALS['dm_email_inline_images'] = array();

/**
 * Hook phpmailer_init pour ajouter les images inline (CID).
 */
add_action('phpmailer_init', 'dm_phpmailer_add_inline_images');
function dm_phpmailer_add_inline_images($phpmailer)
{
    if (! empty($GLOBALS['dm_email_inline_images'])) {
        // S'assurer que l'email est en HTML
        $phpmailer->isHTML(true);

        foreach ($GLOBALS['dm_email_inline_images'] as $cid => $path) {
            if (file_exists($path)) {
                $filename = basename($path);
                $mime     = function_exists('mime_content_type') ? mime_content_type($path) : '';
                if (empty($mime)) {
                    $wp_ft = wp_check_filetype($path);
                    $mime  = !empty($wp_ft['type']) ? $wp_ft['type'] : 'image/jpeg';
                }
                $phpmailer->addEmbeddedImage($path, $cid, $filename, 'base64', $mime);
            }
        }
    }
}

/**
 * Construit le HTML du tableau des produits pour les emails.
 * Utilise des CID (Content-ID) pour les images inline.
 */
function dm_build_order_items_html($order)
{
    $items_html    = '';
    $inline_images = array();

    foreach ($order->get_items() as $item) {
        $product_name = $item->get_name();
        $qty          = $item->get_quantity();
        $line_total   = wc_price($item->get_total());
        $img_url      = dm_get_product_image_url($item);
        $img_path     = dm_get_product_image_path($item);

        $img_html = '';
        if ($img_path && file_exists($img_path)) {
            // Utiliser CID pour les images inline (fiable dans tous les clients mail)
            $cid = 'product_img_' . $item->get_id();
            $inline_images[$cid] = $img_path;
            $img_html = '<img src="cid:' . $cid . '" alt="" style="width:48px;height:48px;border-radius:6px;object-fit:cover;vertical-align:middle;margin-right:10px;border:1px solid #e2e8f0;" />';
        } elseif ($img_url) {
            // Fallback: URL absolue (si le chemin physique n'est pas trouvé)
            $img_html = '<img src="' . esc_url($img_url) . '" alt="" style="width:48px;height:48px;border-radius:6px;object-fit:cover;vertical-align:middle;margin-right:10px;border:1px solid #e2e8f0;" />';
        }

        $items_html .= '<tr>';
        $items_html .= '<td style="padding:10px 12px;border-bottom:1px solid #e2e8f0;color:#334155;font-size:14px;">' . $img_html . esc_html($product_name) . '</td>';
        $items_html .= '<td style="padding:10px 12px;border-bottom:1px solid #e2e8f0;color:#334155;font-size:14px;text-align:center;">' . esc_html($qty) . '</td>';
        $items_html .= '<td style="padding:10px 12px;border-bottom:1px solid #e2e8f0;color:#1B6BB1;font-size:14px;font-weight:600;text-align:right;">' . wp_kses_post($line_total) . '</td>';
        $items_html .= '</tr>';
    }

    $shipping_total = $order->get_shipping_total();
    if ($shipping_total > 0) {
        $items_html .= '<tr>';
        $items_html .= '<td style="padding:10px 12px;color:#64748b;font-size:14px;" colspan="2">Livraison</td>';
        $items_html .= '<td style="padding:10px 12px;color:#334155;font-size:14px;text-align:right;">' . wp_kses_post(wc_price($shipping_total)) . '</td>';
        $items_html .= '</tr>';
    }

    // Stocker les images inline pour le hook phpmailer_init
    if (! empty($inline_images)) {
        $GLOBALS['dm_email_inline_images'] = $inline_images;
    }

    return $items_html;
}

/**
 * Envoie l'email à l'admin avec le détail complet de la commande.
 */
function dm_send_order_email_admin($order, $method)
{
    $admin_email = dm_get_email();
    $site_name   = 'Les Délices de la Mer';

    $order_num   = $order->get_order_number();
    $order_date  = wc_format_datetime($order->get_date_created());
    $status      = wc_get_order_status_name($order->get_status());
    $pay_method  = $order->get_payment_method_title();
    $order_total = $order->get_formatted_order_total();

    $customer_name  = trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name());
    $customer_email = $order->get_billing_email();
    $customer_phone = $order->get_billing_phone();
    $shipping_addr  = $order->get_formatted_shipping_address();
    $billing_addr   = $order->get_formatted_billing_address();

    $items_html    = dm_build_order_items_html($order);
    $method_label  = $method === 'whatsapp' ? 'WhatsApp' : 'Email';

    $body_html = '
<span class="salut" style="color:#1B6BB1;font-weight:700;font-size:16px;display:block;margin-bottom:16px;">Nouvelle commande — ' . esc_html($method_label) . '</span>

<p class="p1" style="color:#475569;font-size:15px;line-height:1.6;margin:12px 0;">
    Une commande a été passée par <strong style="color:#ff6b00;font-weight:600;">' . esc_html($customer_name) . '</strong> via <strong>' . esc_html($method_label) . '</strong>.
</p>

<div class="info-card" style="background:#f8fafc;border-left:4px solid #ff6b00;border-radius:6px;padding:16px 20px;margin:16px 0;">
    <p style="margin:4px 0;font-size:14px;color:#334155;"><strong style="color:#1B6BB1;">N° commande :</strong> #' . esc_html($order_num) . '</p>
    <p style="margin:4px 0;font-size:14px;color:#334155;"><strong style="color:#1B6BB1;">Date :</strong> ' . esc_html($order_date) . '</p>
    <p style="margin:4px 0;font-size:14px;color:#334155;"><strong style="color:#1B6BB1;">Statut :</strong> ' . esc_html($status) . '</p>
    <p style="margin:4px 0;font-size:14px;color:#334155;"><strong style="color:#1B6BB1;">Paiement :</strong> ' . esc_html($pay_method) . '</p>
    <p style="margin:4px 0;font-size:14px;color:#334155;"><strong style="color:#1B6BB1;">Mode de commande :</strong> ' . esc_html($method_label) . '</p>
</div>

<table style="width:100%;border-collapse:collapse;margin:16px 0;">
    <thead>
        <tr style="background:#1B6BB1;">
            <th style="padding:10px 12px;color:#fff;font-size:13px;text-align:left;">Produit</th>
            <th style="padding:10px 12px;color:#fff;font-size:13px;text-align:center;">Qté</th>
            <th style="padding:10px 12px;color:#fff;font-size:13px;text-align:right;">Prix</th>
        </tr>
    </thead>
    <tbody>
        ' . $items_html . '
        <tr style="background:#f8fafc;">
            <td style="padding:12px;color:#1B6BB1;font-size:15px;font-weight:700;" colspan="2">Total</td>
            <td style="padding:12px;color:#ff6b00;font-size:16px;font-weight:700;text-align:right;">' . wp_kses_post($order_total) . '</td>
        </tr>
    </tbody>
</table>

<div class="info-card" style="background:#f8fafc;border-left:4px solid #ff6b00;border-radius:6px;padding:16px 20px;margin:16px 0;">
    <p style="margin:4px 0;font-size:14px;color:#334155;"><strong style="color:#1B6BB1;">Client :</strong> ' . esc_html($customer_name) . '</p>
    <p style="margin:4px 0;font-size:14px;color:#334155;"><strong style="color:#1B6BB1;">Email :</strong> <a href="mailto:' . esc_attr($customer_email) . '" style="color:#ff6b00;text-decoration:none;">' . esc_html($customer_email) . '</a></p>
    ' . (!empty($customer_phone) ? '<p style="margin:4px 0;font-size:14px;color:#334155;"><strong style="color:#1B6BB1;">Téléphone :</strong> <a href="tel:' . esc_attr(preg_replace('/\s+/', '', $customer_phone)) . '" style="color:#ff6b00;text-decoration:none;">' . esc_html($customer_phone) . '</a></p>' : '') . '
</div>

<div class="info-card" style="background:#f8fafc;border-left:4px solid #ff6b00;border-radius:6px;padding:16px 20px;margin:16px 0;">
    <p style="margin:4px 0;font-size:14px;color:#334155;"><strong style="color:#1B6BB1;">Adresse de facturation :</strong><br>' . wp_kses_post($billing_addr ?: '—') . '</p>
    ' . (!empty($shipping_addr) ? '<p style="margin:8px 0 4px;font-size:14px;color:#334155;"><strong style="color:#1B6BB1;">Adresse de livraison :</strong><br>' . wp_kses_post($shipping_addr) . '</p>' : '') . '
</div>

<p style="text-align:center;margin-top:24px;">
    <a href="mailto:' . esc_attr($customer_email) . '?subject=Re: Commande #' . esc_attr($order_num) . '" class="btn" style="display:inline-block;padding:14px 32px;font-size:15px;color:#ffffff !important;text-decoration:none;border-radius:8px;background:linear-gradient(135deg,#ff6b00 0%,#e55a00 100%);box-shadow:0 4px 14px rgba(255,107,0,0.35);font-weight:500;letter-spacing:0.3px;">
        Répondre au client
    </a>
</p>';

    $title    = 'Nouvelle commande #' . $order_num . ' — ' . $method_label;
    $logo_url = home_url('/wp-content/themes/astra-delices-de-la-mer/assets/images/logo/logo1.png');
    $phone    = dm_get_phone();
    $email    = dm_get_email();
    $address  = dm_get_address();
    $socials  = dm_get_social_networks();

    ob_start();
    include DM_THEME_DIR . '/inc/emails/email-template.php';
    $full_html = ob_get_clean();

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . $customer_name . ' <' . $customer_email . '>',
    );

    $subject = 'Nouvelle commande #' . $order_num . ' — ' . $site_name;
    wp_mail($admin_email, $subject, $full_html, $headers);

    // Réinitialiser les images inline après l'envoi
    $GLOBALS['dm_email_inline_images'] = array();
}

/**
 * Envoie l'email au client avec le récap + mention 24h.
 */
function dm_send_order_email_customer($order, $method)
{
    $site_name = 'Les Délices de la Mer';

    $order_num   = $order->get_order_number();
    $order_date  = wc_format_datetime($order->get_date_created());
    $pay_method  = $order->get_payment_method_title();
    $order_total = $order->get_formatted_order_total();

    $customer_name  = trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name());
    $customer_email = $order->get_billing_email();
    $customer_phone = $order->get_billing_phone();
    $shipping_addr  = $order->get_formatted_shipping_address();
    $billing_addr   = $order->get_formatted_billing_address();

    $items_html = dm_build_order_items_html($order);

    $phone_mention = '';
    if (!empty($customer_phone)) {
        $phone_mention = '<p class="p1" style="color:#475569;font-size:15px;line-height:1.6;margin:12px 0;">
            Nous pourrons également vous appeler au <strong style="color:#1B6BB1;">' . esc_html($customer_phone) . '</strong> si nécessaire.
        </p>';
    }

    // Bloc adresse de livraison
    $delivery_html = '';
    if (!empty($shipping_addr)) {
        $delivery_html = '
<div class="info-card" style="background:#f8fafc;border-left:4px solid #ff6b00;border-radius:6px;padding:16px 20px;margin:16px 0;">
    <p style="margin:4px 0;font-size:14px;color:#334155;"><strong style="color:#1B6BB1;">Adresse de livraison :</strong><br>' . wp_kses_post($shipping_addr) . '</p>
</div>';
    } elseif (!empty($billing_addr)) {
        $delivery_html = '
<div class="info-card" style="background:#f8fafc;border-left:4px solid #ff6b00;border-radius:6px;padding:16px 20px;margin:16px 0;">
    <p style="margin:4px 0;font-size:14px;color:#334155;"><strong style="color:#1B6BB1;">Adresse :</strong><br>' . wp_kses_post($billing_addr) . '</p>
</div>';
    }

    $body_html = '
<span class="salut" style="color:#1B6BB1;font-weight:700;font-size:16px;display:block;margin-bottom:16px;">Bonjour ' . esc_html($customer_name) . ',</span>

<p class="p1" style="color:#475569;font-size:15px;line-height:1.6;margin:12px 0;">
    Nous avons bien reçu votre commande <strong style="color:#ff6b00;">#' . esc_html($order_num) . '</strong>. Merci pour votre confiance !
</p>

<div class="info-card" style="background:#f8fafc;border-left:4px solid #ff6b00;border-radius:6px;padding:16px 20px;margin:16px 0;">
    <p style="margin:4px 0;font-size:14px;color:#334155;">
        <strong style="color:#1B6BB1;">Temps de réponse : sous 24h</strong><br>
        Nous reviendrons vers vous dans les meilleurs délais par email' . (!empty($customer_phone) ? ' ou par téléphone' : '') . ' pour confirmer votre commande.
    </p>
</div>

<div class="info-card" style="background:#f8fafc;border-left:4px solid #ff6b00;border-radius:6px;padding:16px 20px;margin:16px 0;">
    <p style="margin:4px 0;font-size:14px;color:#334155;"><strong style="color:#1B6BB1;">N° commande :</strong> #' . esc_html($order_num) . '</p>
    <p style="margin:4px 0;font-size:14px;color:#334155;"><strong style="color:#1B6BB1;">Date :</strong> ' . esc_html($order_date) . '</p>
    <p style="margin:4px 0;font-size:14px;color:#334155;"><strong style="color:#1B6BB1;">Paiement :</strong> ' . esc_html($pay_method) . '</p>
</div>

<table style="width:100%;border-collapse:collapse;margin:16px 0;">
    <thead>
        <tr style="background:#1B6BB1;">
            <th style="padding:10px 12px;color:#fff;font-size:13px;text-align:left;">Produit</th>
            <th style="padding:10px 12px;color:#fff;font-size:13px;text-align:center;">Qté</th>
            <th style="padding:10px 12px;color:#fff;font-size:13px;text-align:right;">Prix</th>
        </tr>
    </thead>
    <tbody>
        ' . $items_html . '
        <tr style="background:#f8fafc;">
            <td style="padding:12px;color:#1B6BB1;font-size:15px;font-weight:700;" colspan="2">Total</td>
            <td style="padding:12px;color:#ff6b00;font-size:16px;font-weight:700;text-align:right;">' . wp_kses_post($order_total) . '</td>
        </tr>
    </tbody>
</table>

' . $delivery_html . '

' . $phone_mention . '

<p class="p1" style="color:#475569;font-size:15px;line-height:1.6;margin:12px 0;">
    Pour toute question, contactez-nous via notre page <a href="' . esc_url(home_url('/contact')) . '" style="color:#ff6b00;text-decoration:none;">contact</a>.
</p>';

    $title    = 'Votre commande #' . $order_num . ' — ' . $site_name;
    $logo_url = home_url('/wp-content/themes/astra-delices-de-la-mer/assets/images/logo/logo1.png');
    $phone    = dm_get_phone();
    $email    = dm_get_email();
    $address  = dm_get_address();
    $socials  = dm_get_social_networks();

    ob_start();
    include DM_THEME_DIR . '/inc/emails/email-template.php';
    $full_html = ob_get_clean();

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
    );

    $subject = 'Votre commande #' . $order_num . ' — ' . $site_name;
    wp_mail($customer_email, $subject, $full_html, $headers);

    // Réinitialiser les images inline après l'envoi
    $GLOBALS['dm_email_inline_images'] = array();
}

/* ========================================================================
   7. BOUTON WHATSAPP SUR LA PAGE THANK YOU
   ======================================================================== */

/**
 * Construit un message WhatsApp propre, sans HTML ni entités.
 * Formate avec des espaces et sauts de ligne cohérents.
 */
function dm_build_whatsapp_message($order)
{
    $order_num   = $order->get_order_number();
    $order_date  = wc_format_datetime($order->get_date_created());
    $pay_method  = $order->get_payment_method_title();

    $customer_name  = trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name());
    $customer_phone = $order->get_billing_phone();
    $customer_email = $order->get_billing_email();

    // Adresse propre sans HTML
    $shipping_addr = dm_clean_text_for_whatsapp($order->get_formatted_shipping_address());
    $billing_addr  = dm_clean_text_for_whatsapp($order->get_formatted_billing_address());

    // Prix propre sans HTML ni &nbsp;
    $order_total_clean = dm_clean_price_for_whatsapp($order->get_total());

    $msg  = "*Nouvelle commande — Les Délices de la Mer*\n";
    $msg .= "Commande #" . $order_num . "\n";
    $msg .= "Date : " . $order_date . "\n";
    $msg .= "\n";
    $msg .= "*Client*\n";
    $msg .= "Nom : " . $customer_name . "\n";
    $msg .= "Email : " . $customer_email . "\n";
    if (!empty($customer_phone)) {
        $msg .= "Téléphone : " . $customer_phone . "\n";
    }

    $msg .= "\n";
    $msg .= "*Produits*\n";
    foreach ($order->get_items() as $item) {
        $item_total = dm_clean_price_for_whatsapp($item->get_total());
        $msg .= "• " . $item->get_name() . " x" . $item->get_quantity() . " — " . $item_total . "\n";
    }

    $shipping_total = $order->get_shipping_total();
    if ($shipping_total > 0) {
        $msg .= "Livraison : " . dm_clean_price_for_whatsapp($shipping_total) . "\n";
    }

    $msg .= "\n";
    $msg .= "*Total : " . $order_total_clean . "*\n";
    $msg .= "Paiement : " . $pay_method . "\n";

    if (!empty($shipping_addr)) {
        $msg .= "\n";
        $msg .= "*Adresse de livraison*\n";
        $msg .= $shipping_addr . "\n";
    } elseif (!empty($billing_addr)) {
        $msg .= "\n";
        $msg .= "*Adresse*\n";
        $msg .= $billing_addr . "\n";
    }

    return $msg;
}

/**
 * Nettoie un texte pour WhatsApp : supprime HTML, entités, balises <br/>.
 */
function dm_clean_text_for_whatsapp($text)
{
    if (empty($text)) {
        return '';
    }
    // Convertir les <br/> et <br> en sauts de ligne
    $text = preg_replace('/<br\s*\/?>/i', "\n", $text);
    // Supprimer toutes les autres balises HTML
    $text = strip_tags($text);
    // Convertir les entités HTML courantes
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    // Supprimer les &nbsp; restants
    $text = str_replace("\xc2\xa0", ' ', $text);
    // Nettoyer les espaces multiples
    $text = preg_replace('/[ \t]+/', ' ', $text);
    // Nettoyer les sauts de ligne multiples
    $text = preg_replace('/\n{3,}/', "\n\n", $text);
    return trim($text);
}

/**
 * Formate un prix pour WhatsApp : nombre propre sans HTML ni &nbsp;.
 */
function dm_clean_price_for_whatsapp($amount)
{
    if (empty($amount)) {
        return '0';
    }
    // Si c'est déjà un string formaté par wc_price, on nettoie
    $cleaned = strip_tags((string) $amount);
    $cleaned = html_entity_decode($cleaned, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $cleaned = str_replace("\xc2\xa0", ' ', $cleaned);
    $cleaned = str_replace('&nbsp;', ' ', $cleaned);
    return trim($cleaned);
}

add_action('woocommerce_thankyou', 'dm_thankyou_whatsapp_button', 20, 1);
function dm_thankyou_whatsapp_button($order_id)
{
    if (! $order_id) {
        return;
    }

    $order = wc_get_order($order_id);
    if (! $order) {
        return;
    }

    $method = $order->get_meta('_dm_order_method');
    if ($method !== 'whatsapp') {
        // Fallback session
        if (function_exists('WC') && WC()->session) {
            $session_method = WC()->session->get('dm_order_method');
            if ($session_method !== 'whatsapp') {
                return;
            }
        } else {
            return;
        }
    }

    $wa_number = function_exists('dm_get_whatsapp_number') ? dm_get_whatsapp_number() : '';
    $msg = dm_build_whatsapp_message($order);
    $wa_url = 'https://wa.me/' . $wa_number . '?text=' . rawurlencode($msg);
    ?>
    <div class="dm-thankyou-wa-section">
        <div class="dm-thankyou-wa-card">
            <div class="dm-thankyou-wa-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </div>
            <div class="dm-thankyou-wa-content">
                <h3>Envoyez votre commande sur WhatsApp</h3>
                <p>Cliquez ci-dessous pour envoyer le récapitulatif de votre commande directement sur WhatsApp.</p>
            </div>
        </div>
        <a href="<?php echo esc_attr($wa_url); ?>" target="_blank" rel="noreferrer" class="dm-thankyou-wa-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            Envoyer sur WhatsApp
        </a>
    </div>
    <?php
    do_action('dm_thankyou_wa_rendered');
}

/**
 * Fallback pour les blocs WC : injecter le bouton WhatsApp via wp_footer
 * sur la page thank you si woocommerce_thankyou ne s'est pas déclenché.
 */
add_action('wp_footer', 'dm_thankyou_whatsapp_button_fallback');
function dm_thankyou_whatsapp_button_fallback()
{
    if (! (function_exists('is_checkout') && is_checkout()) ) {
        return;
    }
    if (! is_wc_endpoint_url('order-received')) {
        return;
    }

    $order_id = absint(get_query_var('order-received'));
    if (! $order_id) {
        $order_id = isset($_GET['order-received']) ? absint($_GET['order-received']) : 0;
    }
    if (! $order_id) {
        return;
    }

    $order = wc_get_order($order_id);
    if (! $order) {
        return;
    }

    $method = $order->get_meta('_dm_order_method');
    if ($method !== 'whatsapp') {
        return;
    }

    // Si le bouton a déjà été rendu par le hook woocommerce_thankyou, ne pas dupliquer
    if (did_action('dm_thankyou_wa_rendered')) {
        return;
    }

    $wa_number = function_exists('dm_get_whatsapp_number') ? dm_get_whatsapp_number() : '';
    $msg = dm_build_whatsapp_message($order);
    $wa_url = 'https://wa.me/' . $wa_number . '?text=' . rawurlencode($msg);
    $wa_html = '<div class="dm-thankyou-wa-section">'
        . '<div class="dm-thankyou-wa-card">'
        . '<div class="dm-thankyou-wa-icon">'
        . '<svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>'
        . '</div>'
        . '<div class="dm-thankyou-wa-content">'
        . '<h3>Envoyez votre commande sur WhatsApp</h3>'
        . '<p>Cliquez ci-dessous pour envoyer le récapitulatif de votre commande directement sur WhatsApp.</p>'
        . '</div>'
        . '</div>'
        . '<a href="' . esc_attr($wa_url) . '" target="_blank" rel="noreferrer" class="dm-thankyou-wa-btn">'
        . '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>'
        . ' Envoyer sur WhatsApp'
        . '</a>'
        . '</div>';
    ?>
    <script>
    (function() {
        var waHtml = <?php echo wp_json_encode($wa_html); ?>;

        function injectWhatsAppButton() {
            if (document.querySelector('.dm-thankyou-wa-section')) return;

            // 1. Juste après la carte overlap (priorité)
            var overlapCard = document.querySelector('.dm-wc-overlap-card');
            if (overlapCard) {
                var div = document.createElement('div');
                div.innerHTML = waHtml;
                overlapCard.parentNode.insertBefore(div.firstElementChild, overlapCard.nextSibling);
                return;
            }

            // 2. Fallback : après les éléments de confirmation
            var targets = [
                '.wp-block-woocommerce-order-confirmation-status',
                '.wp-block-woocommerce-order-confirmation-summary',
                '.wc-block-order-confirmation',
                '.woocommerce-order-overview',
                '.order_details'
            ];

            for (var i = 0; i < targets.length; i++) {
                var target = document.querySelector(targets[i]);
                if (target) {
                    var div = document.createElement('div');
                    div.innerHTML = waHtml;
                    target.parentNode.insertBefore(div.firstElementChild, target.nextSibling);
                    return;
                }
            }
        }

        var attempts = 0;
        var interval = setInterval(function() {
            injectWhatsAppButton();
            attempts++;
            if (document.querySelector('.dm-thankyou-wa-section') || attempts > 40) {
                clearInterval(interval);
            }
        }, 250);

        var observer = new MutationObserver(function() { injectWhatsAppButton(); });
        document.addEventListener('DOMContentLoaded', function() {
            injectWhatsAppButton();
            observer.observe(document.body, { childList: true, subtree: true });
        });
    })();
    </script>
    <?php
}
