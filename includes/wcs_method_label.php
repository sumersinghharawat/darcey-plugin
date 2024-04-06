<?php

// Change label on checkout total for subscription product

function wcs_method_label($order_total_html, $cart)
{

    $order_total_html = '';
    $value            = '<strong>' . $cart->get_total() . '</strong> ';
    // If prices are tax inclusive, show taxes here
    if (wc_tax_enabled() && $cart->tax_display_cart == 'incl') {
        $tax_string_array = array();

        if (get_option('woocommerce_tax_total_display') == 'itemized') {
            foreach ($cart->get_tax_totals() as $code => $tax) {
                $tax_string_array[] = sprintf('%s %s', $tax->formatted_amount, $tax->label);
            }
        } else {
            $tax_string_array[] = sprintf('%s %s', wc_price($cart->get_taxes_total(true, true)), WC()->countries->tax_or_vat());
        }

        if (!empty($tax_string_array)) {
            // translators: placeholder is price string, denotes tax included in cart/order total
            $value .= '<small class="includes_tax">' . sprintf(_x('(Includes %s)', 'includes tax', 'woocommerce-subscriptions'), implode(', ', $tax_string_array)) . '</small>';
        }
    }

    $order_total_html .= $value;

    return '<div style="text-align:right;">' . $order_total_html . '</div>';
}
