<?php

namespace BookPlugin;

class BookSettings extends Singleton
{
    // these are the keys that will be stored in the database.
    // we will reference these constants to avoid typos.
    const SETTINGS_GROUP = 'books';
    const SHOW_PAGE_LENGTH = 'showPageLength';
    const SHOW_PUBLISHER = 'showPublisher';
    const SHOW_RATING = 'showRating;';
    const SHOW_RECOMMEND = 'showRecommend';
    const SHOW_GENRE = 'showGenre';
    const SHOW_REVIEWER = 'showReviewer';
    const SHOW_LOCATION = 'showLocation';
    const SHOW_BOOK_TITLE = 'showBookTitle';

    // redeclare the static instance to make it unique to this class.
    protected static $instance;

    protected function __construct()
    {
        add_action('admin_init', array($this, 'registerSettings'));
        add_action('admin_init', array($this, 'addFields'));

        add_action('admin_menu', array($this, 'addMenuPages'));
        add_action('admin_menu', array($this, 'addCustomMenu'));
    }

    public function registerSettings()
    {
        register_setting(self::SETTINGS_GROUP, self::SHOW_PAGE_LENGTH);
        register_setting(self::SETTINGS_GROUP, self::SHOW_PUBLISHER);
        register_setting(self::SETTINGS_GROUP, self::SHOW_RATING);
        register_setting(self::SETTINGS_GROUP, self::SHOW_RECOMMEND);
        register_setting(self::SETTINGS_GROUP, self::SHOW_GENRE);
        register_setting(self::SETTINGS_GROUP, self::SHOW_REVIEWER);
        register_setting(self::SETTINGS_GROUP, self::SHOW_LOCATION);
        register_setting(self::SETTINGS_GROUP, self::SHOW_BOOK_TITLE);
    }

    public function addCustomMenu()
    {
        add_menu_page(
            'Books & Reviews', // Page Title
            'Books & Reviews', // Menu Title
            'manage_options', // editor and up can access this menu - Capability
            'books_reviews', // slug
            '',
            'dashicons-book-alt', // DASHICONS
            20
        );

        add_submenu_page(
            'books_reviews', // edit.php?post_type=book or tools.php, edit.php, options.php
            'Add New Book',
            'Add New Book',
            'edit_pages',
            'post-new.php?post_type=book',
            '',
            2
        );

        add_submenu_page(
            'books_reviews', // edit.php?post_type=recipe or tools.php, edit.php, options.php
            'Edit Book Genres',
            'Edit Book Genres',
            'manage_options',
            'edit-tags.php?taxonomy=book-genre',
            '',
            50
        );

        add_submenu_page(
            'books_reviews',
            'All Reviews',
            'All Reviews',
            'edit_pages',
            'edit.php?post_type=review',
            '',
            50
        );

        add_submenu_page(
            'books_reviews', // edit.php?post_type=book or tools.php, edit.php, options.php
            'Add New Review',
            'Add New Review',
            'edit_pages',
            'post-new.php?post_type=review',
            '',
            50
        );

        add_submenu_page(
            'books_reviews', // edit.php?post_type=recipe or tools.php, edit.php, options.php
            'Edit Review Categories',
            'Edit Review Categories',
            'manage_options',
            'edit-tags.php?taxonomy=review-category',
            '',
            50
        );



        add_submenu_page(
        'books_reviews', // edit.php?post_type=book or tools.php, edit.php, options.php
        'Book and Review Plugin Settings',
        'Settings',
        'manage_options',
        'book_review_settings',
        array($this, 'settingsPage'),
        99
    );
    }

    public function addMenuPages()
    {
        add_menu_page(
            'Sample Menu Page Title', // Page Title
            'Sample Menu', // Menu Title
            'edit_pages', // editor and up can access this menu - Capability
            'sample_menu_page', // slug
            function () {
                echo "THIS IS THE PAGE CONTENT.";
            },
            'dashicons-nametag', // DASHICONS
            25
        );



        // two options for debugging
        //die('<pre>' . print_r($GLOBALS['menu'], true) . '</pre>');
        //die('<pre>' . print_r($GLOBALS['submenu'], true) . '</pre>');

    }

        public function settingsPage(){
            ?>
            <div class="wrap">
                <h1>Book Settings</h1>
                <p>Configure features of this plugin.</p>
                <form method="post" action="options.php">
                    <!-- this needs to match what is in register_setting() -->
                    <?php settings_fields(self::SETTINGS_GROUP) ?>

                    <!-- this needs to match page defined in add_settings_section() -->
                    <?php do_settings_sections('book') ?>
                    <?php do_settings_sections('review') ?>

                    <?php submit_button('Save Changes') ?>
                </form>
            </div>
            <?php
        }

        public function addFields(){
            add_settings_section(
                'book_general',
                'General Book Settings',
                function(){},// more important if you are adding to an existing settings page
                'book' // or 'general' or 'writing' if you want to add to existing page
            );

            // add fields to the section
            add_settings_field(
                self::SHOW_PAGE_LENGTH,
                'Show Page Length',
                function(){
                    $checked = get_option(self::SHOW_PAGE_LENGTH) ? 'checked' : '';
                    ?>
                    <input type="checkbox" id="<?= self::SHOW_PAGE_LENGTH ?>" name="<?= self::SHOW_PAGE_LENGTH ?>" <?= $checked ?>>
                    <?php
                },
                // these need to match the add_section() function from above!
                'book',
                'book_general',
            );

            add_settings_field(
                self::SHOW_PUBLISHER,
                'Show Publisher',
                function(){
                    $checked = get_option(self::SHOW_PUBLISHER) ? 'checked' : '';
                    ?>
                    <input type="checkbox" id="<?= self::SHOW_PUBLISHER ?>" name="<?= self::SHOW_PUBLISHER ?>" <?= $checked ?>>
                    <?php
                },
                // these need to match the add_section() function from above!
                'book',
                'book_general',
            );

            add_settings_field(
                self::SHOW_GENRE,
                'Show Genre',
                function(){
                    $checked = get_option(self::SHOW_GENRE) ? 'checked' : '';
                    ?>
                    <input type="checkbox" id="<?= self::SHOW_GENRE ?>" name="<?= self::SHOW_GENRE ?>" <?= $checked ?>>
                    <?php
                },
                // these need to match the add_section() function from above!
                'book',
                'book_general',
            );


            add_settings_section(
                'review_general',
                'General Review Settings',
                function(){},// more important if you are adding to an existing settings page
                'review' // or 'general' or 'writing' if you want to add to existing page
            );

            add_settings_field(
                self::SHOW_RATING,
                'Show Rating',
                function(){
                    $checked = get_option(self::SHOW_RATING) ? 'checked' : '';
                    ?>
                    <input type="checkbox" id="<?= self::SHOW_RATING ?>" name="<?= self::SHOW_RATING ?>" <?= $checked ?>>
                    <?php
                },
                // these need to match the add_section() function from above!
                'review',
                'review_general',
            );

            add_settings_field(
                self::SHOW_RECOMMEND,
                'Show Recommendation',
                function(){
                    $checked = get_option(self::SHOW_RECOMMEND) ? 'checked' : '';
                    ?>
                    <input type="checkbox" id="<?= self::SHOW_RECOMMEND ?>" name="<?= self::SHOW_RECOMMEND ?>" <?= $checked ?>>
                    <?php
                },
                // these need to match the add_section() function from above!
                'review',
                'review_general',
            );

            add_settings_field(
                self::SHOW_REVIEWER,
                'Show Reviewer',
                function(){
                    $checked = get_option(self::SHOW_REVIEWER) ? 'checked' : '';
                    ?>
                    <input type="checkbox" id="<?= self::SHOW_REVIEWER ?>" name="<?= self::SHOW_REVIEWER ?>" <?= $checked ?>>
                    <?php
                },
                // these need to match the add_section() function from above!
                'review',
                'review_general',
            );

            add_settings_field(
                self::SHOW_LOCATION,
                'Show Location',
                function(){
                    $checked = get_option(self::SHOW_LOCATION) ? 'checked' : '';
                    ?>
                    <input type="checkbox" id="<?= self::SHOW_LOCATION ?>" name="<?= self::SHOW_LOCATION ?>" <?= $checked ?>>
                    <?php
                },
                // these need to match the add_section() function from above!
                'review',
                'review_general',
            );

            add_settings_field(
                self::SHOW_BOOK_TITLE,
                'Show Book Title',
                function(){
                    $checked = get_option(self::SHOW_BOOK_TITLE) ? 'checked' : '';
                    ?>
                    <input type="checkbox" id="<?= self::SHOW_BOOK_TITLE ?>" name="<?= self::SHOW_BOOK_TITLE ?>" <?= $checked ?>>
                    <?php
                },
                // these need to match the add_section() function from above!
                'review',
                'review_general',
            );
        }

}