<?php

$file_version_js = filemtime(plugin_dir_path(__FILE__) . './../js/custom.js');
wp_enqueue_script('custom-js', plugin_dir_url(__FILE__) . './../js/custom.js', array('jquery'), $file_version_js, true);

$file_version_css = filemtime(plugin_dir_path(__FILE__) . './../css/custom.css');
wp_enqueue_style('custom-css', plugins_url('./../css/custom.css', __FILE__), array(), $file_version_css, 'all');


