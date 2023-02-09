<?php

class RecentBook extends WP_Widget
{

    /**
     * Sets up the widgets name etc
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'nr-recent-book',
            'description' => 'Displays five recent books.',
        );
        parent::__construct('nr_recent_Book', 'Recent Books', $widget_ops);
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */

    public function widget($args, $instance)
    {
// outputs the content of the widget
        echo $args['before_widget'];

        if ($instance['title']) {
            echo $args['before_title'] . "<h2>" . $instance['title'] . "</h2>" . $args['after_title'] . "<hr>";
        }

        $queryObj = new WP_Query(
            array(
                'post_type' => 'book',
                'posts_per_page' => 5,
                'orderby' => 'date',
                'order' => 'DESC',
            )
        );

        $queryObj2 = new WP_Query(
            array(
                'post_type' => 'review',
                'posts_per_page' => 5,
                'orderby' => 'date',
                'order' => 'DESC',
            )
        );


        if ($queryObj->have_posts()) {
            echo "<ul>";


            while ($queryObj->have_posts()) {
                $queryObj->the_post(); // retrieves the post from the database into "current post"
                // uses the query you wrote instead of the one from the page load
                echo "<div>";
                echo "<li><a href='" . get_the_permalink() . "' class='bookLink'>" . get_the_title() . "</a></li>";
              // echo "<li>Title: " . get_post_meta(get_the_ID(), 'name', true ) . "</span></li>";
               //echo "<li>Contact: <span class='adauth'>" . get_post_meta(get_the_ID(), 'adverts_person', true ) . "</span></li>";
               echo "</div>";
            }

            echo "</ul>";
        }

        echo "<h2>" . $instance['secondary'] . "</h2>" . $args['after_title'] . "<hr>";
        if ($queryObj2->have_posts()) {
            echo "<ul>";


            while ($queryObj2->have_posts()) {
                $queryObj2->the_post(); // retrieves the post from the database into "current post"
                // uses the query you wrote instead of the one from the page load
                echo "<div>";
                echo "<li><a href='" . get_the_permalink() . "' class='reviewLink'>" . get_the_title() . "</a></li>";
                // echo "<li>Title: " . get_post_meta(get_the_ID(), 'name', true ) . "</span></li>";
                //echo "<li>Contact: <span class='adauth'>" . get_post_meta(get_the_ID(), 'adverts_person', true ) . "</span></li>";
                echo "</div>";
            }

            echo "</ul>";
        }

        // ALWAYS reset the page query
        wp_reset_query();

        echo $args['after_widget'];
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {
// outputs the options form on admin
        $title = $instance['title'] ?? 'Recent Books';
        $secondary = $instance['secondary'] ?? 'Recent Reviews';

        ?>
        <p>
            <label for="<?= $this->get_field_id('title') ?>">Recent Books</label>
            <input type="text"
                   id="<?= $this->get_field_id('title') ?>"
                   name="<?= $this->get_field_name('title') ?>"
                   class="widefat"
                   placeholder="Recent Books"
                   value="<?= $title ?>">
        </p>
        <p>
            <label for="<?= $this->get_field_id('secondary') ?>">Recent Reviews</label>
            <input type="text"
                   id="<?= $this->get_field_id('secondary') ?>"
                   name="<?= $this->get_field_name('secondary') ?>"
                   class="widefat"
                   placeholder="Recent Reviews"
                   value="<?= $secondary ?>">
        </p>
        <?php
    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     *
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        // processes widget options to be saved
        // create an instance to be saved
        $instance = [];

        // validate/sanitize
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['secondary'] = strip_tags($new_instance['secondary']);

        // return what we want saved in the database
        return $instance;
    }
}