<?php

class FiveForFive extends WP_Widget
{

    /**
     * Sets up the widgets name etc
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'nr-five-for-five',
            'description' => 'Displays five adverts under $5.',
        );
        parent::__construct('nr_five_for_five', 'Five for Five', $widget_ops);
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

        echo "<style> .adprice {color: green;
                                font-weight: bold;}
                      .fiveholder {border-bottom: 1px solid grey;
                                    margin-bottom: 10px;}
                      .adauth {color: black;}
                      .adtitle {font-size: 18px;}
                      .adtitle a:hover {color: black;}</style>";

        // demo 2
        $queryObj = new WP_Query(
            array(
                'post_type' => 'advert',
                'posts_per_page' => 5,
                //'meta_key' => 'adverts_price',
                //'meta_value' => 5, // meta_value compares string, meta_value_num compares numbers but is out of date
                //'meta_compare' => '<',
                'meta_query' => array(
                        array(
                                'key' => 'adverts_price',
                                'value' => 5,
                                'type' => 'numeric',
                                'compare' => '<',
                        )
                )
            )
        );

        // the loop
        if ($queryObj->have_posts()) {
            echo "<ul>";


            while ($queryObj->have_posts()) {
                $queryObj->the_post(); // retrieves the post from the database into "current post"
                // uses the query you wrote instead of the one from the page load
                echo "<div class='fiveholder'>";
                echo "<li class='adtitle'><a href='" . get_the_permalink() . "'>" . get_the_title() . "</a></li>";
               echo "<li>Listed Price: <span class='adprice'>$" . get_post_meta(get_the_ID(), 'adverts_price', true ) . "</span></li>";
               echo "<li>Contact: <span class='adauth'>" . get_post_meta(get_the_ID(), 'adverts_person', true ) . "</span></li>";
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
        $title = $instance['title'] ?? 'Five for $5';

        ?>
        <p>
            <label for="<?= $this->get_field_id('title') ?>">Title</label>
            <input type="text"
                   id="<?= $this->get_field_id('title') ?>"
                   name="<?= $this->get_field_name('title') ?>"
                   class="widefat"
                   placeholder="Enter Custom Title"
                   value="<?= $title ?>">
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

        // return what we want saved in the database
        return $instance;
    }
}