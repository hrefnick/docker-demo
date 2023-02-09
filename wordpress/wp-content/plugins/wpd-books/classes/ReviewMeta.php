<?php

namespace BookPlugin;

class ReviewMeta extends Singleton
{
    // these are the keys that will be stored in the database.
    // we will reference these constants to avoid typos.
    const RATING = 'rating';
    const RECOMMEND = 'recommend';
    const REVIEWER = 'reviewer';
    const LOCATION = 'location';
    const BOOK_TITLE = 'bookTitle';

    // redeclare the static instance to make it unique to this class.
    protected static $instance;

    protected function __construct()
    {
        add_action('admin_init', array($this, 'registerMetaBoxes'));
        add_action('save_post_' . ReviewPostType::POST_TYPE, array($this, 'saveReviewMeta'));
    }

    public function registerMetaBoxes()
    {
        add_meta_box('review_information_meta', 'Information',
                        array($this, 'informationMetaBox'), ReviewPostType::POST_TYPE,
                            'normal'); // normal, side, or advanced
    }

    public function informationMetaBox()
    {
        $post = get_post();
        $rating = get_post_meta($post->ID, self::RATING, true);
        $recommend = get_post_meta($post->ID, self::RECOMMEND, true);
        $reviewer = get_post_meta($post->ID, self::REVIEWER, true);
        $location = get_post_meta($post->ID, self::LOCATION, true);
        $bookTitle = get_post_meta($post->ID, self::BOOK_TITLE, true);
        ?>
            <?php
                if(get_option(BookSettings::SHOW_RATING)):
            ?>
                    <p>
                        <label for="rating">Rating: </label>
                        <select name="rating" id="rating" value="<?= $rating ?>">
                            <option value="1" <?= $rating === '1' ? 'selected': ''; ?>>&starf;&star;&star;&star;&star;</option>
                            <option value="2" <?= $rating === '2' ? 'selected': ''; ?>>&starf;&starf;&star;&star;&star;</option>
                            <option value="3" <?= $rating === '3' ? 'selected': ''; ?>>&starf;&starf;&starf;&star;&star;</option>
                            <option value="4" <?= $rating === '4' ? 'selected': ''; ?>>&starf;&starf;&starf;&starf;&star;</option>
                            <option value="5" <?= $rating === '5' ? 'selected': ''; ?>>&starf;&starf;&starf;&starf;&starf;</option>
                        </select>
                    </p>
                    <?php endif; ?>

                    <?php
                    if(get_option(BookSettings::SHOW_RECOMMEND)):
                        ?>
                    <p>
                        <label for="recommend">Would you recommend? </label><br>
                        <input type="radio" name="recommend" id="mustRead" value="Must Read" <?= $recommend === 'Must Read' ? 'checked': ''; ?>>
                        <label for="mustRead">Must Read</label><br>
                        <input type="radio" name="recommend" id="readIt" value="Read It" <?= $recommend === 'Read It' ? 'checked': ''; ?>>
                        <label for="readIt">Read</label><br>
                        <input type="radio" name="recommend" id="dontRead" value="Do Not Read" <?= $recommend === 'Do No Read' ? 'checked': ''; ?>>
                        <label for="dontRead">Do Not Read</label><br>
                    </p>
                    <?php endif; ?>

        <?php
        if(get_option(BookSettings::SHOW_REVIEWER)):
        ?>
        <p>
            <label for="reviewer">Reviewer's Name: </label>
            <input type="text" name="reviewer" id="reviewer" value="<?= $reviewer ?>">
        </p>
        <?php endif; ?>

        <?php
        if(get_option(BookSettings::SHOW_LOCATION)):
            ?>
            <p>
                <label for="location">Location: </label>
                <input type="text" name="location" id="location" value="<?= $location ?>">
            </p>
        <?php endif; ?>

        <?php

        $books = array(
            'post_type'=> BookPostType::POST_TYPE,
            'name' => self::BOOK_TITLE,
            'id' => self::BOOK_TITLE,
            'show_option_none' => 'Select A Book',
            'selected' => $bookTitle,
            'value' => 'post_title',
        );

        if(get_option(BookSettings::SHOW_BOOK_TITLE)):
             ?>
            <p>
                <label for="bookTitle">Book Title: </label>
        <?php
            wp_dropdown_pages($books);
            ?>
            </p>
        <?php endif; ?>

        <?php
    }

    public function saveReviewMeta()
    {
        // get the current post
        $post = get_post();

        // get and save each field individually.
        if(isset($_POST['rating'])){
            // validate/sanitize
            $rating = sanitize_text_field($_POST['rating']);

            // insert/update database
            update_post_meta($post->ID, self::RATING, $rating);
        }

        if(isset($_POST['recommend'])){
            // validate/sanitize
            $recommend = sanitize_text_field($_POST['recommend']);

            // insert/update database
            update_post_meta($post->ID, self::RECOMMEND, $recommend);
        }

        if(isset($_POST['reviewer'])){
            // validate/sanitize
            $reviewer = sanitize_text_field($_POST['reviewer']);

            // insert/update database
            update_post_meta($post->ID, self::REVIEWER, $reviewer);
        }

        if(isset($_POST['location'])){
            // validate/sanitize
            $location = sanitize_text_field($_POST['location']);

            // insert/update database
            update_post_meta($post->ID, self::LOCATION, $location);
        }

        if(isset($_POST['bookTitle'])){
            // validate/sanitize
            // insert/update database
            update_post_meta($post->ID, self::BOOK_TITLE, $_POST['bookTitle']);
        }

    }
}