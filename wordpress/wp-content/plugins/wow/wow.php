<?php
/**
 * Plugin Name: Five For Five
 * Description: Displays five adverts for under $5.
 * Author: Nick Ryan
 * Text Domain: wow
 * Version: 1.0.0
 */

// include widget file
// require_once "FirstWidget.php won't work
require_once plugin_dir_path(__FILE__) . "/FiveForFive.php";
add_action('widgets_init', function(){
    register_widget('FiveForFive');
});