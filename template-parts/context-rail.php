<?php
/**
 * Static, WordPress-native context windows.
 *
 * @package Birds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = get_categories(
	array(
		'hide_empty' => false,
		'number'     => 8,
	)
);
?>
<aside class="rail" aria-label="<?php esc_attr_e( 'Feed context', 'birds' ); ?>">
	<section class="window inactive" id="topics">
		<div class="titlebar"><span class="control" aria-hidden="true"></span><span class="window-title"><?php esc_html_e( 'Topics', 'birds' ); ?></span><span class="zoom" aria-hidden="true"></span></div>
		<div class="window-body">
			<nav class="rail-list" aria-label="<?php esc_attr_e( 'Topics', 'birds' ); ?>">
				<?php if ( ! empty( $categories ) ) : ?>
					<?php foreach ( $categories as $category ) : ?>
						<a class="<?php echo is_category( $category->term_id ) ? 'is-active' : ''; ?>"<?php echo is_category( $category->term_id ) ? ' aria-current="page"' : ''; ?> href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"><span><?php echo esc_html( $category->name ); ?></span><span class="count"><?php echo esc_html( number_format_i18n( $category->count ) ); ?></span></a>
					<?php endforeach; ?>
				<?php else : ?>
					<span class="rail-note"><?php esc_html_e( 'Topics will appear here after the first category is created.', 'birds' ); ?></span>
				<?php endif; ?>
			</nav>
			<div class="statusbar"><span class="grow"><?php esc_html_e( 'Browse the corpus', 'birds' ); ?></span><span><?php esc_html_e( 'Topics', 'birds' ); ?></span></div>
		</div>
	</section>

	<section class="window inactive">
		<div class="titlebar"><span class="control" aria-hidden="true"></span><span class="window-title"><?php esc_html_e( 'About', 'birds' ); ?></span><span class="zoom" aria-hidden="true"></span></div>
		<div class="window-body">
			<div class="rail-note">
				<strong><?php bloginfo( 'name' ); ?></strong>
				<p><?php bloginfo( 'description' ); ?></p>
				<p><?php esc_html_e( 'A small WordPress publishing surface for notes, links and threaded replies.', 'birds' ); ?></p>
			</div>
			<div class="statusbar"><span class="grow"><?php esc_html_e( 'WordPress Core', 'birds' ); ?></span><span><?php esc_html_e( 'Classic Theme', 'birds' ); ?></span></div>
		</div>
	</section>
</aside>
