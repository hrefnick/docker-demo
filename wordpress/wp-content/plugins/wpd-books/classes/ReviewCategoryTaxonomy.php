<?php

namespace BookPlugin;

class ReviewCategoryTaxonomy extends Singleton
{

    const TAXONOMY = 'review-category';

    // redeclare the static instance to make it unique to this class.
    protected static $instance;

    protected function __construct()
    {
        add_action('init', array($this, 'registerReviewCategoryTaxonomy'));
    }

    public function registerReviewCategoryTaxonomy(){
// Register Custom Taxonomy
            $labels = array(
                'name'                       => _x( 'Reviews', 'Taxonomy General Name', TEXT_DOMAIN ),
                'singular_name'              => _x( 'Reviews', 'Taxonomy Singular Name', TEXT_DOMAIN ),
                'menu_name'                  => __( 'Categories', TEXT_DOMAIN ),
                'all_items'                  => __( 'All Categories', TEXT_DOMAIN ),
                'parent_item'                => __( 'Parent Category', TEXT_DOMAIN ),
                'parent_item_colon'          => __( 'Parent Category:', TEXT_DOMAIN ),
                'new_item_name'              => __( 'New Category', TEXT_DOMAIN ),
                'add_new_item'               => __( 'Add New Category', TEXT_DOMAIN ),
                'edit_item'                  => __( 'Edit Category', TEXT_DOMAIN ),
                'update_item'                => __( 'Update Category', TEXT_DOMAIN ),
                'view_item'                  => __( 'View Category', TEXT_DOMAIN ),
                'separate_items_with_commas' => __( 'Separate items with commas', TEXT_DOMAIN ),
                'add_or_remove_items'        => __( 'Add or Remove Categories', TEXT_DOMAIN ),
                'choose_from_most_used'      => __( 'Choose from the most used', TEXT_DOMAIN ),
                'popular_items'              => __( 'Popular Categories', TEXT_DOMAIN ),
                'search_items'               => __( 'Search Items', TEXT_DOMAIN ),
                'not_found'                  => __( 'Not Found', TEXT_DOMAIN ),
                'no_terms'                   => __( 'No Categories', TEXT_DOMAIN ),
                'items_list'                 => __( 'Categories List', TEXT_DOMAIN ),
                'items_list_navigation'      => __( 'Categories list navigation', TEXT_DOMAIN ),
            );
            $args = array(
                'labels'                     => $labels,
                'hierarchical'               => true,
                'public'                     => true,
                'show_ui'                    => true,
                'show_admin_column'          => true,
                'show_in_nav_menus'          => true,
                'show_tagcloud'              => true,
                'show_in_rest'               => true,
            );
            register_taxonomy( self::TAXONOMY, array( ReviewPostType::POST_TYPE ), $args );
    }
}