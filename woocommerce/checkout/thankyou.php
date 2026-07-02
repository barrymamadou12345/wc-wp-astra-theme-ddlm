<?php
/**
 * Checkout: Thankyou page — override.
 * Affiche le message de confirmation, le récapitulatif de commande,
 * le tableau des articles et les adresses avec un design moderne.
 *
 * @package astra-delices-de-la-mer
 */

if (! defined('ABSPATH')) {
    exit;
}

if ($order) {
    $order_id    = $order->get_id();
    $order_date  = wc_format_datetime($order->get_date_created());
    $order_email = $order->get_billing_email();
    $order_total = $order->get_formatted_order_total();
    $pay_method  = $order->get_payment_method_title();
    $status      = $order->get_status();

    // Classes de statut pour le badge
    $status_classes = array(
        'completed'      => 'dm-order-status--completed',
        'processing'     => 'dm-order-status--processing',
        'pending'        => 'dm-order-status--pending',
        'on-hold'        => 'dm-order-status--on-hold',
        'cancelled'      => 'dm-order-status--cancelled',
        'failed'         => 'dm-order-status--failed',
        'refunded'       => 'dm-order-status--refunded',
    );
    $status_class = isset($status_classes[$status]) ? $status_classes[$status] : '';
    $status_label = wc_get_order_status_name($status);
    ?>

    <div class="woocommerce-order">

        <?php if ($status === 'failed') : ?>
            <p class="woocommerce-thankyou-order-failed">
                <?php esc_html_e('Malheureusement, votre commande ne peut pas être traitée car le paiement a échoué ou a été annulé. Veuillez réessayer.', 'astra-delices-de-la-mer'); ?>
            </p>
            <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button pay">
                <?php esc_html_e('Payer', 'astra-delices-de-la-mer'); ?>
            </a>
        <?php else : ?>
        <?php endif; ?>

        <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
            <li class="woocommerce-order-overview__order order">
                <?php esc_html_e('Commande', 'astra-delices-de-la-mer'); ?>
                <strong><?php echo esc_html($order->get_order_number()); ?></strong>
            </li>
            <li class="woocommerce-order-overview__date date">
                <?php esc_html_e('Date', 'astra-delices-de-la-mer'); ?>
                <strong><?php echo esc_html($order_date); ?></strong>
            </li>
            <li class="woocommerce-order-overview__email email">
                <?php esc_html_e('Email', 'astra-delices-de-la-mer'); ?>
                <strong><?php echo esc_html($order_email); ?></strong>
            </li>
            <li class="woocommerce-order-overview__total total">
                <?php esc_html_e('Total', 'astra-delices-de-la-mer'); ?>
                <strong><?php echo wp_kses_post($order_total); ?></strong>
            </li>
            <li class="woocommerce-order-overview__payment-method method">
                <?php esc_html_e('Paiement', 'astra-delices-de-la-mer'); ?>
                <strong><?php echo esc_html($pay_method); ?></strong>
            </li>
        </ul>

        <?php do_action('woocommerce_thankyou', $order_id); ?>
        <?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order_id); ?>

    </div>

    <?php
} else {
    // Pas de commande — fallback template par défaut
    echo '<p class="woocommerce-notice woocommerce-notice--info woocommerce-info">' .
        esc_html__('Cette commande n\'existe pas ou n\'est plus accessible.', 'astra-delices-de-la-mer') .
        '</p>';
}
