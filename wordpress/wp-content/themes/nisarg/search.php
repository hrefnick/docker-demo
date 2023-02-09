<?php
/**
 * The template for displaying search results pages.
 *
 * @package Nisarg
 */

get_header(); 
$posts_nav_style = get_theme_mod( 'nisarg_posts_nav','old-new-posts');
?>
	<div class="container">
		<div class="row">
				<?php if ( have_posts() ) : ?>
					<header class="search-page-header">
						<h3 class="search-page-title"><?php 
						/* translators: 1: Search Query. */
						printf( esc_html__( 'Search Results for: %s', 'nisarg' ), '<span>' . get_search_query() . '</span>' ); ?></h3>
					</header><!-- .page-header -->
				<section id="primary" class="col-md-9 content-area">
					<main id="main" class="site-main" role="main">

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php
						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'template-parts/content', 'search' );
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

					</main><!-- #main -->
				</section><!-- #primary -->

				<?php else : ?>

				<section id="primary" class="col-md-9 content-area">
					<main id="main" class="site-main" role="main">

					<?php get_template_part( 'template-parts/content', 'none' ); ?>

					</main><!-- #main -->
				</section><!-- #primary -->
				<?php endif; ?>
			<?php get_sidebar( 'sidebar-1' ); ?>
		</div> <!--.row-->
	</div><!--.container-->
	<?php get_footer(); ?>
