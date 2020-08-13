<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="blog-post">
		<div class="entry-thumb">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('large-thumb'); ?></a>
		</div>
		<div class="blog-content-wrapper">
			<header class="entry-header">
				<?php the_title( sprintf( '<h2 class="title-post entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			</header>
			<p class="meta-post"> <?php eduexpert_posted_on(); ?> </p>
			<div class="entry-post">
				<?php if ( (get_theme_mod('full_post') == 1 && is_home() ) || (get_theme_mod('full_archives') == 1 && is_archive() ) ) : ?>
					<?php the_content(); ?>
				<?php else : ?>
					<?php the_excerpt(); ?>
				<?php endif ?>
			</div>
			
		</div>

	</div><!-- /.blog-post -->
</article><!-- /article -->