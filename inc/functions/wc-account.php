<?php
/**
 * Personnalisation du compte client WooCommerce.
 *
 * - Renomme les endpoints du compte (tableau de bord, commandes, etc.)
 * - Ajoute des icônes SVG à la navigation
 * - Remplace le dashboard par un tableau de bord personnalisé (KPIs, raccourcis)
 * - Ajoute un endpoint "commandes non payées"
 * - Filtres divers (textes, descriptions)
 */

if (! defined('ABSPATH')) {
    exit;
}

/* ==========================================================================
   1. RENOMMER LES ENDPOINTS + AJOUTER "IMPAYÉES"
   ========================================================================== */

add_filter('woocommerce_account_menu_items', 'dm_rename_account_menu_items');
function dm_rename_account_menu_items($items)
{
    $items['dashboard']       = __('Tableau de bord', 'astra-delices-de-la-mer');
    $items['orders']          = __('Mes commandes', 'astra-delices-de-la-mer');
    $items['edit-address']    = __('Mes adresses', 'astra-delices-de-la-mer');
    $items['edit-account']    = __('Mon profil', 'astra-delices-de-la-mer');
    $items['customer-logout'] = __('Déconnexion', 'astra-delices-de-la-mer');

    // Insérer "Impayées" juste après "Commandes"
    $new_items = array();
    foreach ($items as $key => $label) {
        $new_items[$key] = $label;
        if ($key === 'orders') {
            $new_items['unpaid-orders'] = __('Impayées', 'astra-delices-de-la-mer');
        }
    }

    return $new_items;
}

/* ==========================================================================
   2. ICÔNES SVG DANS LA NAVIGATION
   ========================================================================== */

add_filter('woocommerce_account_navigation_item', 'dm_account_nav_icons', 10, 3);
function dm_account_nav_icons($html, $endpoint, $label)
{
    $icons = array(
        'dashboard'       => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect></svg>',
        'orders'          => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11H5a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2h-4"></path><rect x="9" y="3" width="6" height="8" rx="1"></rect></svg>',
        'unpaid-orders'   => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="6" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>',
        'edit-address'    => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>',
        'edit-account'    => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
        'customer-logout' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>',
    );

    if (isset($icons[$endpoint])) {
        $html = str_replace('>', '>' . $icons[$endpoint], $html);
    }

    return $html;
}

/* ==========================================================================
   3. DASHBOARD PERSONNALISÉ
   ========================================================================== */

function dm_custom_dashboard()
{
    $user_id      = get_current_user_id();
    $user         = get_userdata($user_id);
    $first_name   = $user ? $user->first_name : '';
    $display_name = $user ? $user->display_name : '';
    $greeting_name = $first_name ?: $display_name;
    $initials     = strtoupper(substr($greeting_name, 0, 1));

    // Récupérer toutes les commandes du client
    $customer_orders = wc_get_orders(array(
        'customer_id' => $user_id,
        'limit'       => -1,
        'orderby'     => 'date_created',
        'order'       => 'DESC',
    ));

    $total_orders  = count($customer_orders);
    $total_spent   = 0;
    $completed     = 0;
    $pending_count = 0;

    foreach ($customer_orders as $o) {
        $status = $o->get_status();
        if ($status === 'completed') {
            $completed++;
            $total_spent += (float) $o->get_total();
        }
        if (in_array($status, array('pending', 'on-hold', 'failed', 'checkout-draft'), true)) {
            $pending_count++;
        }
    }

    $last_order = ! empty($customer_orders) ? $customer_orders[0] : null;
    $recent_orders = ! empty($customer_orders) ? array_slice($customer_orders, 0, 3) : array();

    $billing_address  = wc_get_account_formatted_address('billing');
    $shipping_address = wc_get_account_formatted_address('shipping');

    $orders_url    = wc_get_endpoint_url('orders');
    $addresses_url = wc_get_endpoint_url('edit-address');
    $account_url   = wc_get_endpoint_url('edit-account');
    $unpaid_url    = wc_get_endpoint_url('unpaid-orders');
    $logout_url    = wc_logout_url();
    ?>

    <div class="dm-dashboard">

        <!-- KPIs -->
        <div class="dm-dashboard-kpis">
            <div class="dm-kpi-card dm-kpi-card--navy">
                <div class="dm-kpi-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
                <div class="dm-kpi-value"><?php echo esc_html($total_orders); ?></div>
                <div class="dm-kpi-label"><?php esc_html_e('Commandes', 'astra-delices-de-la-mer'); ?></div>
            </div>
            <div class="dm-kpi-card dm-kpi-card--orange">
                <div class="dm-kpi-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div class="dm-kpi-value"><?php echo wp_kses_post(wc_price($total_spent)); ?></div>
                <div class="dm-kpi-label"><?php esc_html_e('Total dépensé', 'astra-delices-de-la-mer'); ?></div>
            </div>
            <div class="dm-kpi-card dm-kpi-card--green">
                <div class="dm-kpi-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <div class="dm-kpi-value"><?php echo esc_html($completed); ?></div>
                <div class="dm-kpi-label"><?php esc_html_e('Terminées', 'astra-delices-de-la-mer'); ?></div>
            </div>
            <div class="dm-kpi-card dm-kpi-card--blue">
                <div class="dm-kpi-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="dm-kpi-value"><?php echo esc_html($pending_count); ?></div>
                <div class="dm-kpi-label"><?php esc_html_e('En attente', 'astra-delices-de-la-mer'); ?></div>
            </div>
        </div>

        <!-- Dernières commandes -->
        <?php if (!empty($recent_orders)) :
            $status_classes = array(
                'completed'  => 'dm-order-status--completed',
                'processing' => 'dm-order-status--processing',
                'pending'    => 'dm-order-status--pending',
                'on-hold'    => 'dm-order-status--on-hold',
                'cancelled'  => 'dm-order-status--cancelled',
                'failed'     => 'dm-order-status--failed',
                'refunded'   => 'dm-order-status--refunded',
            );
        ?>
        <div class="dm-dashboard-section">
            <h3 class="dm-dashboard-section-title"><?php esc_html_e('Dernières commandes', 'astra-delices-de-la-mer'); ?></h3>
            <?php foreach ($recent_orders as $order) :
                $lo_status = $order->get_status();
                $status_class = isset($status_classes[$lo_status]) ? $status_classes[$lo_status] : '';
                $status_label = wc_get_order_status_name($lo_status);
            ?>
            <div class="dm-last-order">
                <div class="dm-last-order-info">
                    <span class="dm-last-order-num"><?php echo esc_html('#' . $order->get_order_number()); ?></span>
                    <span class="dm-last-order-date"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></span>
                </div>
                <div class="dm-last-order-meta">
                    <span class="dm-order-status <?php echo esc_attr($status_class); ?>"><?php echo esc_html($status_label); ?></span>
                    <span class="dm-last-order-total"><?php echo wp_kses_post($order->get_formatted_order_total()); ?></span>
                    <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="dm-last-order-link"><?php esc_html_e('Voir le détail', 'astra-delices-de-la-mer'); ?></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Raccourcis -->
        <div class="dm-dashboard-section">
            <h3 class="dm-dashboard-section-title"><?php esc_html_e('Raccourcis', 'astra-delices-de-la-mer'); ?></h3>
            <div class="dm-dashboard-shortcuts">
                <a href="<?php echo esc_url($orders_url); ?>" class="dm-shortcut-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    <?php esc_html_e('Mes commandes', 'astra-delices-de-la-mer'); ?>
                </a>
                <a href="<?php echo esc_url($addresses_url); ?>" class="dm-shortcut-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <?php esc_html_e('Mes adresses', 'astra-delices-de-la-mer'); ?>
                </a>
                <a href="<?php echo esc_url($account_url); ?>" class="dm-shortcut-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <?php esc_html_e('Mon profil', 'astra-delices-de-la-mer'); ?>
                </a>
                <a href="<?php echo esc_url($unpaid_url); ?>" class="dm-shortcut-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="6" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <?php esc_html_e('Impayées', 'astra-delices-de-la-mer'); ?>
                </a>
            </div>
        </div>

        <!-- Adresses résumé -->
        <div class="dm-dashboard-section">
            <h3 class="dm-dashboard-section-title"><?php esc_html_e('Mes adresses', 'astra-delices-de-la-mer'); ?></h3>
            <div class="dm-dashboard-addresses">
                <div class="dm-address-card">
                    <div class="dm-address-card-head">
                        <h4><?php esc_html_e('Facturation', 'astra-delices-de-la-mer'); ?></h4>
                        <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', 'billing')); ?>"><?php esc_html_e('Modifier', 'astra-delices-de-la-mer'); ?></a>
                    </div>
                    <p><?php echo $billing_address ? wp_kses_post($billing_address) : esc_html__('Aucune adresse enregistrée.', 'astra-delices-de-la-mer'); ?></p>
                </div>
                <div class="dm-address-card">
                    <div class="dm-address-card-head">
                        <h4><?php esc_html_e('Livraison', 'astra-delices-de-la-mer'); ?></h4>
                        <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', 'shipping')); ?>"><?php esc_html_e('Modifier', 'astra-delices-de-la-mer'); ?></a>
                    </div>
                    <p><?php echo $shipping_address ? wp_kses_post($shipping_address) : esc_html__('Aucune adresse enregistrée.', 'astra-delices-de-la-mer'); ?></p>
                </div>
            </div>
        </div>

    </div>
    <?php
}

/* ==========================================================================
   3b. REMPLACER LE DASHBOARD PAR DÉFAUT PAR LE DASHBOARD PERSONNALISÉ
   ========================================================================== */

function dm_setup_account_dashboard()
{
    remove_action('woocommerce_account_dashboard', 'woocommerce_account_dashboard');
    add_action('woocommerce_account_dashboard', 'dm_custom_dashboard');
}
add_action('init', 'dm_setup_account_dashboard');
add_action('template_redirect', 'dm_setup_account_dashboard');

/* ==========================================================================
   4. ENDPOINT "COMMANDES NON PAYÉES"
   ========================================================================== */

add_action('init', 'dm_add_unpaid_orders_endpoint');
function dm_add_unpaid_orders_endpoint()
{
    add_rewrite_endpoint('unpaid-orders', EP_ROOT | EP_PAGES);
}

add_action('woocommerce_account_unpaid-orders_endpoint', 'dm_unpaid_orders_content');
function dm_unpaid_orders_content()
{
    $user_id = get_current_user_id();

    $unpaid_orders = wc_get_orders(array(
        'customer_id' => $user_id,
        'status'      => array('pending', 'on-hold', 'failed', 'checkout-draft'),
        'limit'       => -1,
        'orderby'     => 'date_created',
        'order'       => 'DESC',
    ));

    echo '<h2>' . esc_html__('Commandes non payées', 'astra-delices-de-la-mer') . '</h2>';
    echo '<p class="woocommerce-account-unpaid-orders__intro">' .
        esc_html__('Retrouvez ici les commandes en attente de paiement. Vous pouvez régler directement en cliquant sur "Payer".', 'astra-delices-de-la-mer') .
        '</p>';

    if (empty($unpaid_orders)) {
        echo '<div class="woocommerce-message woocommerce-message--info">' .
            esc_html__('Vous n\'avez aucune commande en attente de paiement.', 'astra-delices-de-la-mer') .
            '</div>';
        return;
    }

    echo '<table class="woocommerce-table--unpaid-orders shop_table shop_table_responsive">';
    echo '<thead><tr>';
    echo '<th>' . esc_html__('Commande', 'astra-delices-de-la-mer') . '</th>';
    echo '<th>' . esc_html__('Date', 'astra-delices-de-la-mer') . '</th>';
    echo '<th>' . esc_html__('Statut', 'astra-delices-de-la-mer') . '</th>';
    echo '<th>' . esc_html__('Total', 'astra-delices-de-la-mer') . '</th>';
    echo '<th>' . esc_html__('Action', 'astra-delices-de-la-mer') . '</th>';
    echo '</tr></thead>';
    echo '<tbody>';

    foreach ($unpaid_orders as $order) {
        $status       = $order->get_status();
        $status_label = wc_get_order_status_name($status);
        $status_class = 'order-status--' . sanitize_html_class($status);

        echo '<tr>';
        echo '<td data-title="' . esc_attr__('Commande', 'astra-delices-de-la-mer') . '"><a href="' . esc_url($order->get_view_order_url()) . '">' . esc_html('#' . $order->get_order_number()) . '</a></td>';
        echo '<td data-title="' . esc_attr__('Date', 'astra-delices-de-la-mer') . '">' . esc_html(wc_format_datetime($order->get_date_created())) . '</td>';
        echo '<td data-title="' . esc_attr__('Statut', 'astra-delices-de-la-mer') . '"><span class="order-status ' . esc_attr($status_class) . '">' . esc_html($status_label) . '</span></td>';
        echo '<td data-title="' . esc_attr__('Total', 'astra-delices-de-la-mer') . '">' . wp_kses_post($order->get_formatted_order_total()) . '</td>';
        echo '<td data-title="' . esc_attr__('Action', 'astra-delices-de-la-mer') . '"><a href="' . esc_url($order->get_checkout_payment_url()) . '" class="button pay">' . esc_html__('Payer', 'astra-delices-de-la-mer') . '</a></td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
}

/* ==========================================================================
   5. FILTRES DIVERS
   ========================================================================== */

/**
 * Ajoute la classe body 'woocommerce-login' sur la page de connexion/inscription
 * (page compte non connecté) pour permettre le styling overlap.
 */
add_filter('body_class', 'dm_login_body_class');
function dm_login_body_class($classes)
{
    if (function_exists('is_account_page') && is_account_page() && ! is_user_logged_in()) {
        $classes[] = 'woocommerce-login';
    }
    return $classes;
}

/**
 * Raccourcit le texte du bouton "Modifier" sur la page Mes adresses.
 */
add_filter('woocommerce_my_account_edit_address_title', 'dm_address_edit_link_text');
function dm_address_edit_link_text($text)
{
    return __('Modifier', 'astra-delices-de-la-mer');
}

/**
 * Masquer la description par défaut sur la page Mes adresses.
 */
add_filter('woocommerce_my_account_my_address_description', '__return_empty_string');

/**
 * Body class pour la page "pay for order".
 */
add_filter('body_class', 'dm_order_pay_body_class');
function dm_order_pay_body_class($classes)
{
    if (function_exists('is_checkout') && is_checkout() && isset($_GET['pay_for_order'])) {
        $classes[] = 'woocommerce-order-pay';
    }
    return $classes;
}

/**
 * Body class pour la page unpaid-orders.
 */
add_filter('body_class', 'dm_unpaid_orders_body_class');
function dm_unpaid_orders_body_class($classes)
{
    if (function_exists('is_account_page') && is_account_page() && is_wc_endpoint_url('unpaid-orders')) {
        $classes[] = 'woocommerce-account-unpaid-orders';
    }
    return $classes;
}
