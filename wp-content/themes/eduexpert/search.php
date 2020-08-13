<?php

get_header(); ?>


<div class="wrap row">
	
	<header class="page-header">
		<?php if ( have_posts() ) : ?>
		<h3 class="page-title"><?php printf( __( 'Search Results for: %s', 'eduexpert' ), '<span>' . get_search_query() . '</span>' ); ?></h3>
		<?php endif; ?>
	</header><!-- .page-header -->
	
	<div id="primary" class="content-area col-md-9">
		<main id="main" class="post-wrap" role="main">

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'content', 'search' );
				?>

			<?php endwhile; ?>

			<?php the_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->
	
		<?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php get_footer(); ?>
