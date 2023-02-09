<?php
/**
 * Plugin Name: Random Review!
 * Description: Displays a random review!
 * Verision: 1.0.0
 * Author: Nick Ryan
 * Text Domain: random-review
 */

namespace BookPlugin;
class RandomReview {
    // private static attribute to hold the single instance
    private static $instance;

    // private constructor
    private function __construct(){
        add_shortcode('randomReview', array($this, 'randomReviewShortcode'));
    }

    // prevent cloning (PHP Specific)
    private function __clone(){}

    public static function getInstance(){
        if(self::$instance == null){
            self::$instance = new static();
        }

        return self::$instance;
    }

    // any actions/filters/etc. methods need to be public
    public function randomreviewShortcode($attributes){

        // merge the defaults with the ones provided by the post
        $a = shortcode_atts(array(
            'review_title' => '',
            'review_author' => '',
            'review_location' => '',
            'review_book_url' => '',
            'review_book_title' => '',
            'review_name' => '',
        ), $attributes);

        $reviews = get_posts(array(
            'post_type'=>'review',
            'orderby'=>'rand',
            'posts_per_page'=>'1')
        );

        foreach($reviews as $review){
            $title = get_the_title($review->ID);
            if($a['review_title'] != ''){
                $title = $a['review_title'];
            }

            $author = get_post_meta($review->ID, ReviewMeta::REVIEWER, true);
            if($a['review_author'] != ''){
                $author = $a['review_author'];
            }

            $reviewLink = get_the_guid($review->ID);
            if($a['review_book_url'] != ''){
                $reviewLink = $a['review_url'];
            }

            $location = get_post_meta($review->ID, ReviewMeta::LOCATION, true);
            if($a['review_location'] != ''){
                $location = $a['review_location'];
            }

            $bookTitle = get_post_meta($review->ID, ReviewMeta::BOOK_TITLE, true);
            if($a['review_book_title'] != ''){
                $bookTitle = $a['review_book_title'];
            }

            $rating = get_post_meta($review->ID, ReviewMeta::RATING, true);

            $reviewName = 'Random Review';
            if($a['review_name'] != ''){
                $reviewName = $a['review_name'];
            }
        }


        return "<hr>
                <div>
                    <h2 class='py-2'>" . $reviewName . "</h2>
                    <h3><a href='" . $reviewLink . "'>
                       " . $title . "</a>
                    <br><span class='stars-baby'>" . implode('', array_fill(0, intval($rating), '&starf;')) . "</span><span class='no-stars-baby'>" . implode('', array_fill(0, 5 - intval($rating), '&star;')) . "</span></h2>
</h3>
                       
                       <p>" . get_the_title($bookTitle) . " Review<br>
                       " . $author . "<br>
                       " . $location . "                       
                    </p>
                </div>
                <hr>";
    }

}

// instantiate the object (call the constructor)
RandomReview::getInstance();