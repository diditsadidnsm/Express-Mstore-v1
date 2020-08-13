<?php
get_header();

$layout = theme_blog_layout();

?>
<div class="row">

	<div id="primary" class="content-area <?php echo ( get_theme_mod( 'show_sidebar', 1 ) !=0 )  ? 'col-md-12 ' : 'col-md-9 '; echo esc_attr($layout); ?>">
	
		<main id="main" class="site-main" role="main">
		
		<?php
		if ( have_posts() ) : ?>
		<header class="entry-header">
			<?php the_archive_title( '<h4 class="archive-heading">', '</h4>' ); ?>
		</header>
		<div class="entry-content">
		<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();
				if ( $layout != 'special' ) {
					get_template_part( 'content', get_post_format() );
				} else {
					get_template_part( 'content', 'special' );
				}
			endwhile;
			
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'eduexpert' ),
				'after'  => '</div>',
			) );
		?>
		</div>
		<?php
			the_posts_pagination( array(
				'mid_size'  => 1,
			) );
		else : 
			get_template_part( 'content', 'none' );
		endif; 
		?>
		</main>
	</div>
	<?php 
		if ( get_theme_mod( 'show_sidebar', 1 ) !=0 ) :
			get_sidebar();
		endif;
	?>
</div>
<?php
get_footer();
?>