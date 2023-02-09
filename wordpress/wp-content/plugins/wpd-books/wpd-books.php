<?php
/**
 * @wordpress-plugin
 * Plugin Name: Kingly Books & Reviews
 * Description: A plugin for creating and displaying books and reviews!
 * Author: Nick Ryan
 * Version: 1.0.0
 * Text Domain: wpd-books
 */


namespace BookPlugin;

const TEXT_DOMAIN = 'wpd-books';

// include class files
require_once __DIR__ . '/classes/Singleton.php';
require_once __DIR__ . '/classes/BookPostType.php';
require_once __DIR__ . '/classes/ReviewPostType.php';
require_once __DIR__ . '/classes/BookCategoryTaxonomy.php';
require_once __DIR__ . '/classes/ReviewCategoryTaxonomy.php';
require_once __DIR__ . '/classes/BookMeta.php';
require_once __DIR__ . '/classes/ReviewMeta.php';
require_once __DIR__ . '/classes/BookSettings.php';

// instantiate classes
BookPostType::getInstance();
ReviewPostType::getInstance();
BookCategoryTaxonomy::getInstance();
ReviewCategoryTaxonomy::getInstance();
BookMeta::getInstance();
ReviewMeta::getInstance();
BookSettings::getInstance();

// this will flush the permalink cache when the plugin is activated
function activate_plugin(){
    // make sure the post type is registered before the cache is cleared.
    $bookPostType = BookPostType::getInstance();
    $bookPostType->registerBookPostType();
    $reviewPostType = ReviewPostType::getInstance();
    $reviewPostType->registerReviewPostType();

    // you also use chaining
    BookCategoryTaxonomy::getInstance()->registerBookCategoryTaxonomy();
    ReviewCategoryTaxonomy::getInstance()->registerReviewCategoryTaxonomy();

    flush_rewrite_rules();

    // sets default options
    // add_option adds if it doesn't exist already
    // update_option to add/update existing options
    add_option(BookSettings::SHOW_PAGE_LENGTH, 1);
    add_option(BookSettings::SHOW_PUBLISHER, 1);
    add_option(BookSettings::SHOW_RATING, 1);
    add_option(BookSettings::SHOW_RECOMMEND, 1);
}

register_activation_hook(__FILE__, 'BookPlugin\activate_plugin');