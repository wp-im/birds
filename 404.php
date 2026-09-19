<?php
/**
 * Not-found template.
 *
 * @package Birds
 */

get_header();
?>
<main class="workspace" id="top">
	<div class="main-stack">
		<section class="window" aria-labelledby="not-found-title">
			<div class="titlebar">
				<span class="control" aria-hidden="true"></span>
				<h1 class="window-title" id="not-found-title"><?php esc_html_e( 'Not Found', 'birds' ); ?></h1>
				<span class="zoom" aria-hidden="true"></span>
			</div>
			<div class="window-body">
				<div class="empty-state">
					<p><?php esc_html_e( 'This page is not in the current stream.', 'birds' ); ?></p>
					<a class="push small" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Return to Live Feed', 'birds' ); ?></a>
				</div>
			</div>
		</section>
	</div>
	<?php get_template_part( 'template-parts/context-rail' ); ?>
</main>
<?php
get_footer();
