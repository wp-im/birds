<?php
/**
 * Native WordPress comment tree.
 *
 * @package Birds
 */

if ( post_password_required() ) {
	return;
}
?>
<section class="window comments-window" id="comments" aria-labelledby="comments-title">
	<div class="titlebar">
		<span class="control" aria-hidden="true"></span>
		<h2 class="window-title" id="comments-title"><?php esc_html_e( 'Replies', 'birds' ); ?></h2>
		<span class="zoom" aria-hidden="true"></span>
	</div>
	<div class="window-body">
		<?php if ( have_comments() ) : ?>
			<div class="comments-list">
				<?php
			wp_list_comments(
				array(
					'style'       => 'div',
					'short_ping'  => true,
					'avatar_size' => 32,
					'callback'    => 'birds_comment',
				)
			);
			?>
			</div>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
				<nav class="comment-navigation pager" aria-label="<?php esc_attr_e( 'Comment navigation', 'birds' ); ?>">
					<div><?php previous_comments_link( esc_html__( '← Newer', 'birds' ) ); ?></div>
					<span class="stats"><?php esc_html_e( 'Replies', 'birds' ); ?></span>
					<div class="pager-next"><?php next_comments_link( esc_html__( 'Older →', 'birds' ) ); ?></div>
				</nav>
			<?php endif; ?>
		<?php else : ?>
			<div class="empty-state"><?php esc_html_e( 'No replies yet. Start the thread.', 'birds' ); ?></div>
		<?php endif; ?>

		<?php
		comment_form(
			array(
				'title_reply'          => __( 'Reply to this thread', 'birds' ),
				'label_submit'         => __( 'Reply', 'birds' ),
				'comment_notes_before' => '',
				'comment_notes_after'  => '',
				'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your reply', 'birds' ) . '</label><textarea id="comment" name="comment" cols="45" rows="4" required></textarea></p>',
			)
		);
		?>
	</div>
</section>
