<?php
/**
 * The template for displaying all single posts.
 *
 */

get_header(); ?>
	
	<?php if ( get_theme_mod('show_sidebar_single', 1 ) == 0 ) { //Check if the post needs to be full width
		$fullwidth = 'fullwidth';
	} else {
		$fullwidth = '';
	} ?>
	
<div class="row">
	<div id="primary" class="content-area col-md-9 <?php echo esc_attr( $fullwidth ); ?>">
		<main id="main" class="blog-main post-wrap" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>
			
			<div class="single-post-nav">
				<span class="prev-post-nav"><span class="button"><?php previous_post_link('&larr; %link'); ?></span></span>
				<span class="next-post-nav"><?php next_post_link('%link &rarr;'); ?></span>
			</div>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>
		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php if ( get_theme_mod('show_sidebar_single', 1 ) != 0 ) {
	get_sidebar();
} ?>

</div>
<?php get_footer(); ?>
