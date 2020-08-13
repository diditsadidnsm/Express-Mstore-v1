<?php

get_header(); 

$layout = theme_blog_layout();

?>
<div class="row">
	<div id="primary" class="content-area <?php echo ( get_theme_mod( 'show_sidebar', 1 ) == 0 )  ? 'col-md-12 ' : 'col-md-9 '; echo esc_attr($layout); ?>">
		<main id="main" class="blog-main post-wrap" role="main">

		<?php if ( have_posts() ) : ?>

		<div class="posts-layout">
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					if ( $layout != 'special' ) {
						get_template_part( 'content', get_post_format() );
					} else {
						get_template_part( 'content', 'special' );
					}
				?>

			<?php endwhile; ?>
		</div>

		<?php
			the_posts_pagination( array(
				'mid_size'  => 1,
			) );
		?>	

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php 
	if ( get_theme_mod( 'show_sidebar', 1 ) !=0  ) :
	get_sidebar();
	endif;
?>
</div>
<?php get_footer(); ?>