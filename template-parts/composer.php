<?php
/**
 * Minimal front-end publisher.
 *
 * @package Birds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="window composer-window" aria-labelledby="composer-title">
	<div class="titlebar">
		<span class="control" aria-hidden="true"></span>
		<span class="window-title" id="composer-title"><?php esc_html_e( 'New Post', 'birds' ); ?></span>
		<span class="zoom" aria-hidden="true"></span>
	</div>
	<div class="window-body">
		<?php if ( is_user_logged_in() && current_user_can( 'publish_posts' ) ) : ?>
			<?php $current_user = wp_get_current_user(); ?>
			<form class="composer" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<div class="compose-top">
					<a class="avatar" href="<?php echo esc_url( get_author_posts_url( $current_user->ID ) ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Open %s profile', 'birds' ), $current_user->display_name ) ); ?>">
						<?php echo birds_avatar( $current_user->ID, 38 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
					<div class="compose-id">
						<strong><?php echo esc_html( $current_user->display_name ); ?></strong>
						<span class="handle">@<?php echo esc_html( $current_user->user_nicename ); ?> · <?php esc_html_e( 'now', 'birds' ); ?></span>
					</div>
				</div>
					<div class="compose-area">
						<label class="sr-only" for="birds-content"><?php esc_html_e( 'Post content', 'birds' ); ?></label>
						<textarea class="textarea" id="birds-content" name="birds_content" rows="4" maxlength="10000" required placeholder="<?php esc_attr_e( 'What is happening?', 'birds' ); ?>"></textarea>
					</div>
				<div class="compose-attachments">
					<label for="birds-category"><?php esc_html_e( 'Topic', 'birds' ); ?></label>
					<select id="birds-category" name="birds_category">
						<option value=""><?php esc_html_e( 'No topic', 'birds' ); ?></option>
						<?php foreach ( get_categories( array( 'hide_empty' => false ) ) as $category ) : ?>
							<option value="<?php echo esc_attr( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></option>
						<?php endforeach; ?>
					</select>
					<span><?php esc_html_e( 'Plain text first; links and media can remain part of the same post.', 'birds' ); ?></span>
				</div>
				<div class="compose-footer">
					<span class="compose-hint"><?php esc_html_e( 'WordPress post · no title required', 'birds' ); ?></span>
					<button class="push default" type="submit"><?php esc_html_e( 'Publish', 'birds' ); ?></button>
				</div>
				<input type="hidden" name="action" value="birds_publish">
				<?php wp_nonce_field( 'birds_publish_post', 'birds_nonce' ); ?>
			</form>
		<?php else : ?>
			<div class="rail-note">
				<strong><?php esc_html_e( 'Publishing is for members.', 'birds' ); ?></strong>
				<p><?php esc_html_e( 'Log in to write a short note directly from the feed.', 'birds' ); ?></p>
				<a class="push small" href="<?php echo esc_url( wp_login_url( home_url( '/' ) ) ); ?>"><?php esc_html_e( 'Log in to post', 'birds' ); ?></a>
			</div>
		<?php endif; ?>
		<div class="statusbar">
			<span class="grow"><?php esc_html_e( 'Front-end publishing · WordPress Core', 'birds' ); ?></span>
			<span><?php esc_html_e( 'Ready', 'birds' ); ?></span>
		</div>
	</div>
</section>
