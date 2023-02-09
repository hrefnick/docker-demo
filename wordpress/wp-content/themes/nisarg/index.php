<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Nisarg
 */

get_header(); 
$posts_nav_style = get_theme_mod( 'nisarg_posts_nav','old-new-posts');
?>

	<div class="container">
		<div class="row">
			<div id="primary" class="col-md-9 content-area">
				<main id="main" class="site-main" role="main">
				<?php if ( have_posts() ) : ?>
					<?php if ( is_home() && ! is_front_page() ) : ?>
						<header>
							<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
						</header>
					<?php endif; ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php
						/*
						 * If you want to disaplay only excerpt, file content-excerpt.php will be used.
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						$post_display_option = get_theme_mod( 'post_display_option', 'post-excerpt' );
						if ( 'post-excerpt' === $post_display_option ) {
							get_template_part( 'template-parts/content','excerpt' );
						} else {
							get_template_part( 'template-parts/content', get_post_format() );
						}
						?>
					<?php endwhile; ?>
					<?php if( 'old-new-posts'  === $posts_nav_style && have_posts() ) {
						nisarg_posts_navigation();
					} else {
						if ( have_posts() ) :
							the_posts_pagination( array( 
								'mid_size' => 1, 
								'prev_text' => esc_html__( 'Prev', 'nisarg' ),
			    				'next_text' => esc_html__( 'Next', 'nisarg' ), 
			    			) );
						endif;
					}?>
				<?php else : ?>
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				<?php endif; ?>
				</main><!-- #main -->
			</div><!-- #primary -->
			<?php get_sidebar( 'sidebar-1' ); ?>
		</div><!--row-->
	</div><!--.container-->
	<?php get_footer(); ?>
