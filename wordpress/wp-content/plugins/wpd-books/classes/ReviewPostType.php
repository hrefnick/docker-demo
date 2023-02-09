<?php

namespace BookPlugin;

class ReviewPostType extends Singleton
{

    const POST_TYPE = 'review';

    // redeclare the static instance to make it unique to this class.
    protected static $instance;

    protected function __construct()
    {
        add_action('init', array($this, 'registerReviewPostType'));

        add_filter('the_content', array($this, 'reviewContentTemplate'), 1);
    }

    public function registerReviewPostType(){
            $labels = array(
                'name'                  => _x( 'Reviews', 'Post Type General Name', TEXT_DOMAIN ),
                'singular_name'         => _x( 'Review', 'Post Type Singular Name', TEXT_DOMAIN ),
                'menu_name'             => __( 'Reviews', TEXT_DOMAIN ),
                'name_admin_bar'        => __( 'Review', TEXT_DOMAIN ),
                'archives'              => __( 'Reviews Archives', TEXT_DOMAIN ),
                'attributes'            => __( 'Review Attributes', TEXT_DOMAIN ),
                'parent_item_colon'     => __( 'Parent Item:', TEXT_DOMAIN ),
                'all_items'             => __( 'All Reviews', TEXT_DOMAIN ),
                'add_new_item'          => __( 'Add New Review', TEXT_DOMAIN ),
                'add_new'               => __( 'Add New', TEXT_DOMAIN ),
                'new_item'              => __( 'New Review', TEXT_DOMAIN ),
                'edit_item'             => __( 'Edit Review', TEXT_DOMAIN ),
                'update_item'           => __( 'Update Review', TEXT_DOMAIN ),
                'view_item'             => __( 'View Review', TEXT_DOMAIN ),
                'view_items'            => __( 'View Reviews', TEXT_DOMAIN ),
                'search_items'          => __( 'Search Reviews', TEXT_DOMAIN ),
                'not_found'             => __( 'Not found', TEXT_DOMAIN ),
                'not_found_in_trash'    => __( 'Not found in Trash', TEXT_DOMAIN ),
                'featured_image'        => __( 'Featured Image', TEXT_DOMAIN ),
                'set_featured_image'    => __( 'Set featured image', TEXT_DOMAIN ),
                'remove_featured_image' => __( 'Remove featured image', TEXT_DOMAIN ),
                'use_featured_image'    => __( 'Use as featured image', TEXT_DOMAIN ),
                'insert_into_item'      => __( 'Insert into Review', TEXT_DOMAIN ),
                'uploaded_to_this_item' => __( 'Uploaded to this review', TEXT_DOMAIN ),
                'items_list'            => __( 'Reviews list', TEXT_DOMAIN ),
                'items_list_navigation' => __( 'Reviews list navigation', TEXT_DOMAIN ),
                'filter_items_list'     => __( 'Filter reviews list', TEXT_DOMAIN ),
            );
            $args = array(
                'label'                 => __( 'Review', TEXT_DOMAIN ),
                'description'           => __( '15/10 book', TEXT_DOMAIN ),
                'labels'                => $labels,
                'supports'              => array( 'title', 'editor', 'thumbnail' ),
                'hierarchical'          => false,
                'public'                => true,
                'show_ui'               => true,
                'show_in_menu'          => '',
                'menu_position'         => 99,
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

    public function reviewContentTemplate($content){
        // the_content is ALL content, make specific calls to edit specific posts
        $post = get_post();

        // only do this for recipes
        if($post->post_type == self::POST_TYPE && is_single()){
            $rating = get_post_meta($post->ID, ReviewMeta::RATING, true);
            $recommend = get_post_meta($post->ID, ReviewMeta::RECOMMEND, true);
            $reviewer = get_post_meta($post->ID, ReviewMeta::REVIEWER, true);
            $location = get_post_meta($post->ID, ReviewMeta::LOCATION, true);
            $bookTitle = get_the_title(get_post_meta($post->ID, ReviewMeta::BOOK_TITLE, true));
            $reviewContent = $content;

            $args = array(
                'numberposts' => -1,
                'post_type'=> BookPostType::POST_TYPE,
            );

            $bookName = '';

            $books = get_posts($args);
            foreach ($books as $book) {
                if ($book->post_title === $bookTitle) {
                    $bookName .= '<a href="' . $book->guid . '">' . $book->post_title . '</a><br>';
                }
            }


            $content = '<div class="flex-container">';

                        $content .= '<div>' . the_post_thumbnail('nisarg-full-width') . '</div>';
                        $content .= '<div class="review-info">';
                            if(get_option(BookSettings::SHOW_RATING)){
                                $content .= '<h2>' . $bookName . '</h2> <br><p>' . $reviewer . ' rated it <span class="stars-baby">' .
                                    implode('', array_fill(0, intval($rating), '&starf;')) . '</span><span class="no-stars-baby">' .
                                    implode('', array_fill(0, 5 - intval($rating), '&star;')) . '</span><br>
                                    from '. $location . ' 
                                    </p>';
                            }
                        if(get_option(BookSettings::SHOW_RECOMMEND)){
                            $content .= '<p>Recommendation: ' . $recommend . '!</p>';
                        }

                        $content .= '</div>';

                        $content .= '</div><!-- closes flex -->

                        <div class="review-content">' . $reviewContent . '</div>';

        }

        // regardless of post type, return the content
        return $content;
    }
}