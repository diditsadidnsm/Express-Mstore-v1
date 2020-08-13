<?php


get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main post-wrap" role="main">
		<?php 
			if ( have_posts() ) : while ( have_posts() ) : the_post();

				get_template_part( 'content', 'page' );
				
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile;
		?>
		
		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>
		<?php eduexpert_edit_link(); ?>
		</main> <!-- /main -->
	</div> <!-- /.post-wrap -->

<?php get_footer(); ?>