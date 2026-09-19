<?php
/**
 * The footer for Birds.
 *
 * @package Birds
 */
?>

	<footer class="site-footer" role="contentinfo">
		<div class="site-footer-bar">
			<span><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a> · <?php esc_html_e( 'WordPress Classic Theme', 'birds' ); ?></span>
			<span class="site-footer-meta">Birds <?php echo esc_html( BIRDS_VERSION ); ?> · <?php esc_html_e( 'WordPress Core', 'birds' ); ?></span>
		</div>
	</footer>
	<?php wp_footer(); ?>
</body>
</html>
