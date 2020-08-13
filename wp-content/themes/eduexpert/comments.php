<?php
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<?php
	if ( have_comments() ) : ?>

		<div class="comment-header">
				<h4 class="comment-title">
				<?php
					$comments_count = get_comments_number();
					if( $comments_count ==+ '1' ){
						printf( _x( 'One Reply on &ldquo;%s&rdquo;', 'comments title', 'eduexpert' ), get_the_title() );
					}else{
						printf( _nx( '%1$s Reply to &ldquo;%2$s&rdquo;', '%1$s Replies to &ldquo;%2$s&rdquo;', $comments_count, 'comments title', 'eduexpert' ), number_format_i18n( $comments_count ), get_the_title() );
					}
				?>
			</h4>
		</div>
			<ol class="comments-list">
			<?php
				wp_list_comments( array(
					'avatar_size' => 60,
					'style'       => 'ol',
					'short_ping'  => true,
					'reply_text'  => __( 'Reply &rarr;', 'eduexpert' ),
				) );
			?>
			</ol>
			<?php
			if( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav id="comment-nav-below" class="comment-navigation" role="navigation">
			<?php the_comments_pagination( array(
				'prev_text' => '<div class="nav-previous">' . __( '&larr; Previous comments', 'eduexpert' ) . '</div>',
				'next_text' => '<div class="nav-next">' . __( 'Next comments &rarr;', 'eduexpert' ) . '</div>',
			) );?>
			</nav>
			<?php endif;

	endif;

	if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
	<p class="no-comments"><?php _e( 'Comments are closed.', 'eduexpert' ); ?></p>
	<?php 
	endif;
		comment_form();
	?>

</div><!-- #comments -->
