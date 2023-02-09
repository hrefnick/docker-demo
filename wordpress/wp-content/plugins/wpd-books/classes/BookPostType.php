<?php

namespace BookPlugin;

class BookPostType extends Singleton
{

    const POST_TYPE = 'book';

    // redeclare the static instance to make it unique to this class.
    protected static $instance;

    protected function __construct()
    {
        add_action('init', array($this, 'registerBookPostType'));

        add_filter('the_content', array($this, 'bookContentTemplate'), 1);
    }

    public function registerBookPostType(){
            $labels = array(
                'name'                  => _x( 'Books', 'Post Type General Name', TEXT_DOMAIN ),
                'singular_name'         => _x( 'Book', 'Post Type Singular Name', TEXT_DOMAIN ),
                'menu_name'             => __( 'Books', TEXT_DOMAIN ),
                'name_admin_bar'        => __( 'Book', TEXT_DOMAIN ),
                'archives'              => __( 'Books Archives', TEXT_DOMAIN ),
                'attributes'            => __( 'Book Attributes', TEXT_DOMAIN ),
                'parent_item_colon'     => __( 'Parent Item:', TEXT_DOMAIN ),
                'all_items'             => __( 'All Books', TEXT_DOMAIN ),
                'add_new_item'          => __( 'Add New Book', TEXT_DOMAIN ),
                'add_new'               => __( 'Add New', TEXT_DOMAIN ),
                'new_item'              => __( 'New Book', TEXT_DOMAIN ),
                'edit_item'             => __( 'Edit Book', TEXT_DOMAIN ),
                'update_item'           => __( 'Update Book', TEXT_DOMAIN ),
                'view_item'             => __( 'View Book', TEXT_DOMAIN ),
                'view_items'            => __( 'View Books', TEXT_DOMAIN ),
                'search_items'          => __( 'Search Books', TEXT_DOMAIN ),
                'not_found'             => __( 'Not found', TEXT_DOMAIN ),
                'not_found_in_trash'    => __( 'Not found in Trash', TEXT_DOMAIN ),
                'featured_image'        => __( 'Featured Image', TEXT_DOMAIN ),
                'set_featured_image'    => __( 'Set featured image', TEXT_DOMAIN ),
                'remove_featured_image' => __( 'Remove featured image', TEXT_DOMAIN ),
                'use_featured_image'    => __( 'Use as featured image', TEXT_DOMAIN ),
                'insert_into_item'      => __( 'Insert into Book', TEXT_DOMAIN ),
                'uploaded_to_this_item' => __( 'Uploaded to this book', TEXT_DOMAIN ),
                'items_list'            => __( 'Books list', TEXT_DOMAIN ),
                'items_list_navigation' => __( 'Books list navigation', TEXT_DOMAIN ),
                'filter_items_list'     => __( 'Filter books list', TEXT_DOMAIN ),
            );
            $args = array(
                'label'                 => __( 'Book', TEXT_DOMAIN ),
                'description'           => __( 'Story', TEXT_DOMAIN ),
                'labels'                => $labels,
                'supports'              => array( 'title', 'editor', 'thumbnail' ),
                'hierarchical'          => true,
                'public'                => true,
                'show_ui'               => true,
                'show_in_menu'          => 'books_reviews',
                'menu_position'         => 1,
                'show_in_admin_bar'     => true,
                'show_in_nav_menus'     => true,
                'can_export'            => true,
                'has_archive'           => true,
                'exclude_from_search'   => false,
                'publicly_queryable'    => true,
                'capability_type'       => 'page',
                'show_in_rest'          => true,
            );
            register_post_type( self::POST_TYPE, $args);
        } // end registerBookPostType

    public function bookContentTemplate($content){
        // the_content is ALL content, make specific calls to edit specific posts
        $post = get_post();
        $terms = get_the_terms($post->ID, 'book-genre');
        $genres = join(', ', wp_list_pluck($terms, 'name'));

        if($post->post_type == self::POST_TYPE){
            $pageLength = get_post_meta($post->ID, BookMeta::PAGE_LENGTH, true);
            $publisher = get_post_meta($post->ID, BookMeta::PUBLISHER, true);

            $content = '<hr><div>' . $content . '</div><hr>
                        <h3 class="border-bottom py-2">Details</h3>
                        <div><p>';
                        if(get_option(BookSettings::SHOW_PAGE_LENGTH)){
                            $content .= 'Pages: ' . $pageLength . '<br>';
                        }
                        if(get_option(BookSettings::SHOW_PUBLISHER)){
                            $content .= 'Publisher: ' . $publisher . '<br>';
                        }
                        if(get_option(BookSettings::SHOW_GENRE)) {
                            $content .= 'Genre: ' . $genres . '<br>';
                        }
            $content .= '</p>';
            $content .= '<h3>Reviews</h3>';
            $bookTitle = get_the_title($post->ID);


            // wp_dropdown reviews values as books
            $args = array(
                'numberposts' => -1,
                'post_type'=> ReviewPostType::POST_TYPE,
                'meta_query' => array(
                        'key' => 'bookTitle',
                        'value' => $post->ID,
                ),
            );

            $reviews = '';

            $cats = get_posts($args);
                foreach ($cats as $cat) {
                    if (get_the_title($cat->bookTitle) === $bookTitle) {
                        $reviews .= '<a href="' . $cat->guid . '">' . $cat->post_title . '</a><br>';
                    }
                }
                if($reviews === ''){
                    $reviews .= 'There aren\'t any reviews for this book yet.';
                }
                        $content .= '<p>';
                        $content .= $reviews;
                        $content .= '</p>';
                        $content .= '</div>';
        }

        // regardless of post type, return the content
        return $content;
    }
}