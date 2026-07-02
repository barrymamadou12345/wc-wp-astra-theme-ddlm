<?php
/**
 * Order Customer Details — override.
 * Affiche les adresses de facturation/livraison dans une grille moderne.
 *
 * @package astra-delices-de-la-mer
 */

if (! defined('ABSPATH')) {
    exit;
}

$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>

<section class="woocommerce-customer-details">

    <?php do_action('woocommerce_order_details_before_customer_details', $order); ?>

    <div class="dm-address-grid">

        <div class="dm-address-card">
            <h3 class="dm-address-card__title"><?php esc_html_e('Adresse de facturation', 'astra-delices-de-la-mer'); ?></h3>
            <div class="dm-address-card__content">
                <address>
                    <?php echo wp_kses_post($order->get_formatted_billing_address($order->get_billing_first_name() ? '' : __('N/A', 'astra-delices-de-la-mer'))); ?>

                    <?php if ($order->get_billing_phone()) : ?>
                        <p class="woocommerce-customer-details--phone"><?php echo esc_html($order->get_billing_phone()); ?></p>
                    <?php endif; ?>

                    <?php if ($order->get_billing_email()) : ?>
                        <p class="woocommerce-customer-details--email"><?php echo esc_html($order->get_billing_email()); ?></p>
                    <?php endif; ?>
                </address>
            </div>
        </div>

        <?php if ($show_shipping) : ?>
            <div class="dm-address-card">
                <h3 class="dm-address-card__title"><?php esc_html_e('Adresse de livraison', 'astra-delices-de-la-mer'); ?></h3>
                <div class="dm-address-card__content">
                    <address>
                        <?php echo wp_kses_post($order->get_formatted_shipping_address()); ?>

                        <?php if ($order->get_shipping_phone()) : ?>
                            <p class="woocommerce-customer-details--phone"><?php echo esc_html($order->get_shipping_phone()); ?></p>
                        <?php endif; ?>
                    </address>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <?php do_action('woocommerce_order_details_after_customer_details', $order); ?>

</section>
