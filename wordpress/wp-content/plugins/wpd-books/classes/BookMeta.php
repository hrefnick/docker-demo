<?php

namespace BookPlugin;

class BookMeta extends Singleton
{
    // these are the keys that will be stored in the database.
    // we will reference these constants to avoid typos.
    const PAGE_LENGTH = 'pageLength';
    const PUBLISHER = 'publisher';
    const GENRE = 'genre';

    // redeclare the static instance to make it unique to this class.
    protected static $instance;

    protected function __construct()
    {
        add_action('admin_init', array($this, 'registerMetaBoxes'));
        add_action('save_post_' . BookPostType::POST_TYPE, array($this, 'saveBookMeta'));
    }

    public function registerMetaBoxes()
    {
        add_meta_box('book_information_meta', 'Information',
                        array($this, 'informationMetaBox'), BookPostType::POST_TYPE,
                            'normal'); // normal, side, or advanced

    }

    public function informationMetaBox()
    {
        $post = get_post();
        $pageLength = get_post_meta($post->ID, self::PAGE_LENGTH, true);
        $publisher = get_post_meta($post->ID, self::PUBLISHER, true);
        ?>
            <?php
                if(get_option(BookSettings::SHOW_PAGE_LENGTH)):
            ?>
            <p>
                <label for="pageLength">Page Length: </label>
                <input type="text" name="pageLength" id="pageLength" value="<?= $pageLength ?>">
            </p>
                <?php endif; ?>

            <?php
                if(get_option(BookSettings::SHOW_PUBLISHER)):
            ?>
            <p>
                <label for="publisher">Publisher: </label>
                <input type="text" name="publisher" id="publisher" value="<?= $publisher ?>">
            </p>
                <?php endif; ?>

            <?php
            if(get_option(BookSettings::SHOW_GENRE)):
                $post = get_post();
                $terms = get_the_terms($post->ID, 'book-genre');
                $genres = join(', ', wp_list_pluck($terms, 'name'));?>
                <p>
                    Genre: <?= $genres ?>
                </p>
            <?php endif; ?>

        <?php
    }

    public function saveBookMeta()
    {
        // get the current post
        $post = get_post();

        // get and save each field individually.
        if(isset($_POST['pageLength'])){
            // validate/sanitize
            $pageLength = sanitize_text_field($_POST['pageLength']);

            // insert/update database
            update_post_meta($post->ID, self::PAGE_LENGTH, $pageLength);
        }

        if(isset($_POST['publisher'])){
            // validate/sanitize
            $publisher = sanitize_text_field($_POST['publisher']);

            // insert/update database
            update_post_meta($post->ID, self::PUBLISHER, $publisher);
        }

        if(isset($_POST['genre'])){
            // validate/sanitize
            $genre = ($_POST['genre']);

            // insert/update database
            update_post_meta($post->ID, self::GENRE, $genre);
        }

    }
}