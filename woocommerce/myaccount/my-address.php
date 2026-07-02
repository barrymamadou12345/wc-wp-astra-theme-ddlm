<?php
/**
 * My Addresses — template override
 *
 * Override WC my-address.php with clean HTML (no legacy col2-set/col-1/col-2 float classes).
 * Uses dm-address-grid / dm-address-card classes for full CSS control.
 *
 * @package astra-delices-de-la-mer
 */

defined('ABSPATH') || exit;

$get_addresses = apply_filters(
    'woocommerce_my_account_my_addresses_get_addresses',
    array(
        'billing'  => __('Adresse de facturation', 'astra-delices-de-la-mer'),
        'shipping' => __('Adresse de livraison', 'astra-delices-de-la-mer'),
    ),
    get_current_user_id()
);
?>

<div class="dm-address-grid">
    <?php foreach ($get_addresses as $name => $address_title) : ?>
        <?php
        $address = wc_get_account_formatted_address($name);
        ?>
        <div class="dm-address-card">
            <header class="dm-address-card__header">
                <h2 class="dm-address-card__title"><?php echo esc_html($address_title); ?></h2>
                <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', $name)); ?>" class="dm-address-card__edit">
                    <?php esc_html_e('Modifier', 'astra-delices-de-la-mer'); ?>
                </a>
            </header>
            <address class="dm-address-card__content">
                <?php
                if ($address) {
                    echo wp_kses_post($address);
                } else {
                    esc_html_e('Aucune adresse enregistrée pour le moment.', 'astra-delices-de-la-mer');
                }

                do_action('woocommerce_my_account_after_my_address', $name);
                ?>
            </address>
        </div>
    <?php endforeach; ?>
</div>
