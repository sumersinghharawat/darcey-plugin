<?php
/**
 * Recurring cart subtotals totals
 *
 * @author  WooCommerce
 * @package WooCommerce Subscriptions/Templates
 * @version 1.0.0 - Migrated from WooCommerce Subscriptions v3.1.0
 */

defined( 'ABSPATH' ) || exit;
$display_heading = true;

foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) {
	
	$product_id = "";
	$cartdata = $recurring_carts[$recurring_cart_key]->cart_contents;

	foreach($cartdata as $cartdataKey => $cartdataValue){
		$product_id = $cartdataValue['product_id'];
	}

	// echo $product_id;
	$paymentsystem = get_post_meta($product_id, 'payment');

	?>
	<tr class="cart-subtotal recurring-total">

	<?php if ( $display_heading ) { ?>
		<?php $display_heading = false; ?>
		<th rowspan="<?php echo esc_attr( count( $recurring_carts ) ); ?>"><?php esc_html_e( 'Ongoing '.($paymentsystem[0]=="Pre-paid Monthly"?'Monthly':'Delivery').' Payment', 'woocommerce-subscriptions' ); ?></th>
		<td data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce-subscriptions' ); ?>"><?php echo wc_price( $recurring_cart->get_displayed_subtotal() ); ?></td>
	<?php } else { ?>
		<td><?php wcs_cart_totals_subtotal_html( $recurring_cart ); ?></td>
	<?php }
}
