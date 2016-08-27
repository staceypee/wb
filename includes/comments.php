<?php
/**
 * Renders a single comments; Called for each comment
 */
function crb_render_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment-entry">
			<div class="comment-author vcard">
				<?php echo get_avatar( $comment, 48 ); ?>
				<?php comment_author_link(); ?>
				<span class="says"><?php _e( 'says:', 'crb' ); ?></span>
			</div>

			<?php if ( $comment->comment_approved === '0' ) : ?>
				<em class="moderation-notice"><?php _e( 'Your comment is awaiting moderation.', 'crb' ); ?></em><br />
			<?php endif; ?>

			<div class="comment-meta">
				<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
					<?php printf( __( '%1$s at %2$s', 'crb' ), get_comment_date(), get_comment_time() ); ?>
				</a>

				<?php edit_comment_link( __( '(Edit)', 'crb' ), '  ', '' ); ?>
			</div>
			
			<div class="comment-text">
				<?php comment_text(); ?>
			</div>
	
			<div class="comment-reply">
				<?php
				comment_reply_link( array_merge( $args, array(
					'depth'     => $depth,
					'max_depth' => $args['max_depth']
				) ) );
				?>
			</div>
		</div>
	<?php
}