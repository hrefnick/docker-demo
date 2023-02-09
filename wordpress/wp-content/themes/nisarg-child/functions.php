<?php
// prefix functions with the name of your theme/plugin/etc
function grasin_enqueue_styles(){
    // get information about the theme
    $theme = wp_get_theme();

    // dequeue the styles from the parent theme
    wp_dequeue_style('nisarg-style');

    // enqueue real parent stylesheet
    // (again, template refers to the parent)
    wp_enqueue_style('nisarg-parent-style', get_template_directory_uri() . '/style.css',
        $theme->parent()->get('Version'));

    wp_enqueue_style('fonts', 'https://fonts.googleapis.com/css2?family=Merriweather&display=swap');

    // enqueue child theme overrides
    wp_enqueue_style('nisarg-child-style', get_stylesheet_uri(), ['nisarg-parent-style'], $theme->get('Version'));

    // remove anything we are not using
    wp_dequeue_style('flexslide');

}
// call our function when WP is ready to enqueue styles
// set the priority to 100 to make sure it runs after the parent (10 is the default)
add_action('wp_enqueue_scripts', 'grasin_enqueue_styles', 100);

add_action("adverts_template_load", "grasin_override_templates");

function grasin_widgets_init() {
    register_sidebar(
        array(
            'name'          => __( 'Footer Area 1', 'grasin' ),
            'id'            => 'footer-1',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );
}

add_action( 'widgets_init', 'grasin_widgets_init' );