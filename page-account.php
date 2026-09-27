<?php
/**
 * Optional front-end account page.
 *
 * The Theme owns the presentation. Birds Core, when active, owns the update
 * handler. Authentication policy remains outside both packages.
 *
 * @package Birds
 */

wp_enqueue_style(
	'birds-account',
	get_stylesheet_directory_uri() . '/account.css',
	array( 'birds-style' ),
	BIRDS_VERSION
);

$account_enabled = function_exists( 'birds_core_account_enabled' ) && birds_core_account_enabled();
$user            = wp_get_current_user();
$status          = isset( $_GET['birds_core_status'] ) ? sanitize_key( wp_unslash( $_GET['birds_core_status'] ) ) : '';

get_header();
?>
<main class="workspace" id="top">
	<div class="main-stack">
		<section class="window page-window" aria-labelledby="page-title">
			<div class="titlebar">
				<span class="control" aria-hidden="true"></span>
				<h1 class="window-title" id="page-title"><?php the_title(); ?></h1>
				<span class="zoom" aria-hidden="true"></span>
			</div>
			<div class="window-body">
				<article class="page-content bodytext">
					<?php if ( ! is_user_logged_in() ) : ?>
						<div class="empty-state">
							<p><?php esc_html_e( 'Sign in to view your account.', 'birds' ); ?></p>
							<a class="push small" href="<?php echo esc_url( birds_login_url( birds_account_url() ) ); ?>"><?php esc_html_e( 'Log in', 'birds' ); ?></a>
						</div>
					<?php else : ?>
						<div class="birds-account-content">
							<div class="birds-account-intro">
								<div class="birds-account-avatar"><?php echo get_avatar( $user->ID, 64, '', $user->display_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
								<div>
									<strong><?php echo esc_html( $user->display_name ); ?></strong>
									<p><?php esc_html_e( 'Manage the public profile used by your Birds posts and replies.', 'birds' ); ?></p>
								</div>
							</div>

							<?php if ( 'account_updated' === $status ) : ?>
								<div class="live-banner" role="status"><?php esc_html_e( 'Your account was updated.', 'birds' ); ?></div>
							<?php elseif ( 'account_avatar_error' === $status ) : ?>
								<div class="live-banner" role="alert"><?php esc_html_e( 'Your profile was saved, but the avatar could not be uploaded.', 'birds' ); ?></div>
							<?php elseif ( 'error' === $status ) : ?>
								<div class="live-banner" role="alert"><?php esc_html_e( 'The account update could not be completed.', 'birds' ); ?></div>
							<?php endif; ?>

							<?php if ( $account_enabled ) : ?>
								<form class="birds-account-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
									<p class="birds-account-avatar-field">
										<label for="birds-avatar"><?php esc_html_e( 'Avatar', 'birds' ); ?></label>
										<input class="filefield" id="birds-avatar" type="file" name="birds_avatar" accept="image/jpeg,image/png,image/webp">
										<small><?php esc_html_e( 'Optional; JPG, PNG or WebP, up to 2 MB.', 'birds' ); ?></small>
									</p>
									<div class="birds-account-fields">
										<p><label for="birds-display-name"><?php esc_html_e( 'Display name', 'birds' ); ?></label><input class="textfield" id="birds-display-name" type="text" name="display_name" value="<?php echo esc_attr( $user->display_name ); ?>" maxlength="80" required></p>
										<p><label for="birds-user-url"><?php esc_html_e( 'Website', 'birds' ); ?></label><input class="textfield" id="birds-user-url" type="url" name="user_url" value="<?php echo esc_attr( $user->user_url ); ?>" maxlength="200" placeholder="https://"></p>
										<p><label for="birds-first-name"><?php esc_html_e( 'First name', 'birds' ); ?></label><input class="textfield" id="birds-first-name" type="text" name="first_name" value="<?php echo esc_attr( $user->first_name ); ?>" maxlength="50"></p>
										<p><label for="birds-last-name"><?php esc_html_e( 'Last name', 'birds' ); ?></label><input class="textfield" id="birds-last-name" type="text" name="last_name" value="<?php echo esc_attr( $user->last_name ); ?>" maxlength="50"></p>
									</div>
									<p><label for="birds-description"><?php esc_html_e( 'Biography', 'birds' ); ?></label><textarea class="textarea" id="birds-description" name="description" rows="5" maxlength="500"><?php echo esc_textarea( $user->description ); ?></textarea></p>
									<div class="birds-account-actions">
										<button class="push default" type="submit"><?php esc_html_e( 'Save profile', 'birds' ); ?></button>
										<a class="push" href="<?php echo esc_url( get_author_posts_url( $user->ID ) ); ?>"><?php esc_html_e( 'View public page', 'birds' ); ?></a>
									</div>
									<input type="hidden" name="action" value="birds_update_account">
									<?php wp_nonce_field( 'birds_update_account', 'birds_account_nonce' ); ?>
								</form>
							<?php else : ?>
								<div class="rail-note">
									<strong><?php esc_html_e( 'Profile editing is provided by Birds Core.', 'birds' ); ?></strong>
									<p><?php esc_html_e( 'The Theme can display this page on its own; install the companion plugin to edit profile details from the front end.', 'birds' ); ?></p>
								</div>
							<?php endif; ?>

							<dl class="birds-account-facts">
								<div><dt><?php esc_html_e( 'Email', 'birds' ); ?></dt><dd><?php echo wp_kses_post( antispambot( $user->user_email ) ); ?></dd></div>
								<div><dt><?php esc_html_e( 'Role', 'birds' ); ?></dt><dd><?php echo esc_html( ! empty( $user->roles ) ? reset( $user->roles ) : '' ); ?></dd></div>
								<div><dt><?php esc_html_e( 'Posts', 'birds' ); ?></dt><dd><?php echo esc_html( number_format_i18n( count_user_posts( $user->ID, 'post', true ) ) ); ?></dd></div>
							</dl>
							<p class="birds-account-logout"><span><?php esc_html_e( 'Your login provider and password are managed by WordPress or your site integration.', 'birds' ); ?></span><a class="tool-link core-danger" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><?php esc_html_e( 'Log out', 'birds' ); ?></a></p>
						</div>
					<?php endif; ?>
				</article>
			</div>
		</section>
	</div>
	<?php get_template_part( 'template-parts/context-rail' ); ?>
</main>
<?php
get_footer();
