<?php
/**
 * Checkout shipping information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;
?>
<style>
#shipping_wooccm15_field {
    width: 100%;
}
.payment-heading, .woocommerce-shipping-fields__field-wrapper, .payment-form_shipping_wrap, #billing_wooccm11_field{
	display: block !important;
}
.woocommerce-additional-fields{
	display: block !important;
}	
.woocommerce form .wooccm-conditional-child {
    display: block !important;
}
.woocommerce-shipping-fields__field-wrapper {
    display: flex !important;
    flex-wrap: wrap;
    flex-direction: row;
}

p#shipping_country_field {
    width: 50% !important;
}

p#shipping_state_field, #shipping_time_field, #shipping_date_field {
    width: 50% !important;
}

p#shipping_wooccm25_field {
    width: 100% !important;
}

p#shipping_wooccm24_field {
    width: 100% !important;
}

</style>
<div class="woocommerce-shipping-fields">
	<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>

		<h3 id="ship-to-different-address">
			<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
				<input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" /> <span><?php esc_html_e( 'Ship to a different address?', 'woocommerce' ); ?></span>
			</label>
		</h3>

		<div class="shipping_address">
			<h3>2. Delivery Details</h3>
			<?php //do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

			<div class="woocommerce-shipping-fields__field-wrapper">
				<?php
				$fields = $checkout->get_checkout_fields( 'shipping' );

				foreach ( $fields as $key => $field ) {
					$unusedFields = array(
						"shipping_wooccm12",
						"shipping_wooccm13",
						"shipping_wooccm14",
// 						"shipping_wooccm15",
						"shipping_wooccm17",
						"shipping_wooccm18",
						"shipping_wooccm19",
						"shipping_wooccm20",
						"shipping_wooccm21",
						"shipping_wooccm22",
						"shipping_wooccm23",
						// "shipping_wooccm25",
						"shipping_wooccm27",
						"shipping_wooccm28",
						"shipping_wooccm29",
						"shipping_wooccm30",
						"shipping_company",
						"shipping_wooccm16",
						"shipping_first_name",
						"shipping_last_name",
						"shipping_wooccm10",
						"shipping_city"
					);

					if($field['key'] == 'shipping_wooccm25') {

						$fields['shipping_wooccm12']['value'] = $_SESSION['time'];
						$fields['shipping_wooccm13']['value'] = $_SESSION['date'];
						$fields['shipping_wooccm15']['value'] = $_SESSION['msg'];
						
					?>
						<!-- 						Delivery Date,Delivery Time -->
						<input type="hidden" name="shipping_wooccm12" value="<?php echo $_SESSION['time'];?>" />
						<input type="hidden" name="shipping_wooccm13" value="<?php echo $_SESSION['date'];?>" />
						<input type="hidden" name="shipping_wooccm15" value="<?php echo $_SESSION['msg'];?>" />
						<input type="hidden" name="selected_color" value="<?php echo $_SESSION['select-color'];?>" />
						
				<p class="form-row wooccm-conditional-child form-row-wide wooccm-field wooccm-field-time wooccm-type-text validate-required" id="shipping_time_field" data-priority="150"><label for="shipping_wooccm25" class="">Delivery Time<abbr class="required" title="required">*</abbr></label><span class="woocommerce-input-wrapper"><?php echo $fields['shipping_wooccm12']['value'];?></span></p>
				
				
				<p class="form-row wooccm-conditional-child form-row-wide wooccm-field wooccm-field-date wooccm-type-text validate-required" id="shipping_date_field" data-priority="150"><label for="shipping_wooccm25" class="">Delivery Date<abbr class="required" title="required">*</abbr></label><span class="woocommerce-input-wrapper"><?php echo $fields['shipping_wooccm13']['value'];?></span></p>
						<?php
					}
					if(!in_array($field['key'], $unusedFields)) {
						woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
					}
				}
				?>
			</div>

			<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

		</div>

	<?php endif; ?>
</div>
<div class="woocommerce-additional-fields">
	<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

	<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>

		<?php if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>

			<h3><?php esc_html_e( 'Additional information', 'woocommerce' ); ?></h3>

		<?php endif; ?>

		<div class="woocommerce-additional-fields__field-wrapper">
			<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
				<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
			<?php endforeach; ?>
		</div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
</div>
