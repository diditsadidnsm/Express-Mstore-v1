<?php

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">

		<?php the_title( '<h1 class="title-post entry-title">', '</h1>' ); ?>

		
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-thumb">
			<?php the_post_thumbnail('eduexpert-large-thumb'); ?>
		</div>
	<?php endif; ?>
	
	<div class="single-meta">
			<?php eduexpert_posted_on(); 
				eduexpert_edit_link(); 
			?>
	</div><!-- .entry-meta -->
		
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'eduexpert' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
