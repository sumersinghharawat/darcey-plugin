<?php


// Hooks

    $subscription_data = $request->get_json_params();

    if (empty($subscription_data)) {
        return new WP_Error('empty_data', 'No data received.', array('status' => 400));
    }

    if (defined('WC_ABSPATH')) {
        // WC 3.6+ - Cart and other frontend functions are not included for REST requests.
        include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
        include_once WC_ABSPATH . 'includes/wc-notice-functions.php';
        include_once WC_ABSPATH . 'includes/wc-template-hooks.php';
    }

    // Check session
    if (null === WC()->session) {
        $session_class = apply_filters('woocommerce_session_handler', 'WC_Session_Handler');
        WC()->session = new $session_class();
        WC()->session->init();
    }

    if (null === WC()->customer) {
        WC()->customer = new WC_Customer(get_current_user_id(), true);
    }

    // Check Cart
    WC()->cart = new WC_Cart();

    if (WC()->cart->is_empty()) {
        // echo 'Cart is empty.';
    } else {
        // If cart is not empty, empty the cart
        WC()->cart->empty_cart();
        // echo 'Cart has been emptied.';
    }

    //     // _subscription_price =
    //     // _subscription_sign_up_fee =
    //     // _subscription_period_interval = 1 - 6
    //     // _subscription_period = day, week, month, year
    //     // _subscription_length =
    //     // day  -> 0 - 90
    //     // week -> 0 - 52
    //     // month -> 0 - 24
    //     // year -> 0 - 5

    //  $subscription_data;


    // $paymentsystems = array(
    //     'payperdelivery',
    //     'paypermonth'
    // );

    $paymentsystems = $subscription_data['paymentSystem']=="1"?'paypermonth':'payperdelivery';
    

    $productDetails = [$subscription_data['darceyStyle'], $subscription_data['darceySize'], $subscription_data['darceyFrequentDelivery'], $subscription_data['darceyLastSubscription'], $paymentsystems];


    $productDetails = array_map(function ($element) {
        return str_replace(' ', '_', strtolower($element));
    }, $productDetails);

    // Combine elements with underscores
    $productName = implode('_-_', $productDetails);

    $product_id = wc_get_product_id_by_sku($productName);

    if (!$product_id) {
        $response = array(
            'status' => 'success',
            'message' => 'Product not found',
            'data' =>$productName, //wc_get_checkout_url(),
        );

        return new WP_REST_Response($response, 200);
    }


    if (sizeof(WC()->cart->get_cart()) > 0) {
        foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
            $_product = $values['data'];
            if ($_product->get_id() == $product_id)
                $found = true;
        }
        // if product not found, add it
        if (!$found)
            $data = WC()->cart->add_to_cart($product_id);
    } else {
        // if no products in cart, add it
        $data = WC()->cart->add_to_cart($product_id);
    }

    if (!$data) {
        $data = wc_get_notices('error');
    }

    $response = array(
        'status' => 'success',
        'message' => 'Custom API endpoint is working!123',
        'data' => $productDetails, //wc_get_checkout_url(),
    );

    $get_post_meta = get_post_meta($product_id);

    // 	print_r($request['paymentSystem']);

    global $wp_session;

    // 	$wp_session['time'] = $request['darceyDeliveryTime'];
    $_SESSION['time'] = $request['darceyDeliveryTime'];
    $_SESSION['date'] = $request['darceyStartFrom'];
    $_SESSION['paymentsystem'] = $request['paymentSystem'];
    $_SESSION['select-color'] = $request['darceyColor'];
	$_SESSION['msg'] = $request['darceyColor'];

    $this->ordertime = $request['darceyDeliveryTime'];
    $this->ordertime = $request['darceyDeliveryTime'];

    add_filter('woocommerce_checkout_fields', array($this, 'custom_fields_woocommerce'));

    return new WP_REST_Response($response, 200);

