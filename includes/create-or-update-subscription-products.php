<?php


    $subscription_data = $request->get_json_params();

    if (class_exists('WooCommerce')) {
        // Load WooCommerce classes
        include_once WC_ABSPATH . 'includes/class-wc-product-simple.php';

        $packages = array(
            'Every 7 days',
            'Every 14 days',
            'Every 28 days',
        );

        $last_subscriptions = array(
            '1 month',
            '3 months',
            '6 months',
            '12 months',
            'ongoing',
        );

        $paymentsystems = array(
            'payperdelivery',
            'paypermonth'
        );

        $style_size_set = [
            0 => [
                ["name" => "Exquisite Selection"],
                [
                    "packages" => [
                        0 => [
                            "name" => "Premium Impressive",
                            "price" => 1,
                            "initial_price" => 0
                        ],
                        1 => [
                            "name" => "Premium Superb",
                            "price" => 399,
                            "initial_price" => 300,
                        ],
                        2 => [
                            "name" => "Premium Grandeur",
                            "price" => 699,
                            "initial_price" => 500
                        ]
                    ]
                ]
            ],
            1 => [
                "name" => "Tulip Harmony",
                "packages" => [
                    0 => [
                        "name" => "Premium Impressive",
                        "price" => 599,
                        "initial_price" => 200,
                    ],
                    1 => [
                        "name" => "Premium Grandeur",
                        "price" => 1799,
                        "initial_price" => 700,
                    ]
                ]
            ],
            2 => [
                "name" => "Hydrangea Delight Club",
                "packages" => [
                    0 => [
                        "name" => "Premium Impressive",
                        "price" => 299,
                        "initial_price" => 300,
                    ],
                    1 => [
                        "name" => "Premium Superb",
                        "price" => 599,
                        "initial_price" => 500
                    ],
                    2 => [
                        "name" => "Premium Grandeur",
                        "price" => 799,
                        "initial_price" => 600
                    ]
                ]
            ],
            3 => [
                "name" => "Delphinium Dreams",
                "packages" => [
                    0 => [
                        "name" => "Premium Impressive",
                        "price" => 299,
                        "initial_price" => 500
                    ],
                    1 => [
                        "name" => "Premium Superb",
                        "price" => 699,
                        "initial_price" => 400
                    ],
                    2 => [
                        "name" => "Premium Grandeur",
                        "price" => 1299,
                        "initial_price" => 500
                    ]
                ]
            ],
            4 => [
                "name" => "Julieta Rose Subscription",
                "packages" => [
                    0 => [
                        "name" => "Premium Impressive",
                        "price" => 299,
                        "initial_price" => 200
                    ],
                    1 => [
                        "name" => "Premium Superb",
                        "price" => 599,
                        "initial_price" => 200
                    ],
                    2 => [
                        "name" => "Premium Grandeur",
                        "price" => 1399,
                        "initial_price" => 500
                    ]
                ]
            ]
        ];

        $style_size_data = get_option('nayagroup_styles');

        if (isset($style_size_data) && !empty(get_option('nayagroup_styles'))) {
            $style_size_set = get_option('nayagroup_styles');
        }
        foreach ($style_size_set as $style) {
            foreach ($style['packages'] as $package_set) {
                foreach ($packages as $package) {
                    foreach ($last_subscriptions as $last_subscription) {
                        foreach ($paymentsystems as $paymentsystem) {

                            $newProductType = 'subscription';

                            // Generate a unique SKU for each product
                            $product_name = "{$style['name']} - {$package_set['name']} - {$package} - {$last_subscription} - $paymentsystem";

                            $sku = strtolower(str_replace(' ', '_', $product_name));

                            $product_name = "{$style['name']} - {$package_set['name']}";
                            
                            // Check if the product already exists
                            $existing_product_id = wc_get_product_id_by_sku($sku);

                            // Set package set price considering payment system
                            $package_set_price = $package_set['price'];
                            if ($paymentsystem == 'paypermonth') {
                                if ($package == 'Every 7 days') {
                                    $package_set_price = $package_set_price * 4;
                                } elseif ($package == 'Every 14 days') {
                                    $package_set_price = $package_set_price * 2;
                                }
                            }



                            if ($existing_product_id) {
                                // Product exists, update it

                                wp_set_object_terms($existing_product_id, $newProductType, 'product_type', false);

                                $product = wc_get_product($existing_product_id);

                                // Update product data
                                $this->update_subscription_product($product, $package,$product_name, $package_set_price, $package_set, $last_subscription, $subscription_data, $paymentsystem);

                                $this->add_product_variation($product, $package_set);

                                // Save the product
                                $product->save();
                            } else {
                                // Product doesn't exist, create it
                                $product = $this->create_subscription_product($product_name, $sku,$package, $package_set_price, $package_set,$last_subscription, $paymentsystem);

                                // Add variation
                                $this->add_product_variation($product, $package_set);

                                // Save the product
                                $product->save();
                            }
                        }
                    }
                }
            }
        }

        $response = array(
            'status' => 'success',
            'message' => 'Product Updated',
            'data' => "", //wc_get_checkout_url(),
        );

        return new WP_REST_Response($response, 200);
    }

