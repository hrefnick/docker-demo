<?php
/**
 * Plugin Name: Recent Bookss
 * Description: Displays recent books.
 * Author: Nick Ryan
 * Text Domain: wow
 * Version: 1.0.0
 */

// include widget file
// require_once "FirstWidget.php won't work
require_once plugin_dir_path(__FILE__) . "/RecentBook.php";
add_action('widgets_init', function(){
    register_widget('RecentBook');
});