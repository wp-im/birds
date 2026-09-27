<?php
/**
 * The footer for Birds.
 *
 * @package Birds
 */
?>

	<footer class="site-footer" role="contentinfo">
		<div class="site-footer-bar">
			<span>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => 'nav',
							'container_class' => 'footer-menu',
							'menu_class'     => 'theme-menu',
							'fallback_cb'    => false,
						)
					);
					?>
				<?php endif; ?>
			</span>
			<?php if ( birds_theme_option( 'show_footer_version', true ) ) : ?>
				<span class="site-footer-meta">Birds <?php echo esc_html( BIRDS_VERSION ); ?> · <?php esc_html_e( 'WordPress Core', 'birds' ); ?></span>
			<?php endif; ?>
		</div>
	</footer>
	<?php wp_footer(); ?>
</body>
</html>
