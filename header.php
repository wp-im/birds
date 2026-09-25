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
			<span class="brand-mark" aria-hidden="true"></span>
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
			<a class="menu-link<?php echo is_category() ? ' is-active' : ''; ?>" href="<?php echo esc_url( home_url( '/#topics' ) ); ?>"><?php esc_html_e( 'Topics', 'birds' ); ?></a>
		<?php endif; ?>
	</div>
	<div class="menu-right">
		<?php if ( function_exists( 'birds_demo_palette_control' ) ) : ?>
			<?php birds_demo_palette_control(); ?>
		<?php endif; ?>
		<?php if ( is_user_logged_in() ) : ?>
			<span class="small-status"><?php echo esc_html( wp_get_current_user()->display_name ); ?></span>
			<a class="menu-link" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><?php esc_html_e( 'Log out', 'birds' ); ?></a>
		<?php else : ?>
			<a class="menu-link" href="<?php echo esc_url( add_query_arg( 'redirect_to', home_url( '/' ), home_url( '/login/' ) ) ); ?>"><?php esc_html_e( 'Log in', 'birds' ); ?></a>
		<?php endif; ?>
		<span class="clock"><?php echo esc_html( current_time( 'H:i' ) ); ?></span>
		<details class="mobile-menu">
			<summary><?php esc_html_e( 'Menu', 'birds' ); ?></summary>
			<div class="sheet">
				<?php if ( $primary_menu ) : ?>
					<?php
					echo wp_kses_post(
						wp_nav_menu(
							array(
								'theme_location'  => 'primary',
								'container'       => 'nav',
								'container_class' => 'mobile-theme-nav',
								'container_aria_label' => __( 'Mobile navigation', 'birds' ),
								'menu_class'      => 'theme-menu',
								'fallback_cb'     => false,
								'echo'            => false,
								'depth'           => 1,
							)
						)
					);
					?>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Live', 'birds' ); ?></a>
					<a href="<?php echo esc_url( get_search_link() ); ?>"><?php esc_html_e( 'Search', 'birds' ); ?></a>
						<a class="<?php echo is_category() ? 'is-active' : ''; ?>" href="<?php echo esc_url( home_url( '/#topics' ) ); ?>"><?php esc_html_e( 'Topics', 'birds' ); ?></a>
				<?php endif; ?>
			</div>
		</details>
	</div>
</header>
