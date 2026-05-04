<?php
/**
 * Comments helpers for demo-like single post markup.
 *
 * @package news
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'news_comment_markup' ) ) {
	/**
	 * Custom comment item renderer to match demo classes.
	 */
	function news_comment_markup( $comment, $args, $depth ) {
		$tag = 'li';
		if ( 'div' === $args['style'] ) {
			$tag = 'div';
		}
		?>
		<<?php echo $tag; ?> <?php comment_class( 'comment' ); ?> id="li-comment-<?php comment_ID(); ?>">
			<div id="comment-<?php comment_ID(); ?>" class="comment-wrap">
				<div class="comment-meta">
					<div class="comment-author vcard">
						<span class="comment-avatar"><?php echo get_avatar( $comment, 60 ); ?></span>
					</div>
				</div>

				<div class="comment-content">
					<div class="comment-author">
						<?php echo esc_html( get_comment_author( $comment ) ); ?>
						<span>
							<a href="<?php echo esc_url( get_comment_link( $comment ) ); ?>" title="Permalink to this comment">
								<?php echo esc_html( get_comment_date( 'F j, Y', $comment ) . ' at ' . get_comment_time( 'g:i a', false, $comment ) ); ?>
							</a>
						</span>
					</div>

					<?php if ( '0' === (string) $comment->comment_approved ) : ?>
						<p>Your comment is awaiting moderation.</p>
					<?php endif; ?>

					<?php comment_text( $comment ); ?>

					<?php
					echo get_comment_reply_link(
						array_merge(
							$args,
							array(
								'depth'      => $depth,
								'max_depth'  => $args['max_depth'],
								'reply_text' => '<i class="bi-reply-fill"></i>',
							)
						),
						$comment,
						$comment->comment_post_ID
					);
					?>
				</div>

				<div class="clear"></div>
			</div>
		</<?php echo $tag; ?>>
		<?php
	}
}
