<?php
/*
Plugin Name: Naya Group Custom Plugin
Description: Naya Group Custom Plugin for Customization with subscription
Version: 1.0
Author: Naya Group
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class NayagroupCustomPlugin
{

    public $orderdate;

    public $ordertime;

    public function __construct()
    {


        $this->orderdate = "";

        $this->ordertime = "";

        // Add hooks and filters
        add_action('init', array($this, 'register_session'));
        add_action('admin_menu', array($this, 'register_admin_menu'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'load_media_files'));
        add_action('template_redirect', array($this, 'redirect_to_checkout_if_subscription'));
        add_action('woocommerce_checkout_create_order', array($this, 'save_extra_checkout_fields'), 10, 2);
        add_filter('wcs_cart_totals_order_total_html', array($this, 'wcs_method_label'), 1, 2);
        add_filter('woocommerce_locate_template', array($this, 'custom_override_checkout_billing_template'), 10, 3);
        add_filter('woocommerce_subscriptions_product_price_string', array($this, 'custom_price_string'), 10, 3);
        add_filter('woocommerce_is_sold_individually', array($this, 'remove_quantity_fields'), 10, 2);
        add_filter('woocommerce_cart_item_name', array($this, 'add_meta_on_checkout_order_review_item'), 10, 3);
        add_filter('woocommerce_add_cart_item_data', array($this, 'modify_recurring_product_start_date'), 10, 3);
        add_action('rest_api_init', array($this, 'register_rest_routes'));
        add_action('wp_body_open', array($this, 'add_div_after_body_open'));
        add_action('woocommerce_checkout_process', array($this, 'custom_checkout_fields_validation_process'));
        // Hook into payment complete action
        add_action('woocommerce_payment_complete', array($this, 'create_order_on_payment_complete'));
    }

    public function register_session()
    {
        if (!session_id()) {
            session_start();
        }
    }
    
    public function custom_checkout_fields_validation_process()
    {
        // Check if 'shipping_wooccm25' field is set
        if (!isset($_POST['shipping_wooccm25']) || empty($_POST['shipping_wooccm25'])) {
            wc_add_notice(__('Please complete your address field.', 'woocommerce'), 'error');
        }
    }    

    public function register_admin_menu()
    {
        // Add admin menu items
        add_menu_page(
            'Naya Group Subscription Product',
            'Naya Group Subscription Product',
            'manage_options',
            'naya-group-subscription-product',
            array($this, 'naya_group_subscription_product'),
            'dashicons-admin-generic',
            99
        );
    }

    public function enqueue_scripts()
    {
        // Enqueue scripts and styles
        require(plugin_dir_path(__FILE__) . "includes/custom-css-js-enqueue-script.php");
    }

    public function load_media_files()
    {
        wp_enqueue_media();
    }

    public function naya_group_subscription_product(){

        include(plugin_dir_path(__FILE__) . "includes/naya-group-subscription-product.php");
    }

    public function redirect_to_checkout_if_subscription()
    {
        // Redirect logic
        if (class_exists('WooCommerce') && is_cart()) {
            // Check if the cart contains a subscription product
            foreach (WC()->cart->get_cart() as $cart_item) {
                $product = wc_get_product($cart_item['product_id']);
                if ($product && $product->is_type('subscription')) {
                    wp_safe_redirect(wc_get_checkout_url());
                    exit;
                }
            }
        }
    }

    public function save_extra_checkout_fields($order, $data)
    {
        // Save checkout fields
        require(plugin_dir_path(__FILE__) . "includes/update-order-time-date.php");
    }

    public function custom_price_string($pricestring, $product, $include)
    {
        // Customize price string
        $price_html = wc_price($product->get_price() + $product->get_meta('_subscription_sign_up_fee'));
        return $price_html;
    }

    public function remove_quantity_fields($return, $product)
    {
        return true;
    }

    public function add_meta_on_checkout_order_review_item($name, $item, $cart_item_key)
    {
        // Add meta on checkout order review item
        foreach (WC()->cart->get_cart() as $cart_item) {
            $product_id = $cart_item['product_id'];
            $product = $cart_item['data'];
            ?>
            <style>
                .checkout_page table.shop_table tbody td.product-name {
                    height: 200px;
                }

                .checkout_page .shop_table p {
                    margin: 5px 0px;
                }

                .addition-info {
                    font-size: 14px;
                }

                .first-payment-date {
                    text-align: end;
                }
            </style>
            
            <?php
            // Get the product name (Added compatibility with Woocommerce 3+)
            echo '<div>';
            echo '<div>';

            $product_name = method_exists($product, 'get_name') ? $product->get_name() : $product->post->post_title;
            echo '<strong>' . $product_name . '</strong>';
			
			// print_r(get_post_meta($product_id));
			
            echo '</div>';
            echo '<div class="addition-info">';
            // Custom Field 1
            $custom_field_1 = get_post_meta($product_id, 'frequency', true);
            echo '<p>Frequency : ' . esc_html($custom_field_1) . '</p>';

            // Custom Field 2
            $custom_field_2 = get_post_meta($product_id, 'duration', true);
            echo '<p>Duration : ' . esc_html($custom_field_2) . '</p>';

            // Custom Field 3
            $custom_field_3 = get_post_meta($product_id, 'payment', true);
            echo '<p>Payment : ' . $custom_field_3 . '</p>';

            echo '</div>';
            echo '</div>';
        }
    }

    public function modify_recurring_product_start_date($cart_item_data, $product_id, $variation_id)
    {
        // Modify recurring product start date
        if (wcs_is_subscription($product_id)) {
            // Set the start date to the value stored in $_SESSION['date']
            if (isset($_SESSION['date'])) {
                $start_date = $_SESSION['date'];
            } else {
                // Default to a fallback date if $_SESSION['date'] is not set
                $start_date = date('Y-m-d'); // Fallback to current date
            }

            // Add the start date to cart item data
            $cart_item_data['wcs_recurring_cart_start_date'] = $start_date;
        }

        return $cart_item_data;
    }

    public function register_rest_routes()
    {
        // Register REST API routes
        register_rest_route('nayagroup-custom/v1', '/add-to-cart/', array(
            'methods' => 'POST',
            'callback' => array($this, 'add_to_cart_subscription_api'),
            'permission_callback' => '__return_true',
        ));

        register_rest_route('nayagroup-custom/v1', '/get-package/', array(
            'methods' => 'POST',
            'callback' => array($this, 'get_packages_with_style'),
            'permission_callback' => '__return_true',
        ));

        register_rest_route('nayagroup-custom/v1', '/update-product/', array(
            'methods' => 'POST',
            'callback' => array($this, 'create_or_update_subscription_products'),
            'permission_callback' => '__return_true',
        ));
    }

    public function add_div_after_body_open()
    {
        echo '<style>#loading{display:none;}</style><div id="loading">Loading...</div>';
    }

    public function add_to_cart_subscription_api($request)
    {
        // Callback for adding to cart via REST API
        require(plugin_dir_path(__FILE__) . "includes/add-cart-redirect-checkout-api.php");
    }

    public function custom_fields_woocommerce($fields)
    {
        global $wp_session;
        
        $fields['shipping']['shipping_wooccm12']['value'] = $this->ordertime?$this->ordertime:$_SESSION['time'];
        $fields['shipping']['shipping_wooccm13']['value'] = $this->orderdate?$this->orderdate:$_SESSION['date'];
        $fields['shipping']['shipping_wooccm15']['value'] = $this->orderdate?$this->orderdate:$_SESSION['darceyMessage'];

        return $fields;
    }

    public function get_packages_with_style()
    {
        $nayagroup_styles = get_option('nayagroup_styles');

        foreach($nayagroup_styles as $nayagroup_styleKey => $nayagroup_style){
            foreach($nayagroup_style['packages'] as $nayagroup_packageKey => $nayagroup_package){
                foreach($nayagroup_package['variant'] as $nayagroup_variantKey => $nayagroup_variant){
                    if(isset($nayagroup_variant['image'])){
                        $nayagroup_styles[$nayagroup_styleKey]['packages'][$nayagroup_packageKey]['variant'][$nayagroup_variantKey]['image'] = wp_get_attachment_image_url($nayagroup_variant['image']);
                    }
                }
            }
        }

        // Callback for getting packages with style via REST API
        return $nayagroup_styles;
    }

    public function create_or_update_subscription_products($request)
    {
        // Callback for creating or updating subscription products via REST API
        require(plugin_dir_path(__FILE__) . "includes/create-or-update-subscription-products.php");
    }


    public function get_subscription_period_interval($package)
    {
        switch ($package) {
            case 'Every 7 days':
                return 1;
            case 'Every 14 days':
                return 2;
            case 'Every 28 days':
                return 4;
            default:
                return 0;
        }
    }


    public function get_subscription_length_for_payment($lastsubscription)
    {
        switch ($lastsubscription) {
            case '1 month':
                # code...
                return 4;
                break;

            case '3 months':
                # code...
                return 13;
                break;

            case '6 months':
                # code...
                return 26;
                break;

            case '12 months':
                # code...
                return 52;
                break;
            default:
                return 0;
                # code...
                break;
        }
        return $lastsubscription;
    }


    // Function to update subscription product data
    public function update_subscription_product($product,$package, $product_name, $package_set_price, $package_set, $last_subscription, $subscription_data, $paymentsystems)
    {
        $product->set_name($product_name);
        $product->set_regular_price($package_set_price);
        $product->set_price($package_set_price);
        $product->set_description('Darcey Flowers subscription for ' . $product_name);
        $product->set_status('publish');
        $product->set_manage_stock('no');
        $product->set_stock_status('instock');

        // Update subscription related meta data
        $product->update_meta_data('_subscription', 'yes');
        $product->update_meta_data('_subscription_sign_up_fee', $package_set['initial_price']);
        $product->update_meta_data('_subscription_price', $package_set['price']);

        // Payment Schedule
        if( $paymentsystems == "paypermonth"){
            $product->update_meta_data('_subscription_period_interval', 4);
            $product->update_meta_data('_subscription_period', 'week');
        }else{
            $product->update_meta_data('_subscription_period_interval', $this->get_subscription_period_interval($package));
            $product->update_meta_data('_subscription_period', 'week');
        }

        $product->update_meta_data('_subscription_length', $this->get_subscription_length_for_payment($last_subscription));

        // Delivery Schedule
        $product->update_meta_data('_subscription_trial_period', 'week');
        $product->update_meta_data('_subscription_trial_period_interval', $this->get_subscription_period_interval($package));

        // Update values for week & 
        $product->update_meta_data('delivery_period_interval', $this->get_subscription_period_interval($package));
        $product->update_meta_data('delivery_period', 'week');

        // Custom Fields for Checkout
        $product->update_meta_data('frequency', $package);
        $product->update_meta_data('duration', $last_subscription);
        $product->update_meta_data('payment', $paymentsystems == "paypermonth" ? 'Pre-paid Monthly' : 'Pay as You Go');

        // Set product image
        if (!empty($package_set['image'])) {
            $image_id = $package_set['image'];
            if ($image_id) {
                $product->set_image_id($image_id);
            }
        }
    }

    // Function to add product variation
    public function add_product_variation($product, $package_set)
    {
        $product_id = $product->get_id();

        // Prepare package set variants and their images
        foreach ($package_set['variant'] as $variantKey => $variantValue) {
            $variant_name = $variantValue['name'];

            update_post_meta($product_id, 'color_'.$variantKey.'_name', $variant_name);

            if(isset($variantValue['image'])){
                update_post_meta($product_id, 'color_'.$variantKey.'_image', $variantValue['image']);
            }

        }
    }
    
    // Function to create subscription product
    public function create_subscription_product($product_name, $sku,$package, $package_set_price, $package_set,$last_subscription, $paymentsystems)
    {
        $product = new WC_Product_Simple();

        // Set general product data

        $product->set_name($product_name);
        $product->set_regular_price($package_set_price);
        $product->set_price($package_set_price);
        $product->set_description('Darcey Flowers subscription for ' . $product_name);
        $product->set_status('publish');
        $product->set_manage_stock('no');
        $product->set_stock_status('instock');
        // $product->set_type('subscription');

        $product_id = $product->get_id();

        update_post_meta($product_id, '_subscription', 'yes');
        // Payment for signup
        update_post_meta($product_id, '_subscription_sign_up_fee', $package_set['initial_price']);
        update_post_meta($product_id, '_subscription_price', $package_set['price']);

        // // Payment Schedule
        // update_post_meta($product_id, '_subscription_period_interval', get_subscription_period_interval($package));
        // update_post_meta($product_id, '_subscription_period', 'week');

        if( $paymentsystems == "paypermonth"){
            $product->update_meta_data('_subscription_period_interval', 4);
            $product->update_meta_data('_subscription_period', 'week');
        }else{
            $product->update_meta_data('_subscription_period_interval', $this->get_subscription_period_interval($package));
            $product->update_meta_data('_subscription_period', 'week');
        }

        update_post_meta($product_id, '_subscription_length', get_subscription_length_for_payment($last_subscription));

        // Delivery Schedule
        update_post_meta($product_id, '_subscription_trial_period', 'week');
        update_post_meta($product_id, '_subscription_trial_period_interval', get_subscription_period_interval($package));

        // Update values for week & 
        $product->update_meta_data('delivery_period_interval', $this->get_subscription_period_interval($package));
        $product->update_meta_data('delivery_period', 'week');

        update_post_meta($product_id, 'frequency', $package);
        update_post_meta($product_id, 'duration', $last_subscription);
        update_post_meta($product_id, 'payment', $paymentsystems == "paypermonth" ? 'Pre-paid Monthly' : 'Pay as You Go');

        set_post_thumbnail($product_id, $package_set['image']);
        $product->set_sku($sku);

        // Save the product
        $product->save();
    }

    public function custom_override_checkout_billing_template($template, $template_name, $template_path){
        // Callback for adding to cart via REST API
            if (is_checkout() && $this->is_cart_contains_subscription()) {
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
                    $template = plugin_dir_path(__FILE__) . 'woocommerce/checkout/recurring-totals.php';
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

    }

    public function is_cart_contains_subscription(){
        if (class_exists('WooCommerce')) {
            // Loop through each item in the cart
            foreach (WC()->cart->get_cart() as $cart_item) {
                // Check if the product is a subscription product
                $product = wc_get_product($cart_item['product_id']);
                if ($product && $product->is_type('subscription')) {
                    return true;
                }
            }
        }
    
        return false;    
    }
    
    public function wcs_method_label($order_total_html, $cart){

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

    public function create_order_on_payment_complete($order_id) {
        // Get the order object
        $order = wc_get_order($order_id);
        
        // Check if the order is already created
        if (!$order) {
            return;
        }
        $product_id = 0;
        // Loop through existing order items and add them to the new order
        foreach ($order->get_items() as $item_id => $item_data) {
            $product_id = $item_data->get_product_id();   
        }

        $subscription_id = "";
        $subscription_status = "";
        $parent_subscription_id = "";
        $subscriptions = "";
        if (wcs_order_contains_subscription($order)) {
            // Get subscription details
            $subscriptions = wcs_get_subscriptions_for_order($order);
            
            // Loop through subscriptions
            foreach ($subscriptions as $subscription) {
                // Get subscription ID
                $subscription_id = $subscription->get_id();
                
                $parent_subscription_id = $subscription->get_parent_id();

                
            }
        }

        // echo "-------------------------------";
        // print_r($subscriptions);
        // echo "-------------------------------";
        // print_r($subscription_status);
        // echo "-------------------------------";
        // print_r($subscription_id);
        // echo "-------------------------------";
        // print_r($parent_subscription_id);
        // echo "-------------------------------";
        // die();


        // Get order meta data
        $order_meta = get_post_meta($order_id);
        $delivery_date = isset($order_meta['_shipping_wooccm12'][0]) ? $order_meta['_shipping_wooccm12'][0] : '';
        $delivery_time = isset($order_meta['_shipping_wooccm13'][0]) ? $order_meta['_shipping_wooccm13'][0] : '';
        $_shipping_wooccm26 = isset($order_meta['_shipping_wooccm25'][0]) ? $order_meta['_shipping_wooccm25'][0] : '';

        $product_meta = get_post_meta($product_id); 

        // $customer_id = $order->get_user_id();
        $delivery_period_interval = isset($product_meta['delivery_period_interval'][0]) ? $product_meta['delivery_period_interval'][0] : '';

        // Check if product payment is Pre-paid Monthly and delivery should be every week or twice in a month
        if ($delivery_date && $delivery_time && isset($product_meta['payment'][0]) && $product_meta['payment'][0] === "Pre-paid Monthly" && ($delivery_period_interval == 1 || $delivery_period_interval == 2)) {

            if ($delivery_period_interval == 1) {
                $this->create_new_order($delivery_date, 1, $order, $delivery_time, $_shipping_wooccm26, $product_id, $subscription_id, $parent_subscription_id);
                $this->create_new_order($delivery_date, 2, $order, $delivery_time, $_shipping_wooccm26, $product_id, $subscription_id, $parent_subscription_id);
                $this->create_new_order($delivery_date, 3, $order, $delivery_time, $_shipping_wooccm26, $product_id, $subscription_id, $parent_subscription_id);
            }

            if ($delivery_period_interval == 2) {
                $this->create_new_order($delivery_date, 2, $order, $delivery_time, $_shipping_wooccm26, $product_id, $subscription_id, $parent_subscription_id);
            }

           
        }
    }

    public function create_new_order($delivery_date, $i, $order, $delivery_time, $_shipping_wooccm26, $product_id, $subscription_id, $parent_subscription_id){

        // Get the subscription object
        $subscription = wcs_get_subscription($subscription_id);

        // Create a renewal order for the subscription
        $new_order = wcs_create_renewal_order($subscription);

        $new_delivery_date = date('d/m/Y', strtotime(str_replace('/', '-', $delivery_date) . ' +' . $i . ' weeks'));

        // // set user id
        $customer_id = $order->get_user_id();
        $new_order->set_customer_id($customer_id);

        // Set billing details for the new order based on an existing order
        $new_order->set_billing_first_name($order->get_billing_first_name());
        $new_order->set_billing_last_name($order->get_billing_last_name());
        $new_order->set_billing_company($order->get_billing_company());
        $new_order->set_billing_address_1($order->get_billing_address_1());
        $new_order->set_billing_address_2($order->get_billing_address_2());
        $new_order->set_billing_city($order->get_billing_city());
        $new_order->set_billing_state($order->get_billing_state());
        $new_order->set_billing_postcode($order->get_billing_postcode());
        $new_order->set_billing_country($order->get_billing_country());
        $new_order->set_billing_email($order->get_billing_email());
        $new_order->set_billing_phone($order->get_billing_phone());

        // Similarly, you can set shipping details if required
        $new_order->set_shipping_first_name($order->get_shipping_first_name());
        $new_order->set_shipping_last_name($order->get_shipping_last_name());
        $new_order->set_shipping_company($order->get_shipping_company());
        $new_order->set_shipping_address_1($order->get_shipping_address_1());
        $new_order->set_shipping_address_2($order->get_shipping_address_2());
        $new_order->set_shipping_city($order->get_shipping_city());
        $new_order->set_shipping_state($order->get_shipping_state());
        $new_order->set_shipping_postcode($order->get_shipping_postcode());
        $new_order->set_shipping_country($order->get_shipping_country());

        

        // Set custom shipping and payment data
        update_post_meta($new_order->get_id(), '_shipping_wooccm12', $new_delivery_date);
        update_post_meta($new_order->get_id(), '_shipping_wooccm13', $delivery_time);
        update_post_meta($new_order->get_id(), '_shipping_wooccm25', $_shipping_wooccm26);

        $new_order->set_total(0.1); 
        $new_order->save();
        $new_order->update_status( 'wc-processing' );
    }
}

$nayagroup_custom_plugin = new NayagroupCustomPlugin();
