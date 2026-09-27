<?php
/**
 * The header for Birds.
 *
 * @package Birds
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?><?php echo function_exists( 'birds_demo_palette_body_attribute' ) ? birds_demo_palette_body_attribute() : ''; ?>>
<?php wp_body_open(); ?>

<header class="menubar">
	<div class="menu-left">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Birds home', 'birds' ); ?>">
			<?php $birds_logo_id = absint( get_theme_mod( 'custom_logo' ) ); ?>
			<?php if ( $birds_logo_id ) : ?>
				<?php echo wp_get_attachment_image( $birds_logo_id, 'thumbnail', false, array( 'class' => 'brand-logo', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<span class="brand-mark" aria-hidden="true"></span>
			<?php endif; ?>
			<span><?php bloginfo( 'name' ); ?></span>
		</a>
		<?php
		$primary_menu = wp_nav_menu(
			array(
				'theme_location'  => 'primary',
				'container'       => 'nav',
				'container_class' => 'theme-nav',
				'container_aria_label' => __( 'Primary navigation', 'birds' ),
				'menu_class'      => 'theme-menu',
				'fallback_cb'     => false,
				'echo'            => false,
				'depth'           => 1,
			)
		);
		?>
		<?php if ( $primary_menu ) : ?>
			<?php echo wp_kses_post( $primary_menu ); ?>
		<?php else : ?>
			<a class="menu-link<?php echo ( is_front_page() || is_home() ) ? ' is-active' : ''; ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Live', 'birds' ); ?></a>
			<a class="menu-link<?php echo is_search() ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_search_link() ); ?>"><?php esc_html_e( 'Search', 'birds' ); ?></a>
		<?php endif; ?>
		<details class="mobile-menu">
			<summary><?php esc_html_e( 'Menu', 'birds' ); ?></summary>
			<div class="sheet">
				<?php if ( $primary_menu ) : ?>
					<?php
					echo wp_kses_post(
						wp_nav_menu(
							array(
								'theme_location'       => 'primary',
								'container'            => 'nav',
								'container_class'      => 'mobile-theme-nav',
								'container_aria_label' => __( 'Mobile navigation', 'birds' ),
								'menu_class'           => 'theme-menu',
								'fallback_cb'          => false,
								'echo'                 => false,
								'depth'                => 1,
							)
						)
					);
					?>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Live', 'birds' ); ?></a>
					<a href="<?php echo esc_url( get_search_link() ); ?>"><?php esc_html_e( 'Search', 'birds' ); ?></a>
				<?php endif; ?>
			</div>
		</details>
	</div>
	<div class="menu-right">
		<?php if ( function_exists( 'birds_demo_palette_control' ) ) : ?>
			<?php birds_demo_palette_control(); ?>
		<?php endif; ?>
		<?php if ( is_user_logged_in() ) : ?>
			<a class="menu-link small-status" href="<?php echo esc_url( birds_account_url() ); ?>" aria-label="<?php esc_attr_e( 'Open account settings', 'birds' ); ?>"><?php echo esc_html( wp_get_current_user()->display_name ); ?></a>
		<?php else : ?>
			<a class="menu-link" href="<?php echo esc_url( birds_login_url( home_url( '/' ) ) ); ?>"><?php esc_html_e( 'Log in', 'birds' ); ?></a>
		<?php endif; ?>
		<?php if ( birds_theme_option( 'show_clock', true ) ) : ?>
			<span class="clock"><?php echo esc_html( current_time( 'H:i' ) ); ?></span>
		<?php endif; ?>
	</div>
</header>
