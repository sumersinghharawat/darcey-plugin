<?php

// Custom Billing form for checkout page


    if (is_checkout() && is_cart_contains_subscription()) {
        // echo $template_name;

        if ($template_name === 'checkout/form-billing.php') {
            // Set the path to your custom template within the plugin folder
            $template = plugin_dir_path(__FILE__) . 'woocommerce/checkout/form-billing.php';
        }
        if ($template_name === 'checkout/form-shipping.php') {
            // Set the path to your custom template within the plugin folder
            $template = plugin_dir_path(__FILE__) . 'woocommerce/checkout/form-shipping.php';
        }

        if ($template_name === 'checkout/payment.php') {
            //                 // Set the path to your custom template within the plugin folder
            $template = plugin_dir_path(__FILE__) . 'woocommerce/checkout/payment.php';
        }

        if ($template_name === 'checkout/review-order.php') {
            // Set the path to your custom template within the plugin folder
            $template = plugin_dir_path(__FILE__) . 'woocommerce/checkout/review-order.php';
        }

        if ($template_name === 'checkout/form-checkout.php') {
            // Set the path to your custom template within the plugin folder
            $template = plugin_dir_path(__FILE__) . 'woocommerce/checkout/form-checkout.php';
        }

        if ($template_name === 'checkout/recurring-totals.php') {
            // Set the path to your custom template within the plugin folder
            $template = "";
        }

        if ($template_name === 'checkout/recurring-subtotals.php') {
            // Set the path to your custom template within the plugin folder
            $template = plugin_dir_path(__FILE__) . 'woocommerce/checkout/recurring-subtotals.php';
        }

        if ($template_name === 'checkout/form-coupon.php') {
            // Set the path to your custom template within the plugin folder
            $template = plugin_dir_path(__FILE__) . 'woocommerce/checkout/form-coupon.php';
        }

        if (is_wc_endpoint_url('order-received')) {
            // Look for the custom thankyou.php file in your plugin directory
            $custom_template = plugin_dir_path(__FILE__) . 'woocommerce/checkout/thankyou.php';

            // Use the custom template if it exists, otherwise fall back to default WooCommerce template
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
    }
    return $template;
