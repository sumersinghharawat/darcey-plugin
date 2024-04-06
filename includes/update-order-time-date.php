<?php

// Update Order Date Time Fields
if (isset($_SESSION['date'])) {
    $order->update_meta_data('_shipping_wooccm12', $_SESSION['date']);
}
if (isset($_SESSION['time'])) {
    $order->update_meta_data('_shipping_wooccm13', $_SESSION['time']);
}
if (isset($_SESSION['select-color'])) {
    $order->update_meta_data('_select_color', $_SESSION['select-color']);
}
